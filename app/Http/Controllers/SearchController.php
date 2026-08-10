<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Services\ListingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function __construct(private readonly ListingService $listingService) {}

    public function index(Request $request): View
    {
        $query = $request->input('q', $request->input('search', ''));
        $listings = $this->listingService->getPaginatedListings(
            $request->only(['q', 'search', 'city', 'room_type', 'min_price', 'max_price', 'bedrooms']),
            12
        );

        $cities = Listing::public()->distinct()->pluck('city')->sort()->values();
        $roomTypes = ['single' => 'Single Room', 'double' => 'Double Room', 'apartment' => 'Apartment', 'hostel' => 'Hostel'];

        return view('search', compact('listings', 'cities', 'roomTypes', 'query'));
    }

    public function apiSearch(Request $request): JsonResponse
    {
        $query = $request->input('q', $request->input('query', ''));
        $results = $this->listingService->searchGlobal($query, 8);

        return response()->json($results);
    }
}
