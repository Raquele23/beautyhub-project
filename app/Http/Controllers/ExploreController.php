<?php

namespace App\Http\Controllers;

use App\Models\Professional;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    public function index(Request $request)
    {
        $professionals = Professional::with(['services', 'user'])
            ->when($request->search, function ($query) use ($request) {
                $query->where('city', 'like', "%{$request->search}%")
                    ->orWhere('establishment_name', 'like', "%{$request->search}%")
                    ->orWhereHas('user', function ($q) use ($request) {
                        $q->where('name', 'like', "%{$request->search}%");
                    });
            })
            ->when($request->service, function ($query) use ($request) {
                $query->whereHas('services', function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->service}%");
                });
            })
            ->get();

        return view('explore', compact('professionals'));
    }
}