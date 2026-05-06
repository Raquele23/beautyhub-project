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
            'cropped_photo' => ['nullable', 'required_without:photo', 'string', 'regex:/^data:image\/(png|jpe?g|webp);base64,/'],
            'original_photo' => ['nullable', File::image()->max(10 * 1024)],
            'original_photo_base64' => ['nullable', 'string', 'regex:/^data:image\/(png|jpe?g|webp);base64,/'],
            'description' => 'nullable|string|max:30',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'cropped_photo.regex' => 'A imagem recortada deve ser PNG, JPG, JPEG ou WEBP.',
            'original_photo_base64.regex' => 'A imagem original deve ser PNG, JPG, JPEG ou WEBP.',
        ];
    }
}
