<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvitationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'template_id'    => 'required|exists:templates,id',
            'title'          => 'required|string|max:255',
            'event_date'     => 'required|date|after:today',
            'event_time'     => 'required',
            'event_location' => 'required|string|max:255',
            'event_address'  => 'nullable|string|max:500',
            'latitude'       => 'nullable|numeric|between:-90,90',
            'longitude'      => 'nullable|numeric|between:-180,180',
        ];
    }

    public function messages(): array
    {
        return [
            'template_id.required'    => 'Please select a template.',
            'title.required'          => 'Invitation title is required.',
            'event_date.required'     => 'Event date is required.',
            'event_date.after'        => 'Event date must be in the future.',
            'event_time.required'     => 'Event time is required.',
            'event_location.required' => 'Event location is required.',
        ];
    }
}