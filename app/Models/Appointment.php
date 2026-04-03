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
        'client_name',
        'client_email',
        'client_phone',
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

    public function getDisplayClientNameAttribute(): string
    {
        return $this->client?->name
            ?? $this->client_name
            ?? 'Cliente externo';
    }

    public function getDisplayClientEmailAttribute(): ?string
    {
        return $this->client?->email
            ?? $this->client_email;
    }

    public function getDisplayClientPhoneAttribute(): ?string
    {
        return $this->client_phone;
    }

    public function getDisplayClientPhoneFormattedAttribute(): ?string
    {
        $phone = preg_replace('/\D+/', '', (string) $this->client_phone);

        if ($phone === '') {
            return null;
        }

        if (strlen($phone) === 10) {
            return sprintf('(%s) %s-%s', substr($phone, 0, 2), substr($phone, 2, 4), substr($phone, 6, 4));
        }

        if (strlen($phone) === 11) {
            return sprintf('(%s) %s-%s', substr($phone, 0, 2), substr($phone, 2, 5), substr($phone, 7, 4));
        }

        return $this->client_phone;
    }

    public function getDisplayClientContactAttribute(): ?string
    {
        if ($this->client?->email) {
            return $this->client->email;
        }

        return $this->is_external_client
            ? $this->display_client_phone_formatted
            : $this->client_phone;
    }

    public function getClientOriginLabelAttribute(): string
    {
        return $this->is_external_client ? 'Externo' : 'Plataforma';
    }

    public function getIsExternalClientAttribute(): bool
    {
        return $this->client_id === null;
    }

}