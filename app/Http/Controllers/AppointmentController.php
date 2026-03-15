<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Notification;
use App\Models\Professional;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function create(Professional $professional, Service $service)
    {
        if ($service->professional_id !== $professional->id) {
            abort(404);
        }

        return view('appointments.create', [
            'professional' => $professional,
            'service'      => $service,
            'weekdays'     => \App\Models\Availability::WEEKDAYS,
        ]);
    }

    public function slots(Professional $professional, Request $request)
    {
        $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $slots = $professional->getAvailableSlotsForDate($request->date);

        return response()->json($slots);
    }

    public function store(Request $request, Professional $professional, Service $service)
    {
        $request->validate([
            'date'  => ['required', 'date', 'after_or_equal:today'],
            'time'  => ['required', 'date_format:H:i'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $scheduledAt = $request->date . ' ' . $request->time . ':00';

        $alreadyBooked = Appointment::where('professional_id', $professional->id)
            ->where('scheduled_at', $scheduledAt)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($alreadyBooked) {
            return back()->withErrors(['time' => 'Este horário já foi reservado. Escolha outro.']);
        }

        $appointment = Appointment::create([
            'client_id'       => Auth::id(),
            'professional_id' => $professional->id,
            'service_id'      => $service->id,
            'scheduled_at'    => $scheduledAt,
            'status'          => 'pending',
            'notes'           => $request->notes,
        ]);

        // Notifica o profissional sobre novo agendamento
        Notification::create([
            'user_id'        => $professional->user_id,
            'type'           => 'appointment_created',
            'message'        => Auth::user()->name . ' agendou ' . $service->name . ' para ' . $appointment->scheduled_at->format('d/m/Y \às H:i') . '.',
            'appointment_id' => $appointment->id,
        ]);

        return redirect()->route('client.dashboard')
            ->with('status', 'Agendamento realizado! Aguarde a confirmação do profissional.');
    }

    public function confirm(Appointment $appointment)
    {
        $this->authorizeProfessional($appointment);

        abort_if($appointment->status !== 'pending', 403, 'Apenas agendamentos pendentes podem ser confirmados.');

        $appointment->update(['status' => 'confirmed']);

        // Notifica o cliente
        Notification::create([
            'user_id'        => $appointment->client_id,
            'type'           => 'appointment_confirmed',
            'message'        => 'Seu agendamento de ' . $appointment->service->name . ' foi confirmado para ' . $appointment->scheduled_at->format('d/m/Y \às H:i') . '.',
            'appointment_id' => $appointment->id,
        ]);

        return back()->with('status', 'Agendamento confirmado!');
    }

    public function complete(Appointment $appointment)
    {
        $this->authorizeProfessional($appointment);

        abort_if($appointment->status !== 'confirmed', 403, 'Apenas agendamentos confirmados podem ser concluídos.');

        $appointment->update(['status' => 'completed']);

        // Notifica o cliente que pode avaliar
        Notification::create([
            'user_id'        => $appointment->client_id,
            'type'           => 'appointment_completed',
            'message'        => 'Seu atendimento de ' . $appointment->service->name . ' foi concluído. Que tal deixar uma avaliação?',
            'appointment_id' => $appointment->id,
        ]);

        return back()->with('status', 'Atendimento marcado como concluído!');
    }

    public function cancel(Appointment $appointment)
    {
        $user = Auth::user();

        if ($user->isProfessional()) {
            $this->authorizeProfessional($appointment);

            // Notifica o cliente que o profissional cancelou
            Notification::create([
                'user_id'        => $appointment->client_id,
                'type'           => 'appointment_cancelled',
                'message'        => 'Seu agendamento de ' . $appointment->service->name . ' em ' . $appointment->scheduled_at->format('d/m/Y \às H:i') . ' foi cancelado pelo profissional.',
                'appointment_id' => $appointment->id,
            ]);
        } else {
            if ($appointment->client_id !== $user->id) {
                abort(403);
            }

            // Notifica o profissional que o cliente cancelou
            Notification::create([
                'user_id'        => $appointment->professional->user_id,
                'type'           => 'appointment_cancelled',
                'message'        => $user->name . ' cancelou o agendamento de ' . $appointment->service->name . ' em ' . $appointment->scheduled_at->format('d/m/Y \às H:i') . '.',
                'appointment_id' => $appointment->id,
            ]);
        }

        $appointment->update(['status' => 'cancelled']);

        return back()->with('status', 'Agendamento cancelado.');
    }

    private function authorizeProfessional(Appointment $appointment): void
    {
        if ($appointment->professional->user_id !== Auth::id()) {
            abort(403);
        }
    }
}