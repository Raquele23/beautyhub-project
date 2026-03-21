<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function clientIndex(): View
    {
        $reviews = auth()->user()
            ->reviewsGiven()
            ->with(['appointment.professional.user', 'appointment.service'])
            ->latest()
            ->paginate(10);

        return view('reviews.client-index', compact('reviews'));
    }

    public function professionalIndex(): View
    {
        $reviews = auth()->user()
            ->reviewsReceived()
            ->with(['client', 'appointment.service'])
            ->latest()
            ->paginate(10);

        $starCounts = auth()->user()
            ->reviewsReceived()
            ->selectRaw('rating, count(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating')
            ->toArray();

        return view('reviews.professional-index', compact('reviews', 'starCounts'));
    }

    public function create(Appointment $appointment): View
    {
        Gate::authorize('review', $appointment);

        abort_if($appointment->status !== 'completed', 403, 'Agendamento não concluído.');
        abort_if($appointment->review()->exists(), 409, 'Agendamento já avaliado.');

        return view('reviews.create', compact('appointment'));
    }

    public function store(Request $request, Appointment $appointment): RedirectResponse
    {
        Gate::authorize('review', $appointment);

        abort_if($appointment->status !== 'completed', 403);
        abort_if($appointment->review()->exists(), 409);

        $validated = $request->validate([
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $appointment->review()->create([
            ...$validated,
            'client_id'       => auth()->id(),
            'professional_id' => $appointment->professional->user_id,
        ]);

        return redirect()
            ->route('reviews.client.index')
            ->with('success', 'Avaliação enviada com sucesso!');
    }

    public function reply(Request $request, Review $review): RedirectResponse
    {
        Gate::authorize('reply', $review);

        $validated = $request->validate([
            'professional_reply' => ['required', 'string', 'max:500'],
        ]);

        $review->update([
            ...$validated,
            'replied_at' => now(),
        ]);

        return back()->with('success', 'Resposta publicada!');
    }

    public function destroy(Review $review): RedirectResponse
    {
        Gate::authorize('delete', $review);

        abort_if(
            $review->created_at->diffInHours(now()) > 24,
            403,
            'Prazo para excluir a avaliação expirou.'
        );

        $review->delete();

        return back()->with('success', 'Avaliação removida.');
    }
}