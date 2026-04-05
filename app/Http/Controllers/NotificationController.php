<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function open(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->update(['read_at' => now()]);

        // ── Profissional recebeu uma avaliação ───────────────────────────
        if ($notification->type === 'review_received') {
            return redirect()->route('reviews.professional.index');
        }

        // ── Cliente recebeu resposta do profissional ─────────────────────
        if ($notification->type === 'review_reply_received') {
            return redirect()->route('reviews.client.index', ['tab' => 'reviewed']);
        }
        // ─────────────────────────────────────────────────────────────────

        if (Auth::user()->isProfessional()) {
            return redirect()->route('professional.appointments');
        }

        return redirect()->route('client.appointments');
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->update(['read_at' => now()]);

        return back();
    }

    public function markAllAsRead()
    {
        Auth::user()->notifications()->unread()->update(['read_at' => now()]);

        return back();
    }
}