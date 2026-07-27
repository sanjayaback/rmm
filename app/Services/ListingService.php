<?php

namespace App\Services;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;

class ListingService
{
    private const APPROX_OFFSET = 0.003; // ~300 m

    public function create(User $owner, array $data, ?UploadedFile $image = null): Listing
    {
        return Listing::create([
            'user_id'       => $owner->id,
            'title'         => $data['title'],
            'description'   => $data['description'],
            'price'         => $data['price'],
            'city'          => $data['city'],
            'area'          => $data['area'],
            'exact_address' => $data['exact_address'],
            'phone'         => $data['phone'],
            'lat'           => $data['lat'],
            'lng'           => $data['lng'],
            'approx_lat'    => $this->offset((float)$data['lat']),
            'approx_lng'    => $this->offset((float)$data['lng']),
            'image_path'    => $image ? $image->store('listings', 'public') : null,
            'unlock_fee'    => $data['unlock_fee'] ?? config('roomrent.default_unlock_fee', 50),
            'bedrooms'      => $data['bedrooms']   ?? 1,
            'bathrooms'     => $data['bathrooms']  ?? 1,
            'room_type'     => $data['room_type']  ?? 'single',
            'amenities'     => $data['amenities']  ?? [],
            'status'        => 'pending',
        ]);
    }

    public function update(Listing $listing, array $data, ?UploadedFile $image = null): Listing
    {
        if ($image) {
            if ($listing->image_path) Storage::disk('public')->delete($listing->image_path);
            $data['image_path'] = $image->store('listings', 'public');
        }

        if (isset($data['lat']) || isset($data['lng'])) {
            $data['approx_lat'] = $this->offset((float)($data['lat'] ?? $listing->lat));
            $data['approx_lng'] = $this->offset((float)($data['lng'] ?? $listing->lng));
        }

        if ($listing->status === 'rejected') {
            $data['status']           = 'pending';
            $data['rejection_reason'] = null;
        }

        $listing->update($data);
        return $listing->fresh();
    }

    public function delete(Listing $listing): void
    {
        if ($listing->image_path) Storage::disk('public')->delete($listing->image_path);
        $listing->delete();
    }

    public function approve(Listing $listing): Listing
    {
        $listing->update(['status' => 'approved', 'rejection_reason' => null]);
        return $listing;
    }

    public function reject(Listing $listing, string $reason): Listing
    {
        $listing->update(['status' => 'rejected', 'rejection_reason' => $reason]);
        return $listing;
    }

    public function getMapListings(?string $city = null, ?string $search = null): Collection
    {
        $q = Listing::forMap();
        if ($city) $q->where('city', $city);
        if (!empty($search)) {
            $term = trim($search);
            $q->where(function($sub) use ($term) {
                $sub->where('title', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%")
                    ->orWhere('area', 'like', "%{$term}%")
                    ->orWhere('city', 'like', "%{$term}%")
                    ->orWhere('room_type', 'like', "%{$term}%");
            });
        }
        return $q->latest()->get();
    }

    public function getMapListingsJson(?string $city = null, ?string $search = null): string
    {
        return $this->getMapListings($city, $search)
            ->map(fn(Listing $l) => $l->getPublicData())
            ->toJson();
    }

    public function getPaginatedListings(array $filters = [], int $perPage = 12)
    {
        $q = Listing::public()->with('owner');

        $searchTerm = $filters['search'] ?? $filters['q'] ?? null;
        if (!empty($searchTerm)) {
            $term = trim($searchTerm);
            $q->where(function($sub) use ($term) {
                $sub->where('title', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%")
                    ->orWhere('area', 'like', "%{$term}%")
                    ->orWhere('city', 'like', "%{$term}%")
                    ->orWhere('room_type', 'like', "%{$term}%");
            });
        }

        if (!empty($filters['city']))       $q->where('city', $filters['city']);
        if (!empty($filters['room_type']))  $q->where('room_type', $filters['room_type']);
        if (!empty($filters['min_price']))  $q->where('price', '>=', $filters['min_price']);
        if (!empty($filters['max_price']))  $q->where('price', '<=', $filters['max_price']);
        if (!empty($filters['bedrooms']))   $q->where('bedrooms', $filters['bedrooms']);

        return $q->latest()->paginate($perPage)->withQueryString();
    }

    private function offset(float $coord): float
    {
        $delta = (mt_rand(-100, 100) / 100) * self::APPROX_OFFSET;
        return round($coord + $delta, 7);
    }
}
