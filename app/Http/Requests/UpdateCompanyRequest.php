<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'website' => 'nullable|url|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Company name is required.',
            'email.email' => 'Please provide a valid email address.',
            'logo.image' => 'Logo must be an image file.',
            'logo.mimes' => 'Logo must be jpeg, jpg, or png.',
            'logo.max' => 'Logo size must not exceed 2MB.',
            'website.url' => 'Please provide a valid URL.',
        ];
    }
}
