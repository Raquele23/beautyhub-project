<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    // Retorna o nome do dia da semana
    public function getWeekdayNameAttribute(): string
    {
        return self::WEEKDAYS[$this->weekday] ?? '';
    }

    // Gera todos os slots de horário disponíveis para este dia
    public function generateSlots(): array
    {
        $slots = [];
        $current = strtotime($this->open_time);
        $close = strtotime($this->close_time);
        $interval = $this->slot_interval * 60; // converte para segundos

        while ($current < $close) {
            $slots[] = date('H:i', $current);
            $current += $interval;
        }

        return $slots;
    }
}