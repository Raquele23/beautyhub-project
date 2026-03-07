<?php

namespace App\Models;

use App\Models\Service;
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

    public function getFullAddressAttribute(): string
    {
        return "{$this->street}, {$this->house_number} - {$this->city}, {$this->state}";
    }
}
