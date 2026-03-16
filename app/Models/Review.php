<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'client_id',
        'professional_id',
        'rating',
        'comment',
        'professional_reply',
        'replied_at',
    ];

    protected $casts = [
        'rating'     => 'integer',
        'replied_at' => 'datetime',
    ];

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function professional(): BelongsTo
    {
        return $this->belongsTo(User::class, 'professional_id');
    }

    public function hasReply(): bool
    {
        return ! is_null($this->professional_reply);
    }
}