<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'section_type'  => 'required|string',
            'content'       => 'required|array',
            'is_visible'    => 'boolean',
            'order'         => 'integer|min:0',
        ];
    }
}