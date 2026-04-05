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

    public const GRID_STEP_MINUTES = 5;

    protected $fillable = [
        'professional_id',
        'weekday',
        'open_time',
        'close_time',
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

        $current = $this->alignToGrid($open->copy());
        while ($current->lessThan($close)) {
            $slotStart = $current->copy();
            $slotEnd = $slotStart->copy()->addMinutes($serviceDurationMinutes);

            if ($slotEnd->greaterThan($close)) {
                $current->addMinutes(self::GRID_STEP_MINUTES);
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

            $current->addMinutes(self::GRID_STEP_MINUTES);
        }

        return $slots;
    }

    private function alignToGrid(Carbon $time): Carbon
    {
        $remainder = $time->minute % self::GRID_STEP_MINUTES;

        if ($remainder === 0 && (int) $time->second === 0) {
            return $time->startOfMinute();
        }

        $minutesToAdd = self::GRID_STEP_MINUTES - $remainder;

        return $time->addMinutes($minutesToAdd)->startOfMinute();
    }
}