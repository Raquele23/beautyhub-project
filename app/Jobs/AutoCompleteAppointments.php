<?php

namespace App\Jobs;

use App\Models\Appointment;
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
                }
            });
    }
}