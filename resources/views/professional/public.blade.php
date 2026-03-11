<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $professional->establishment_name ?? $professional->user->name }}
            </h2>
            <a href="{{ route('explore') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm font-semibold rounded-lg hover:bg-gray-200 transition">
                ← Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- CARD DE PERFIL --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md overflow-hidden">

                {{-- Banner --}}
                <div class="h-40 bg-gradient-to-r from-purple-100 to-pink-100 dark:from-purple-900 dark:to-pink-900"></div>

                {{-- Conteúdo do perfil --}}
                <div class="px-8 pb-8">
                    <div class="flex flex-col sm:flex-row sm:items-end gap-6 -mt-14">

                        {{-- Foto de perfil --}}
                        <div class="w-28 h-28 rounded-full border-4 border-white dark:border-gray-800 overflow-hidden shadow-lg bg-yellow-100 flex-shrink-0">
                            @if($professional->profile_photo)
                                <img src="{{ Storage::url($professional->profile_photo) }}"
                                     alt="Foto de perfil"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-yellow-200 text-yellow-600 text-4xl font-bold">
                                    {{ strtoupper(substr($professional->user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        {{-- Nome --}}
                        <div class="pb-1">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $professional->establishment_name ?? $professional->user->name }}
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $professional->user->name }}</p>
                        </div>
                    </div>

                    {{-- Informações --}}
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                        @if($professional->description)
                        <div class="lg:col-span-3">
                            <p class="text-xs text-gray-400 uppercase font-semibold tracking-wide mb-1">Descrição</p>
                            <p class="text-gray-700 dark:text-gray-300">{{ $professional->description }}</p>
                        </div>
                        @endif

                        @if($professional->phone)
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-semibold tracking-wide mb-1">Telefone</p>
                            <div class="flex items-center gap-2 text-gray-800 dark:text-gray-200">
                                <svg class="w-4 h-4 text-purple-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                {{ $professional->phone }}
                            </div>
                        </div>
                        @endif

                        @if($professional->instagram)
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-semibold tracking-wide mb-1">Instagram</p>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-purple-500 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                                <a href="https://instagram.com/{{ ltrim($professional->instagram, '@') }}"
                                   target="_blank"
                                   class="text-purple-600 hover:underline dark:text-purple-400">
                                    {{ $professional->instagram }}
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($professional->full_address)
                        <div class="lg:col-span-2">
                            <p class="text-xs text-gray-400 uppercase font-semibold tracking-wide mb-1">Endereço</p>
                            <div class="flex items-start gap-2 text-gray-800 dark:text-gray-200">
                                <svg class="w-4 h-4 text-purple-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>{{ $professional->full_address }}</span>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>

            {{-- PORTFÓLIO --}}
            @if($professional->portfolioPhotos->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Portfólio</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($professional->portfolioPhotos as $photo)
                        <div>
                            <div class="rounded-xl overflow-hidden aspect-square">
                                <img src="{{ Storage::url($photo->photo) }}"
                                     alt="{{ $photo->description ?? '' }}"
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                            </div>
                            @if($photo->description)
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $photo->description }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- SERVIÇOS --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Serviços</h3>

                    @forelse($professional->services as $service)
                    <div class="flex items-center justify-between py-4 border-b last:border-0 dark:border-gray-700">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700 flex-shrink-0">
                                @if($service->image)
                                    <img src="{{ Storage::url($service->image) }}"
                                         alt="{{ $service->name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <div>
                                <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $service->name }}</p>
                                @if($service->description)
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $service->description }}</p>
                                @endif
                                <div class="flex items-center gap-3 mt-1">
                                    <p class="text-sm font-medium text-purple-600 dark:text-purple-400">
                                        R$ {{ number_format($service->price, 2, ',', '.') }}
                                    </p>
                                    <span class="text-xs text-gray-400">⏱ {{ $service->duration_formatted }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Botão Agendar — exige login --}}
                        @auth
                            @if(auth()->user()->isClient())
                                <a href="{{ route('appointments.create', [$professional->id, $service->id]) }}"
                                   class="bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-200 text-sm font-semibold px-6 py-2 rounded-full hover:bg-purple-200 dark:hover:bg-purple-800 transition whitespace-nowrap">
                                    Agendar
                                </a>
                            @endif
                        @else
                            <button onclick="document.getElementById('loginModal').classList.remove('hidden')"
                                    class="bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-200 text-sm font-semibold px-6 py-2 rounded-full hover:bg-purple-200 dark:hover:bg-purple-800 transition whitespace-nowrap">
                                Agendar
                            </button>
                        @endauth
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center py-10 text-gray-400 dark:text-gray-600">
                        <p class="text-sm">Nenhum serviço cadastrado.</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    {{-- MODAL DE LOGIN --}}
    <div id="loginModal"
         class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
         onclick="if(event.target===this) this.classList.add('hidden')">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 max-w-sm w-full text-center shadow-2xl">
            <div class="text-4xl mb-4">💅</div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Quase lá!</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                Para agendar você precisa estar logado. É rapidinho!
            </p>
            <div class="flex flex-col gap-3">
                <a href="{{ route('login') }}"
                   class="bg-purple-600 text-white text-sm font-semibold px-6 py-3 rounded-full hover:bg-purple-700 transition">
                    Entrar na minha conta
                </a>
                <a href="{{ route('register') }}"
                   class="border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold px-6 py-3 rounded-full hover:border-purple-400 transition">
                    Criar conta grátis
                </a>
                <button onclick="document.getElementById('loginModal').classList.add('hidden')"
                        class="text-xs text-gray-400 hover:text-gray-600 mt-1">
                    Continuar navegando
                </button>
            </div>
        </div>
    </div>

</x-app-layout>