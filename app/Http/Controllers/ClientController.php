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

        return view('client.home', compact('nextAppointment'));
    }

    public function appointments()
    {
        $user = Auth::user();

        // Roda o job de auto complete ao abrir a página
        AutoCompleteAppointments::dispatchSync();

        $nextAppointment = $user->appointments()
            ->with(['service', 'professional.user'])
            ->upcoming()
            ->where('status', 'confirmed')
            ->first();

        $pendingAppointments = $user->appointments()
            ->with(['service', 'professional.user'])
            ->where('status', 'pending')
            ->where('scheduled_at', '>=', now())
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
}