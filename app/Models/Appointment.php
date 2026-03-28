<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'professional_id',
        'service_id',
        'scheduled_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    // ─── Relacionamentos ───────────────────────────────────────────────────────

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function professional(): BelongsTo
    {
        return $this->belongsTo(Professional::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    // ─── Scopes ────────────────────────────────────────────────────────────────

    public function scopeUpcoming($query)
    {
                return $query->where(function ($outer) {
                                                $outer->where(function ($q) {
                                                                $q->where('scheduled_at', '>=', now())
                                                                    ->whereNotIn('status', ['cancelled']);
                                                        })
                                                        ->orWhere(function ($q) {
                                                                $q->where('scheduled_at', '<', now())
                                                                    ->whereIn('status', ['pending', 'confirmed']);
                                                        });
                                        })
                                        ->orderBy('scheduled_at');
    }

    public function scopePast($query)
    {
        return $query->whereIn('status', ['completed', 'cancelled'])
                     ->orderByDesc('scheduled_at');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    // ─── Helpers ───────────────────────────────────────────────────────────────

    public function isPending(): bool   { return $this->status === 'pending'; }
    public function isConfirmed(): bool { return $this->status === 'confirmed'; }
    public function isCancelled(): bool { return $this->status === 'cancelled'; }
    public function isCompleted(): bool { return $this->status === 'completed'; }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'Pendente',
            'confirmed' => 'Confirmado',
            'cancelled' => 'Cancelado',
            'completed' => 'Concluído',
            default     => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'yellow',
            'confirmed' => 'green',
            'cancelled' => 'red',
            'completed' => 'gray',
            default     => 'gray',
        };
    }

    public function canBeReviewed(): bool
    {
        return $this->status === 'completed'
            && ! $this->review()->exists();
    }
}