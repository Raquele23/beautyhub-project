<?php

namespace App\Http\Controllers;

use App\Jobs\AutoCompleteAppointments;
use App\Models\Professional;
use App\Models\Review;
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
            ->where('scheduled_at', '>=', now())
            ->count();

        $nearbyRecommendations = Professional::with(['user', 'services'])
            ->where('user_id', '!=', $user->id)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->whereHas('services')
            ->take(8)
            ->get();

        $professionalUserIds = $nearbyRecommendations->pluck('user_id')->unique()->values();

        $ratingSummary = Review::query()
            ->selectRaw('professional_id, ROUND(AVG(rating), 1) as avg_rating, COUNT(*) as ratings_count')
            ->whereIn('professional_id', $professionalUserIds)
            ->groupBy('professional_id')
            ->get()
            ->keyBy('professional_id');

        return view('client.home', compact(
            'nextAppointment',
            'pendingAppointmentsCount',
            'nearbyRecommendations',
            'ratingSummary'
        ));
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