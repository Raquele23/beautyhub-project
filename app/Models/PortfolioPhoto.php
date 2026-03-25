<?php

namespace App\Models;

use App\Models\Professional;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioPhoto extends Model
{
    use HasFactory;

    protected $table = 'portfolio_photos';

    protected $fillable = [
        'professional_id',
        'photo',
        'original_photo',
        'description',
    ];

    public function professional(): BelongsTo
    {
        return $this->belongsTo(Professional::class);
    }
}
