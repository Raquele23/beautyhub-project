<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_completed',
        'profile_photo_path', // <-- ADICIONADO
    ];

    protected $attributes = [
        'role' => 'client',
        'profile_completed' => false,
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function professional(): HasOne
    {
        return $this->hasOne(Professional::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'client_id');
    }

    public function isProfessional(): bool
    {
        return $this->role === 'professional';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class)->latest();
    }

    public function unreadNotificationsCount(): int
    {
        return $this->notifications()->unread()->count();
    }

    // ─── Reviews ───────────────────────────────────────────────────────────────

    public function reviewsGiven(): HasMany
    {
        return $this->hasMany(Review::class, 'client_id');
    }

    public function reviewsReceived(): HasMany
    {
        return $this->hasMany(Review::class, 'professional_id');
    }

    public function getAverageRatingAttribute(): float
    {
        return round($this->reviewsReceived()->avg('rating') ?? 0, 1);
    }
}