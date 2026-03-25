<x-app-layout>

    <style>
        @import url('https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css');
    </style>

    <div class="max-w-4xl mx-auto px-4 sm:px-8 py-10 space-y-6">

        {{-- ── Toast: sucesso ── --}}
        @if(session('status'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-cloak
                x-init="setTimeout(() => show = false, 5000)"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-4"
                class="fixed top-20 left-1/2 -translate-x-1/2 z-50 flex items-center gap-3 bg-white border border-purple-200 shadow-xl shadow-purple-100 rounded-2xl px-5 py-4 w-max max-w-sm">
                <div class="w-8 h-8 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-purple-800">{{ session('status') }}</p>
                <button @click="show = false" class="ml-2 text-purple-300 hover:text-purple-600 transition-colors flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- ── Toast: erro ── --}}
        @if(session('error'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-cloak
                x-init="setTimeout(() => show = false, 5000)"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-4"
                class="fixed top-20 left-1/2 -translate-x-1/2 z-50 flex items-center gap-3 bg-white border border-red-200 shadow-xl shadow-red-100 rounded-2xl px-5 py-4 w-max max-w-sm">
                <div class="w-8 h-8 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
                <button @click="show = false" class="ml-2 text-red-300 hover:text-red-600 transition-colors flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- ── Topo ── --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold tracking-widest uppercase text-purple-400">Beauty Hub</p>
                <h1 class="text-2xl font-bold text-purple-800 mt-0.5">Minha Loja</h1>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('professional.portfolio.manage', ['from' => 'show']) }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-purple-700 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md" style="background-color: #E3D0F9;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Portfólio
                </a>
                <a href="{{ route('services.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-purple-700 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
                   style="background-color: #E3D0F9;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Serviços
                </a>
                <a href="{{ route('professional.edit') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-white text-xs font-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5 shadow-lg shadow-purple-200"
                   style="background-color: #6A0DAD;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar perfil
                </a>
            </div>
        </div>

        {{-- ── Card de Perfil ── --}}
        <div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden">

            @php
                $bannerStyle = $professional->banner_photo
                    ? 'background-image: url(' . asset('storage/' . $professional->banner_photo) . '); background-size: cover; background-position: center;'
                    : 'background-color: ' . ($professional->banner_color ?? '#6A0DAD') . ';';
            @endphp
            <div class="relative h-36 w-full" style="{{ $bannerStyle }}">
                <div class="absolute left-1/2 -bottom-16 -translate-x-1/2 w-32 h-32 rounded-full border-4 border-white shadow-lg overflow-hidden" style="background-color: #EDE4F8;">
                    @if($professional->profile_photo)
                        <img src="{{ Storage::url($professional->profile_photo) }}" alt="Foto de perfil" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-4xl font-bold text-purple-300">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="pt-20 pb-5 text-center px-6">
                <h2 class="text-xl font-bold text-gray-900">{{ $professional->establishment_name ?? auth()->user()->name }}</h2>
                <p class="text-xs text-purple-400 mt-0.5">{{ auth()->user()->name }}</p>
                @php
                    $storeAvg = round($professional->user->reviewsReceived()->avg('rating') ?? 0, 1);
                    $storeCount = $professional->user->reviewsReceived()->count();
                @endphp
                @if($storeAvg > 0)
                    <div class="flex items-center justify-center gap-2 mt-2">
                        <x-star-rating :rating="$storeAvg" size="sm" />
                        <span class="text-xs font-semibold text-gray-800">{{ number_format($storeAvg, 1) }}</span>
                        <span class="text-xs text-purple-300">({{ $storeCount }})</span>
                    </div>
                @endif
            </div>

            @php
                $address = $professional->full_address;
                $hasInfos = $professional->phone || $professional->instagram || $address;
            @endphp
            @if($hasInfos || $professional->description)
            <div class="border-t border-purple-50">
                @if($professional->description)
                <div class="px-6 py-4 border-b border-purple-50">
                    <p class="text-sm text-gray-600 text-center leading-relaxed">{{ $professional->description }}</p>
                </div>
                @endif
                @if($hasInfos)
                <div class="px-6 py-4 flex flex-wrap items-center justify-center gap-x-6 gap-y-3">
                    @if($professional->phone)
                    <div class="flex items-center gap-2 text-sm text-gray-700">
                        <svg class="w-4 h-4 text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span class="font-medium">{{ $professional->phone }}</span>
                    </div>
                    @endif
                    @if($professional->instagram)
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-purple-400 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                        <a href="https://instagram.com/{{ ltrim($professional->instagram, '@') }}" target="_blank" class="text-sm font-medium text-purple-600 hover:underline">{{ $professional->instagram }}</a>
                    </div>
                    @endif
                    @if($address)
                    <div class="flex items-center gap-2 text-sm text-gray-700">
                        <svg class="w-4 h-4 text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="font-medium">{{ $address }}</span>
                    </div>
                    @endif
                </div>
                @endif
            </div>
            @endif

        </div>

        {{-- ── Portfólio ── --}}
        <div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-purple-50">
                <p class="text-sm font-bold text-purple-400 uppercase tracking-wide">Portfólio</p>
                <a href="{{ route('professional.portfolio.manage', ['from' => 'show']) }}"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-purple-600 hover:text-purple-800 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Adicionar
                </a>
            </div>

            @if($professional->portfolioPhotos->count() > 0)
            <div class="p-6">
                <div class="flex gap-3 overflow-x-auto pb-3 scroll-smooth scrollbar-thin-soft">
                    @foreach($professional->portfolioPhotos as $photo)
                    <div class="group relative flex-shrink-0 w-32 sm:w-40"
                        x-data="{ editOpen: false, editDescription: @js($photo->description ?? '') }">
                        <div class="rounded-xl overflow-hidden aspect-[4/5]">
                            <img src="{{ Storage::url($photo->photo) }}"
                                 alt="{{ $photo->description ?? '' }}"
                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        </div>

                        <div class="absolute inset-0 rounded-xl bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex flex-col items-center justify-center gap-2">
                            <button @click="editOpen = true"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-white text-xs font-semibold text-purple-600 rounded-xl hover:bg-purple-50 transition-colors shadow-lg">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Editar
                            </button>
                            <form action="{{ route('professional.portfolio.delete', $photo) }}"
                                  method="POST"
                                  onsubmit="return confirm('Tem certeza que deseja excluir esta foto?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-white text-xs font-semibold text-red-500 rounded-xl hover:bg-red-50 transition-colors shadow-lg">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Excluir
                                </button>
                            </form>
                        </div>

                        @if($photo->description)
                            <p class="mt-1 text-xs font-medium text-gray-600 leading-snug break-words">{{ $photo->description }}</p>
                        @endif

                            <div x-show="editOpen"
                                x-cloak
                             @click="editOpen = false"
                             x-transition
                             class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                               <div @click.stop
                                   x-transition
                                   class="bg-white rounded-2xl max-w-md w-full p-4 sm:p-6 space-y-4 max-h-[90vh] overflow-y-auto">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-bold text-purple-800">Editar foto</h3>
                                    <button @click="editOpen = false"
                                            class="text-purple-300 hover:text-purple-600 transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                <form action="{{ route('professional.portfolio.update', $photo) }}"
                                      method="POST"
                                      enctype="multipart/form-data"
                                      @submit="editOpen = false"
                                      class="space-y-4">
                                    @csrf @method('PATCH')

                                    <div class="relative w-full max-w-[260px] mx-auto rounded-xl overflow-hidden bg-gray-100 aspect-[4/5]">
                                        <img src="{{ Storage::url($photo->original_photo ?? $photo->photo) }}"
                                             id="show_edit_preview_img_{{ $photo->id }}"
                                             class="w-full h-full object-cover">
                                        <label class="absolute inset-0 bg-black/40 opacity-0 hover:opacity-100 transition-opacity duration-200 flex items-center justify-center cursor-pointer group">
                                            <div class="text-center">
                                                <svg class="w-6 h-6 text-white mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <span class="text-xs text-white font-medium">Trocar foto</span>
                                            </div>
                                            <input type="file"
                                                   name="photo"
                                                   accept="image/*"
                                                   class="sr-only"
                                                   id="show_edit_photo_input_{{ $photo->id }}"
                                                   data-crop-target="show-edit-{{ $photo->id }}"
                                                   data-preview-img="show_edit_preview_img_{{ $photo->id }}"
                                                   data-hidden-input="show_edit_cropped_photo_{{ $photo->id }}"
                                                   data-original-hidden-input="show_edit_original_photo_base64_{{ $photo->id }}"
                                                   data-original-src="{{ Storage::url($photo->original_photo ?? $photo->photo) }}">
                                        </label>
                                    </div>

                                    <input type="hidden" name="cropped_photo" id="show_edit_cropped_photo_{{ $photo->id }}">
                                    <input type="hidden" name="original_photo_base64" id="show_edit_original_photo_base64_{{ $photo->id }}">

                                    <button type="button"
                                            data-recrop-input="show_edit_photo_input_{{ $photo->id }}"
                                            class="text-xs font-semibold text-purple-600 hover:text-purple-800 transition-colors">
                                        Recortar novamente
                                    </button>

                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 mb-2">Descrição</label>
                                        <input type="text"
                                               name="description"
                                               x-model="editDescription"
                                               placeholder="Ex: Corte e tingimento"
                                                 maxlength="30"
                                               class="w-full px-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 placeholder-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm">
                                    </div>

                                    <div class="flex gap-2 pt-4">
                                        <button type="button"
                                                @click="editOpen = false"
                                                class="flex-1 px-4 py-2.5 rounded-xl border border-purple-100 text-xs font-semibold text-purple-600 hover:bg-purple-50 transition-colors">
                                            Cancelar
                                        </button>
                                        <button type="submit"
                                                class="flex-1 px-4 py-2.5 rounded-xl text-white text-xs font-semibold transition-all duration-200 hover:-translate-y-0.5 shadow-lg shadow-purple-200"
                                                style="background-color: #6A0DAD;">
                                            Salvar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="px-6 py-10 text-center">
                <p class="text-sm text-purple-300">Nenhuma foto no portfólio ainda.</p>
            </div>
            @endif
        </div>

        {{-- ── Serviços ── --}}
        <div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-purple-50">
                <p class="text-sm font-bold text-purple-400 uppercase tracking-wide">Serviços</p>
                <a href="{{ route('services.create') }}"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-purple-600 hover:text-purple-800 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Adicionar
                </a>
            </div>

            @forelse($professional->services ?? [] as $service)
            <div class="flex items-center gap-4 px-6 py-4 border-b border-purple-50 last:border-0">

                <div class="w-14 h-14 rounded-xl overflow-hidden flex-shrink-0" style="background-color: #EDE4F8;">
                    @if($service->image)
                        <img src="{{ Storage::url($service->image) }}" alt="{{ $service->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                        </div>
                    @endif
                </div>

                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $service->name }}</p>
                    @if($service->description)
                        <p class="text-xs text-purple-300 mt-0.5 truncate">{{ $service->description }}</p>
                    @endif
                    <div class="flex items-center gap-3 mt-1">
                        <span class="text-xs font-bold text-purple-700">R$ {{ number_format($service->price, 2, ',', '.') }}</span>
                        <span class="text-xs text-purple-300">⏱ {{ $service->duration_formatted }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-2 flex-shrink-0">
                    <a href="{{ route('services.edit', $service->id) }}"
                       class="inline-flex items-center gap-1 px-3 py-2 text-xs font-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5"
                       style="background-color: #EDE4F8; color: #6A0DAD;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </a>
                    <form method="POST" action="{{ route('services.destroy', $service->id) }}"
                          onsubmit="return confirm('Tem certeza que deseja excluir este serviço?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-1 px-3 py-2 text-xs font-semibold rounded-xl bg-red-50 text-red-400 hover:bg-red-100 transition-all duration-200 hover:-translate-y-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Excluir
                        </button>
                    </form>
                </div>
            </div>

            @empty
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4" style="background-color: #EDE4F8;">
                    <svg class="w-6 h-6 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-800">Nenhum serviço cadastrado ainda.</p>
                <p class="text-xs text-purple-400 mt-1 mb-4">Adicione seus serviços para que clientes possam agendar.</p>
                <a href="{{ route('services.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 text-white text-xs font-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5 shadow-lg shadow-purple-200"
                   style="background-color: #6A0DAD;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Adicionar serviço
                </a>
            </div>
            @endforelse
        </div>

    </div>

    <div id="cropper_modal_show" class="fixed inset-0 z-[80] hidden">
        <div class="absolute inset-0 bg-black/70" id="cropper_backdrop_show"></div>
        <div class="relative z-10 min-h-full flex items-center justify-center p-4">
            <div class="w-full max-w-3xl bg-white rounded-2xl overflow-hidden shadow-2xl">
                <div class="px-5 py-4 border-b border-purple-100 flex items-center justify-between">
                    <h3 class="text-base font-bold text-purple-800">Ajustar recorte 4:5</h3>
                    <button type="button" id="cropper_close_show" class="text-purple-300 hover:text-purple-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="p-5">
                    <div class="w-full max-h-[65vh] overflow-hidden rounded-xl bg-gray-100">
                        <img id="cropper_image_show" alt="Imagem para recorte" class="max-w-full block">
                    </div>
                    <p class="mt-3 text-xs text-purple-400">Arraste e ajuste a área para escolher o corte da foto.</p>
                </div>
                <div class="px-5 py-4 border-t border-purple-100 flex justify-end gap-2">
                    <button type="button" id="cropper_cancel_show" class="px-4 py-2.5 rounded-xl border border-purple-100 text-xs font-semibold text-purple-600 hover:bg-purple-50 transition-colors">
                        Cancelar
                    </button>
                    <button type="button" id="cropper_confirm_show" class="px-4 py-2.5 rounded-xl text-white text-xs font-semibold shadow-lg shadow-purple-200" style="background-color: #6A0DAD;">
                        Usar recorte
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const cropperModal = document.getElementById('cropper_modal_show');
        const cropperImage = document.getElementById('cropper_image_show');
        const cropperClose = document.getElementById('cropper_close_show');
        const cropperCancel = document.getElementById('cropper_cancel_show');
        const cropperConfirm = document.getElementById('cropper_confirm_show');
        const cropperBackdrop = document.getElementById('cropper_backdrop_show');

        let cropper = null;
        let activeInput = null;
        const selectedFiles = new window.Map();
        const selectedOriginalBase64 = new window.Map();

        function blobToDataUrl(blob) {
            return new window.Promise(function (resolve, reject) {
                const reader = new window.FileReader();
                reader.onloadend = function () { resolve(reader.result); };
                reader.onerror = reject;
                reader.readAsDataURL(blob);
            });
        }

        function setOriginalBase64ForInput(input, dataUrl) {
            const hiddenOriginalId = input.dataset.originalHiddenInput;
            if (!hiddenOriginalId || !dataUrl) {
                return;
            }

            selectedOriginalBase64.set(input.id, dataUrl);
            const hiddenOriginalInput = document.getElementById(hiddenOriginalId);
            if (hiddenOriginalInput) {
                hiddenOriginalInput.value = dataUrl;
            }
        }

        function openCropper(file, input) {
            if (!file) {
                return;
            }

            activeInput = input;
            const objectUrl = URL.createObjectURL(file);
            cropperImage.src = objectUrl;
            cropperModal.classList.remove('hidden');

            if (cropper) {
                cropper.destroy();
            }

            cropper = new window.Cropper(cropperImage, {
                aspectRatio: 4 / 5,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 1,
                responsive: true,
                background: false,
            });
        }

        function closeCropper(clearPendingSelection = false) {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }

            cropperModal.classList.add('hidden');
            cropperImage.removeAttribute('src');

            if (clearPendingSelection && activeInput) {
                activeInput.value = '';
            }

            activeInput = null;
        }

        document.querySelectorAll('input[data-crop-target^="show-edit-"]').forEach(function (input) {
            input.addEventListener('change', async function (event) {
                const file = event.target.files && event.target.files[0] ? event.target.files[0] : null;
                if (file) {
                    selectedFiles.set(input.id, file);
                    try {
                        const originalBase64 = await blobToDataUrl(file);
                        setOriginalBase64ForInput(input, originalBase64);
                    } catch (error) {
                        console.error('Falha ao preparar foto original para envio:', error);
                    }
                }
                openCropper(file, input);
            });
        });

        document.querySelectorAll('[data-recrop-input^="show_edit_photo_input_"]').forEach(function (button) {
            button.addEventListener('click', function () {
                const inputId = button.dataset.recropInput;
                const input = document.getElementById(inputId);

                if (!input) {
                    return;
                }

                let fileToUse = selectedFiles.get(input.id);

                if (!fileToUse && input.files && input.files[0]) {
                    fileToUse = input.files[0];
                    selectedFiles.set(input.id, fileToUse);
                }

                if (fileToUse) {
                    openCropper(fileToUse, input);
                    return;
                }

                const originalSrc = input.dataset.originalSrc;
                if (originalSrc) {
                    fetch(originalSrc, { cache: 'no-store' })
                            .then(response => response.blob())
                            .then(async blob => {
                                const originalBase64 = await blobToDataUrl(blob);
                                setOriginalBase64ForInput(input, originalBase64);
                                openCropper(blob, input);
                            })
                            .catch(error => {
                                console.error('Falha ao carregar imagem original:', error);
                                input.click();
                            });
                    return;
                }

                input.click();
            });
        });

        function handleCropConfirm() {
            if (!cropper || !activeInput) {
                closeCropper(true);
                return;
            }

            const canvas = cropper.getCroppedCanvas({
                width: 1200,
                height: 1500,
                imageSmoothingQuality: 'high',
            });

            if (!canvas) {
                closeCropper(true);
                return;
            }

            const hiddenInputId = activeInput.dataset.hiddenInput;
            const previewImgId = activeInput.dataset.previewImg;

            canvas.toBlob(function (blob) {
                if (!blob) {
                    closeCropper(true);
                    return;
                }

                const reader = new window.FileReader();
                reader.onloadend = function () {
                    const hiddenInput = document.getElementById(hiddenInputId);
                    if (hiddenInput) {
                        hiddenInput.value = reader.result;
                    }

                    const fallbackOriginalBase64 = selectedOriginalBase64.get(activeInput.id);
                    if (fallbackOriginalBase64) {
                        setOriginalBase64ForInput(activeInput, fallbackOriginalBase64);
                    }

                    const previewImg = document.getElementById(previewImgId);
                    if (previewImg) {
                        previewImg.src = URL.createObjectURL(blob);
                    }

                    activeInput.value = '';
                    closeCropper(false);
                };

                reader.readAsDataURL(blob);
            }, 'image/jpeg', 0.92);
        }

        cropperConfirm.addEventListener('click', handleCropConfirm);
        cropperClose.addEventListener('click', function () { closeCropper(true); });
        cropperCancel.addEventListener('click', function () { closeCropper(true); });
        cropperBackdrop.addEventListener('click', function () { closeCropper(true); });
    });
    </script>

</x-app-layout>