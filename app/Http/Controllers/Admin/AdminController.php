<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Listing;
use App\Models\Setting;
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

    public function editListing(Listing $listing): View
    {
        return view('admin.listings.edit', compact('listing'));
    }

    public function updateListing(Request $request, Listing $listing): RedirectResponse
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'price'         => 'required|numeric|min:0',
            'unlock_fee'    => 'required|numeric|min:0',
            'city'          => 'required|string|max:100',
            'area'          => 'required|string|max:100',
            'exact_address' => 'required|string|max:255',
            'phone'         => 'required|string|max:30',
            'room_type'     => 'required|in:single,double,apartment,hostel',
            'status'        => 'required|in:pending,approved,rejected',
        ]);

        $oldStatus = $listing->status;
        $this->listingService->update($listing, $validated, $request->file('image'));

        AuditLog::log('admin_update_listing', [
            'listing_id' => $listing->id,
            'title'      => $listing->title,
            'old_status' => $oldStatus,
            'new_status' => $listing->status,
        ], $listing);

        return redirect()->route('admin.listings.show', $listing)
            ->with('success', 'Listing updated successfully by Admin.');
    }

    public function approveListing(Listing $listing): RedirectResponse
    {
        $this->listingService->approve($listing);

        AuditLog::log('admin_approve_listing', [
            'listing_id' => $listing->id,
            'title'      => $listing->title,
        ], $listing);

        return back()->with('success', "Listing \"{$listing->title}\" approved.");
    }

    public function rejectListing(Request $request, Listing $listing): RedirectResponse
    {
        $request->validate(['reason' => 'required|string|min:10|max:500']);
        $this->listingService->reject($listing, $request->reason);

        AuditLog::log('admin_reject_listing', [
            'listing_id' => $listing->id,
            'title'      => $listing->title,
            'reason'     => $request->reason,
        ], $listing);

        return back()->with('success', "Listing \"{$listing->title}\" rejected.");
    }

    public function deleteListing(Listing $listing): RedirectResponse
    {
        $title = $listing->title;
        $id    = $listing->id;
        $this->listingService->delete($listing);

        AuditLog::log('admin_delete_listing', [
            'listing_id' => $id,
            'title'      => $title,
        ]);

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

        $oldRole = $user->role;
        $user->update(['role' => $request->role]);

        AuditLog::log('admin_update_user_role', [
            'target_user_id' => $user->id,
            'old_role'       => $oldRole,
            'new_role'       => $request->role,
        ], $user);

        return back()->with('success', "User role updated to {$request->role}.");
    }

    public function toggleUserStatus(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activated' : 'deactivated';

        AuditLog::log('admin_toggle_user_status', [
            'target_user_id' => $user->id,
            'is_active'      => $user->is_active,
        ], $user);

        return back()->with('success', "User account {$status}.");
    }

    // ── Payments & Audits ──────────────────────────────────────────

    public function payments(Request $request): View
    {
        $unlocks = Unlock::with(['user', 'listing'])
            ->when($request->status, fn($q) => $q->where('payment_status', $request->status))
            ->latest()
            ->paginate(20);

        $totalRevenue = Unlock::where('payment_status', 'completed')->sum('amount_paid');

        return view('admin.payments.index', compact('unlocks', 'totalRevenue'));
    }

    public function auditLogs(Request $request): View
    {
        $logs = AuditLog::with('user')
            ->when($request->action, fn($q) => $q->where('action', 'like', '%'.$request->action.'%'))
            ->latest()
            ->paginate(25);

        return view('admin.audit_logs', compact('logs'));
    }

    // ── Site Settings CRM ──────────────────────────────────────────

    public function settings(): View
    {
        $settings = [
            'site_name'          => Setting::get('site_name', config('app.name', 'Rentivo')),
            'support_email'      => Setting::get('support_email', 'support@rentivo.rent'),
            'support_phone'      => Setting::get('support_phone', '+977 9800000000'),
            'default_unlock_fee' => Setting::get('default_unlock_fee', config('roomrent.default_unlock_fee', 50)),
            'referral_reward'    => Setting::get('referral_reward', config('roomrent.referral_reward', 25)),
            'khalti_fake_mode'   => Setting::get('khalti_fake_mode', config('roomrent.khalti_fake_mode', false)),
        ];

        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name'          => 'required|string|max:100',
            'support_email'      => 'required|email|max:150',
            'support_phone'      => 'required|string|max:50',
            'default_unlock_fee' => 'required|numeric|min:0',
            'referral_reward'    => 'required|numeric|min:0',
            'khalti_fake_mode'   => 'nullable|boolean',
        ]);

        foreach ($validated as $key => $val) {
            if ($key === 'khalti_fake_mode') {
                Setting::set($key, $request->has('khalti_fake_mode') ? '1' : '0', 'payment');
            } else {
                Setting::set($key, (string)$val, 'general');
            }
        }

        AuditLog::log('admin_update_settings', $validated);

        return back()->with('success', 'Site settings updated successfully.');
    }
}
