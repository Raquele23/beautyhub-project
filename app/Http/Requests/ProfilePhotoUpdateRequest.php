<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfilePhotoUpdateRequest extends FormRequest
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
            'cropped_photo' => [
                'required',
                'string',
                'regex:/^data:image\/(png|jpe?g|webp);base64,/',
            ],
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
            'cropped_photo.required' => 'A foto de perfil é obrigatória.',
            'cropped_photo.string' => 'A foto de perfil deve ser uma imagem válida.',
            'cropped_photo.regex' => 'A foto deve ser um arquivo PNG, JPG, JPEG ou WEBP válido.',
        ];
    }
}
