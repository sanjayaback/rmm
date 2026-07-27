<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Unlock;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View|\Illuminate\Http\RedirectResponse
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isOwner()) {
            return $this->ownerDashboard($user);
        }

        return $this->userDashboard($user);
    }

    private function ownerDashboard($user): View
    {
        $listingIds = $user->listings()->pluck('id');

        $stats = [
            'total_listings' => $user->listings()->count(),
            'approved'       => $user->listings()->where('status', 'approved')->count(),
            'pending'        => $user->listings()->where('status', 'pending')->count(),
            'total_unlocks'  => Unlock::whereIn('listing_id', $listingIds)->where('payment_status', 'completed')->count(),
            'total_earned'   => Unlock::whereIn('listing_id', $listingIds)->where('payment_status', 'completed')->sum('amount_paid'),
            'total_views'    => $user->listings()->sum('views'),
        ];

        $listings = $user->listings()
            ->withCount(['unlocks' => fn($q) => $q->where('payment_status', 'completed')])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.owner', compact('stats', 'listings'));
    }

    private function userDashboard($user): View
    {
        $recentUnlocks = Unlock::with('listing')
            ->where('user_id', $user->id)
            ->where('payment_status', 'completed')
            ->latest('paid_at')
            ->take(6)
            ->get();

        $stats = [
            'total_unlocks' => Unlock::where('user_id', $user->id)->where('payment_status', 'completed')->count(),
            'total_spent'   => Unlock::where('user_id', $user->id)->where('payment_status', 'completed')->sum('amount_paid'),
        ];

        return view('dashboard.user', compact('recentUnlocks', 'stats'));
    }
}
