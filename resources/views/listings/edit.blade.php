@extends('layouts.app')
@section('title','Edit Listing')

@push('head')
<style>
    #pick-map{height:260px;border-radius:12px;cursor:crosshair}
    .chip{cursor:pointer;user-select:none;border-radius:999px;padding:6px 14px;font-size:13px;transition:all .2s;border:1px solid rgba(255,255,255,.15);color:rgba(255,255,255,.5)}
    .chip:hover,.chip.on{border-color:#F97316;color:#F97316;background:rgba(249,115,22,.1)}
</style>
@endpush

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <a href="{{ route('listings.index') }}" class="inline-flex items-center gap-2 text-white/50 hover:text-orange-400 text-sm mb-4 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>My Listings
        </a>
        <h1 class="font-display text-3xl font-bold text-white">Edit Listing</h1>
        @if($listing->status === 'rejected')
        <div class="mt-3 bg-red-500/10 border border-red-500/30 rounded-xl p-3 text-red-400 text-sm">
            <strong>Rejected:</strong> {{ $listing->rejection_reason }} — Saving will resubmit for review.
        </div>
        @endif
    </div>

    <form action="{{ route('listings.update', $listing) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <section class="bg-[#111113] border border-white/[0.08] rounded-2xl p-6 space-y-4">
            <h2 class="font-display font-semibold text-white">Basic Information</h2>
            <div>
                <label class="block text-sm text-white/60 mb-1.5">Title *</label>
                <input type="text" name="title" value="{{ old('title', $listing->title) }}" maxlength="100"
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-white/25 focus:outline-none focus:border-orange-500/60 transition-all @error('title') border-red-500/60 @enderror">
                @error('title')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm text-white/60 mb-1.5">Description *</label>
                <textarea name="description" rows="4"
                          class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-white/25 focus:outline-none focus:border-orange-500/60 transition-all">{{ old('description', $listing->description) }}</textarea>
                @error('description')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-white/60 mb-1.5">Monthly Rent (NPR) *</label>
                    <input type="number" name="price" value="{{ old('price', $listing->price) }}" min="500"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-orange-500/60 transition-all">
                </div>
                <div>
                    <label class="block text-sm text-white/60 mb-1.5">Unlock Fee (NPR)</label>
                    <input type="number" name="unlock_fee" value="{{ old('unlock_fee', $listing->unlock_fee) }}" min="10"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-orange-500/60 transition-all">
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm text-white/60 mb-1.5">Room Type</label>
                    <select name="room_type" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-orange-500/60 transition-all">
                        @foreach(['single'=>'Single','double'=>'Double','apartment'=>'Apartment','hostel'=>'Hostel'] as $v=>$l)
                            <option value="{{ $v }}" {{ old('room_type',$listing->room_type)==$v?'selected':'' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-white/60 mb-1.5">Bedrooms</label>
                    <input type="number" name="bedrooms" value="{{ old('bedrooms',$listing->bedrooms) }}" min="1" max="20"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-orange-500/60 transition-all">
                </div>
                <div>
                    <label class="block text-sm text-white/60 mb-1.5">Bathrooms</label>
                    <input type="number" name="bathrooms" value="{{ old('bathrooms',$listing->bathrooms) }}" min="1" max="10"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-orange-500/60 transition-all">
                </div>
            </div>
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_available" id="avail" value="1"
                       {{ old('is_available', $listing->is_available) ? 'checked' : '' }}
                       class="w-4 h-4 accent-orange-500">
                <label for="avail" class="text-sm text-white/70">Room is currently available</label>
            </div>
        </section>

        <section class="bg-[#111113] border border-white/[0.08] rounded-2xl p-6 space-y-4">
            <h2 class="font-display font-semibold text-white">Location</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-white/60 mb-1.5">City *</label>
                    <input type="text" name="city" value="{{ old('city',$listing->city) }}"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-orange-500/60 transition-all">
                </div>
                <div>
                    <label class="block text-sm text-white/60 mb-1.5">Area *</label>
                    <input type="text" name="area" value="{{ old('area',$listing->area) }}"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-orange-500/60 transition-all">
                </div>
            </div>
            <div>
                <label class="block text-sm text-white/60 mb-1.5">Exact Address (hidden)</label>
                <input type="text" name="exact_address" value="{{ old('exact_address',$listing->exact_address) }}"
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-orange-500/60 transition-all">
            </div>
            <div>
                <label class="block text-sm text-white/60 mb-1.5">Contact Phone (hidden)</label>
                <input type="text" name="phone" value="{{ old('phone',$listing->phone) }}"
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-orange-500/60 transition-all">
            </div>
            <input type="hidden" name="lat" id="lat" value="{{ old('lat',$listing->lat) }}">
            <input type="hidden" name="lng" id="lng" value="{{ old('lng',$listing->lng) }}">
            <div>
                <label class="block text-sm text-white/60 mb-2">Update Pin <span class="text-white/30 text-xs">(click map)</span></label>
                <div id="pick-map"></div>
            </div>
        </section>

        <section class="bg-[#111113] border border-white/[0.08] rounded-2xl p-6">
            <h2 class="font-display font-semibold text-white mb-4">Amenities</h2>
            <div class="flex flex-wrap gap-2">
                @foreach(['WiFi','Parking','Kitchen','Attached Bathroom','Hot Water','Balcony','Security','Generator','Laundry','AC','Furnished','Garden'] as $a)
                <label class="chip {{ in_array($a, old('amenities',$listing->amenities??[])) ? 'on':'' }}">
                    <input type="checkbox" name="amenities[]" value="{{ $a }}" class="sr-only"
                           {{ in_array($a, old('amenities',$listing->amenities??[])) ? 'checked':'' }}>{{ $a }}
                </label>
                @endforeach
            </div>
        </section>

        <section class="bg-[#111113] border border-white/[0.08] rounded-2xl p-6">
            <h2 class="font-display font-semibold text-white mb-4">Room Photo</h2>
            @if($listing->image_path)
            <div class="mb-4">
                <img src="{{ $listing->image_url }}" class="h-40 rounded-xl object-cover" alt="Current">
                <p class="text-white/30 text-xs mt-1">Current photo — upload new to replace</p>
            </div>
            @endif
            <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp"
                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white/60 text-sm cursor-pointer focus:outline-none transition-all">
            @error('image')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
        </section>

        <div class="flex gap-3">
            <button type="submit"
                    class="flex-1 bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3.5 rounded-xl transition-all">Save Changes</button>
            <a href="{{ route('listings.index') }}"
               class="border border-white/10 text-white/60 hover:text-white px-6 py-3.5 rounded-xl transition-all text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
const lat0 = parseFloat(document.getElementById('lat').value);
const lng0 = parseFloat(document.getElementById('lng').value);
const pickMap = L.map('pick-map').setView([lat0, lng0], 15);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { className: 'map-tile-dark' }).addTo(pickMap);
let pin = L.marker([lat0, lng0]).addTo(pickMap);
pickMap.on('click', e => {
    pin.setLatLng(e.latlng);
    document.getElementById('lat').value = e.latlng.lat.toFixed(7);
    document.getElementById('lng').value = e.latlng.lng.toFixed(7);
});
document.querySelectorAll('.chip').forEach(c => c.addEventListener('click', () => c.classList.toggle('on')));
</script>
@endpush
