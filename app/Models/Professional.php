<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function getAvailableSlotsForDate(string $date): array
    {
        $weekday = (int) date('w', strtotime($date));

        $availability = $this->availabilities->firstWhere('weekday', $weekday);

        if (!$availability) {
            return [];
        }

        $allSlots = $availability->generateSlots();

        $bookedTimes = $this->appointments()
            ->whereDate('scheduled_at', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->pluck('scheduled_at')
            ->map(fn($dt) => $dt->format('H:i'))
            ->toArray();

        return array_values(array_diff($allSlots, $bookedTimes));
    }
}