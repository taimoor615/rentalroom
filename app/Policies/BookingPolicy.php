<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BookingPolicy
{
    public function view(User $user, Booking $booking): bool
    {
         return $user->isAdmin() ||
               $user->id === $booking->user_id ||
               $user->id === $booking->booked_by ||
               $user->id === $booking->listing->host_id;
    }

    public function pay(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id && $booking->isPending();
    }

    public function cancel(User $user, Booking $booking): bool
    {
        return $user->isAdmin() ||
               $user->id === $booking->user_id ||
               $user->id === $booking->listing->host_id;
    }

    public function create(User $user)
    {
        return $user->isAdmin() || $user->isGuest();
    }

    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Booking $booking): bool
    {
        return $user->isAdmin() ||
               $user->id === $booking->listing->host_id;
    }

    public function delete(User $user, Booking $booking): bool
    {
        return $user->isAdmin();
    }

    public function updateStatus(User $user, Booking $booking): bool
    {
        return $user->isAdmin() ||
               $user->id === $booking->listing->host_id;
    }
}
