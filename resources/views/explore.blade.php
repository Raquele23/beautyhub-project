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

            {{-- LOCALIZAÇÃO --}}
            <div id="location-bar" class="hidden mb-4 flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span id="location-text">Ordenando por proximidade...</span>
            </div>

            {{-- CONTAGEM --}}
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                {{ $professionals->count() }} profissional(is) encontrado(s)
            </p>

            {{-- GRID --}}
            @if($professionals->count())
                <div id="professionals-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($professionals as $professional)
                        <a href="{{ route('professional.public', $professional->id) }}"
                           class="professional-card bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md transition block"
                           data-lat="{{ $professional->latitude }}"
                           data-lon="{{ $professional->longitude }}"
                           data-id="{{ $professional->id }}">

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
                                <div class="flex items-center justify-between mb-3">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        📍 {{ $professional->city }}, {{ $professional->state }}
                                    </p>
                                    {{-- Badge de distância (preenchido via JS) --}}
                                    @if($professional->latitude && $professional->longitude)
                                        <span class="distance-badge-{{ $professional->id }} hidden text-xs font-semibold px-2 py-1 bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-full">
                                        </span>
                                    @endif
                                </div>

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

    {{-- MODAL DE LOGIN --}}
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

    <script>
        function haversine(lat1, lon1, lat2, lon2) {
            const R = 6371;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat/2) ** 2
                + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180)
                * Math.sin(dLon/2) ** 2;
            return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        }

        function formatDistance(km) {
            if (km < 1) return Math.round(km * 1000) + ' m de distância';
            return km.toFixed(1) + ' km de distância';
        }

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const userLat = position.coords.latitude;
                const userLon = position.coords.longitude;

                // Mostra barra de localização
                const bar = document.getElementById('location-bar');
                bar.classList.remove('hidden');
                document.getElementById('location-text').textContent = 'Apresentando profissionais próximos a você';

                // Calcula distância e preenche badges
                const cards = Array.from(document.querySelectorAll('.professional-card'));

                cards.forEach(card => {
                    const lat = parseFloat(card.dataset.lat);
                    const lon = parseFloat(card.dataset.lon);
                    const id  = card.dataset.id;

                    if (lat && lon) {
                        const dist = haversine(userLat, userLon, lat, lon);
                        card.dataset.distance = dist;

                        const badge = document.querySelector(`.distance-badge-${id}`);
                        if (badge) {
                            badge.textContent = formatDistance(dist);
                            badge.classList.remove('hidden');
                        }
                    } else {
                        card.dataset.distance = 999999;
                    }
                });

                // Reordena os cards por proximidade
                const grid = document.getElementById('professionals-grid');
                cards.sort((a, b) => parseFloat(a.dataset.distance) - parseFloat(b.dataset.distance));
                cards.forEach(card => grid.appendChild(card));

            }, function() {
                // Usuário negou ou erro — não faz nada, mantém ordem original
            });
        }
    </script>

</x-app-layout>