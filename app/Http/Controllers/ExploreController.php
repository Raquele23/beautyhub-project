<?php

namespace App\Http\Controllers;

use App\Models\Professional;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    public function index(Request $request)
    {
        $categoryKeywords = [
            'cabelo'      => ['cabelo', 'corte', 'coloração', 'tintura', 'luzes', 'hidratação', 'escova', 'progressiva', 'química', 'alisamento', 'mechas'],
            'manicure'    => ['manicure', 'pedicure', 'unhas', 'gel', 'alongamento', 'esmaltação'],
            'depilacao'   => ['depilação', 'cera', 'laser', 'pelo', 'depilação a laser'],
            'sobrancelha' => ['sobrancelha', 'design de sobrancelha', 'henna', 'micropigmentação', 'brow'],
            'maquiagem'   => ['maquiagem', 'make', 'noiva', 'visagismo', 'automaquiagem'],
            'tratamentos' => ['facial', 'corporal', 'limpeza de pele', 'massagem', 'drenagem', 'redução de medidas', 'peeling', 'estética', 'relaxante', 'modeladora', 'anticelulite'],
        ];

        $professionals = Professional::with(['services', 'user'])
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('city', 'like', "%{$request->search}%")
                        ->orWhere('establishment_name', 'like', "%{$request->search}%")
                        ->orWhereHas('user', function ($q2) use ($request) {
                            $q2->where('name', 'like', "%{$request->search}%");
                        })
                        ->orWhereHas('services', function ($q2) use ($request) {
                            $q2->where('name', 'like', "%{$request->search}%");
                        });
                });
            })
            ->when($request->service, function ($query) use ($request) {
                $query->whereHas('services', function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->service}%");
                });
            })
            ->when($request->category && isset($categoryKeywords[$request->category]), function ($query) use ($request, $categoryKeywords) {
                $keywords = $categoryKeywords[$request->category];
                $query->whereHas('services', function ($q) use ($keywords) {
                    $q->where(function ($inner) use ($keywords) {
                        foreach ($keywords as $keyword) {
                            $inner->orWhere('name', 'like', "%{$keyword}%");
                        }
                    });
                });
            })
            ->get();

        return view('explore', compact('professionals'));
    }
}