<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreListingRequest;
use App\Http\Requests\UpdateListingRequest;
use App\Models\Listing;
use App\Services\ListingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ListingController extends Controller
{
    public function __construct(private readonly ListingService $listingService) {}

    /** Owner's listing dashboard */
    public function index(): View
    {
        $listings = Listing::where('user_id', auth()->id())
            ->withCount('unlocks')
            ->latest()
            ->paginate(10);

        return view('listings.index', compact('listings'));
    }

    /** Public browse page */
    public function browse(): View
    {
        $listings = $this->listingService->getPaginatedListings(
            request()->only(['search', 'q', 'city', 'room_type', 'min_price', 'max_price', 'bedrooms'])
        );
        $cities = Listing::public()->distinct()->pluck('city')->sort()->values();

        return view('listings.browse', compact('listings', 'cities'));
    }

    /** Single listing detail */
    public function show(Listing $listing): View
    {
        $this->authorize('view', $listing);
        $listing->incrementViews();
        $listing->load('owner');

        $user        = auth()->user();
        $isOwner     = $user && $user->id === $listing->user_id;
        $isUnlocked  = !$isOwner && $user && $listing->isUnlockedBy($user);
        $unlockedData = null;

        if ($isUnlocked || $isOwner) {
            $unlockedData = [
                'phone'         => $listing->phone,
                'exact_address' => $listing->exact_address,
                'lat'           => $listing->lat,
                'lng'           => $listing->lng,
            ];
        }

        return view('listings.show', compact('listing','isUnlocked','isOwner','unlockedData'));
    }

    /** Create form */
    public function create(): View
    {
        $this->authorize('create', Listing::class);
        return view('listings.create');
    }

    /** Store listing */
    public function store(StoreListingRequest $request): RedirectResponse
    {
        $this->listingService->create(auth()->user(), $request->validated(), $request->file('image'));

        return redirect()->route('listings.index')
            ->with('success', 'Listing submitted for review! You\'ll be notified once approved.');
    }

    /** Edit form */
    public function edit(Listing $listing): View
    {
        $this->authorize('update', $listing);
        return view('listings.edit', compact('listing'));
    }

    /** Update listing */
    public function update(UpdateListingRequest $request, Listing $listing): RedirectResponse
    {
        $this->listingService->update($listing, $request->validated(), $request->file('image'));

        return redirect()->route('listings.index')
            ->with('success', 'Listing updated successfully.');
    }

    /** Delete listing */
    public function destroy(Listing $listing): RedirectResponse
    {
        $this->authorize('delete', $listing);
        $this->listingService->delete($listing);

        return redirect()->route('listings.index')->with('success', 'Listing deleted.');
    }

    /** Map homepage */
    public function map(): View
    {
        $listingsJson = $this->listingService->getMapListingsJson(request('city'), request('search'));
        $cities       = Listing::public()->distinct()->pluck('city')->sort()->values();

        return view('home', compact('listingsJson', 'cities'));
    }

    /** API: listings JSON for map */
    public function apiMap()
    {
        $listings = $this->listingService->getMapListings(request('city'), request('search'));
        return response()->json($listings->map(fn($l) => $l->getPublicData()));
    }
}
