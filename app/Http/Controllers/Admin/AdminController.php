<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\Unlock;
use App\Models\User;
use App\Services\ListingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct(private readonly ListingService $listingService) {}

    public function dashboard(): View
    {
        $stats = [
            'total_users'       => User::count(),
            'total_listings'    => Listing::count(),
            'pending_listings'  => Listing::where('status', 'pending')->count(),
            'approved_listings' => Listing::where('status', 'approved')->count(),
            'total_unlocks'     => Unlock::where('payment_status', 'completed')->count(),
            'total_revenue'     => Unlock::where('payment_status', 'completed')->sum('amount_paid'),
            'new_users_today'   => User::whereDate('created_at', today())->count(),
            'unlocks_today'     => Unlock::where('payment_status', 'completed')->whereDate('paid_at', today())->count(),
        ];

        $recentListings = Listing::with('owner')->latest()->take(5)->get();
        $recentUnlocks  = Unlock::with(['user', 'listing'])
            ->where('payment_status', 'completed')
            ->latest('paid_at')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentListings', 'recentUnlocks'));
    }

    // ── Listings ──────────────────────────────────────────────────

    public function listings(Request $request): View
    {
        $listings = Listing::with('owner')
            ->withCount('unlocks')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->search, fn($q) => $q->where('title', 'like', '%'.$request->search.'%'))
            ->latest()
            ->paginate(15);

        return view('admin.listings.index', compact('listings'));
    }

    public function showListing(Listing $listing): View
    {
        $listing->load('owner', 'unlocks.user');
        return view('admin.listings.show', compact('listing'));
    }

    public function approveListing(Listing $listing): RedirectResponse
    {
        $this->listingService->approve($listing);
        return back()->with('success', "Listing \"{$listing->title}\" approved.");
    }

    public function rejectListing(Request $request, Listing $listing): RedirectResponse
    {
        $request->validate(['reason' => 'required|string|min:10|max:500']);
        $this->listingService->reject($listing, $request->reason);
        return back()->with('success', "Listing \"{$listing->title}\" rejected.");
    }

    public function deleteListing(Listing $listing): RedirectResponse
    {
        $this->listingService->delete($listing);
        return redirect()->route('admin.listings.index')->with('success', 'Listing deleted.');
    }

    // ── Users ─────────────────────────────────────────────────────

    public function users(Request $request): View
    {
        $users = User::withCount(['listings', 'unlocks'])
            ->when($request->role, fn($q) => $q->where('role', $request->role))
            ->when($request->search, fn($q) => $q
                ->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('email', 'like', '%'.$request->search.'%'))
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function showUser(User $user): View
    {
        $user->load('listings', 'unlocks.listing');
        return view('admin.users.show', compact('user'));
    }

    public function updateUserRole(Request $request, User $user): RedirectResponse
    {
        $request->validate(['role' => 'required|in:admin,owner,user']);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot change your own role.');
        }

        $user->update(['role' => $request->role]);
        return back()->with('success', "User role updated to {$request->role}.");
    }

    public function toggleUserStatus(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User account {$status}.");
    }

    // ── Payments ──────────────────────────────────────────────────

    public function payments(Request $request): View
    {
        $unlocks = Unlock::with(['user', 'listing'])
            ->when($request->status, fn($q) => $q->where('payment_status', $request->status))
            ->latest()
            ->paginate(20);

        $totalRevenue = Unlock::where('payment_status', 'completed')->sum('amount_paid');

        return view('admin.payments.index', compact('unlocks', 'totalRevenue'));
    }
}
