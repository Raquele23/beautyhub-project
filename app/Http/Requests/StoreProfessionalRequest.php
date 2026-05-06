<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StoreProfessionalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'establishment_name' => 'nullable|string|max:255',
            'description' => 'required|string',
            'phone' => 'required|string|max:20',
            'state' => 'required|string|max:2',
            'city' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'house_number' => 'required|string|max:10',
            'zip_code' => 'nullable|string|regex:/^\d{5}-?\d{3}$/',
            'instagram' => 'nullable|string|max:255',
            'profile_photo' => ['nullable', File::image()->max(5 * 1024), 'dimensions:ratio=1/1'],
            'cropped_profile_photo' => ['nullable', 'string'],
            'banner_style' => 'nullable|in:color,photo',
            'banner_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'banner_photo' => ['nullable', 'required_if:banner_style,photo', File::image()->max(8 * 1024)],
            'banner_photo_base64' => ['nullable', 'string'],
            'portfolio_photos' => 'nullable|array|max:10',
            'portfolio_photos.*' => [File::image()->max(5 * 1024), 'dimensions:ratio=4/5'],
            'auto_complete' => 'boolean',
        ];
    }
}
