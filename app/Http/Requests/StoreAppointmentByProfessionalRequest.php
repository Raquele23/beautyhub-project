<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentByProfessionalRequest extends FormRequest
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
            'service_id'         => ['required', 'integer', 'exists:services,id'],
            'date'               => ['required', 'date', 'after_or_equal:today'],
            'time'               => ['required', 'date_format:H:i'],
            'notes'              => ['nullable', 'string', 'max:500'],
            'client_mode'        => ['required', 'in:known,external'],
            'known_client_id'    => ['exclude_unless:client_mode,known', 'required_if:client_mode,known', 'integer', 'exists:users,id'],
            'external_name'      => ['exclude_unless:client_mode,external', 'required_if:client_mode,external', 'string', 'max:255'],
            'external_email'     => ['exclude_unless:client_mode,external', 'nullable', 'email', 'max:255'],
            'external_phone'     => ['exclude_unless:client_mode,external', 'required_if:client_mode,external', 'string', 'max:20'],
        ];
    }
}
