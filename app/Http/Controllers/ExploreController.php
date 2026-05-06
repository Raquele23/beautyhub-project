<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExploreRequest;
use App\Models\Professional;
use App\Models\Service;

class ExploreController extends Controller
{
    public function index(ExploreRequest $request)
    {
        $validCategories = array_keys(Service::categoryOptions());

        $validated = $request->validated();

        $userLat = isset($validated['lat']) ? (float) $validated['lat'] : null;
        $userLon = isset($validated['lon']) ? (float) $validated['lon'] : null;
        $hasUserCoordinates = $userLat !== null && $userLon !== null;

        $professionalsQuery = Professional::query()
            ->with(['services', 'user'])
            ->leftJoin('users', 'users.id', '=', 'professionals.user_id')
            ->select('professionals.*')
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
            });

        if ($hasUserCoordinates) {
            $distanceSql = '(6371 * ACOS(COS(RADIANS(?)) * COS(RADIANS(professionals.latitude)) * COS(RADIANS(professionals.longitude) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(professionals.latitude))))';

            $professionalsQuery
                ->orderByRaw('CASE WHEN professionals.latitude IS NULL OR professionals.longitude IS NULL THEN 1 ELSE 0 END ASC')
                ->orderByRaw($distanceSql . ' ASC', [$userLat, $userLon, $userLat]);
        } else {
            $professionalsQuery->orderByRaw("LOWER(COALESCE(NULLIF(professionals.establishment_name, ''), users.name)) ASC");
        }

        $professionals = $professionalsQuery->get();

        return view('explore', compact('professionals'));
    }
}