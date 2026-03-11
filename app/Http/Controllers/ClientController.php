<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function dashboard()
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
            ->with(['service', 'professional.user'])
            ->past()
            ->get();

        return view('client.dashboard', compact(
            'nextAppointment',
            'upcomingAppointments',
            'pastAppointments'
        ));
    }
}