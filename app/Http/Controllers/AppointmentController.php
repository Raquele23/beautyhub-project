<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Professional;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    // Tela de agendamento — cliente escolhe data e horário
    public function create(Professional $professional, Service $service)
    {
        // Verifica se o serviço pertence ao profissional
        if ($service->professional_id !== $professional->id) {
            abort(404);
        }

        return view('appointments.create', [
            'professional' => $professional,
            'service'      => $service,
            'weekdays'     => \App\Models\Availability::WEEKDAYS,
        ]);
    }

    // Retorna os slots disponíveis para uma data (chamada via fetch)
    public function slots(Professional $professional, Request $request)
    {
        $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $slots = $professional->getAvailableSlotsForDate($request->date);

        return response()->json($slots);
    }

    // Cliente confirma o agendamento
    public function store(Request $request, Professional $professional, Service $service)
    {
        $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time' => ['required', 'date_format:H:i'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $scheduledAt = $request->date . ' ' . $request->time . ':00';

        // Verifica se o slot ainda está disponível
        $alreadyBooked = Appointment::where('professional_id', $professional->id)
            ->where('scheduled_at', $scheduledAt)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($alreadyBooked) {
            return back()->withErrors(['time' => 'Este horário já foi reservado. Escolha outro.']);
        }

        Appointment::create([
            'client_id'       => Auth::id(),
            'professional_id' => $professional->id,
            'service_id'      => $service->id,
            'scheduled_at'    => $scheduledAt,
            'status'          => 'pending',
            'notes'           => $request->notes,
        ]);

        return redirect()->route('client.dashboard')
            ->with('status', 'Agendamento realizado! Aguarde a confirmação do profissional.');
    }

    // Profissional confirma o agendamento
    public function confirm(Appointment $appointment)
    {
        $this->authorizeProfessional($appointment);

        $appointment->update(['status' => 'confirmed']);

        return back()->with('status', 'Agendamento confirmado!');
    }

    // Profissional ou cliente cancela o agendamento
    public function cancel(Appointment $appointment)
    {
        $user = Auth::user();

        if ($user->isProfessional()) {
            $this->authorizeProfessional($appointment);
        } else {
            if ($appointment->client_id !== $user->id) {
                abort(403);
            }
        }

        $appointment->update(['status' => 'cancelled']);

        return back()->with('status', 'Agendamento cancelado.');
    }

    // Garante que só o profissional dono do agendamento pode agir
    private function authorizeProfessional(Appointment $appointment): void
    {
        if ($appointment->professional->user_id !== Auth::id()) {
            abort(403);
        }
    }
}