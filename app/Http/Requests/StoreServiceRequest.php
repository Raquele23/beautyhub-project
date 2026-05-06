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
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:5|max:720',
            'price' => 'required|numeric|min:0.01',
            'image' => ['nullable', File::image()->max(5 * 1024), 'dimensions:ratio=4/5'],
            'cropped_image' => ['nullable', 'string'],
            'original_image_base64' => ['nullable', 'string'],
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
        ];
    }
}
