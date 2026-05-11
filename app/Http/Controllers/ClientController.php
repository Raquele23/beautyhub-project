<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function home()
    {
        $user = Auth::user();

        $nextAppointment = $user->appointments()
            ->with(['service', 'professional.user'])
            ->where('status', 'confirmed')
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')
            ->first();

        $pendingAppointmentsCount = $user->appointments()
            ->where('status', 'pending')
            ->count();

        return view('client.home', compact(
            'nextAppointment',
            'pendingAppointmentsCount'
        ));
    }

    public function appointments()
    {
        $user = Auth::user();

        $appointments = $user->appointments()
            ->with(['service', 'professional.user', 'review'])
            ->orderBy('scheduled_at')
            ->get();

        $confirmedAppointments = $appointments->filter(function ($appointment) {
            return $appointment->status === 'confirmed';
        });

        $nextAppointment = $confirmedAppointments
            ->first(function ($appointment) {
                return $appointment->scheduled_at->greaterThanOrEqualTo(now());
            });

        $ongoingAppointment = $confirmedAppointments
            ->first(function ($appointment) {
                return $appointment->isCurrentlyOngoing();
            });

        $pendingAppointments = $user->appointments()
            ->with(['service', 'professional.user'])
            ->where('status', 'pending')
            ->orderBy('scheduled_at')
            ->get();

        $upcomingAppointments = $confirmedAppointments
            ->filter(function ($appointment) {
                return $appointment->scheduled_at->greaterThanOrEqualTo(now());
            })
            ->values();

        $ongoingAppointments = $confirmedAppointments
            ->filter(function ($appointment) {
                return $appointment->isCurrentlyOngoing();
            })
            ->sortByDesc('scheduled_at')
            ->values();

        $pastAppointments = $appointments
            ->filter(function ($appointment) {
                return $appointment->hasEndedForClient();
            })
            ->sortByDesc('scheduled_at')
            ->values();

        return view('client.appointments', compact(
            'nextAppointment',
            'ongoingAppointment',
            'pendingAppointments',
            'upcomingAppointments',
            'ongoingAppointments',
            'pastAppointments'
        ));
    }

    public function calendar()
    {
        $user = Auth::user();

        $appointmentsJson = $user->appointments()
            ->with(['service', 'professional.user'])
            ->orderBy('scheduled_at')
            ->get()
            ->map(function ($a) {
                $dt = $a->scheduled_at;
                return [
                    'id'           => $a->id,
                    'date'         => $dt->format('Y-m-d'),
                    'time'         => $dt->format('H:i'),
                    'service'      => $a->service->name ?? 'Serviço',
                    'professional' => $a->professional->user->name ?? '',
                    'price'        => $a->service->price
                                        ? number_format($a->service->price, 2, ',', '.')
                                        : null,
                    'status'       => $a->status,
                ];
            });

        return view('client.calendar', compact('appointmentsJson'));
    }
}