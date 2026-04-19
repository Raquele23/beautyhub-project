<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Professional extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'establishment_name',
        'description',
        'phone',
        'state',
        'city',
        'street',
        'house_number',
        'zip_code',
        'instagram',
        'profile_photo',
        'banner_color',
        'banner_photo',
        'latitude',
        'longitude',
        'auto_complete',
        'preparation_time_minutes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function portfolioPhotos(): HasMany
    {
        return $this->hasMany(PortfolioPhoto::class);
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(Availability::class)->orderBy('weekday');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function getFullAddressAttribute(): string
    {
        return "{$this->street}, {$this->house_number} - {$this->city}, {$this->state}";
    }

    // Chama múltiplas APIs para converter endereço em coordenadas
    // Fallback: ViaCEP → Google Maps → Photon → Nominatim
    public function geocode(): void
    {
        try {
            // ===== ESTRATÉGIA 1: ViaCEP (valida endereço) =====
            if (!empty($this->zip_code)) {
                $cleanZip = preg_replace('/\D/', '', $this->zip_code);
                if (strlen($cleanZip) === 8) {
                    // ViaCEP valida e retorna endereço formatado
                    $viacepResult = $this->validateViaCEP($cleanZip);
                    if ($viacepResult) {
                        // Se ViaCEP validou, tenta geocodificar o endereço validado
                        if ($this->geocodeGoogle()) {
                            return;
                        }
                    }
                }
            }

            // ===== ESTRATÉGIA 2: Google Maps Geocoding =====
            if ($this->geocodeGoogle()) {
                return;
            }

            // ===== ESTRATÉGIA 3: Photon =====
            if ($this->geocodePhoton()) {
                return;
            }

            // ===== ESTRATÉGIA 4: Nominatim (fallback final) =====
            if ($this->geocodeNominatim()) {
                return;
            }

            Log::info('Geocoding fallback to city center for professional ' . $this->id, [
                'address' => $this->full_address,
                'zip_code' => $this->zip_code,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Geocoding failed for professional ' . $this->id . ': ' . $e->getMessage());
        }
    }

    private function validateViaCEP(string $cleanZip): bool
    {
        try {
            $zip = substr($cleanZip, 0, 5) . '-' . substr($cleanZip, 5);

            $response = Http::withoutVerifying()
                ->timeout(10)
                ->retry(2, 300)
                ->get("https://viacep.com.br/ws/{$zip}/json/");

            $result = $response->json();

            if (!empty($result['logradouro']) && empty($result['erro'])) {
                $this->street = $result['logradouro'];
                $this->city = $result['localidade'];
                $this->state = $result['uf'];
                return true;
            }

            return false;
        } catch (\Throwable $e) {
            Log::debug('ViaCEP validation failed: ' . $e->getMessage());
            return false;
        }
    }

    private function geocodeGoogle(): bool
    {
        try {
            $key = config('services.google.maps_key');
            if (empty($key)) {
                return false;
            }

            $address = trim("{$this->street}, {$this->house_number}, {$this->city}, {$this->state}, Brasil", " ,");
            if (empty($address)) {
                return false;
            }

            $response = Http::withoutVerifying()
                ->timeout(10)
                ->retry(2, 300)
                ->get('https://maps.googleapis.com/maps/api/geocode/json', [
                    'address' => $address,
                    'key' => $key,
                    'region' => 'br',
                    'language' => 'pt-BR',
                ]);

            $result = $response->json();

            if ($result['status'] === 'OK' && !empty($result['results'][0]['geometry']['location'])) {
                $loc = $result['results'][0]['geometry']['location'];
                $this->latitude = $loc['lat'];
                $this->longitude = $loc['lng'];
                $this->saveQuietly();
                Log::debug('Geocoded via Google Maps for professional ' . $this->id);
                return true;
            }

            return false;
        } catch (\Throwable $e) {
            Log::debug('Google Maps geocoding failed: ' . $e->getMessage());
            return false;
        }
    }

    private function geocodePhoton(): bool
    {
        try {
            $query = trim("{$this->street}, {$this->city}, {$this->state}, Brasil", " ,");
            if (empty($query)) {
                return false;
            }

            $response = Http::withoutVerifying()
                ->timeout(10)
                ->retry(2, 300)
                ->get('https://photon.komoot.io/api/', [
                    'q' => $query,
                    'limit' => 1,
                    'lang' => 'pt',
                ]);

            $result = $response->json();

            if (!empty($result['features'][0]['geometry']['coordinates'])) {
                $coords = $result['features'][0]['geometry']['coordinates'];
                $this->longitude = $coords[0];
                $this->latitude = $coords[1];
                $this->saveQuietly();
                return true;
            }

            return false;
        } catch (\Throwable $e) {
            Log::debug('Photon geocoding failed: ' . $e->getMessage());
            return false;
        }
    }

    private function geocodeNominatim(): bool
    {
        try {
            $queries = [];

            // Query 1: Rua + Cidade + Estado (prioridade)
            $streetCityStateAddress = trim("{$this->street}, {$this->city}, {$this->state}, Brasil", " ,");
            if (!empty($streetCityStateAddress)) {
                $queries[] = $streetCityStateAddress;
            }

            // Query 2: Completo com número + CEP
            $fullAddress = trim("{$this->street}, {$this->house_number}, {$this->city}, {$this->state}, {$this->zip_code}, Brasil", " ,");
            if (!empty($fullAddress) && $fullAddress !== $streetCityStateAddress) {
                $queries[] = $fullAddress;
            }

            // Query 3: Apenas Cidade + Estado (fallback genérico)
            $cityStateAddress = trim("{$this->city}, {$this->state}, Brasil", " ,");
            if (!empty($cityStateAddress) && $cityStateAddress !== $streetCityStateAddress) {
                $queries[] = $cityStateAddress;
            }

            foreach ($queries as $query) {
                $response = Http::withHeaders([
                    'User-Agent' => 'BeautyHub/1.0 (contato@beautyhub.local)',
                    'Accept-Language' => 'pt-BR,pt;q=0.9,en;q=0.8',
                ])
                    ->withoutVerifying()
                    ->timeout(10)
                    ->retry(2, 300)
                    ->get('https://nominatim.openstreetmap.org/search', [
                        'q' => $query,
                        'format' => 'json',
                        'limit' => 1,
                        'countrycodes' => 'br',
                        'addressdetails' => 0,
                    ]);

                $results = $response->json();

                if (!empty($results[0]['lat']) && !empty($results[0]['lon'])) {
                    $this->latitude = $results[0]['lat'];
                    $this->longitude = $results[0]['lon'];
                    $this->saveQuietly();
                    return true;
                }
            }

            return false;
        } catch (\Throwable $e) {
            Log::debug('Nominatim geocoding failed: ' . $e->getMessage());
            return false;
        }
    }

    // Calcula distância em km usando fórmula de Haversine
    public function distanceTo(float $lat, float $lon): ?float
    {
        if (!$this->latitude || !$this->longitude) {
            return null;
        }

        $earthRadius = 6371;

        $latDelta = deg2rad($lat - $this->latitude);
        $lonDelta = deg2rad($lon - $this->longitude);

        $a = sin($latDelta / 2) ** 2
            + cos(deg2rad($this->latitude)) * cos(deg2rad($lat)) * sin($lonDelta / 2) ** 2;

        return $earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    public function getAvailableSlotsForDate(string $date, int $serviceDurationMinutes): array
    {
        if ($serviceDurationMinutes <= 0) {
            return [];
        }

        $availability = $this->getAvailabilityForDate($date);

        if (!$availability) {
            return [];
        }

        $blockedRanges = $this->getBlockedRangesForDate($date);
        $allSlots = $availability->generateSlots($date, $serviceDurationMinutes, $blockedRanges);

        // Não retorna horários no passado quando a data é hoje.
        $selectedDate = Carbon::parse($date)->toDateString();
        if ($selectedDate === now()->toDateString()) {
            $now = now();
            $allSlots = array_values(array_filter($allSlots, function (string $slot) use ($date, $now) {
                return Carbon::parse("{$date} {$slot}:00")->greaterThan($now);
            }));
        }

        return $allSlots;
    }

    public function isSlotAvailableForService(string $date, string $time, int $serviceDurationMinutes): bool
    {
        if ($serviceDurationMinutes <= 0) {
            return false;
        }

        $availability = $this->getAvailabilityForDate($date);

        if (!$availability) {
            return false;
        }

        $slotStart = Carbon::parse("{$date} {$time}:00");
        $open = Carbon::parse("{$date} {$availability->open_time}");
        $close = Carbon::parse("{$date} {$availability->close_time}");

        if ($slotStart->lessThanOrEqualTo(now())) {
            return false;
        }

        if ($close->lessThanOrEqualTo($open)) {
            return false;
        }

        if ($slotStart->lessThan($open)) {
            return false;
        }

        if ((int) $slotStart->format('i') % Availability::GRID_STEP_MINUTES !== 0 || (int) $slotStart->format('s') !== 0) {
            return false;
        }

        $slotEnd = $slotStart->copy()->addMinutes($serviceDurationMinutes);
        if ($slotEnd->greaterThan($close)) {
            return false;
        }

        $blockedRanges = $this->getBlockedRangesForDate($date);
        foreach ($blockedRanges as [$blockedStart, $blockedEnd]) {
            if ($slotStart->lessThan($blockedEnd) && $slotEnd->greaterThan($blockedStart)) {
                return false;
            }
        }

        return true;
    }

    private function getAvailabilityForDate(string $date): ?Availability
    {
        $weekday = (int) Carbon::parse($date)->dayOfWeek;

        return $this->availabilities()
            ->where('weekday', $weekday)
            ->first();
    }

    private function getBlockedRangesForDate(string $date): array
    {
        $ranges = [];

        $availability = $this->getAvailabilityForDate($date);
        if ($availability) {
            $availability->loadMissing('breaks');

            foreach ($availability->breaks as $break) {
                $breakStart = Carbon::parse("{$date} {$break->start_time}");
                $breakEnd = Carbon::parse("{$date} {$break->end_time}");
                if ($breakEnd->greaterThan($breakStart)) {
                    $ranges[] = [$breakStart, $breakEnd];
                }
            }
        }

        $appointments = $this->appointments()
            ->with('service:id,duration')
            ->whereDate('scheduled_at', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        $preparationTimeMinutes = max((int) ($this->preparation_time_minutes ?? 15), 0);

        foreach ($appointments as $appointment) {
            $serviceDuration = max((int) ($appointment->service->duration ?? 0), 0);
            $start = $appointment->scheduled_at->copy();
            $end = $start->copy()->addMinutes($serviceDuration + $preparationTimeMinutes);
            $ranges[] = [$start, $end];
        }

        return $ranges;
    }
}