<?php

namespace App\Policies;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ListingPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Listing $listing): bool
    {
        return $listing->is_active;
    }

    public function create(User $user): bool
    {
        return $user->isHost();
    }

    public function update(User $user, Listing $listing): bool
    {
        return $user->isHost() && $user->id === $listing->host_id;
    }

    public function delete(User $user, Listing $listing): bool
    {
        return $user->isHost() && $user->id === $listing->host_id;
    }
}
