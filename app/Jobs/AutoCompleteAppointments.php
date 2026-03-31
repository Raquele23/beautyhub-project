<?php

namespace App\Jobs;

use App\Models\Appointment;
use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AutoCompleteAppointments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        Appointment::query()
            ->where('status', 'confirmed')
            ->whereHas('professional', fn($q) => $q->where('auto_complete', true))
            ->with('service')
            ->get()
            ->each(function (Appointment $appointment) {
                $endsAt = $appointment->scheduled_at
                    ->copy()
                    ->addMinutes($appointment->service->duration ?? 60);

                if ($endsAt->isPast()) {
                    $appointment->update(['status' => 'completed']);

                    if (!empty($appointment->client_id)) {
                        Notification::create([
                            'user_id'        => $appointment->client_id,
                            'type'           => 'appointment_completed',
                            'message'        => 'Seu atendimento de ' . $appointment->service->name . ' foi concluído. Que tal deixar uma avaliação?',
                            'appointment_id' => $appointment->id,
                        ]);
                    }
                }
            });
    }
}