<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Availability extends Model
{
    use HasFactory;

    protected $fillable = [
        'professional_id',
        'weekday',
        'open_time',
        'close_time',
        'slot_interval',
    ];

    // Nomes dos dias da semana para exibição
    const WEEKDAYS = [
        0 => 'Domingo',
        1 => 'Segunda-feira',
        2 => 'Terça-feira',
        3 => 'Quarta-feira',
        4 => 'Quinta-feira',
        5 => 'Sexta-feira',
        6 => 'Sábado',
    ];

    public function professional(): BelongsTo
    {
        return $this->belongsTo(Professional::class);
    }

    public function breaks(): HasMany
    {
        return $this->hasMany(AvailabilityBreak::class)->orderBy('start_time');
    }

    // Retorna o nome do dia da semana
    public function getWeekdayNameAttribute(): string
    {
        return self::WEEKDAYS[$this->weekday] ?? '';
    }

    // Gera todos os slots de horário possíveis para este dia.
    public function generateSlots(string $date, int $serviceDurationMinutes, array $blockedRanges = []): array
    {
        if ($serviceDurationMinutes <= 0) {
            return [];
        }

        $slots = [];
        $open = Carbon::parse("{$date} {$this->open_time}");
        $close = Carbon::parse("{$date} {$this->close_time}");

        if ($close->lessThanOrEqualTo($open)) {
            return [];
        }

        $current = $open->copy();
        while ($current->lessThan($close)) {
            $slotStart = $current->copy();
            $slotEnd = $slotStart->copy()->addMinutes($serviceDurationMinutes);

            if ($slotEnd->greaterThan($close)) {
                $current->addMinutes($this->slot_interval);
                continue;
            }

            $overlapsBlockedRange = false;
            foreach ($blockedRanges as [$blockedStart, $blockedEnd]) {
                if ($slotStart->lessThan($blockedEnd) && $slotEnd->greaterThan($blockedStart)) {
                    $overlapsBlockedRange = true;
                    break;
                }
            }

            if (!$overlapsBlockedRange) {
                $slots[] = $slotStart->format('H:i');
            }

            $current->addMinutes($this->slot_interval);
        }

        return $slots;
    }
}