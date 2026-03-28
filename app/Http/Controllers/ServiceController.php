<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\File;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
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

        $services = $professional->services()->paginate(15);

        return view('professional.services.index', ['services' => $services]);
    }

    public function create()
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

        return view('professional.services.create', [
            'categories' => Service::categoryOptions(),
        ]);
    }

    public function store(Request $request)
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
            'category' => 'required|in:' . implode(',', array_keys(Service::categoryOptions())),
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:5',
            'price' => 'required|numeric|min:0.01',
            'image' => ['nullable', File::image()->max(5 * 1024), 'dimensions:ratio=4/5'],
            'cropped_image' => ['nullable', 'string'],
            'original_image_base64' => ['nullable', 'string'],
        ]);

        // Processar imagem do serviço
        if ($request->filled('cropped_image')) {
            $validated['image'] = $this->storeServiceBase64Image($validated['cropped_image']);
        } elseif ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('services', 'public');
        }

        $validated['professional_id'] = $professional->id;

        Service::create($validated);

        return redirect()->route('services.index')->with('status', 'Serviço adicionado com sucesso!');
    }

    public function edit(Service $service)
    {
        $user = Auth::user();

        if ($service->professional->user_id !== $user->id) {
            abort(403);
        }

        return view('professional.services.edit', [
            'service' => $service,
            'categories' => Service::categoryOptions(),
        ]);
    }

    public function update(Request $request, Service $service)
    {
        $user = Auth::user();

        if ($service->professional->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'category' => 'required|in:' . implode(',', array_keys(Service::categoryOptions())),
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:5',
            'price' => 'required|numeric|min:0.01',
            'image' => ['nullable', File::image()->max(5 * 1024), 'dimensions:ratio=4/5'],
            'cropped_image' => ['nullable', 'string'],
            'original_image_base64' => ['nullable', 'string'],
        ]);

        // Processar nova imagem
        if ($request->filled('cropped_image') || $request->hasFile('image')) {
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }

            $validated['image'] = $request->filled('cropped_image')
                ? $this->storeServiceBase64Image($validated['cropped_image'])
                : $request->file('image')->store('services', 'public');
        }

        $service->update($validated);

        return redirect()->route('services.index')->with('status', 'Serviço atualizado com sucesso!');
    }

    public function destroy(Service $service)
    {
        $user = Auth::user();

        if ($service->professional->user_id !== $user->id) {
            abort(403);
        }

        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }

        $service->delete();

        return redirect()->route('services.index')->with('status', 'Serviço removido com sucesso!');
    }

    private function storeServiceBase64Image(string $dataUrl, string $directory = 'services'): string
    {
        if (!preg_match('/^data:image\/(png|jpe?g|webp);base64,/', $dataUrl, $matches)) {
            throw ValidationException::withMessages([
                'image' => 'Formato da imagem recortada inválido.',
            ]);
        }

        [$meta, $encoded] = array_pad(explode(',', $dataUrl, 2), 2, null);

        if (!$encoded) {
            throw ValidationException::withMessages([
                'image' => 'Imagem recortada inválida.',
            ]);
        }

        $binary = base64_decode($encoded, true);

        if ($binary === false || strlen($binary) > (5 * 1024 * 1024)) {
            throw ValidationException::withMessages([
                'image' => 'A imagem recortada deve ter no máximo 5MB.',
            ]);
        }

        $extension = strtolower($matches[1]) === 'jpeg' ? 'jpg' : strtolower($matches[1]);
        $path = trim($directory, '/') . '/' . Str::uuid() . '.' . $extension;

        Storage::disk('public')->put($path, $binary);

        return $path;
    }
}
