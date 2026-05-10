<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

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

    public function serviceEndTime(): Carbon
    {
        return $this->scheduled_at->copy()->addMinutes($this->service->duration ?? 0);
    }

    public function hasEndedForClient(): bool
    {
        if (in_array($this->status, ['completed', 'cancelled'], true)) {
            return true;
        }

        if ($this->status !== 'confirmed') {
            return false;
        }

        return now()->greaterThanOrEqualTo($this->serviceEndTime());
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

    // ─── Status Verification ────────────────────────────────────────────────────

    /**
     * Verifica se passou do horário do agendamento sem confirmação
     */
    public function hasPassedWithoutConfirmation(): bool
    {
        return $this->status === 'pending' && now()->isAfter($this->scheduled_at);
    }

    public function isPendingOverdue(): bool
    {
        return $this->status === 'pending' && now()->isAfter($this->scheduled_at);
    }

    public function isPendingDueSoon(): bool
    {
        if ($this->status !== 'pending') {
            return false;
        }

        $minutesRemaining = now()->diffInMinutes($this->scheduled_at, false);

        return $minutesRemaining >= 0 && $minutesRemaining <= (24 * 60);
    }

    public function needsProfessionalAttention(): bool
    {
        return $this->isPendingOverdue() || $this->isPendingDueSoon();
    }

    /**
     * Verifica se o horário está próximo (falta menos de 24h e não foi confirmado)
     */
    public function isUpcomingAndUnconfirmed(): bool
    {
        return $this->isPendingDueSoon();
    }

    /**
     * Retorna horas restantes até o agendamento
     */
    public function hoursUntilScheduled(): int
    {
        return (int) now()->diffInHours($this->scheduled_at, false);
    }

    /**
     * Retorna o texto de tempo restante formatado
     */
    public function timeUntilScheduledFormatted(): string
    {
        $minutes = now()->diffInMinutes($this->scheduled_at, false);

        if ($minutes <= 0) {
            return 'menos de 1 minuto';
        }

        $days = intdiv($minutes, 1440);
        $remainingAfterDays = $minutes % 1440;
        $hours = intdiv($remainingAfterDays, 60);
        $remainingMinutes = $remainingAfterDays % 60;

        $parts = [];

        if ($days > 0) {
            $parts[] = $days . ' dia' . ($days > 1 ? 's' : '');
        }

        if ($hours > 0) {
            $parts[] = $hours . ' hora' . ($hours > 1 ? 's' : '');
        }

        if ($remainingMinutes > 0) {
            $parts[] = $remainingMinutes . ' minuto' . ($remainingMinutes > 1 ? 's' : '');
        }

        return implode(' e ', $parts);
    }

    /**
     * Verifica se deve auto-completar (passou do horário + tempo de duração).
     * Nao considera buffer adicional para a virada de status.
     */
    public function shouldAutoComplete(): bool
    {
        if ($this->status !== 'confirmed') {
            return false;
        }

        // Calcula o tempo de conclusao esperado: scheduled_at + duracao do servico
        $serviceEndTime = $this->scheduled_at->copy()
            ->addMinutes($this->service->duration ?? 0);

        return now()->isAfter($serviceEndTime);
    }

    /**
     * Verifica se o atendimento está em andamento (entre início e término previsto)
     */
    public function isCurrentlyOngoing(): bool
    {
        if ($this->status !== 'confirmed') {
            return false;
        }

        $start = $this->scheduled_at;
        $end = $this->serviceEndTime();

        return now()->greaterThanOrEqualTo($start) && now()->lessThan($end);
    }

}