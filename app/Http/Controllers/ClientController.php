<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // Quando criar o model Appointment, substitua os nulls abaixo:
        // $nextAppointment     = $user->appointments()->upcoming()->first();
        // $upcomingAppointments = $user->appointments()->upcoming()->get();
        // $pastAppointments    = $user->appointments()->past()->get();

        return view('client.dashboard', [
            'nextAppointment'      => null,
            'upcomingAppointments' => collect(),
            'pastAppointments'     => collect(),
        ]);
    }
}