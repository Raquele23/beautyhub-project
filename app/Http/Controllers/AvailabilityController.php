<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveAvailabilityRequest;
use App\Models\Availability;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    public function index()
    {
        $professional = Auth::user()->professional;
        $availabilities = $professional->availabilities()->with('breaks')->get()->keyBy('weekday');

        return view('professional.availability', [
            'professional' => $professional,
            'availabilities' => $availabilities,
            'weekdays' => Availability::WEEKDAYS,
        ]);
    }

    public function save(SaveAvailabilityRequest $request)
    {
        $professional = Auth::user()->professional;

        if ($request->has('preparation_time_minutes') && !$request->has('days')) {
            $validated = $request->validated();

            $professional->preparation_time_minutes = (int) $validated['preparation_time_minutes'];
            $professional->save();

            return redirect()->route('professional.availability')
                ->with('status', 'Tempo de preparação atualizado com sucesso!');
        }

        $validated = $request->validated();

        DB::transaction(function () use ($professional, $request) {
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
                        'slot_interval' => $professional->preparation_time_minutes ?? 15,
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
        });

        return redirect()->route('professional.availability')
            ->with('status', 'Disponibilidade atualizada com sucesso!');
    }

}