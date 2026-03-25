<?php

namespace App\Http\Controllers;

use App\Jobs\AutoCompleteAppointments;
use App\Models\Professional;
use App\Models\PortfolioPhoto;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\File;

class ProfessionalController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        if ($user->professional) {
            return redirect()->route('professional.show');
        }
        return view('professional.create');
    }

    public function dashboard()
    {
        $user = Auth::user();
        $professional = $user->professional;

        if (!$professional) {
            return redirect()->route('professional.create');
        }

        // ─── Agendamentos ────────────────────────────────────────────────────
        $allAppointments = $professional->appointments()->with('service')->get();

        $today     = now()->toDateString();
        $weekStart = now()->startOfWeek()->toDateString();
        $weekEnd   = now()->endOfWeek()->toDateString();

        $todayAppointments = $allAppointments->filter(
            fn($a) => $a->scheduled_at->toDateString() === $today
                   && !in_array($a->status, ['cancelled'])
        )->count();

        $weekAppointments = $allAppointments->filter(
            fn($a) => $a->scheduled_at->toDateString() >= $weekStart
                   && $a->scheduled_at->toDateString() <= $weekEnd
                   && !in_array($a->status, ['cancelled'])
        )->count();

        $totalCompleted = $allAppointments->where('status', 'completed')->count();
        $totalCancelled = $allAppointments->where('status', 'cancelled')->count();

        // ─── Financeiro ──────────────────────────────────────────────────────
        $completedAppointments = $allAppointments->where('status', 'completed');

        $revenueToday = $completedAppointments
            ->filter(fn($a) => $a->scheduled_at->toDateString() === $today)
            ->sum(fn($a) => $a->service->price ?? 0);

        $revenueWeek = $completedAppointments
            ->filter(fn($a) => $a->scheduled_at->toDateString() >= $weekStart
                            && $a->scheduled_at->toDateString() <= $weekEnd)
            ->sum(fn($a) => $a->service->price ?? 0);

        $revenueTotal = $completedAppointments
            ->sum(fn($a) => $a->service->price ?? 0);

        // ─── Avaliações recentes ─────────────────────────────────────────────
        $recentReviews = $user->reviewsReceived()
            ->with('client')
            ->latest()
            ->take(3)
            ->get();

        $averageRating = $user->average_rating;

        $stats = [
            'total_services'         => $professional->services()->count(),
            'total_portfolio_photos' => $professional->portfolioPhotos()->count(),
        ];

        return view('professional.dashboard', compact(
            'professional',
            'stats',
            'todayAppointments',
            'weekAppointments',
            'totalCompleted',
            'totalCancelled',
            'revenueToday',
            'revenueWeek',
            'revenueTotal',
            'recentReviews',
            'averageRating',
        ));
    }

    public function show()
    {
        $user = Auth::user();
        $professional = $user->professional;
        if (!$professional) {
            return redirect()->route('professional.create');
        }
        return view('professional.show', ['professional' => $professional]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->professional) {
            return redirect()->route('professional.show');
        }

        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'establishment_name' => 'nullable|string|max:255',
            'description'        => 'required|string',
            'phone'              => 'required|string|max:20',
            'state'              => 'required|string|max:2',
            'city'               => 'required|string|max:255',
            'street'             => 'required|string|max:255',
            'house_number'       => 'required|string|max:10',
            'instagram'          => 'nullable|string|max:255',
            'profile_photo'      => ['nullable', File::image()->max(5 * 1024)],
            'banner_style'       => 'nullable|in:color,photo',
            'banner_color'       => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'banner_photo'       => ['nullable', 'required_if:banner_style,photo', File::image()->max(8 * 1024)],
            'portfolio_photos'   => 'nullable|array|max:10',
            'portfolio_photos.*' => [File::image()->max(5 * 1024), 'dimensions:ratio=4/5'],
            'auto_complete'      => 'boolean',
        ]);

        // Atualiza o nome do usuário
        $user->update(['name' => $validated['name']]);
        unset($validated['name']);

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request->file('profile_photo')->store('professionals/profiles', 'public');
        }

        $bannerStyle = $validated['banner_style'] ?? 'color';

        if ($bannerStyle === 'photo' && $request->hasFile('banner_photo')) {
            $validated['banner_photo'] = $request->file('banner_photo')->store('professionals/banners', 'public');
            $validated['banner_color'] = null;
        } else {
            $validated['banner_photo'] = null;
            $validated['banner_color'] = $validated['banner_color'] ?? '#6A0DAD';
        }

        unset($validated['banner_style']);

        $professional = $user->professional()->create($validated);

        if ($request->hasFile('portfolio_photos')) {
            foreach ($request->file('portfolio_photos') as $photo) {
                $path = $photo->store('professionals/portfolio', 'public');
                $professional->portfolioPhotos()->create(['photo' => $path]);
            }
        }

        $user->update(['profile_completed' => true]);

        return redirect()->route('professional.portfolio.manage')
            ->with('status', 'Perfil criado! Agora adicione fotos ao seu portfólio.');
    }

    public function edit()
    {
        $user = Auth::user();
        $professional = $user->professional;
        if (!$professional) {
            return redirect()->route('professional.create');
        }
        return view('professional.edit', ['professional' => $professional]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $professional = $user->professional;
        if (!$professional) {
            return redirect()->route('professional.create');
        }

        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'establishment_name' => 'nullable|string|max:255',
            'description'        => 'required|string',
            'phone'              => 'required|string|max:20',
            'state'              => 'required|string|max:2',
            'city'               => 'required|string|max:255',
            'street'             => 'required|string|max:255',
            'house_number'       => 'required|string|max:10',
            'instagram'          => 'nullable|string|max:255',
            'profile_photo'      => ['nullable', File::image()->max(5 * 1024)],
            'banner_style'       => 'nullable|in:color,photo',
            'banner_color'       => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'banner_photo'       => ['nullable', File::image()->max(8 * 1024)],
            'delete_profile_photo' => 'nullable|boolean',
        ]);

        // Atualiza o nome do usuário
        $user->update(['name' => $validated['name']]);

        // Exclui foto se solicitado
        if ($request->input('delete_profile_photo') == '1') {
            if ($professional->profile_photo) {
                Storage::disk('public')->delete($professional->profile_photo);
            }
            $validated['profile_photo'] = null;
        } elseif ($request->hasFile('profile_photo')) {
            if ($professional->profile_photo) {
                Storage::disk('public')->delete($professional->profile_photo);
            }
            $validated['profile_photo'] = $request->file('profile_photo')->store('professionals/profiles', 'public');
        }

        $bannerStyle = $validated['banner_style'] ?? ($professional->banner_photo ? 'photo' : 'color');

        if ($bannerStyle === 'photo' && !$professional->banner_photo && !$request->hasFile('banner_photo')) {
            return back()->withErrors([
                'banner_photo' => 'Envie uma foto para usar no banner.',
            ])->withInput();
        }

        if ($bannerStyle === 'photo') {
            if ($request->hasFile('banner_photo')) {
                if ($professional->banner_photo) {
                    Storage::disk('public')->delete($professional->banner_photo);
                }
                $validated['banner_photo'] = $request->file('banner_photo')->store('professionals/banners', 'public');
            }
            $validated['banner_color'] = null;
        } else {
            if ($professional->banner_photo) {
                Storage::disk('public')->delete($professional->banner_photo);
            }
            $validated['banner_photo'] = null;
            $validated['banner_color'] = $validated['banner_color'] ?? '#6A0DAD';
        }

        unset($validated['name'], $validated['delete_profile_photo'], $validated['banner_style']);

        $professional->update($validated);

        return redirect()->route('professional.show')->with('status', 'Perfil atualizado com sucesso!');
    }

    // ─── Portfólio ───────────────────────────────────────────────────────────

    public function portfolioManage()
    {
        $user = Auth::user();
        $professional = $user->professional;
        if (!$professional) {
            return redirect()->route('professional.create');
        }
        return view('professional.portfolio.manage', ['professional' => $professional]);
    }

    public function addPortfolioPhoto(Request $request)
    {
        $user = Auth::user();
        $professional = $user->professional;
        if (!$professional) {
            return redirect()->route('professional.create');
        }

        $validated = $request->validate([
            'photo' => ['nullable', 'required_without:cropped_photo', File::image()->max(5 * 1024), 'dimensions:ratio=4/5'],
            'cropped_photo' => ['nullable', 'required_without:photo', 'string'],
            'original_photo' => ['nullable', File::image()->max(10 * 1024)],
            'original_photo_base64' => ['nullable', 'string'],
            'description' => 'nullable|string|max:30',
        ]);

        if ($professional->portfolioPhotos()->count() >= 10) {
            return back()->with('error', 'Limite de 10 fotos no portfólio atingido!');
        }

        $path = $request->filled('cropped_photo')
            ? $this->storePortfolioBase64Image($validated['cropped_photo'])
            : $request->file('photo')->store('professionals/portfolio', 'public');

        $originalPath = null;
        if ($request->hasFile('original_photo')) {
            $originalPath = $request->file('original_photo')->store('professionals/portfolio/originals', 'public');
        } elseif (!empty($validated['original_photo_base64'])) {
            $originalPath = $this->storePortfolioBase64Image($validated['original_photo_base64'], 'professionals/portfolio/originals');
        } elseif ($request->hasFile('photo')) {
            // Se não há original_photo mas foi enviado arquivo photo direto, usar como original
            $originalPath = $path;
        }

        $professional->portfolioPhotos()->create([
            'photo'           => $path,
            'original_photo'  => $originalPath,
            'description'     => $validated['description'] ?? null,
        ]);

        return back()->with('status', 'Foto adicionada ao portfólio!');
    }

    public function deletePortfolioPhoto(PortfolioPhoto $photo)
    {
        if ($photo->professional->user_id !== Auth::id()) {
            abort(403);
        }
        Storage::disk('public')->delete($photo->photo);
        if ($photo->original_photo) {
            Storage::disk('public')->delete($photo->original_photo);
        }
        $photo->delete();
        return back()->with('status', 'Foto removida do portfólio!');
    }

    public function updatePortfolioPhoto(Request $request, PortfolioPhoto $photo)
    {
        if ($photo->professional->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'photo' => ['nullable', File::image()->max(5 * 1024), 'dimensions:ratio=4/5'],
            'cropped_photo' => ['nullable', 'string'],
            'original_photo' => ['nullable', File::image()->max(10 * 1024)],
            'original_photo_base64' => ['nullable', 'string'],
            'description' => 'nullable|string|max:30',
        ]);

        // Se uma nova foto foi enviada/recortada, atualizar
        if ($request->filled('cropped_photo') || $request->hasFile('photo')) {
            Storage::disk('public')->delete($photo->photo);
            $path = $request->filled('cropped_photo')
                ? $this->storePortfolioBase64Image($validated['cropped_photo'])
                : $request->file('photo')->store('professionals/portfolio', 'public');
            $photo->photo = $path;

            // Salvar foto original se enviada
            if ($request->hasFile('original_photo')) {
                if ($photo->original_photo) {
                    Storage::disk('public')->delete($photo->original_photo);
                }
                $photo->original_photo = $request->file('original_photo')->store('professionals/portfolio/originals', 'public');
            } elseif (!empty($validated['original_photo_base64'])) {
                if ($photo->original_photo) {
                    Storage::disk('public')->delete($photo->original_photo);
                }
                $photo->original_photo = $this->storePortfolioBase64Image($validated['original_photo_base64'], 'professionals/portfolio/originals');
            } elseif ($request->hasFile('photo')) {
                // Se não há original_photo mas foi enviado arquivo photo direto, usar como original
                if ($photo->original_photo) {
                    Storage::disk('public')->delete($photo->original_photo);
                }
                $photo->original_photo = $path;
            }
        }

        // Atualizar descrição
        if (isset($validated['description'])) {
            $photo->description = $validated['description'];
        }

        $photo->save();

        return back()->with('status', 'Foto atualizada com sucesso!');
    }

    private function storePortfolioBase64Image(string $dataUrl, string $directory = 'professionals/portfolio'): string
    {
        if (!preg_match('/^data:image\/(png|jpe?g|webp);base64,/', $dataUrl, $matches)) {
            throw ValidationException::withMessages([
                'photo' => 'Formato da imagem recortada inválido.',
            ]);
        }

        [$meta, $encoded] = array_pad(explode(',', $dataUrl, 2), 2, null);

        if (!$encoded) {
            throw ValidationException::withMessages([
                'photo' => 'Imagem recortada inválida.',
            ]);
        }

        $binary = base64_decode($encoded, true);

        if ($binary === false || strlen($binary) > (5 * 1024 * 1024)) {
            throw ValidationException::withMessages([
                'photo' => 'A imagem recortada deve ter no máximo 5MB.',
            ]);
        }

        $extension = strtolower($matches[1]) === 'jpeg' ? 'jpg' : strtolower($matches[1]);
        $path = trim($directory, '/') . '/' . Str::uuid() . '.' . $extension;

        Storage::disk('public')->put($path, $binary);

        return $path;
    }

    public function publicShow(Professional $professional)
    {
        $reviews = $professional->user
            ->reviewsReceived()
            ->with('client')
            ->latest()
            ->paginate(5);

        $starCounts = $professional->user
            ->reviewsReceived()
            ->selectRaw('rating, count(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating')
            ->toArray();

        return view('professional.public', [
            'professional'  => $professional->load(['services', 'portfolioPhotos', 'user']),
            'reviews'       => $reviews,
            'starCounts'    => $starCounts,
            'averageRating' => $professional->user->average_rating,
        ]);
    }

    public function appointments()
    {
        $professional = Auth::user()->professional;

        if ($professional->auto_complete) {
            AutoCompleteAppointments::dispatchSync();
        }

        $pending = $professional->appointments()
            ->with(['client', 'service'])
            ->pending()
            ->orderBy('scheduled_at')
            ->get();

        $agenda = $professional->appointments()
            ->with(['client', 'service'])
            ->confirmed()
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')
            ->get();

        $awaitingComplete = $professional->appointments()
            ->with(['client', 'service'])
            ->confirmed()
            ->where('scheduled_at', '<', now())
            ->orderByDesc('scheduled_at')
            ->get();

        $completed = $professional->appointments()
            ->with(['client', 'service'])
            ->where('status', 'completed')
            ->orderByDesc('scheduled_at')
            ->get();

        $cancelled = $professional->appointments()
            ->with(['client', 'service'])
            ->where('status', 'cancelled')
            ->orderByDesc('scheduled_at')
            ->get();

        return view('professional.appointments', compact(
            'pending',
            'agenda',
            'awaitingComplete',
            'completed',
            'cancelled'
        ));
    }

    public function calendar()
    {
        $professional = Auth::user()->professional;

        $appointments = $professional->appointments()
            ->with(['client', 'service'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->get()
            ->map(fn($a) => [
                'id'      => $a->id,
                'date'    => $a->scheduled_at->format('Y-m-d'),
                'time'    => $a->scheduled_at->format('H:i'),
                'service' => $a->service->name,
                'client'  => $a->client->name,
                'status'  => $a->status,
            ]);

        return view('professional.calendar', [
            'appointmentsJson' => $appointments->toJson(),
        ]);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $professional = Auth::user()->professional;
        if (!$professional) {
            return redirect()->route('professional.create');
        }

        $validated = $request->validate([
            'auto_complete' => ['required', 'boolean'],
        ]);

        $professional->update($validated);

        return back()->with('status', 'Configurações salvas!');
    }
}