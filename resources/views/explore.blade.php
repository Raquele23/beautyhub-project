<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Explorar Profissionais') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- BUSCA --}}
            <form method="GET" action="{{ route('explore') }}" class="mb-6 flex flex-col sm:flex-row gap-3">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Buscar por nome, serviço ou cidade..."
                    class="flex-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:ring-purple-500 focus:border-purple-500 text-sm"
                >
                <button type="submit"
                    class="px-6 py-2 bg-purple-600 text-white text-sm font-semibold rounded-lg hover:bg-purple-700 transition">
                    Buscar
                </button>
                @if(request('search'))
                    <a href="{{ route('explore') }}"
                       class="px-6 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-lg hover:bg-gray-200 transition text-center">
                        Limpar
                    </a>
                @endif
            </form>

            {{-- CONTAGEM --}}
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                {{ $professionals->count() }} profissional(is) encontrado(s)
            </p>

            {{-- GRID --}}
            @if($professionals->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($professionals as $professional)
                        <a href="{{ route('professional.public', $professional->id) }}"
                           class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md transition block">

                            {{-- Foto --}}
                            <div class="h-48 bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                @if($professional->profile_photo)
                                    <img src="{{ Storage::url($professional->profile_photo) }}"
                                         alt="{{ $professional->user->name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-4xl font-bold text-gray-400 dark:text-gray-500">
                                        {{ strtoupper(substr($professional->user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="p-5">
                                <h3 class="text-base font-bold text-gray-900 dark:text-white">
                                    {{ $professional->establishment_name ?? $professional->user->name }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                                    📍 {{ $professional->city }}, {{ $professional->state }}
                                </p>

                                @if($professional->services->count())
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        @foreach($professional->services->take(3) as $service)
                                            <span class="text-xs px-3 py-1 bg-purple-50 dark:bg-purple-900 text-purple-700 dark:text-purple-300 rounded-full">
                                                {{ $service->name }}
                                            </span>
                                        @endforeach
                                        @if($professional->services->count() > 3)
                                            <span class="text-xs px-3 py-1 text-gray-400 dark:text-gray-500">
                                                +{{ $professional->services->count() - 3 }} mais
                                            </span>
                                        @endif
                                    </div>

                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        A partir de
                                        <span class="font-semibold text-gray-900 dark:text-white">
                                            R$ {{ number_format($professional->services->min('price'), 2, ',', '.') }}
                                        </span>
                                    </p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 text-gray-400 dark:text-gray-600">
                    <p class="text-4xl mb-4">🔍</p>
                    <p class="text-base font-medium">Nenhum profissional encontrado.</p>
                    <p class="text-sm mt-1">Tente buscar por outro nome ou cidade.</p>
                </div>
            @endif

        </div>
    </div>

    {{-- MODAL DE LOGIN (aparece ao tentar agendar sem estar logado) --}}
    <div id="loginModal"
         class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
         onclick="if(event.target===this) this.classList.add('hidden')">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 max-w-sm w-full text-center shadow-2xl">
            <p class="text-4xl mb-4">💅</p>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Quase lá!</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                Para agendar você precisa estar logado.
            </p>
            <div class="flex flex-col gap-3">
                <a href="{{ route('login') }}"
                   class="bg-purple-600 text-white text-sm font-semibold px-6 py-3 rounded-lg hover:bg-purple-700 transition">
                    Entrar na minha conta
                </a>
                <a href="{{ route('register') }}"
                   class="border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold px-6 py-3 rounded-lg hover:border-purple-400 transition">
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