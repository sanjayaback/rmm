<?php

namespace App\Policies;

use App\Models\Listing;
use App\Models\User;

class ListingPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) return true;
        return null;
    }

    public function viewAny(?User $user): bool { return true; }

    public function view(?User $user, Listing $listing): bool
    {
        if ($listing->status === 'approved') return true;
        if (!$user) return false;
        return $user->id === $listing->user_id;
    }

    public function create(User $user): bool  { return $user->isOwner() || $user->isAdmin(); }
    public function update(User $user, Listing $listing): bool { return $user->id === $listing->user_id; }
    public function delete(User $user, Listing $listing): bool { return $user->id === $listing->user_id; }
    public function approve(User $user, Listing $listing): bool { return $user->isAdmin(); }
    public function reject(User $user, Listing $listing): bool  { return $user->isAdmin(); }

    public function unlock(User $user, Listing $listing): bool
    {
        if ($user->id === $listing->user_id) return false;
        return $listing->status === 'approved' && $listing->is_available;
    }
}
