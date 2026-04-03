<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Notification;
use App\Models\Professional;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
            'service_id' => ['required', 'integer', 'exists:services,id'],
        ]);

        $service = $professional->services()
            ->whereKey((int) $request->input('service_id'))
            ->first();

        if (!$service) {
            return response()->json([]);
        }

        $slots = $professional->getAvailableSlotsForDate($request->date, (int) $service->duration);

        return response()->json($slots);
    }

    public function store(Request $request, Professional $professional, Service $service)
    {
        if ($service->professional_id !== $professional->id) {
            abort(404);
        }

        $request->validate([
            'date'  => ['required', 'date', 'after_or_equal:today'],
            'time'  => ['required', 'date_format:H:i'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        if (!$professional->isSlotAvailableForService($request->date, $request->time, (int) $service->duration)) {
            return back()->withErrors(['time' => 'Horário indisponível para este serviço. Escolha outro.']);
        }

        $scheduledAt = null;
        $appointment = null;

        DB::transaction(function () use ($request, $professional, $service, &$scheduledAt, &$appointment) {
            Professional::query()
                ->whereKey($professional->id)
                ->lockForUpdate()
                ->first();

            if (!$professional->fresh()->isSlotAvailableForService($request->date, $request->time, (int) $service->duration)) {
                throw ValidationException::withMessages([
                    'time' => 'Este horário acabou de ser ocupado. Escolha outro.',
                ]);
            }

            $scheduledAt = $request->date . ' ' . $request->time . ':00';

            $appointment = Appointment::create([
                'client_id'       => Auth::id(),
                'client_name'     => Auth::user()->name,
                'client_email'    => Auth::user()->email,
                'professional_id' => $professional->id,
                'service_id'      => $service->id,
                'scheduled_at'    => $scheduledAt,
                'status'          => 'pending',
                'notes'           => $request->notes,
            ]);
        });

        // Notifica o profissional sobre novo agendamento
        Notification::create([
            'user_id'        => $professional->user_id,
            'type'           => 'appointment_created',
            'message'        => Auth::user()->name . ' agendou ' . $service->name . ' para ' . $appointment->scheduled_at->format('d/m/Y \às H:i') . '.',
            'appointment_id' => $appointment->id,
        ]);

        return redirect()->route('client.appointments')
            ->with('status', 'Agendamento realizado! Aguarde a confirmação do profissional.');
    }

    public function createByProfessional(Request $request)
    {
        $professional = Auth::user()->professional;

        $services = $professional->services()
            ->orderBy('name')
            ->get();

        $suggestedDate = null;
        if ($request->filled('next_date')) {
            try {
                $suggestedDate = Carbon::parse($request->string('next_date'))->toDateString();
            } catch (\Throwable $e) {
                $suggestedDate = null;
            }
        }

        return view('professional.appointments-create', [
            'professional'   => $professional,
            'services'       => $services,
            'prefillService' => $request->integer('service_id') ?: null,
            'prefillClient'  => $request->integer('client_id') ?: null,
            'prefillName'    => $request->string('client_name')->toString(),
            'prefillEmail'   => $request->string('client_email')->toString(),
            'prefillPhone'   => $request->string('client_phone')->toString(),
            'suggestedDate'  => $suggestedDate,
        ]);
    }

    public function storeByProfessional(Request $request)
    {
        $professional = Auth::user()->professional;

        $validated = $request->validate([
            'service_id'         => ['required', 'integer', 'exists:services,id'],
            'date'               => ['required', 'date', 'after_or_equal:today'],
            'time'               => ['required', 'date_format:H:i'],
            'notes'              => ['nullable', 'string', 'max:500'],
            'client_mode'        => ['required', 'in:known,external'],
            'known_client_id'    => ['exclude_unless:client_mode,known', 'required_if:client_mode,known', 'integer', 'exists:users,id'],
            'external_name'      => ['exclude_unless:client_mode,external', 'required_if:client_mode,external', 'string', 'max:255'],
            'external_email'     => ['exclude_unless:client_mode,external', 'nullable', 'email', 'max:255'],
            'external_phone'     => ['exclude_unless:client_mode,external', 'required_if:client_mode,external', 'string', 'max:20'],
        ]);

        $service = $professional->services()
            ->whereKey($validated['service_id'])
            ->firstOrFail();

        if (!$professional->isSlotAvailableForService($validated['date'], $validated['time'], (int) $service->duration)) {
            return back()
                ->withErrors(['time' => 'Horário indisponível para este serviço. Escolha outro.'])
                ->withInput();
        }

        $clientPayload = $this->prepareProfessionalClientPayload($validated);

        $appointment = null;
        DB::transaction(function () use ($professional, $validated, $service, $clientPayload, &$appointment) {
            Professional::query()
                ->whereKey($professional->id)
                ->lockForUpdate()
                ->first();

            if (!$professional->fresh()->isSlotAvailableForService($validated['date'], $validated['time'], (int) $service->duration)) {
                throw ValidationException::withMessages([
                    'time' => 'Este horário acabou de ser ocupado. Escolha outro.',
                ]);
            }

            $scheduledAt = $validated['date'] . ' ' . $validated['time'] . ':00';

            $appointment = Appointment::create([
                ...$clientPayload,
                'professional_id' => $professional->id,
                'service_id'      => $service->id,
                'scheduled_at'    => $scheduledAt,
                'status'          => 'pending',
                'notes'           => $validated['notes'] ?? null,
            ]);
        });

        if (!empty($appointment->client_id)) {
            Notification::create([
                'user_id'        => $appointment->client_id,
                'type'           => 'appointment_created',
                'message'        => 'Seu agendamento de ' . $service->name . ' foi registrado para ' . $appointment->scheduled_at->format('d/m/Y \às H:i') . '.',
                'appointment_id' => $appointment->id,
            ]);
        }

        $redirectRoute = $request->input('source') === 'calendar'
            ? 'professional.calendar'
            : 'professional.appointments';

        return redirect()->route($redirectRoute)
            ->with('status', 'Agendamento criado e pendente de confirmação.');
    }

    public function searchKnownClients(Request $request)
    {
        $query = trim((string) $request->input('q', ''));
        if (mb_strlen($query) < 2) {
            return response()->json([]);
        }

        $clients = User::query()
            ->select(['id', 'name', 'email'])
            ->where('role', 'client')
            ->where(function ($builder) use ($query) {
                $builder
                    ->where('name', 'like', '%' . $query . '%')
                    ->orWhere('email', 'like', '%' . $query . '%');
            })
            ->orderBy('name')
            ->limit(10)
            ->get();

        return response()->json($clients);
    }

    public function confirm(Appointment $appointment)
    {
        $this->authorizeProfessional($appointment);

        abort_if($appointment->status !== 'pending', 403, 'Apenas agendamentos pendentes podem ser confirmados.');

        $appointment->update(['status' => 'confirmed']);

        // Notifica o cliente
        if (!empty($appointment->client_id)) {
            Notification::create([
                'user_id'        => $appointment->client_id,
                'type'           => 'appointment_confirmed',
                'message'        => 'Seu agendamento de ' . $appointment->service->name . ' foi confirmado para ' . $appointment->scheduled_at->format('d/m/Y \às H:i') . '.',
                'appointment_id' => $appointment->id,
            ]);
        }

        return back()->with('status', 'Agendamento confirmado!');
    }

    public function complete(Appointment $appointment)
    {
        $this->authorizeProfessional($appointment);

        abort_if($appointment->status !== 'confirmed', 403, 'Apenas agendamentos confirmados podem ser concluídos.');

        $appointment->update(['status' => 'completed']);

        // Notifica o cliente que pode avaliar
        if (!empty($appointment->client_id)) {
            Notification::create([
                'user_id'        => $appointment->client_id,
                'type'           => 'appointment_completed',
                'message'        => 'Seu atendimento de ' . $appointment->service->name . ' foi concluído. Que tal deixar uma avaliação?',
                'appointment_id' => $appointment->id,
            ]);
        }

        return back()->with('status', 'Atendimento marcado como concluído!');
    }

    public function cancel(Appointment $appointment)
    {
        $user = Auth::user();

        if ($user->isProfessional()) {
            $this->authorizeProfessional($appointment);

            // Notifica o cliente que o profissional cancelou
            if (!empty($appointment->client_id)) {
                Notification::create([
                    'user_id'        => $appointment->client_id,
                    'type'           => 'appointment_cancelled',
                    'message'        => 'Seu agendamento de ' . $appointment->service->name . ' em ' . $appointment->scheduled_at->format('d/m/Y \às H:i') . ' foi cancelado pelo profissional.',
                    'appointment_id' => $appointment->id,
                ]);
            }
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

    private function prepareProfessionalClientPayload(array $validated): array
    {
        if (($validated['client_mode'] ?? 'external') === 'known') {
            $knownClientId = (int) ($validated['known_client_id'] ?? 0);

            if ($knownClientId <= 0) {
                throw ValidationException::withMessages([
                    'known_client_id' => 'Selecione um cliente da plataforma.',
                ]);
            }

            $client = User::query()
                ->whereKey($knownClientId)
                ->where('role', 'client')
                ->firstOrFail();

            return [
                'client_id'    => $client->id,
                'client_name'  => $client->name,
                'client_email' => $client->email,
                'client_phone' => null,
            ];
        }

        $name = trim((string) ($validated['external_name'] ?? ''));

        if ($name === '') {
            throw ValidationException::withMessages([
                'external_name' => 'Informe o nome do cliente externo.',
            ]);
        }

        $phone = preg_replace('/\D+/', '', (string) ($validated['external_phone'] ?? ''));
        if ($phone === '') {
            throw ValidationException::withMessages([
                'external_phone' => 'Informe o telefone do cliente externo.',
            ]);
        }

        if (! in_array(strlen($phone), [10, 11], true)) {
            throw ValidationException::withMessages([
                'external_phone' => 'O telefone do cliente externo deve ter 10 ou 11 dígitos.',
            ]);
        }

        return [
            'client_id'    => null,
            'client_name'  => $name,
            'client_email' => $validated['external_email'] ?? null,
            'client_phone' => $phone,
        ];
    }
}