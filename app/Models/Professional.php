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
        'instagram',
        'profile_photo',
        'banner_color',
        'banner_photo',
        'latitude',
        'longitude',
        'auto_complete',
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

    // Chama OpenStreetMap para converter endereço em coordenadas
    public function geocode(): void
    {
        try {
            $query = urlencode("{$this->street}, {$this->house_number}, {$this->city}, {$this->state}, Brasil");

            $response = Http::withHeaders([
            'User-Agent' => 'AgendaApp/1.0'
            ])->withoutVerifying()->get("https://nominatim.openstreetmap.org/search?q={$query}&format=json&limit=1");
                $results = $response->json();

            if (!empty($results)) {
                $this->latitude  = $results[0]['lat'];
                $this->longitude = $results[0]['lon'];
                $this->saveQuietly();
            }
        } catch (\Exception $e) {
            Log::warning('Geocoding failed for professional ' . $this->id . ': ' . $e->getMessage());
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

        $minutesFromOpen = $open->diffInMinutes($slotStart, false);
        if ($minutesFromOpen < 0 || $minutesFromOpen % (int) $availability->slot_interval !== 0) {
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

        foreach ($appointments as $appointment) {
            $serviceDuration = max((int) ($appointment->service->duration ?? 0), 0);
            $start = $appointment->scheduled_at->copy();
            $end = $start->copy()->addMinutes($serviceDuration);
            $ranges[] = [$start, $end];
        }

        return $ranges;
    }
}