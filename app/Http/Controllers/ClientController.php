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
            ->upcoming()
            ->where('status', 'confirmed')
            ->first();

        return view('client.home', compact('nextAppointment'));
    }

    public function appointments()
    {
        $user = Auth::user();

        $nextAppointment = $user->appointments()
            ->with(['service', 'professional.user'])
            ->upcoming()
            ->first();

        $upcomingAppointments = $user->appointments()
            ->with(['service', 'professional.user'])
            ->upcoming()
            ->get();

        $pastAppointments = $user->appointments()
            ->with(['service', 'professional.user', 'review'])
            ->past()
            ->get();

        return view('client.appointments', compact(
            'nextAppointment',
            'upcomingAppointments',
            'pastAppointments'
        ));
    }
}