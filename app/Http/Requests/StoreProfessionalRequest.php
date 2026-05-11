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
            'description' => 'required|string|max:500',
            'phone' => 'required|string|min:10|max:20|regex:/\d/',
            'state' => 'required|string|max:2',
            'city' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'house_number' => 'required|string|max:10',
            'zip_code' => 'nullable|string|regex:/^\d{5}-?\d{3}$/',
            'instagram' => 'nullable|string|max:255',
            'profile_photo' => ['nullable', File::image()->types(['png', 'jpg', 'jpeg', 'webp'])->max(5 * 1024), 'dimensions:ratio=1/1'],
            'cropped_profile_photo' => ['nullable', 'string', 'regex:/^data:image\/(png|jpe?g|webp);base64,/'],
            'banner_style' => 'nullable|in:color,photo',
            'banner_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'banner_photo' => ['nullable', 'required_if:banner_style,photo', File::image()->types(['png', 'jpg', 'jpeg', 'webp'])->max(8 * 1024)],
            'banner_photo_base64' => ['nullable', 'string', 'regex:/^data:image\/(png|jpe?g|webp);base64,/'],
            'portfolio_photos' => 'nullable|array|max:10',
            'portfolio_photos.*' => [File::image()->types(['png', 'jpg', 'jpeg', 'webp'])->max(5 * 1024), 'dimensions:ratio=4/5'],
            'auto_complete' => 'boolean',
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
            'phone.required' => 'O telefone é obrigatório.',
            'phone.min' => 'O telefone deve ter no mínimo 10 dígitos.',
            'phone.max' => 'O telefone deve ter no máximo 20 caracteres.',
            'phone.regex' => 'O telefone deve conter números.',
            'description.max' => 'A descrição deve ter no máximo 500 caracteres.',
            'house_number.required' => 'O número da casa é obrigatório.',
            'house_number.max' => 'O número da casa deve ter no máximo 10 caracteres.',
            'cropped_profile_photo.regex' => 'A foto de perfil recortada deve ser PNG, JPG, JPEG ou WEBP.',
            'banner_photo_base64.regex' => 'A foto do banner deve ser PNG, JPG, JPEG ou WEBP.',
        ];
    }
}
