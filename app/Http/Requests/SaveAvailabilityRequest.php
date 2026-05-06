<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveAvailabilityRequest extends FormRequest
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
            'preparation_time_minutes' => ['required_without:days', 'integer', 'min:5', 'max:240'],
            'days' => ['nullable', 'array'],
            'days.*' => ['in:0,1,2,3,4,5,6'],
            'open_time' => ['nullable', 'array'],
            'open_time.*' => ['nullable', 'date_format:H:i'],
            'close_time' => ['nullable', 'array'],
            'close_time.*' => ['nullable', 'date_format:H:i'],
            'breaks' => ['nullable', 'array'],
            'breaks.*' => ['nullable', 'string'],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Só valida dias se foram enviados
            if (!$this->has('days')) {
                return;
            }

            $selectedDays = array_map('intval', $this->input('days', []));

            foreach ($selectedDays as $weekday) {
                $open = $this->input("open_time.$weekday");
                $close = $this->input("close_time.$weekday");

                if (!$open || !$close) {
                    $validator->errors()->add("open_time.$weekday", 'Preencha abertura e fechamento para os dias ativos.');
                    continue;
                }

                if ($close <= $open) {
                    $validator->errors()->add("close_time.$weekday", 'O horário de fechamento deve ser depois da abertura.');
                }

                $rawBreaks = trim((string) $this->input("breaks.$weekday", ''));
                $parsed = $this->parseBreakLines($rawBreaks);
                if ($parsed['error']) {
                    $validator->errors()->add("breaks.$weekday", $parsed['error']);
                    continue;
                }

                $parsedBreaks = $parsed['breaks'];
                $normalizedBreaks = [];

                foreach ($parsedBreaks as $break) {
                    if ($break['start'] <= $open || $break['end'] >= $close) {
                        $validator->errors()->add("breaks.$weekday", 'Todas as pausas devem ficar dentro do horário de atendimento.');
                    }

                    $normalizedBreaks[] = $break;
                }

                usort($normalizedBreaks, fn(array $a, array $b) => strcmp($a['start'], $b['start']));
                for ($i = 1; $i < count($normalizedBreaks); $i++) {
                    if ($normalizedBreaks[$i]['start'] < $normalizedBreaks[$i - 1]['end']) {
                        $validator->errors()->add("breaks.$weekday", 'As pausas não podem se sobrepor.');
                        break;
                    }
                }
            }
        });
    }

    /**
     * Parse break lines from a string.
     *
     * @param  string  $value
     * @return array
     */
    private function parseBreakLines(string $value): array
    {
        $value = trim($value);
        if ($value === '') {
            return ['breaks' => [], 'error' => null];
        }

        $lines = preg_split('/\r\n|\r|\n/', $value) ?: [];
        $breaks = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            if (!preg_match('/^(\d{1,2}):(\d{2})\s*-\s*(\d{1,2}):(\d{2})$/', $line, $matches)) {
                return ['breaks' => [], 'error' => 'Formato inválido. Use: 12:00 - 13:00 (um por linha)'];
            }

            $startHour = (int) $matches[1];
            $startMin = (int) $matches[2];
            $endHour = (int) $matches[3];
            $endMin = (int) $matches[4];

            if ($startHour < 0 || $startHour > 23 || $startMin < 0 || $startMin > 59 ||
                $endHour < 0 || $endHour > 23 || $endMin < 0 || $endMin > 59) {
                return ['breaks' => [], 'error' => 'Horários inválidos (horas 0-23, minutos 0-59).'];
            }

            $start = sprintf('%02d:%02d', $startHour, $startMin);
            $end = sprintf('%02d:%02d', $endHour, $endMin);

            if ($start >= $end) {
                return ['breaks' => [], 'error' => 'Hora de fim deve ser depois da de início em cada pausa.'];
            }

            $breaks[] = ['start' => $start, 'end' => $end];
        }

        return ['breaks' => $breaks, 'error' => null];
    }
}
