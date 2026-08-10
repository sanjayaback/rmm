@extends('layouts.app')

@section('title', 'Admin Edit Listing')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">✏️ Edit Listing (Admin Direct CRM)</h1>
            <p class="text-sm text-gray-500 mt-1">Listing ID: #{{ $listing->id }} — {{ $listing->title }}</p>
        </div>
        <a href="{{ route('admin.listings.show', $listing) }}" class="px-4 py-2 bg-gray-100 text-gray-700 font-bold rounded-xl text-sm hover:bg-gray-200 transition">
            ← Cancel
        </a>
    </div>

    <form action="{{ route('admin.listings.update', $listing) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Title</label>
                <input type="text" name="title" value="{{ old('title', $listing->title) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#00796B] outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Monthly Rent (NPR)</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $listing->price) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#00796B] outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Unlock Fee (NPR)</label>
                <input type="number" step="0.01" name="unlock_fee" value="{{ old('unlock_fee', $listing->unlock_fee) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#00796B] outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">City</label>
                <input type="text" name="city" value="{{ old('city', $listing->city) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#00796B] outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Area / Tole</label>
                <input type="text" name="area" value="{{ old('area', $listing->area) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#00796B] outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Exact Address (Hidden until unlocked)</label>
                <input type="text" name="exact_address" value="{{ old('exact_address', $listing->exact_address) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#00796B] outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Contact Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $listing->phone) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#00796B] outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Room Type</label>
                <select name="room_type" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#00796B] outline-none">
                    <option value="single" {{ $listing->room_type === 'single' ? 'selected' : '' }}>Single Room</option>
                    <option value="double" {{ $listing->room_type === 'double' ? 'selected' : '' }}>Double Room</option>
                    <option value="apartment" {{ $listing->room_type === 'apartment' ? 'selected' : '' }}>Apartment</option>
                    <option value="hostel" {{ $listing->room_type === 'hostel' ? 'selected' : '' }}>Hostel</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Moderation Status</label>
                <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#00796B] outline-none">
                    <option value="pending" {{ $listing->status === 'pending' ? 'selected' : '' }}>Pending Review</option>
                    <option value="approved" {{ $listing->status === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ $listing->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Description</label>
                <textarea name="description" rows="4" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#00796B] outline-none">{{ old('description', $listing->description) }}</textarea>
            </div>
        </div>

        <div class="flex justify-end space-x-3 pt-4 border-t">
            <button type="submit" class="px-6 py-2.5 bg-[#00796B] text-white font-bold rounded-xl hover:bg-[#004D40] transition">
                Update Listing
            </button>
        </div>
    </form>
</div>
@endsection
