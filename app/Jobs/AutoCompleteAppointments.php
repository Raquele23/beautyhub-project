<?php

namespace App\Jobs;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class AutoCompleteAppointments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        // Busca agendamentos confirmados de profissionais com auto_complete ativo
        Appointment::query()
            ->where('status', 'confirmed')
            ->whereHas('professional', fn($q) => $q->where('auto_complete', true))
            ->with('service') // precisa da duração
            ->get()
            ->each(function (Appointment $appointment) {
                // Horário de término = scheduled_at + duração do serviço (em minutos)
                $endsAt = $appointment->scheduled_at
                    ->copy()
                    ->addMinutes($appointment->service->duration ?? 60);

                if ($endsAt->isPast()) {
                    $appointment->update(['status' => 'completed']);
                }
            });
    }
}