@extends('layouts.app')
@section('title','Post a Room Listing')

@push('head')
<style>
    #pick-map { height: 320px; border-radius: 16px; cursor: crosshair; }
    .chip { cursor: pointer; user-select: none; border-radius: 999px; padding: 7px 16px; font-size: 12px; font-weight: 600; transition: all .2s; border: 1px solid rgba(255,255,255,0.12); color: rgba(255,255,255,0.6); background: #16161C; }
    .chip:hover { border-color: rgba(255,56,92,0.5); color: #fff; }
    .chip.on { border-color: #FF385C; color: #FF385C; background: rgba(255,56,92,0.15); box-shadow: 0 4px 12px rgba(255,56,92,0.2); }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-white/50 hover:text-[#FF385C] mb-3 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Dashboard
        </a>
        <h1 class="text-3xl font-extrabold text-white tracking-tight">Host Your Room on RoomRent</h1>
        <p class="text-white/60 text-sm mt-1">Provide room details, pricing, and exact location to publish your listing.</p>
    </div>

    <form action="{{ route('listings.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <!-- Section 1: Basic Info -->
        <section class="card-airbnb p-6 sm:p-8 space-y-6">
            <div class="flex items-center gap-2 border-b border-white/10 pb-4">
                <span class="w-7 h-7 bg-[#FF385C]/10 text-[#FF385C] font-extrabold rounded-full flex items-center justify-center text-xs">1</span>
                <h2 class="font-bold text-white text-lg">Property Details & Pricing</h2>
            </div>

            <div>
                <label class="block text-xs font-bold text-white/80 uppercase tracking-wider mb-2">Listing Title *</label>
                <input type="text" name="title" value="{{ old('title') }}"
                       class="input-airbnb text-sm @error('title') !border-rose-500 @enderror"
                       placeholder="e.g. Spacious Sunny Single Room in Lazimpat" maxlength="100">
                @error('title')<p class="text-rose-400 text-xs mt-1 font-semibold">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-white/80 uppercase tracking-wider mb-2">Description *</label>
                <textarea name="description" rows="4"
                          class="input-airbnb text-sm @error('description') !border-rose-500 @enderror"
                          placeholder="Describe your property — include house rules, nearby college/bus stop landmarks, and environment details...">{{ old('description') }}</textarea>
                @error('description')<p class="text-rose-400 text-xs mt-1 font-semibold">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-white/80 uppercase tracking-wider mb-2">Monthly Rent (NPR) *</label>
                    <input type="number" name="price" value="{{ old('price') }}"
                           class="input-airbnb text-sm" placeholder="8000" min="500">
                    @error('price')<p class="text-rose-400 text-xs mt-1 font-semibold">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-white/80 uppercase tracking-wider mb-2">Unlock Fee (NPR)</label>
                    <input type="number" name="unlock_fee" value="{{ old('unlock_fee', 50) }}"
                           class="input-airbnb text-sm" placeholder="50" min="10" max="1000">
                    <p class="text-white/40 text-[11px] mt-1">One-time fee tenants pay to view your phone number.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold text-white/80 uppercase tracking-wider mb-2">Room Type *</label>
                    <select name="room_type" class="input-airbnb text-sm py-3">
                        @foreach(['single'=>'Single Room','double'=>'Double Room','apartment'=>'Apartment','hostel'=>'Hostel'] as $v=>$l)
                            <option value="{{ $v }}" {{ old('room_type') == $v ? 'selected':'' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-white/80 uppercase tracking-wider mb-2">Bedrooms *</label>
                    <input type="number" name="bedrooms" value="{{ old('bedrooms',1) }}" min="1" max="20" class="input-airbnb text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-white/80 uppercase tracking-wider mb-2">Bathrooms *</label>
                    <input type="number" name="bathrooms" value="{{ old('bathrooms',1) }}" min="1" max="10" class="input-airbnb text-sm">
                </div>
            </div>
        </section>

        <!-- Section 2: Location -->
        <section class="card-airbnb p-6 sm:p-8 space-y-6">
            <div class="flex items-center gap-2 border-b border-white/10 pb-4">
                <span class="w-7 h-7 bg-[#FF385C]/10 text-[#FF385C] font-extrabold rounded-full flex items-center justify-center text-xs">2</span>
                <h2 class="font-bold text-white text-lg">Location & Contact Protection</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-white/80 uppercase tracking-wider mb-2">City *</label>
                    <input type="text" name="city" value="{{ old('city') }}" class="input-airbnb text-sm" placeholder="Kathmandu">
                    @error('city')<p class="text-rose-400 text-xs mt-1 font-semibold">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-white/80 uppercase tracking-wider mb-2">Area / Neighbourhood *</label>
                    <input type="text" name="area" value="{{ old('area') }}" class="input-airbnb text-sm" placeholder="Thamel">
                    @error('area')<p class="text-rose-400 text-xs mt-1 font-semibold">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-white/80 uppercase tracking-wider mb-2">
                    Exact Address <span class="text-[#FF385C] font-normal text-[11px]">(Protected — revealed only upon unlock)</span> *
                </label>
                <input type="text" name="exact_address" value="{{ old('exact_address') }}" class="input-airbnb text-sm" placeholder="House No. 23, Thamel Marg, Ward No. 29">
                @error('exact_address')<p class="text-rose-400 text-xs mt-1 font-semibold">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-white/80 uppercase tracking-wider mb-2">
                    Contact Phone Number <span class="text-[#FF385C] font-normal text-[11px]">(Protected — revealed only upon unlock)</span> *
                </label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="input-airbnb text-sm font-mono" placeholder="9841000000">
                @error('phone')<p class="text-rose-400 text-xs mt-1 font-semibold">{{ $message }}</p>@enderror
            </div>

            <input type="hidden" name="lat" id="lat" value="{{ old('lat', 27.7172) }}">
            <input type="hidden" name="lng" id="lng" value="{{ old('lng', 85.3240) }}">

            <div>
                <label class="block text-xs font-bold text-white/80 uppercase tracking-wider mb-2">
                    Pin Location on Map <span class="text-white/40 font-normal text-[11px]">(Click map to place exact pin)</span>
                </label>
                <div id="pick-map" class="border border-white/10"></div>
                <p class="text-white/40 text-xs mt-2 font-mono" id="coord-display">
                    Selected Pin — Lat: {{ old('lat', 27.7172) }}, Lng: {{ old('lng', 85.3240) }}
                </p>
            </div>
        </section>

        <!-- Section 3: Amenities & Photo -->
        <section class="card-airbnb p-6 sm:p-8 space-y-6">
            <div class="flex items-center gap-2 border-b border-white/10 pb-4">
                <span class="w-7 h-7 bg-[#FF385C]/10 text-[#FF385C] font-extrabold rounded-full flex items-center justify-center text-xs">3</span>
                <h2 class="font-bold text-white text-lg">Amenities & Showcase Image</h2>
            </div>

            <div>
                <label class="block text-xs font-bold text-white/80 uppercase tracking-wider mb-3">Select Available Amenities</label>
                <div class="flex flex-wrap gap-2.5">
                    @foreach(['WiFi','Parking','Kitchen','Attached Bathroom','Hot Water','Balcony','Security','Generator','Laundry','AC','Furnished','Garden'] as $a)
                        <label class="chip {{ in_array($a, old('amenities',[])) ? 'on' : '' }}">
                            <input type="checkbox" name="amenities[]" value="{{ $a }}" class="sr-only"
                                   {{ in_array($a, old('amenities',[])) ? 'checked' : '' }}>
                            {{ $a }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="pt-4">
                <label class="block text-xs font-bold text-white/80 uppercase tracking-wider mb-2">Room Photo</label>
                <div class="border-2 border-dashed border-white/15 rounded-2xl p-8 text-center hover:border-[#FF385C]/50 transition-colors bg-[#121216]" id="drop-zone">
                    <input type="file" name="image" id="img-input" accept=".jpg,.jpeg,.png,.webp" class="sr-only">
                    <div id="preview-wrap" class="hidden mb-4">
                        <img id="img-preview" class="max-h-48 mx-auto rounded-xl object-cover border border-white/10 shadow-lg" alt="Preview">
                    </div>
                    <div class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-3 text-white/40">📷</div>
                    <p class="text-white/60 text-xs mb-1">Click or drag image file here to upload</p>
                    <p class="text-white/30 text-[11px] mb-4">High resolution JPG, PNG, WebP supported</p>
                    <button type="button" onclick="document.getElementById('img-input').click()"
                            class="btn-secondary !py-2 !px-4 text-xs font-semibold">Browse File</button>
                </div>
                @error('image')<p class="text-rose-400 text-xs mt-1 font-semibold">{{ $message }}</p>@enderror
            </div>
        </section>

        <!-- Submit Button Group -->
        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="btn-airbnb flex-1 !py-3.5 text-base font-bold shadow-xl">
                Submit Listing for Verification
            </button>
            <a href="{{ route('listings.index') }}" class="btn-secondary !py-3.5 !px-6 text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
const pickMap = L.map('pick-map').setView([
    parseFloat(document.getElementById('lat').value),
    parseFloat(document.getElementById('lng').value)
], 14);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { className: 'map-tile-dark' }).addTo(pickMap);
let pin = L.marker([parseFloat(document.getElementById('lat').value), parseFloat(document.getElementById('lng').value)]).addTo(pickMap);

pickMap.on('click', e => {
    pin.setLatLng(e.latlng);
    document.getElementById('lat').value = e.latlng.lat.toFixed(7);
    document.getElementById('lng').value = e.latlng.lng.toFixed(7);
    document.getElementById('coord-display').textContent = `Selected Pin — Lat: ${e.latlng.lat.toFixed(5)}, Lng: ${e.latlng.lng.toFixed(5)}`;
});

document.querySelectorAll('.chip').forEach(chip => {
    chip.addEventListener('click', () => chip.classList.toggle('on'));
});

document.getElementById('img-input').addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('img-preview').src = e.target.result;
        document.getElementById('preview-wrap').classList.remove('hidden');
    };
    reader.readAsDataURL(file);
});
</script>
@endpush
