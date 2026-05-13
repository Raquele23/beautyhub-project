<?php

namespace App\Http\Requests;

use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StoreServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isProfessional() && $this->user()->professional;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category' => 'required|in:' . implode(',', array_keys(Service::categoryOptions())),
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:250',
            'duration' => 'required|integer|min:5|max:720',
            'price' => 'required|numeric|min:0.01',
            'image' => ['nullable', File::image()->types(['png', 'jpg', 'jpeg', 'webp'])->max(5 * 1024), 'dimensions:ratio=4/5'],
            'cropped_image' => ['nullable', 'string', 'regex:/^data:image\/(png|jpe?g|webp);base64,/'],
            'original_image_base64' => ['nullable', 'string', 'regex:/^data:image\/(png|jpe?g|webp);base64,/'],
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
            'duration.required' => 'A duração do serviço é obrigatória.',
            'duration.integer' => 'A duração do serviço deve ser um número inteiro.',
            'duration.min' => 'A duração mínima do serviço é de 5 minutos.',
            'duration.max' => 'A duração máxima do serviço é de 12 horas.',
            'description.max' => 'A descrição do serviço deve ter no máximo 250 caracteres.',
            'image.image' => 'O arquivo deve ser uma imagem válida.',
            'image.types' => 'A imagem deve ser do tipo PNG, JPG, JPEG ou WEBP.',
            'image.max' => 'A imagem deve ter no máximo 5 MB.',
            'image.dimensions' => 'A imagem deve ter uma proporção de 4:5.',
            'cropped_image.regex' => 'A imagem recortada deve ser PNG, JPG, JPEG ou WEBP.',
            'original_image_base64.regex' => 'A imagem original deve ser PNG, JPG, JPEG ou WEBP.',
        ];
    }
}
