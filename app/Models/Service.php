<?php

namespace App\Models;

use App\Models\Professional;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    use HasFactory;

    public const CATEGORY_OPTIONS = [
        'cabelo' => 'Cuidados Capilares',
        'manicure' => 'Manicure & Pedicure',
        'depilacao' => 'Depilação',
        'sobrancelha' => 'Sobrancelhas',
        'maquiagem' => 'Maquiagem',
        'tratamentos' => 'Tratamentos',
    ];

    protected $fillable = [
        'professional_id',
        'category',
        'name',
        'description',
        'duration',
        'price',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public static function categoryOptions(): array
    {
        return self::CATEGORY_OPTIONS;
    }

    public function professional(): BelongsTo
    {
        return $this->belongsTo(Professional::class);
    }

    public function getDurationFormattedAttribute(): string
    {
        if ($this->duration >= 60) {
            $hours = intdiv($this->duration, 60);
            $minutes = $this->duration % 60;
            
            if ($minutes === 0) {
                return "{$hours}h";
            }
            
            return "{$hours}h {$minutes}min";
        }
        
        return "{$this->duration}min";
    }
}
