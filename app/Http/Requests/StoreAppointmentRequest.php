<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
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
            'date'  => ['required', 'date', 'after_or_equal:today'],
            'time'  => ['required', 'date_format:H:i'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'date.required' => 'Selecione a data.',
            'date.date' => 'Data inválida.',
            'date.after_or_equal' => 'A data deve ser hoje ou posterior.',

            'time.required' => 'Selecione o horário.',
            'time.date_format' => 'Horário inválido.',

            'notes.max' => 'Observações muito longas.',
        ];
    }
}
