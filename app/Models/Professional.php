<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    // Retorna os slots disponíveis para uma data específica, excluindo os já agendados
    public function getAvailableSlotsForDate(string $date): array
    {
        $weekday = (int) date('w', strtotime($date));

        $availability = $this->availabilities->firstWhere('weekday', $weekday);

        if (!$availability) {
            return [];
        }

        $allSlots = $availability->generateSlots();

        // Busca horários já agendados nessa data (apenas confirmados e pendentes)
        $bookedTimes = $this->appointments()
            ->whereDate('scheduled_at', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->pluck('scheduled_at')
            ->map(fn($dt) => $dt->format('H:i'))
            ->toArray();

        // Remove os slots já ocupados
        return array_values(array_diff($allSlots, $bookedTimes));
    }
}