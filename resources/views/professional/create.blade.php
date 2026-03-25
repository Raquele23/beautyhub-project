<x-app-layout>
    <x-slot name="header"></x-slot>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif !important; }
    </style>

    <div class="min-h-screen" style="background-color: #EDE4F8;">
        <div class="max-w-2xl mx-auto px-4 sm:px-8 py-10 space-y-6">

            {{-- ── Topo ── --}}
            <div>
                <p class="text-xs font-bold tracking-widest uppercase text-purple-400">Beauty Hub</p>
                <h1 class="text-2xl font-bold text-purple-800 mt-0.5">Criar Perfil</h1>
            </div>

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

            <form action="{{ route('professional.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                {{-- ── Foto de perfil ── --}}
                <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-6"
                     x-data="{ preview: null }">
                    <p class="text-xs font-bold text-purple-400 uppercase tracking-wide mb-1">Foto de perfil</p>
                    <p class="text-xs text-purple-300 mb-4">Imagem exibida no seu perfil público.</p>

                    <div class="flex items-center gap-5">
                        {{-- Preview circular --}}
                        <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-purple-100 flex-shrink-0 flex items-center justify-center text-2xl font-bold text-purple-300"
                             style="background-color: #EDE4F8;">
                            <img x-show="preview" :src="preview" class="w-full h-full object-cover">
                            <span x-show="!preview">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                        </div>

                        {{-- Upload --}}
                        <label class="flex-1 flex flex-col items-center justify-center gap-2 h-20 rounded-xl border-2 border-dashed border-purple-100 cursor-pointer hover:border-purple-300 hover:bg-purple-50/50 transition-all duration-200">
                            <svg class="w-5 h-5 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-xs text-purple-400 font-medium" x-text="preview ? 'Clique para trocar a foto' : 'Clique para adicionar uma foto'"></span>
                            <input type="file" name="profile_photo" id="profile_photo" accept="image/*" class="sr-only"
                                   @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">
                        </label>
                    </div>

                    @error('profile_photo')
                        <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ── Banner da loja ── --}}
                <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-6 space-y-4"
                     x-data="{ bannerStyle: '{{ old('banner_style', 'color') }}', bannerPreview: null, selectedColor: '{{ old('banner_color', '#6A0DAD') }}' }">
                    <p class="text-xs font-bold text-purple-400 uppercase tracking-wide">Banner da loja</p>
                    <p class="text-xs text-purple-300">Escolha uma cor ou envie uma foto para o topo do seu perfil.</p>

                    <div class="grid grid-cols-2 gap-3">
                        <button type="button"
                                @click="bannerStyle = 'color'"
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
                        <div class="grid grid-cols-5 gap-2">
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
                                        :class="selectedColor === '{{ $hex }}' ? 'ring-2 ring-yellow-400' : 'ring-1 ring-gray-300'"
                                        class="flex items-center justify-center w-full h-14 rounded-lg transition-all duration-150 hover:shadow-md"
                                        style="background-color: {{ $hex }}"
                                        title="{{ $name }}">
                                    <svg x-show="selectedColor === '{{ $hex }}'" class="w-6 h-6 text-white drop-shadow-lg" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            @endforeach
                        </div>
                        <input type="hidden" name="banner_color" value="{{ old('banner_color', '#6A0DAD') }}">
                        @error('banner_color')
                            <p class="text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                                        </svg>
                                    </span>
                                </button>
                            @endforeach
                        </div>
                        <input type="hidden" name="banner_color" value="{{ old('banner_color', '#6A0DAD') }}">
                        @error('banner_color')
                            <p class="text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-show="bannerStyle === 'photo'" class="space-y-3">
                        <label class="block text-xs font-semibold text-gray-500">Foto do banner</label>
                        <label class="w-full h-28 rounded-xl border-2 border-dashed border-purple-100 cursor-pointer hover:border-purple-300 hover:bg-purple-50/50 transition-all duration-200 flex items-center justify-center text-xs text-purple-400 font-medium">
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

                    {{-- Nome --}}
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
                               value="{{ old('establishment_name') }}"
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
                                  class="w-full px-4 py-3 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 placeholder-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm resize-none">{{ old('description') }}</textarea>
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
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
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
                            <input type="text" name="instagram" id="instagram" value="{{ old('instagram') }}"
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

                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-2">
                            <label for="street" class="block text-xs font-semibold text-gray-500 mb-2">Rua</label>
                            <input type="text" name="street" id="street" value="{{ old('street') }}"
                                   placeholder="Rua das Flores"
                                   class="w-full px-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 placeholder-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm">
                            @error('street')
                                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="house_number" class="block text-xs font-semibold text-gray-500 mb-2">Número</label>
                            <input type="text" name="house_number" id="house_number" value="{{ old('house_number') }}"
                                   placeholder="123"
                                   class="w-full px-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 placeholder-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm">
                            @error('house_number')
                                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ── Portfólio (aviso) ── --}}
                <div class="flex items-center gap-3 px-5 py-4 bg-white rounded-2xl border border-purple-100 shadow-sm">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color: #EDE4F8;">
                        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-xs text-purple-400 leading-relaxed">
                        Após criar o perfil, você poderá adicionar fotos ao seu <span class="font-semibold text-purple-600">portfólio</span> com descrições individuais.
                    </p>
                </div>

                {{-- ── Botão criar ── --}}
                <button type="submit"
                        class="w-full py-3 text-white text-sm font-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5 shadow-lg shadow-purple-200"
                        style="background-color: #6A0DAD;">
                    Criar Perfil
                </button>

            </form>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const phoneInput = document.getElementById('phone');
            const stateSelect = document.getElementById('state');
            const citySelect = document.getElementById('city');

            const oldState = "{{ old('state') }}";
            const oldCity = "{{ old('city') }}";

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

            stateSelect.addEventListener('change', function () {
                loadCities(stateSelect.value, '');
            });

            loadStates(oldState).then(function () {
                if (oldState) {
                    loadCities(oldState, oldCity);
                }
            });
        });
    </script>
</x-app-layout>