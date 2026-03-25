<?php

namespace App\Http\Controllers;

use App\Models\Professional;
use App\Models\Service;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    public function index(Request $request)
    {
        $validCategories = array_keys(Service::categoryOptions());

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
            ->when($request->category && in_array($request->category, $validCategories, true), function ($query) use ($request) {
                $query->whereHas('services', function ($q) use ($request) {
                    $q->where('category', $request->category);
                });
            })
            ->get();

        return view('explore', compact('professionals'));
    }
}