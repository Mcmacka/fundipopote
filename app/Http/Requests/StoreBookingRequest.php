<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isCustomer();
    }

    public function rules(): array
    {
        return [
            'technician_id'    => 'required|exists:users,id',
            'category_id'      => 'required|exists:categories,id',
            'description'      => 'required|string|min:1|max:1000',
            'location_address' => 'required|string|max:255',
            'location_lat'     => 'nullable|numeric|between:-90,90',
            'location_lng'     => 'nullable|numeric|between:-180,180',
            'scheduled_at'     => 'nullable|date|after:now',
        ];
    }

    public function messages(): array
    {
        return [
            'technician_id.required'    => 'Tafadhali chagua fundi.',
            'category_id.required'      => 'Tafadhali chagua aina ya huduma.',
            'description.required'      => 'Eleza tatizo lako kwa undani.',
            'description.min'           => 'Maelezo lazima yawe na herufi 20 au zaidi.',
            'location_address.required' => 'Tafadhali ingiza anwani yako.',
            'scheduled_at.after'        => 'Tarehe ya huduma lazima iwe baadaye.',
        ];
    }
}
