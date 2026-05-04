<?php

namespace App\Http\Controllers;

use App\Jobs\AutoCompleteAppointments;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function home()
    {
        $user = Auth::user();

        $nextAppointment = $user->appointments()
            ->with(['service', 'professional.user'])
            ->upcoming()
            ->where('status', 'confirmed')
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

        AutoCompleteAppointments::dispatchSync();

        $nextAppointment = $user->appointments()
            ->with(['service', 'professional.user'])
            ->upcoming()
            ->where('status', 'confirmed')
            ->first();

        $pendingAppointments = $user->appointments()
            ->with(['service', 'professional.user'])
            ->where('status', 'pending')
            ->orderBy('scheduled_at')
            ->get();

        $upcomingAppointments = $user->appointments()
            ->with(['service', 'professional.user'])
            ->where('status', 'confirmed')
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')
            ->get();

        $pastAppointments = $user->appointments()
            ->with(['service', 'professional.user', 'review'])
            ->past()
            ->get();

        return view('client.appointments', compact(
            'nextAppointment',
            'pendingAppointments',
            'upcomingAppointments',
            'pastAppointments'
        ));
    }

    public function calendar()
    {
        $user = Auth::user();

        AutoCompleteAppointments::dispatchSync();

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