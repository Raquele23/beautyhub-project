<x-app-layout>

    <style>
        @import url('https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css');
    </style>

    <div class="max-w-2xl mx-auto px-4 sm:px-8 py-10 space-y-6">

        {{-- ── Topo ── --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold tracking-widest uppercase text-purple-400">Beauty Hub</p>
                <h1 class="text-2xl font-bold text-purple-800 mt-0.5">Editar Perfil</h1>
            </div>
                <a href="{{ route('professional.show') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-purple-700 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
               style="background-color: #E3D0F9;">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
                Voltar
            </a>
        </div>

        {{-- ── Acesso rápido ao portfólio ── --}}
        <a href="{{ route('professional.portfolio.manage', ['from' => 'edit']) }}"
           class="flex items-center justify-between px-5 py-4 bg-white rounded-2xl border border-purple-100 shadow-sm hover:border-purple-300 hover:shadow-md transition-all duration-200 group">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color: #EDE4F8;">
                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">Portfólio de fotos</p>
                    <p class="text-xs text-purple-400">{{ $professional->portfolioPhotos()->count() }}/10 fotos adicionadas</p>
                </div>
            </div>
            <svg class="w-4 h-4 text-purple-300 group-hover:text-purple-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

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

        {{-- ── Erros de validação ── --}}
        @if($errors->any())
            <div class="flex items-center gap-3 px-5 py-4 bg-white border border-red-100 rounded-2xl shadow-sm">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 bg-red-50">
                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-red-500">{{ $errors->first() }}</p>
            </div>
        @endif

        <form action="{{ route('professional.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PATCH')

            {{-- ── Foto de perfil ── --}}
            <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-6">
                <p class="text-xs font-bold text-purple-400 uppercase tracking-wide mb-1">Foto de perfil</p>
                <p class="text-xs text-purple-300 mb-4">Imagem exibida no seu perfil público.</p>

                <div class="flex items-center gap-5">
                    {{-- Preview circular --}}
                    <div class="relative w-20 h-20 flex-shrink-0">
                        <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-purple-100" style="background-color: #EDE4F8;">
                            <img id="profile_preview_img"
                                 src="{{ $professional->profile_photo ? Storage::url($professional->profile_photo) : '' }}"
                                 class="w-full h-full object-cover {{ $professional->profile_photo ? '' : 'hidden' }}"
                                 alt="Prévia da foto de perfil recortada">
                            <div id="profile_initial" class="w-full h-full flex items-center justify-center text-2xl font-bold text-purple-300 {{ $professional->profile_photo ? 'hidden' : '' }}">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        </div>

                        <button type="button"
                                id="profile_remove_icon_button"
                                class="absolute -top-1 -right-1 w-6 h-6 rounded-full bg-white shadow-md border border-red-100 flex items-center justify-center text-red-400 hover:text-red-600 transition-colors {{ $professional->profile_photo ? '' : 'hidden' }}"
                                title="Remover foto"
                                class="absolute -top-1 -right-1 w-6 h-6 rounded-full bg-white shadow-md border border-red-100 flex items-center justify-center text-red-400 hover:text-red-600 transition-colors">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Upload --}}
                    <label class="flex-1 flex flex-col items-center justify-center gap-2 h-20 rounded-xl border-2 border-dashed border-purple-100 cursor-pointer hover:border-purple-300 hover:bg-purple-50/50 transition-all duration-200">
                        <svg class="w-5 h-5 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span id="profile_upload_text" class="text-xs text-purple-400 font-medium">
                            {{ $professional->profile_photo ? 'Clique para trocar a foto' : 'Clique para adicionar uma foto' }}
                        </span>
                        <input type="file"
                               name="profile_photo"
                               id="profile_photo_input"
                               accept="image/*"
                               class="sr-only"
                               data-crop-target="profile"
                               data-preview-img="profile_preview_img"
                               data-hidden-input="cropped_profile_photo"
                               data-original-src="{{ $professional->profile_photo ? Storage::url($professional->profile_photo) : '' }}">
                    </label>
                </div>

                <div class="mt-3 flex items-center gap-3">
                    <button type="button"
                            id="profile_recrop_button"
                            data-recrop-input="profile_photo_input"
                            class="text-xs font-semibold text-purple-600 hover:text-purple-800 transition-colors {{ $professional->profile_photo ? '' : 'hidden' }}">
                        Recortar novamente
                    </button>
                    <button type="button"
                            id="profile_remove_button"
                            class="text-xs font-semibold text-red-500 hover:text-red-700 transition-colors {{ $professional->profile_photo ? '' : 'hidden' }}">
                        Remover foto
                    </button>
                </div>

                <input type="hidden" name="cropped_profile_photo" id="cropped_profile_photo">

                {{-- Campo hidden para sinalizar exclusão --}}
                <input type="hidden" name="delete_profile_photo" id="delete_profile_photo_input" value="0">

                @error('profile_photo')
                    <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── Banner da loja ── --}}
              <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-6 space-y-4"
                  x-data="{ bannerStyle: '{{ old('banner_style', $professional->banner_photo ? 'photo' : 'color') }}', bannerPreview: {{ $professional->banner_photo ? "'".Storage::url($professional->banner_photo)."'" : 'null' }}, selectedColor: '{{ old('banner_color', $professional->banner_color ?? '#6A0DAD') }}', showAllColors: false }">
                <p class="text-xs font-bold text-purple-400 uppercase tracking-wide">Banner da loja</p>
                <p class="text-xs text-purple-300">Escolha uma cor ou envie uma foto para o topo do seu perfil.</p>

                <div class="grid grid-cols-2 gap-3">
                    <button type="button"
                            @click="bannerStyle = 'color'; bannerPreview = null"
                            :class="bannerStyle === 'color' ? 'border-purple-400 bg-purple-50' : 'border-purple-100 bg-white'"
                            class="px-4 py-2.5 rounded-xl border text-xs font-semibold text-purple-600 transition-all duration-200">
                        Usar cor
                    </button>
                    <button type="button"
                            @click="bannerStyle = 'photo'"
                            :class="bannerStyle === 'photo' ? 'border-purple-400 bg-purple-50' : 'border-purple-100 bg-white'"
                            class="px-4 py-2.5 rounded-xl border text-xs font-semibold text-purple-600 transition-all duration-200">
                        Usar foto
                    </button>
                </div>

                <input type="hidden" name="banner_style" :value="bannerStyle">

                <div x-show="bannerStyle === 'color'" class="space-y-3">
                    <label class="block text-xs font-semibold text-gray-500">Escolha uma cor para o banner</label>
                    <div class="grid grid-cols-8 gap-2">
                        @php
                            $colors = [
                                '#6A0DAD' => 'Roxo Premium',
                                '#4A235A' => 'Roxo Profundo',
                                '#2D1B4E' => 'Roxo Escuro',
                                '#2D5016' => 'Verde Profundo',
                                '#3D6630' => 'Verde Médio',
                                '#1F4E78' => 'Azul Marinho',
                                '#0056B3' => 'Azul Royal',
                                '#1a237e' => 'Azul Escuro',
                                '#8B4513' => 'Marrom Elegante',
                                '#654321' => 'Marrom Profundo',
                                '#C41E3A' => 'Vinho Sofisticado',
                                '#E94B3C' => 'Coral Moderno',
                                '#FF6B6B' => 'Coral Claro',
                                '#FFB81C' => 'Ouro Luxo',
                                '#FF8C00' => 'Laranja Queimado',
                                '#1B1B1B' => 'Preto Elegante',
                            ];
                        @endphp
                        @foreach($colors as $hex => $name)
                            <button type="button"
                                    @click="selectedColor = '{{ $hex }}'; document.querySelector('input[name=banner_color]').value = '{{ $hex }}'"
                                    x-show="showAllColors || {{ $loop->index }} < 8"
                                    :class="selectedColor === '{{ $hex }}' ? 'ring-2 ring-yellow-400' : 'ring-1 ring-gray-300'"
                                    class="flex items-center justify-center w-full h-10 rounded-lg transition-all duration-150 hover:shadow-md"
                                    style="background-color: {{ $hex }}"
                                    title="{{ $name }}">
                                <svg x-show="selectedColor === '{{ $hex }}'" class="w-6 h-6 text-white drop-shadow-lg" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        @endforeach
                    </div>

                    @if(count($colors) > 8)
                        <button type="button"
                                @click="showAllColors = !showAllColors"
                                class="text-xs font-semibold text-purple-600 hover:text-purple-800 transition-colors">
                            <span x-show="!showAllColors">Mostrar mais cores</span>
                            <span x-show="showAllColors">Mostrar menos</span>
                        </button>
                    @endif

                    <input type="hidden" name="banner_color" value="{{ old('banner_color', $professional->banner_color ?? '#6A0DAD') }}">
                    @error('banner_color')
                        <p class="text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div x-show="bannerStyle === 'photo'" class="space-y-3">
                    <label class="block text-xs font-semibold text-gray-500">Foto do banner</label>
                    <label class="w-full h-28 rounded-xl border-2 border-dashed border-purple-100 cursor-pointer hover:border-purple-300 hover:bg-purple-50/50 transition-all duration-200 flex items-center justify-center text-xs text-purple-400 font-medium overflow-hidden">
                        <span x-show="!bannerPreview">Clique para selecionar uma foto</span>
                        <img x-show="bannerPreview" :src="bannerPreview" class="w-full h-full rounded-xl object-cover">
                        <input type="file" name="banner_photo" accept="image/*" class="sr-only"
                               @change="bannerPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">
                    </label>
                    @error('banner_photo')
                        <p class="text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- ── Identidade ── --}}
            <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-6 space-y-5">
                <p class="text-xs font-bold text-purple-400 uppercase tracking-wide">Identidade</p>

                {{-- Nome editável --}}
                <div>
                    <label for="name" class="block text-xs font-semibold text-gray-500 mb-2">Seu nome</label>
                    <input type="text" name="name" id="name" value="{{ old('name', Auth::user()->name) }}"
                           placeholder="Seu nome completo"
                           class="w-full px-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 placeholder-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nome do estabelecimento --}}
                <div>
                    <label for="establishment_name" class="block text-xs font-semibold text-gray-500 mb-2">
                        Nome do estabelecimento
                        <span class="text-purple-300 font-normal ml-1">(opcional)</span>
                    </label>
                    <input type="text" name="establishment_name" id="establishment_name"
                           value="{{ old('establishment_name', $professional->establishment_name) }}"
                           placeholder="Ex: Salão de Beleza XYZ"
                           class="w-full px-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 placeholder-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm">
                    @error('establishment_name')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Descrição --}}
                <div>
                    <label for="description" class="block text-xs font-semibold text-gray-500 mb-2">Descrição</label>
                    <textarea name="description" id="description" rows="3"
                              placeholder="Fale sobre você e seus serviços..."
                              class="w-full px-4 py-3 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 placeholder-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm resize-none">{{ old('description', $professional->description) }}</textarea>
                    @error('description')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- ── Contato ── --}}
            <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-6 space-y-5">
                <p class="text-xs font-bold text-purple-400 uppercase tracking-wide">Contato</p>

                {{-- Telefone --}}
                <div>
                    <label for="phone" class="block text-xs font-semibold text-gray-500 mb-2">Telefone</label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2">
                            <svg class="w-4 h-4 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $professional->phone) }}"
                               placeholder="(11) 99999-9999"
                               maxlength="15"
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 placeholder-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm">
                    </div>
                    @error('phone')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Instagram --}}
                <div>
                    <label for="instagram" class="block text-xs font-semibold text-gray-500 mb-2">
                        Instagram
                        <span class="text-purple-300 font-normal ml-1">(opcional)</span>
                    </label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2">
                            <svg class="w-4 h-4 text-purple-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </div>
                        <input type="text" name="instagram" id="instagram" value="{{ old('instagram', $professional->instagram) }}"
                               placeholder="@seu_instagram"
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 placeholder-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm">
                    </div>
                    @error('instagram')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- ── Endereço ── --}}
            <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-6 space-y-5">
                <p class="text-xs font-bold text-purple-400 uppercase tracking-wide">Endereço</p>

                {{-- Estado + Cidade --}}
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label for="state" class="block text-xs font-semibold text-gray-500 mb-2">Estado (UF)</label>
                        <select name="state" id="state"
                                class="w-full px-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm">
                            <option value="">Selecione</option>
                        </select>
                        @error('state')
                            <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-span-2">
                        <label for="city" class="block text-xs font-semibold text-gray-500 mb-2">Cidade</label>
                        <select name="city" id="city"
                                class="w-full px-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm"
                                disabled>
                            <option value="">Selecione um estado primeiro</option>
                        </select>
                        @error('city')
                            <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Rua + Número --}}
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-2">
                        <label for="street" class="block text-xs font-semibold text-gray-500 mb-2">Rua</label>
                        <input type="text" name="street" id="street" value="{{ old('street', $professional->street) }}"
                               placeholder="Rua das Flores"
                               class="w-full px-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 placeholder-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm">
                        @error('street')
                            <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="house_number" class="block text-xs font-semibold text-gray-500 mb-2">Número</label>
                        <input type="text" name="house_number" id="house_number" value="{{ old('house_number', $professional->house_number) }}"
                               placeholder="123"
                               class="w-full px-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 placeholder-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm">
                        @error('house_number')
                            <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- ── Botão salvar ── --}}
            <button type="submit"
                    class="w-full py-3 text-white text-sm font-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5 shadow-lg shadow-purple-200"
                    style="background-color: #6A0DAD;">
                Salvar Alterações
            </button>

        </form>

    </div>

</x-app-layout>

<div id="cropper_modal" class="fixed inset-0 z-[80] hidden">
    <div class="absolute inset-0 bg-black/70" id="cropper_backdrop"></div>
    <div class="relative z-10 min-h-full flex items-center justify-center p-4">
        <div class="w-full max-w-3xl bg-white rounded-2xl overflow-hidden shadow-2xl">
            <div class="px-5 py-4 border-b border-purple-100 flex items-center justify-between">
                <h3 class="text-base font-bold text-purple-800">Ajustar foto de perfil</h3>
                <button type="button" id="cropper_close" class="text-purple-300 hover:text-purple-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-5">
                <div class="w-full max-h-[65vh] overflow-hidden rounded-xl bg-gray-100">
                    <img id="cropper_image" alt="Imagem para recorte" class="max-w-full block">
                </div>
                <p class="mt-3 text-xs text-purple-400">Arraste e ajuste a área para escolher o recorte.</p>
            </div>
            <div class="px-5 py-4 border-t border-purple-100 flex justify-end gap-2">
                <button type="button" id="cropper_cancel" class="px-4 py-2.5 rounded-xl border border-purple-100 text-xs font-semibold text-purple-600 hover:bg-purple-50 transition-colors">
                    Cancelar
                </button>
                <button type="button" id="cropper_confirm" class="px-4 py-2.5 rounded-xl text-white text-xs font-semibold shadow-lg shadow-purple-200" style="background-color: #6A0DAD;">
                    Usar recorte
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const phoneInput = document.getElementById('phone');
        const stateSelect = document.getElementById('state');
        const citySelect = document.getElementById('city');
        const profileInput = document.getElementById('profile_photo_input');
        const profilePreview = document.getElementById('profile_preview_img');
        const profileInitial = document.getElementById('profile_initial');
        const profileUploadText = document.getElementById('profile_upload_text');
        const profileRecropButton = document.getElementById('profile_recrop_button');
        const profileRemoveButton = document.getElementById('profile_remove_button');
        const profileRemoveIconButton = document.getElementById('profile_remove_icon_button');
        const croppedProfileInput = document.getElementById('cropped_profile_photo');
        const deleteProfileInput = document.getElementById('delete_profile_photo_input');
        const cropperModal = document.getElementById('cropper_modal');
        const cropperImage = document.getElementById('cropper_image');
        const cropperClose = document.getElementById('cropper_close');
        const cropperCancel = document.getElementById('cropper_cancel');
        const cropperConfirm = document.getElementById('cropper_confirm');
        const cropperBackdrop = document.getElementById('cropper_backdrop');

        const oldState = "{{ old('state', $professional->state) }}";
        const oldCity = "{{ old('city', $professional->city) }}";
        let cropper = null;
        let activeInput = null;
        const selectedFiles = new window.Map();

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
                aspectRatio: 1,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 1,
                responsive: true,
                background: false,
            });
        }

        function closeCropper(clearPendingSelection) {
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

        function showRemoveControls(visible) {
            const method = visible ? 'remove' : 'add';

            if (profileRecropButton) {
                profileRecropButton.classList[method]('hidden');
            }
            if (profileRemoveButton) {
                profileRemoveButton.classList[method]('hidden');
            }
            if (profileRemoveIconButton) {
                profileRemoveIconButton.classList[method]('hidden');
            }
        }

        function applyProfilePreview(src) {
            if (!profilePreview || !profileInitial) {
                return;
            }

            profilePreview.src = src;
            profilePreview.classList.remove('hidden');
            profileInitial.classList.add('hidden');

            if (profileUploadText) {
                profileUploadText.textContent = 'Clique para trocar a foto';
            }

            if (deleteProfileInput) {
                deleteProfileInput.value = '0';
            }

            showRemoveControls(true);
        }

        function clearProfilePreview(markAsDeleted) {
            if (!profilePreview || !profileInitial) {
                return;
            }

            profilePreview.removeAttribute('src');
            profilePreview.classList.add('hidden');
            profileInitial.classList.remove('hidden');

            if (profileUploadText) {
                profileUploadText.textContent = 'Clique para adicionar uma foto';
            }

            if (croppedProfileInput) {
                croppedProfileInput.value = '';
            }

            if (deleteProfileInput) {
                deleteProfileInput.value = markAsDeleted ? '1' : '0';
            }

            showRemoveControls(false);
            selectedFiles.delete('profile_photo_input');
        }

        if (profileInput) {
            profileInput.addEventListener('change', function (event) {
                const file = event.target.files && event.target.files[0] ? event.target.files[0] : null;
                if (!file) {
                    return;
                }

                selectedFiles.set(profileInput.id, file);
                openCropper(file, profileInput);
            });
        }

        if (profileRecropButton) {
            profileRecropButton.addEventListener('click', function () {
                let fileToUse = selectedFiles.get('profile_photo_input');

                if (!fileToUse && profileInput && profileInput.files && profileInput.files[0]) {
                    fileToUse = profileInput.files[0];
                    selectedFiles.set('profile_photo_input', fileToUse);
                }

                if (fileToUse) {
                    openCropper(fileToUse, profileInput);
                    return;
                }

                const originalSrc = profileInput ? profileInput.dataset.originalSrc : null;
                if (originalSrc) {
                    fetch(originalSrc, { cache: 'no-store' })
                        .then(response => response.blob())
                        .then(blob => openCropper(blob, profileInput))
                        .catch(error => {
                            console.error('Falha ao carregar foto atual:', error);
                            profileInput.click();
                        });
                    return;
                }

                if (profileInput) {
                    profileInput.click();
                }
            });
        }

        if (profileRemoveButton) {
            profileRemoveButton.addEventListener('click', function () {
                clearProfilePreview(true);
                if (profileInput) {
                    profileInput.value = '';
                }
            });
        }

        if (profileRemoveIconButton) {
            profileRemoveIconButton.addEventListener('click', function () {
                clearProfilePreview(true);
                if (profileInput) {
                    profileInput.value = '';
                }
            });
        }

        function handleCropConfirm() {
            if (!cropper || !activeInput) {
                closeCropper(true);
                return;
            }

            const canvas = cropper.getCroppedCanvas({
                width: 900,
                height: 900,
                imageSmoothingQuality: 'high',
            });

            if (!canvas) {
                closeCropper(true);
                return;
            }

            canvas.toBlob(function (blob) {
                if (!blob) {
                    closeCropper(true);
                    return;
                }

                const reader = new window.FileReader();
                reader.onloadend = function () {
                    if (croppedProfileInput) {
                        croppedProfileInput.value = reader.result;
                    }

                    applyProfilePreview(URL.createObjectURL(blob));

                    if (activeInput) {
                        activeInput.value = '';
                    }
                    closeCropper(false);
                };
                reader.readAsDataURL(blob);
            }, 'image/jpeg', 0.92);
        }

        if (cropperConfirm) {
            cropperConfirm.addEventListener('click', handleCropConfirm);
        }
        if (cropperClose) {
            cropperClose.addEventListener('click', function () { closeCropper(true); });
        }
        if (cropperCancel) {
            cropperCancel.addEventListener('click', function () { closeCropper(true); });
        }
        if (cropperBackdrop) {
            cropperBackdrop.addEventListener('click', function () { closeCropper(true); });
        }

        async function loadStates(selectedState) {
            stateSelect.disabled = true;
            setSelectPlaceholder(stateSelect, 'Carregando estados...');

            try {
                const response = await fetch('https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome');
                const states = await response.json();

                setSelectPlaceholder(stateSelect, 'Selecione');
                states.forEach(function (state) {
                    const option = document.createElement('option');
                    option.value = state.sigla;
                    option.textContent = state.nome + ' (' + state.sigla + ')';
                    stateSelect.appendChild(option);
                });

                stateSelect.disabled = false;

                if (selectedState) {
                    stateSelect.value = selectedState;
                }
            } catch (error) {
                setSelectPlaceholder(stateSelect, 'Nao foi possivel carregar os estados');
            }
        }

        function setSelectPlaceholder(select, placeholder) {
            select.innerHTML = '';
            const option = document.createElement('option');
            option.value = '';
            option.textContent = placeholder;
            select.appendChild(option);
        }

        async function loadCities(uf, selectedCity) {
            citySelect.disabled = true;
            setSelectPlaceholder(citySelect, 'Carregando cidades...');

            if (!uf) {
                citySelect.disabled = true;
                setSelectPlaceholder(citySelect, 'Selecione um estado primeiro');
                return;
            }

            try {
                const response = await fetch('https://servicodados.ibge.gov.br/api/v1/localidades/estados/' + uf + '/municipios');
                const cities = await response.json();

                setSelectPlaceholder(citySelect, 'Selecione');
                cities.forEach(function (city) {
                    const option = document.createElement('option');
                    option.value = city.nome;
                    option.textContent = city.nome;
                    citySelect.appendChild(option);
                });

                citySelect.disabled = false;

                if (selectedCity) {
                    citySelect.value = selectedCity;
                }
            } catch (error) {
                setSelectPlaceholder(citySelect, 'Nao foi possivel carregar as cidades');
            }
        }

        function applyPhoneMask(value) {
            const digits = value.replace(/\D/g, '').slice(0, 11);

            if (!digits) {
                return '';
            }

            if (digits.length <= 2) {
                return '(' + digits;
            }

            if (digits.length <= 6) {
                return '(' + digits.slice(0, 2) + ') ' + digits.slice(2);
            }

            if (digits.length <= 10) {
                return '(' + digits.slice(0, 2) + ') ' + digits.slice(2, 6) + '-' + digits.slice(6);
            }

            return '(' + digits.slice(0, 2) + ') ' + digits.slice(2, 7) + '-' + digits.slice(7);
        }

        if (phoneInput) {
            phoneInput.value = applyPhoneMask(phoneInput.value);
            phoneInput.addEventListener('input', function () {
                phoneInput.value = applyPhoneMask(phoneInput.value);
            });
        }

        if (stateSelect && citySelect) {
            stateSelect.addEventListener('change', function () {
                loadCities(stateSelect.value, '');
            });

            loadStates(oldState).then(function () {
                if (oldState) {
                    loadCities(oldState, oldCity);
                }
            });
        }
    });
</script>