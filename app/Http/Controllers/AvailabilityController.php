<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AvailabilityController extends Controller
{
    public function index()
    {
        $professional = Auth::user()->professional;
        $availabilities = $professional->availabilities()->with('breaks')->get()->keyBy('weekday');

        return view('professional.availability', [
            'availabilities' => $availabilities,
            'weekdays' => Availability::WEEKDAYS,
        ]);
    }

    public function save(Request $request)
    {
        $professional = Auth::user()->professional;

        $validator = Validator::make($request->all(), [
            'days' => ['nullable', 'array'],
            'days.*' => ['in:0,1,2,3,4,5,6'],
            'open_time' => ['nullable', 'array'],
            'open_time.*' => ['nullable', 'date_format:H:i'],
            'close_time' => ['nullable', 'array'],
            'close_time.*' => ['nullable', 'date_format:H:i'],
            'slot_interval' => ['nullable', 'array'],
            'slot_interval.*' => ['nullable', 'integer', 'min:15', 'max:240'],
            'breaks' => ['nullable', 'array'],
            'breaks.*' => ['nullable', 'string'],
        ]);

        $validator->after(function ($validator) use ($request) {
            $selectedDays = array_map('intval', $request->input('days', []));

            foreach ($selectedDays as $weekday) {
                $open = $request->input("open_time.$weekday");
                $close = $request->input("close_time.$weekday");
                $slotInterval = $request->input("slot_interval.$weekday");

                if (!$open || !$close || !$slotInterval) {
                    $validator->errors()->add("open_time.$weekday", 'Preencha abertura, fechamento e intervalo para os dias ativos.');
                    continue;
                }

                if ($close <= $open) {
                    $validator->errors()->add("close_time.$weekday", 'O horário de fechamento deve ser depois da abertura.');
                }

                $rawBreaks = trim((string) $request->input("breaks.$weekday", ''));
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

        $validator->validate();

        $selectedDays = $request->input('days', []);

        // Remove dias que foram desmarcados
        $professional->availabilities()
            ->whereNotIn('weekday', $selectedDays)
            ->delete();

        // Salva ou atualiza os dias selecionados
        foreach ($selectedDays as $weekday) {
            $availability = $professional->availabilities()->updateOrCreate(
                ['weekday' => $weekday],
                [
                    'open_time'     => $request->input("open_time.$weekday"),
                    'close_time'    => $request->input("close_time.$weekday"),
                    'slot_interval' => $request->input("slot_interval.$weekday"),
                ]
            );

            $parsed = $this->parseBreakLines((string) $request->input("breaks.$weekday", ''));
            $parsedBreaks = $parsed['breaks'];

            $availability->breaks()->delete();
            if (!empty($parsedBreaks)) {
                $availability->breaks()->createMany(array_map(function (array $break) {
                    return [
                        'start_time' => $break['start'],
                        'end_time' => $break['end'],
                    ];
                }, $parsedBreaks));
            }
        }

        return redirect()->route('professional.availability')
            ->with('status', 'Disponibilidade atualizada com sucesso!');
    }

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

            if (!preg_match('/^((?:[01]\d|2[0-3]):[0-5]\d)\s*-\s*((?:[01]\d|2[0-3]):[0-5]\d)$/', $line, $matches)) {
                return ['breaks' => [], 'error' => 'Use o formato HH:MM-HH:MM para cada pausa.'];
            }

            if ($matches[2] <= $matches[1]) {
                return ['breaks' => [], 'error' => 'O fim da pausa deve ser maior que o início.'];
            }

            $breaks[] = ['start' => $matches[1], 'end' => $matches[2]];
        }

        return ['breaks' => $breaks, 'error' => null];
    }
}