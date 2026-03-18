<x-app-layout>

    <div class="max-w-2xl mx-auto px-4 sm:px-8 py-10 space-y-6">

        {{-- ── Topo ── --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold tracking-widest uppercase text-purple-400">Beauty Hub</p>
                <h1 class="text-2xl font-bold text-purple-800 mt-0.5">Editar Perfil</h1>
            </div>
            <a href="{{ route('professional.store') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-purple-700 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
               style="background-color: #E3D0F9;">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
                Voltar
            </a>
        </div>

        {{-- ── Acesso rápido ao portfólio ── --}}
        <a href="{{ route('professional.portfolio.edit') }}"
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
        @if(Session::get('status'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 5000)"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-4"
                class="fixed bottom-6 right-6 z-50 flex items-center gap-3 bg-white border border-purple-100 shadow-xl shadow-purple-100 rounded-2xl px-5 py-4 max-w-sm">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color: #E0F7F4;">
                    <svg class="w-4 h-4" style="color: #0D9488;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-800">{{ Session::get('status') }}</p>
                <button @click="show = false" class="ml-2 text-purple-300 hover:text-purple-500 transition-colors flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- ── Toast: erro ── --}}
        @if(Session::get('error'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 5000)"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-4"
                class="fixed bottom-6 right-6 z-50 flex items-center gap-3 bg-white border border-red-100 shadow-xl shadow-red-100 rounded-2xl px-5 py-4 max-w-sm">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 bg-red-50">
                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-800">{{ Session::get('error') }}</p>
                <button @click="show = false" class="ml-2 text-purple-300 hover:text-purple-500 transition-colors flex-shrink-0">
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
            <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-6"
                 x-data="{ preview: {{ $professional->profile_photo ? "'".Storage::url($professional->profile_photo)."'" : 'null' }}, hasExisting: {{ $professional->profile_photo ? 'true' : 'false' }}, deleted: false }">
                <p class="text-xs font-bold text-purple-400 uppercase tracking-wide mb-1">Foto de perfil</p>
                <p class="text-xs text-purple-300 mb-4">Imagem exibida no seu perfil público.</p>

                <div class="flex items-center gap-5">
                    {{-- Preview circular --}}
                    <div class="relative w-20 h-20 flex-shrink-0">
                        <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-purple-100" style="background-color: #EDE4F8;">
                            <img x-show="preview && !deleted" :src="preview" class="w-full h-full object-cover">
                            <div x-show="!preview || deleted" class="w-full h-full flex items-center justify-center text-2xl font-bold text-purple-300">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        </div>
                        {{-- Botão excluir foto --}}
                        <button type="button"
                                x-show="(preview && !deleted)"
                                @click="deleted = true; preview = null"
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
                        <span class="text-xs text-purple-400 font-medium" x-text="preview && !deleted ? 'Clique para trocar a foto' : 'Clique para adicionar uma foto'"></span>
                        <input type="file" name="profile_photo" id="profile_photo" accept="image/*" class="sr-only"
                               @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null; deleted = false">
                    </label>
                </div>

                {{-- Campo hidden para sinalizar exclusão --}}
                <input type="hidden" name="delete_profile_photo" :value="deleted ? '1' : '0'">

                @error('profile_photo')
                    <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                @enderror
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
                        <input type="text" name="state" id="state" value="{{ old('state', $professional->state) }}"
                               maxlength="2" placeholder="CE"
                               class="w-full px-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 placeholder-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm uppercase">
                        @error('state')
                            <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-span-2">
                        <label for="city" class="block text-xs font-semibold text-gray-500 mb-2">Cidade</label>
                        <input type="text" name="city" id="city" value="{{ old('city', $professional->city) }}"
                               placeholder="São Paulo"
                               class="w-full px-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 placeholder-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm">
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