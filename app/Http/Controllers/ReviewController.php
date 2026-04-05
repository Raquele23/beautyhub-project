<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Notification;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function clientIndex(Request $request): View
    {
        $activeTab = $request->query('tab', 'pending');

        if (!in_array($activeTab, ['pending', 'reviewed'], true)) {
            $activeTab = 'pending';
        }

        $appointmentsToReview = auth()->user()
            ->appointments()
            ->with(['professional.user', 'service'])
            ->where('status', 'completed')
            ->whereDoesntHave('review')
            ->orderByDesc('scheduled_at')
            ->get();

        $reviews = auth()->user()
            ->reviewsGiven()
            ->with(['appointment.professional.user', 'appointment.service'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('reviews.client-index', compact('reviews', 'appointmentsToReview', 'activeTab'));
    }

    public function professionalIndex(Request $request): View
    {
        $activeTab = $request->query('tab', 'pending');

        if (!in_array($activeTab, ['pending', 'replied', 'all'], true)) {
            $activeTab = 'pending';
        }

        $reviewsQuery = auth()->user()
            ->reviewsReceived()
            ->with(['client', 'appointment.service']);

        if ($activeTab === 'pending') {
            $reviewsQuery->whereNull('professional_reply');
        } elseif ($activeTab === 'replied') {
            $reviewsQuery->whereNotNull('professional_reply');
        }

        $reviews = $reviewsQuery
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $pendingRepliesCount = auth()->user()
            ->reviewsReceived()
            ->whereNull('professional_reply')
            ->count();

        $repliedCount = auth()->user()
            ->reviewsReceived()
            ->whereNotNull('professional_reply')
            ->count();

        $totalReviewsCount = $pendingRepliesCount + $repliedCount;

        $starCounts = auth()->user()
            ->reviewsReceived()
            ->selectRaw('rating, count(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating')
            ->toArray();

        return view('reviews.professional-index', compact(
            'reviews',
            'starCounts',
            'activeTab',
            'pendingRepliesCount',
            'repliedCount',
            'totalReviewsCount'
        ));
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

        $review = $appointment->review()->create([
            ...$validated,
            'client_id'       => auth()->id(),
            'professional_id' => $appointment->professional->user_id,
        ]);

        // ── Notificação para o profissional ──────────────────────────────
        Notification::create([
            'user_id'        => $appointment->professional->user_id,
            'type'           => 'review_received',
            'message'        => auth()->user()->name . ' avaliou o serviço "' . $appointment->service->name . '".',
            'appointment_id' => $appointment->id,
            'review_id'      => $review->id,
        ]);
        // ─────────────────────────────────────────────────────────────────

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

        // ── Notificação para o cliente ────────────────────────────────────
        Notification::create([
            'user_id'        => $review->client_id,
            'type'           => 'review_reply_received',
            'message'        => auth()->user()->name . ' respondeu à sua avaliação de "' . $review->appointment->service->name . '".',
            'appointment_id' => $review->appointment_id,
            'review_id'      => $review->id,
        ]);
        // ─────────────────────────────────────────────────────────────────

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