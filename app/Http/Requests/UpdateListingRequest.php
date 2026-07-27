<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateListingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('listing'));
    }

    public function rules(): array
    {
        return [
            'title'         => ['sometimes', 'required', 'string', 'min:10', 'max:100'],
            'description'   => ['sometimes', 'required', 'string', 'min:30', 'max:2000'],
            'price'         => ['sometimes', 'required', 'numeric', 'min:500', 'max:500000'],
            'city'          => ['sometimes', 'required', 'string', 'max:100'],
            'area'          => ['sometimes', 'required', 'string', 'max:150'],
            'exact_address' => ['sometimes', 'required', 'string', 'max:255'],
            'phone'         => ['sometimes', 'required', 'string', 'regex:/^[0-9+\-\s]{7,20}$/'],
            'lat'           => ['sometimes', 'required', 'numeric', 'between:-90,90'],
            'lng'           => ['sometimes', 'required', 'numeric', 'between:-180,180'],
            'room_type'     => ['sometimes', 'required', 'in:single,double,apartment,hostel'],
            'bedrooms'      => ['sometimes', 'required', 'integer', 'min:1', 'max:20'],
            'bathrooms'     => ['sometimes', 'required', 'integer', 'min:1', 'max:10'],
            'unlock_fee'    => ['nullable', 'numeric', 'min:10', 'max:1000'],
            'amenities'     => ['nullable', 'array'],
            'amenities.*'   => ['string', 'max:50'],
            'is_available'  => ['nullable', 'boolean'],
            'image'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ];
    }
}
