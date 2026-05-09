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

        // ── Serviço concluído → leva o cliente direto para avaliar ───────
        if ($notification->type === 'appointment_completed' && $notification->appointment_id) {
            return redirect()->route('reviews.create', ['appointment' => $notification->appointment_id]);
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

    /**
     * Endpoint de polling — retorna contagem de não lidas e ID da mais recente.
     * Chamado pelo frontend a cada 60 segundos via fetch().
     */
    public function poll()
    {
        $user = Auth::user();

        $unread = $user->notifications()->unread()->count();
        $latestNotification = $user->notifications()->first();

        return response()->json([
            'unread_count' => $unread,
            'latest_id'    => $latestNotification?->id,
        ]);
    }

    /**
     * Lista notificações do usuário em JSON (para atualização dinâmica).
     */
    public function list()
    {
        $user = Auth::user();
        $unread = $user->notifications()->unread()->count();
        $notifications = $user->notifications()->take(10)->get();

        $data = $notifications->map(function ($notification) {
            $iconType = $this->getIconType($notification->type);

            return [
                'id'         => $notification->id,
                'message'    => $notification->message,
                'type'       => $notification->type,
                'icon_type'  => $iconType,
                'created_at' => $notification->created_at->diffForHumans(),
                'is_unread'  => $notification->isUnread(),
                'url'        => route('notifications.open', $notification->id),
            ];
        });

        return response()->json([
            'unread_count'   => $unread,
            'notifications'  => $data,
        ]);
    }

    private function getIconType($type)
    {
        return match ($type) {
            'appointment_confirmed' => 'confirmed',
            'appointment_cancelled' => 'cancelled',
            'appointment_completed' => 'completed',
            'review_received', 'review_reply_received' => 'review',
            default => 'default',
        };
    }
}