<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    public function review(User $user, Appointment $appointment): bool
    {
        return $user->id === $appointment->client_id;
    }

    public function reply(User $user, Review $review): bool
    {
        return $user->id === $review->professional_id
            && ! $review->hasReply();
    }

    public function delete(User $user, Review $review): bool
    {
        return $user->id === $review->client_id;
    }
}