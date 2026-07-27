<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Unlock;
use App\Services\UnlockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UnlockController extends Controller
{
    public function __construct(private readonly UnlockService $unlockService) {}

    public function show(Listing $listing): View|RedirectResponse
    {
        $this->authorize('unlock', $listing);
        $user = auth()->user();

        if ($this->unlockService->isUnlocked($user, $listing)) {
            return redirect()->route('listings.show', $listing)
                ->with('info', 'You have already unlocked this listing.');
        }

        $isFakeMode = config('roomrent.khalti_fake_mode', true);
        return view('unlocks.show', compact('listing', 'isFakeMode'));
    }

    public function initiate(Listing $listing): RedirectResponse
    {
        $this->authorize('unlock', $listing);
        $user = auth()->user();

        if ($this->unlockService->isUnlocked($user, $listing)) {
            return redirect()->route('listings.show', $listing)->with('info', 'Already unlocked.');
        }

        $unlock = $this->unlockService->initiate($user, $listing);

        return redirect()->route('unlocks.payment', ['listing' => $listing->id, 'unlock' => $unlock->id]);
    }

    public function payment(Listing $listing, Unlock $unlock): View|RedirectResponse
    {
        if ($unlock->user_id !== auth()->id()) abort(403);

        if ($unlock->isCompleted()) {
            return redirect()->route('listings.show', $listing)->with('success', 'Listing already unlocked!');
        }

        $isFakeMode      = config('roomrent.khalti_fake_mode', true);
        $khaltiPublicKey = config('roomrent.khalti_public_key', '');

        return view('unlocks.payment', compact('listing', 'unlock', 'isFakeMode', 'khaltiPublicKey'));
    }

    public function process(Request $request, Listing $listing, Unlock $unlock): RedirectResponse
    {
        if ($unlock->user_id !== auth()->id()) abort(403);

        if ($unlock->isCompleted()) {
            return redirect()->route('listings.show', $listing)->with('success', 'Listing already unlocked!');
        }

        $token  = $request->input('token', 'fake-token');
        $result = $this->unlockService->processPayment($unlock, $token);

        if ($result['success']) {
            return redirect()->route('listings.show', $listing)
                ->with('success', '🎉 Payment successful! Full details are now visible.');
        }

        return redirect()->route('unlocks.payment', compact('listing', 'unlock'))
            ->with('error', 'Payment failed: ' . ($result['error'] ?? 'Please try again.'));
    }

    public function history(): View
    {
        $unlocks = $this->unlockService->getUserUnlocks(auth()->user());
        return view('unlocks.history', compact('unlocks'));
    }

    public function khaltiCallback(Request $request): RedirectResponse
    {
        $pidx   = $request->query('pidx');
        $status = $request->query('status');

        if ($status !== 'Completed' || !$pidx) {
            return redirect()->route('home')->with('error', 'Payment was not completed.');
        }

        $unlock = Unlock::where('khalti_idx', $pidx)->first();
        if (!$unlock) {
            return redirect()->route('home')->with('error', 'Payment record not found.');
        }

        if (!$unlock->isCompleted()) {
            $this->unlockService->complete($unlock, $request->all());
        }

        return redirect()->route('listings.show', $unlock->listing_id)
            ->with('success', '🎉 Payment successful! Full details are now visible.');
    }
}
