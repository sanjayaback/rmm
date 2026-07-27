<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreListingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Listing::class);
    }

    public function rules(): array
    {
        return [
            'title'         => ['required', 'string', 'min:10', 'max:100'],
            'description'   => ['required', 'string', 'min:30', 'max:2000'],
            'price'         => ['required', 'numeric', 'min:500', 'max:500000'],
            'city'          => ['required', 'string', 'max:100'],
            'area'          => ['required', 'string', 'max:150'],
            'exact_address' => ['required', 'string', 'max:255'],
            'phone'         => ['required', 'string', 'regex:/^[0-9+\-\s]{7,20}$/'],
            'lat'           => ['required', 'numeric', 'between:-90,90'],
            'lng'           => ['required', 'numeric', 'between:-180,180'],
            'room_type'     => ['required', 'in:single,double,apartment,hostel'],
            'bedrooms'      => ['required', 'integer', 'min:1', 'max:20'],
            'bathrooms'     => ['required', 'integer', 'min:1', 'max:10'],
            'unlock_fee'    => ['nullable', 'numeric', 'min:10', 'max:1000'],
            'amenities'     => ['nullable', 'array'],
            'amenities.*'   => ['string', 'max:50'],
            'image'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ];
    }

    public function messages(): array
    {
        return [
            'price.min'   => 'Price must be at least NPR 500.',
            'phone.regex' => 'Please enter a valid phone number.',
            'image.max'   => 'Image must be smaller than 3MB.',
        ];
    }
}
