<x-app-layout>

    <div class="max-w-5xl mx-auto px-4 sm:px-8 py-10 space-y-6">

        {{-- ── Topo ── --}}
        <div>
            <p class="text-xs font-bold tracking-widest uppercase text-purple-400">Beauty Hub</p>
            <h1 class="text-2xl font-bold text-purple-800 mt-0.5">Explorar Profissionais</h1>
        </div>

        {{-- ── Busca ── --}}
        <form method="GET" action="{{ route('explore') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                </svg>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Buscar por nome, serviço ou cidade..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 placeholder-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm"
                >
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
            </div>
            <button type="submit"
                class="px-6 py-2.5 text-white text-xs font-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5 shadow-lg shadow-purple-200"
                style="background-color: #6A0DAD;">
                Buscar
            </button>
            @if(request('search') || request('category'))
                <a href="{{ route('explore') }}"
                   class="px-6 py-2.5 text-xs font-semibold rounded-xl border transition-all duration-200 hover:-translate-y-0.5 text-center"
                   style="background-color: #EDE4F8; color: #6A0DAD; border-color: #C4A8E8;">
                    Limpar
                </a>
            @endif
        </form>

        {{-- ── Filtros de categoria ── --}}
        <div class="flex flex-wrap gap-2">
            @php
                $categories = [
                    'todos'       => ['label' => 'Todos',            'emoji' => '✨'],
                    'cabelo'      => ['label' => 'Cuidados Capilares','emoji' => '✂️'],
                    'manicure'    => ['label' => 'Manicure & Pedicure','emoji' => '💅'],
                    'depilacao'   => ['label' => 'Depilação',         'emoji' => '🌿'],
                    'sobrancelha' => ['label' => 'Sobrancelhas',      'emoji' => '🪮'],
                    'maquiagem'   => ['label' => 'Maquiagem',         'emoji' => '💄'],
                    'tratamentos' => ['label' => 'Tratamentos',       'emoji' => '🧖'],
                ];
                $activeCategory = request('category', 'todos');
            @endphp

            @foreach($categories as $key => $cat)
                <a href="{{ route('explore', array_merge(request()->except('category'), $key !== 'todos' ? ['category' => $key] : [])) }}"
                   class="flex-shrink-0 inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-semibold transition-all duration-200
                       {{ $activeCategory === $key
                           ? 'text-white shadow-lg shadow-purple-200'
                           : 'bg-white text-purple-500 border border-purple-100 hover:border-purple-300' }}"
                   @if($activeCategory === $key) style="background-color: #6A0DAD;" @endif>
                    <span>{{ $cat['emoji'] }}</span>
                    {{ $cat['label'] }}
                </a>
            @endforeach
        </div>

        {{-- ── Barra de localização ── --}}
        <div id="location-bar" class="hidden items-center gap-2 text-xs text-purple-400">
            <svg class="w-4 h-4 text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span id="location-text">Ordenando por proximidade...</span>
        </div>

        {{-- ── Contagem ── --}}
        <p class="text-xs text-purple-400 font-medium -mt-2">
            {{ $professionals->count() }} profissional(is) encontrado(s)
        </p>

        {{-- ── Grid ── --}}
        @if($professionals->count())
            <div id="professionals-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($professionals as $professional)
                    <a href="{{ route('professional.public', $professional->id) }}"
                       class="professional-card bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 block"
                       data-lat="{{ $professional->latitude }}"
                       data-lon="{{ $professional->longitude }}"
                       data-id="{{ $professional->id }}">

                        {{-- Foto --}}
                        <div class="h-44 overflow-hidden" style="background-color: #EDE4F8;">
                            @if($professional->profile_photo)
                                <img src="{{ Storage::url($professional->profile_photo) }}"
                                     alt="{{ $professional->user->name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <span class="text-4xl font-bold text-purple-300">
                                        {{ strtoupper(substr($professional->user->name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-2 mb-1">
                                <h3 class="text-sm font-bold text-gray-900 leading-snug">
                                    {{ $professional->establishment_name ?? $professional->user->name }}
                                </h3>
                                @if($professional->latitude && $professional->longitude)
                                    <span class="distance-badge-{{ $professional->id }} hidden text-xs font-semibold px-2.5 py-1 rounded-full flex-shrink-0"
                                          style="background-color: #EDE4F8; color: #6A0DAD;">
                                    </span>
                                @endif
                            </div>

                            <p class="text-xs text-purple-400 mb-3">
                                📍 {{ $professional->city }}, {{ $professional->state }}
                            </p>

                            {{-- Portfólio --}}
                            @if($professional->portfolioPhotos->count() > 0)
                            <div class="mb-3">
                                <div class="flex gap-1.5 overflow-x-auto pb-3 scrollbar-thin-soft">
                                    @foreach($professional->portfolioPhotos->take(5) as $photo)
                                    <div class="flex-shrink-0">
                                        <img src="{{ Storage::url($photo->photo) }}"
                                             alt="{{ $photo->description ?? '' }}"
                                            @click.prevent.stop="window.location.href='{{ route('professional.public', $professional->id) }}#portfolio'"
                                             class="w-12 h-12 rounded-lg object-cover hover:scale-110 transition-transform cursor-pointer shadow-sm">
                                    </div>
                                    @endforeach
                                    @if($professional->portfolioPhotos->count() > 5)
                                    <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-gradient-to-br from-purple-200 to-purple-100 flex items-center justify-center text-xs font-bold text-purple-600 shadow-sm">
                                        +{{ $professional->portfolioPhotos->count() - 5 }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if($professional->services->count())
                                <div class="flex flex-wrap gap-1.5 mb-4">
                                    @foreach($professional->services->take(3) as $service)
                                        <span class="text-xs px-2.5 py-1 rounded-full border"
                                              style="background-color: #F5EFFE; color: #6A0DAD; border-color: #E3D0F9;">
                                            {{ $service->name }}
                                        </span>
                                    @endforeach
                                    @if($professional->services->count() > 3)
                                        <span class="text-xs px-2.5 py-1 text-purple-300">
                                            +{{ $professional->services->count() - 3 }} mais
                                        </span>
                                    @endif
                                </div>

                                <p class="text-xs text-purple-400">
                                    A partir de
                                    <span class="font-bold text-gray-900">
                                        R$ {{ number_format($professional->services->min('price'), 2, ',', '.') }}
                                    </span>
                                </p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl border border-purple-100 shadow-sm px-6 py-16 text-center">
                <p class="text-4xl mb-4">🔍</p>
                <p class="text-sm font-semibold text-gray-800">Nenhum profissional encontrado.</p>
                <p class="text-xs text-purple-400 mt-1">Tente buscar por outro nome, cidade ou categoria.</p>
            </div>
        @endif

    </div>

    {{-- ── Modal de Login ── --}}
    <div id="loginModal"
         class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background-color: rgba(146, 64, 204, 0.15);"
         onclick="if(event.target===this) this.classList.add('hidden')">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm border border-purple-100">
            <div class="p-8 text-center">
                <p class="text-4xl mb-4">💅</p>
                <h2 class="text-lg font-bold text-purple-800 mb-2">Quase lá!</h2>
                <p class="text-sm text-purple-400 mb-6">
                    Para agendar você precisa estar logado.
                </p>
                <div class="flex flex-col gap-3">
                    <a href="{{ route('login') }}"
                       class="inline-flex justify-center items-center px-6 py-2.5 text-white text-xs font-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5 shadow-lg shadow-purple-200"
                       style="background-color: #6A0DAD;">
                        Entrar na minha conta
                    </a>
                    <a href="{{ route('register') }}"
                       class="inline-flex justify-center items-center px-6 py-2.5 text-xs font-semibold rounded-xl border transition-all duration-200 hover:-translate-y-0.5"
                       style="background-color: #EDE4F8; color: #6A0DAD; border-color: #C4A8E8;">
                        Criar conta grátis
                    </a>
                    <button onclick="document.getElementById('loginModal').classList.add('hidden')"
                            class="text-xs text-purple-300 hover:text-purple-500 transition-colors mt-1">
                        Continuar navegando
                    </button>
                </div>
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
            if (km < 1) return Math.round(km * 1000) + ' m';
            return km.toFixed(1) + ' km';
        }

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const userLat = position.coords.latitude;
                const userLon = position.coords.longitude;

                const bar = document.getElementById('location-bar');
                bar.classList.remove('hidden');
                bar.classList.add('flex');
                document.getElementById('location-text').textContent = 'Apresentando profissionais próximos a você';

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

                const grid = document.getElementById('professionals-grid');
                cards.sort((a, b) => parseFloat(a.dataset.distance) - parseFloat(b.dataset.distance));
                cards.forEach(card => grid.appendChild(card));

            }, function() {
                // Usuário negou ou erro — mantém ordem original
            });
        }
    </script>

</x-app-layout>