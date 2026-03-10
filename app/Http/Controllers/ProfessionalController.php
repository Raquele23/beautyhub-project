<?php

namespace App\Http\Controllers;

use App\Models\PortfolioPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

class ProfessionalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $user = Auth::user();

        // Apenas profissionais podem acessar
        if (!$user->isProfessional()) {
            abort(403, 'Apenas profissionais têm acesso a esta área.');
        }

        if ($user->professional) {
            return redirect()->route('professional.show');
        }

        return view('professional.create');
    }

    public function dashboard()
    {
        $user = Auth::user();

        if (!$user->isProfessional()) {
            abort(403, 'Apenas profissionais têm acesso a esta área.');
        }

        $professional = $user->professional;
        if (!$professional) {
            return redirect()->route('professional.create');
        }

        $stats = [
            'total_services' => $professional->services()->count(),
            'total_portfolio_photos' => $professional->portfolioPhotos()->count(),
        ];

        return view('professional.dashboard', [
            'professional' => $professional,
            'services' => $professional->services()->get(),
            'portfolio_photos' => $professional->portfolioPhotos()->get(),
            'stats' => $stats,
        ]);
    }

    public function show()
    {
        $user = Auth::user();

        if (!$user->isProfessional()) {
            abort(403, 'Apenas profissionais têm acesso a esta área.');
        }

        $professional = $user->professional;
        if (!$professional) {
            return redirect()->route('professional.create');
        }

        return view('professional.show', ['professional' => $professional]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Apenas profissionais podem acessar
        if (!$user->isProfessional()) {
            abort(403, 'Apenas profissionais têm acesso a esta área.');
        }

        if ($user->professional) {
            return redirect()->route('professional.show');
        }

        $validated = $request->validate([
            'establishment_name' => 'nullable|string|max:255',
            'description' => 'required|string',
            'phone' => 'required|string|max:20',
            'state' => 'required|string|max:2',
            'city' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'house_number' => 'required|string|max:10',
            'instagram' => 'nullable|string|max:255',
            'profile_photo' => ['nullable', File::image()->max(5 * 1024)],
            'portfolio_photos' => 'nullable|array|max:10',
            'portfolio_photos.*' => File::image()->max(5 * 1024),
        ]);

        // Processar foto de perfil
        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request->file('profile_photo')->store('professionals/profiles', 'public');
        }

        // Criar profissional
        $professional = $user->professional()->create($validated);

        // Processar fotos do portfólio
        if ($request->hasFile('portfolio_photos')) {
            foreach ($request->file('portfolio_photos') as $photo) {
                $path = $photo->store('professionals/portfolio', 'public');
                $professional->portfolioPhotos()->create(['photo' => $path]);
            }
        }

        // Marcar perfil como completo
        $user->update([
            'profile_completed' => true,
            'role' => 'professional',
        ]);

        return redirect()->route('professional.show')->with('status', 'Perfil criado com sucesso!');
    }

    public function edit()
    {
        $user = Auth::user();

        // Apenas profissionais podem acessar
        if (!$user->isProfessional()) {
            abort(403, 'Apenas profissionais têm acesso a esta área.');
        }

        $professional = $user->professional;

        if (!$professional) {
            return redirect()->route('professional.create');
        }

        return view('professional.edit', ['professional' => $professional]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Apenas profissionais podem acessar
        if (!$user->isProfessional()) {
            abort(403, 'Apenas profissionais têm acesso a esta área.');
        }

        $professional = $user->professional;

        if (!$professional) {
            return redirect()->route('professional.create');
        }

        $validated = $request->validate([
            'establishment_name' => 'nullable|string|max:255',
            'description' => 'required|string',
            'phone' => 'required|string|max:20',
            'state' => 'required|string|max:2',
            'city' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'house_number' => 'required|string|max:10',
            'instagram' => 'nullable|string|max:255',
            'profile_photo' => ['nullable', File::image()->max(5 * 1024)],
        ]);

        // Processar nova foto de perfil
        if ($request->hasFile('profile_photo')) {
            if ($professional->profile_photo) {
                Storage::disk('public')->delete($professional->profile_photo);
            }
            $validated['profile_photo'] = $request->file('profile_photo')->store('professionals/profiles', 'public');
        }

        $professional->update($validated);

        return redirect()->route('professional.show')->with('status', 'Perfil atualizado com sucesso!');
    }

    public function addPortfolioPhoto(Request $request)
    {
        $user = Auth::user();
        $professional = $user->professional;

        if (!$professional) {
            return redirect()->route('professional.create');
        }

        $validated = $request->validate([
            'photo' => ['required', File::image()->max(5 * 1024)],
            'description' => 'nullable|string',
        ]);

        // Verificar limite de 10 fotos
        if ($professional->portfolioPhotos()->count() >= 10) {
            return redirect()->route('professional.edit')->with('error', 'Limite de 10 fotos no portfólio atingido!');
        }

        $path = $request->file('photo')->store('professionals/portfolio', 'public');
        $professional->portfolioPhotos()->create([
            'photo' => $path,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('professional.edit')->with('status', 'Foto adicionada ao portfólio!');
    }

    public function deletePortfolioPhoto(PortfolioPhoto $photo)
    {
        $user = Auth::user();

        if ($photo->professional->user_id !== $user->id) {
            abort(403);
        }

        Storage::disk('public')->delete($photo->photo);
        $photo->delete();

        return redirect()->route('professional.edit')->with('status', 'Foto removida do portfólio!');
    }
}
