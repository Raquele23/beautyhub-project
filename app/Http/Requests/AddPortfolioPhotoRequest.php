<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class AddPortfolioPhotoRequest extends FormRequest
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
            'photo' => ['nullable', 'required_without:cropped_photo', File::image()->max(5 * 1024), 'dimensions:ratio=4/5'],
            'cropped_photo' => ['nullable', 'required_without:photo', 'string'],
            'original_photo' => ['nullable', File::image()->max(10 * 1024)],
            'original_photo_base64' => ['nullable', 'string'],
            'description' => 'nullable|string|max:30',
        ];
    }
}
