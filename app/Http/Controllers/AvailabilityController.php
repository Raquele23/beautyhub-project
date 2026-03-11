<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    public function index()
    {
        $professional = Auth::user()->professional;
        $availabilities = $professional->availabilities->keyBy('weekday');

        return view('professional.availability', [
            'availabilities' => $availabilities,
            'weekdays' => Availability::WEEKDAYS,
        ]);
    }

    public function save(Request $request)
    {
        $professional = Auth::user()->professional;

        $request->validate([
            'days' => ['nullable', 'array'],
            'days.*' => ['in:0,1,2,3,4,5,6'],
            'open_time' => ['required', 'array'],
            'open_time.*' => ['required', 'date_format:H:i'],
            'close_time' => ['required', 'array'],
            'close_time.*' => ['required', 'date_format:H:i', 'after:open_time.*'],
            'slot_interval' => ['required', 'array'],
            'slot_interval.*' => ['required', 'integer', 'min:15', 'max:240'],
        ]);

        $selectedDays = $request->input('days', []);

        // Remove dias que foram desmarcados
        $professional->availabilities()
            ->whereNotIn('weekday', $selectedDays)
            ->delete();

        // Salva ou atualiza os dias selecionados
        foreach ($selectedDays as $weekday) {
            $professional->availabilities()->updateOrCreate(
                ['weekday' => $weekday],
                [
                    'open_time'     => $request->input("open_time.$weekday"),
                    'close_time'    => $request->input("close_time.$weekday"),
                    'slot_interval' => $request->input("slot_interval.$weekday"),
                ]
            );
        }

        return redirect()->route('professional.availability')
            ->with('status', 'Disponibilidade atualizada com sucesso!');
    }
}