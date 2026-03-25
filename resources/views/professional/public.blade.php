<x-app-layout>

    {{-- ── Botão Voltar (fora do card, flutuando no topo) ── --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-8 pt-10 pb-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold tracking-widest uppercase text-purple-400">Beauty Hub</p>
                <h1 class="text-2xl font-bold text-purple-800 mt-0.5">
                    {{ $professional->establishment_name ?? $professional->user->name }}
                </h1>
            </div>
            <a href="{{ route('explore') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-purple-700 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
               style="background-color: #E3D0F9;">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
                Voltar
            </a>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-8 pt-4 pb-10 space-y-6">

        {{-- ── Card de Perfil ── --}}
        <div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden">

            {{-- Banner com foto centrada --}}
            @php
                $bannerStyle = $professional->banner_photo
                    ? 'background-image: url(' . asset('storage/' . $professional->banner_photo) . '); background-size: cover; background-position: center;'
                    : 'background-color: ' . ($professional->banner_color ?? '#6A0DAD') . ';';
            @endphp
            <div class="relative h-36 w-full" style="{{ $bannerStyle }}">
                <div class="absolute left-1/2 -bottom-16 -translate-x-1/2 w-32 h-32 rounded-full border-4 border-white shadow-lg overflow-hidden" style="background-color: #EDE4F8;">
                    @if($professional->profile_photo)
                        <img src="{{ Storage::url($professional->profile_photo) }}"
                             alt="Foto de perfil"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-4xl font-bold text-purple-300">
                            {{ strtoupper(substr($professional->user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Nome + avaliação + distância centrados --}}
            <div class="pt-20 pb-5 text-center px-6">
                <h2 class="text-xl font-bold text-gray-900">
                    {{ $professional->establishment_name ?? $professional->user->name }}
                </h2>
                <p class="text-xs text-purple-400 mt-0.5">{{ $professional->user->name }}</p>

                @if($averageRating > 0)
                    <div class="flex items-center justify-center gap-2 mt-2">
                        <x-star-rating :rating="$averageRating" size="sm" />
                        <span class="text-xs font-semibold text-gray-800">{{ number_format($averageRating, 1) }}</span>
                        <span class="text-xs text-purple-300">({{ $reviews->total() }} {{ Str::plural('avaliação', $reviews->total()) }})</span>
                    </div>
                @endif

                @if($professional->latitude && $professional->longitude)
                    <p id="distance-label" class="hidden mt-2 text-xs font-medium text-purple-500">
                        📍 <span id="distance-text"></span>
                    </p>
                @endif
            </div>

            {{-- Faixa de infos --}}
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
                        <a href="https://instagram.com/{{ ltrim($professional->instagram, '@') }}"
                           target="_blank"
                           class="text-sm font-medium text-purple-600 hover:underline">
                            {{ $professional->instagram }}
                        </a>
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
        @if($professional->portfolioPhotos->count() > 0)
           <div id="portfolio" class="bg-white rounded-2xl border border-purple-100 shadow-sm p-6"
               x-data="{
                   photoModal: false,
                   currentIndex: 0,
                   photos: @js($professional->portfolioPhotos->map(fn($photo) => [
                       'url' => Storage::url($photo->photo),
                       'description' => $photo->description ?? 'Sem descrição para esta foto.',
                   ])->values()),
                   get selectedPhoto() {
                       return this.photos[this.currentIndex]?.url ?? null;
                   },
                   get selectedDescription() {
                       return this.photos[this.currentIndex]?.description ?? '';
                   },
                   openPhoto(index) {
                       this.currentIndex = index;
                       this.photoModal = true;
                   },
                   nextPhoto() {
                       if (!this.photos.length) return;
                       this.currentIndex = (this.currentIndex + 1) % this.photos.length;
                   },
                   prevPhoto() {
                       if (!this.photos.length) return;
                       this.currentIndex = (this.currentIndex - 1 + this.photos.length) % this.photos.length;
                   }
               }">
            <p class="text-sm font-bold text-purple-400 uppercase tracking-wide mb-4">Portfólio</p>
            <div class="flex gap-3 overflow-x-auto pb-3 scroll-smooth scrollbar-thin-soft">
                @foreach($professional->portfolioPhotos as $photo)
                <div class="flex-shrink-0">
                    <div class="rounded-xl overflow-hidden aspect-[4/5] w-40">
                        <img src="{{ Storage::url($photo->photo) }}"
                             alt="{{ $photo->description ?? '' }}"
                             @click="openPhoto({{ $loop->index }})"
                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-300 cursor-pointer">
                    </div>
                    @if($photo->description)
                        <p class="mt-1 text-xs font-medium text-gray-600 leading-snug break-words">{{ $photo->description }}</p>
                    @endif
                </div>
                @endforeach
            </div>

            {{-- Modal de ampliação --}}
              <div x-show="photoModal" x-cloak @click="photoModal = false"
                 @keydown.right.window="if (photoModal) nextPhoto()"
                 @keydown.left.window="if (photoModal) prevPhoto()"
                 class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4"
                 x-transition>
                <div @click.stop class="relative max-w-2xl w-full max-h-[80vh]">
                    <div class="mx-auto w-fit max-w-full">
                        <img :src="selectedPhoto"
                             class="w-auto max-w-full h-auto max-h-[70vh] rounded-2xl shadow-2xl object-contain mx-auto">

                        <div class="mt-2 rounded-xl bg-black/45 px-4 py-2.5 shadow-md backdrop-blur-sm max-h-[10vh] overflow-y-auto w-full">
                            <p class="text-xs text-white/90 leading-relaxed break-words">
                                <span class="font-semibold">Descrição:</span>
                                <span x-text="selectedDescription"></span>
                            </p>
                        </div>
                    </div>

                    <button @click="prevPhoto()"
                            class="absolute left-2 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/45 text-white hover:bg-black/65 transition-colors flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>

                    <button @click="nextPhoto()"
                            class="absolute right-2 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/45 text-white hover:bg-black/65 transition-colors flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                    <button @click="photoModal = false"
                            class="absolute -top-10 right-0 text-white hover:text-gray-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @endif

        {{-- ── Serviços ── --}}
        <div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-purple-50">
                <p class="text-sm font-bold text-purple-400 uppercase tracking-wide">Serviços</p>
            </div>

            @forelse($professional->services as $service)
            <div class="flex items-center gap-4 px-6 py-4 border-b border-purple-50 last:border-0">

                {{-- Imagem do serviço --}}
                <div class="w-14 h-14 rounded-xl overflow-hidden flex-shrink-0" style="background-color: #EDE4F8;">
                    @if($service->image)
                        <img src="{{ Storage::url($service->image) }}"
                             alt="{{ $service->name }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Info --}}
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

                {{-- Botão Agendar --}}
                <div class="flex-shrink-0">
                    @auth
                        @if(auth()->user()->isClient())
                            <a href="{{ route('appointments.create', [$professional->id, $service->id]) }}"
                               class="inline-flex items-center gap-1.5 px-4 py-2 text-white text-xs font-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5 shadow-lg shadow-purple-200 whitespace-nowrap"
                               style="background-color: #6A0DAD;">
                                Agendar
                            </a>
                        @endif
                    @else
                        <button onclick="document.getElementById('loginModal').classList.remove('hidden')"
                                class="inline-flex items-center gap-1.5 px-4 py-2 text-white text-xs font-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5 shadow-lg shadow-purple-200 whitespace-nowrap"
                                style="background-color: #6A0DAD;">
                            Agendar
                        </button>
                    @endauth
                </div>
            </div>

            @empty
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4" style="background-color: #EDE4F8;">
                    <svg class="w-6 h-6 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-800">Nenhum serviço cadastrado.</p>
            </div>
            @endforelse
        </div>

        {{-- ── Avaliações ── --}}
        <div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-purple-50">
                <p class="text-sm font-bold text-purple-400 uppercase tracking-wide">Avaliações</p>
                @if($reviews->total() > 0)
                    <div class="flex items-center gap-2">
                        <x-star-rating :rating="$averageRating" size="sm" />
                        <span class="text-xs font-semibold text-gray-800">{{ number_format($averageRating, 1) }}</span>
                        <span class="text-xs text-purple-300">({{ $reviews->total() }})</span>
                    </div>
                @endif
            </div>

            {{-- Distribuição de estrelas --}}
            @if($reviews->total() > 0)
            <div class="px-6 pt-4 pb-2 space-y-1.5">
                @for($star = 5; $star >= 1; $star--)
                    @php
                        $count = $starCounts[$star] ?? 0;
                        $pct   = $reviews->total() > 0 ? ($count / $reviews->total()) * 100 : 0;
                    @endphp
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <span class="w-3 text-right font-medium">{{ $star }}</span>
                        <svg class="w-3 h-3 text-yellow-400 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/>
                        </svg>
                        <div class="flex-1 rounded-full h-1.5" style="background-color: #EDE4F8;">
                            <div class="h-1.5 rounded-full" style="width: {{ $pct }}%; background-color: #9B4DCA;"></div>
                        </div>
                        <span class="w-4 text-right text-purple-300">{{ $count }}</span>
                    </div>
                @endfor
            </div>
            @endif

            {{-- Lista de avaliações --}}
            <div class="px-6 pb-4">
                @forelse($reviews as $review)
                <div class="py-4 border-b border-purple-50 last:border-0">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 text-sm font-bold text-purple-500"
                                 style="background-color: #EDE4F8;">
                                {{ strtoupper(substr($review->client->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $review->client->name }}</p>
                                <p class="text-xs text-purple-300">{{ $review->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <x-star-rating :rating="$review->rating" size="sm" />
                    </div>

                    @if($review->comment)
                        <p class="text-sm text-gray-600 mt-2 leading-relaxed">{{ $review->comment }}</p>
                    @endif

                    @if($review->hasReply())
                        <div class="mt-3 p-3 rounded-xl border-l-4 border-purple-300" style="background-color: #F7F0FD;">
                            <p class="text-xs font-semibold text-purple-400 mb-1">
                                Resposta do profissional
                                <span class="font-normal ml-1 text-purple-300">· {{ $review->replied_at->diffForHumans() }}</span>
                            </p>
                            <p class="text-sm text-gray-600">{{ $review->professional_reply }}</p>
                        </div>
                    @endif
                </div>

                @empty
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3" style="background-color: #EDE4F8;">
                        <svg class="w-6 h-6 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-800">Nenhuma avaliação ainda.</p>
                    <p class="text-xs text-purple-300 mt-0.5">Seja o primeiro a avaliar!</p>
                </div>
                @endforelse
            </div>

            {{-- Paginação --}}
            @if($reviews->hasPages())
                <div class="px-6 pb-4">{{ $reviews->links() }}</div>
            @endif
        </div>

    </div>

    {{-- ── Modal de Login ── --}}
    <div id="loginModal"
         class="hidden fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center p-4"
         onclick="if(event.target===this) this.classList.add('hidden')">
        <div class="bg-white rounded-2xl p-8 max-w-sm w-full text-center shadow-2xl border border-purple-100">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl" style="background-color: #EDE4F8;">
                💅
            </div>
            <h2 class="text-xl font-bold text-gray-900 mb-2">Quase lá!</h2>
            <p class="text-sm text-purple-400 mb-6">
                Para agendar você precisa estar logado. É rapidinho!
            </p>
            <div class="flex flex-col gap-3">
                <a href="{{ route('login') }}"
                   class="inline-flex items-center justify-center px-6 py-3 text-white text-sm font-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5 shadow-lg shadow-purple-200"
                   style="background-color: #6A0DAD;">
                    Entrar na minha conta
                </a>
                <a href="{{ route('register') }}"
                   class="inline-flex items-center justify-center px-6 py-3 text-xs font-semibold text-purple-700 rounded-xl border border-purple-200 hover:border-purple-400 transition-all duration-200">
                    Criar conta grátis
                </a>
                <button onclick="document.getElementById('loginModal').classList.add('hidden')"
                        class="text-xs text-purple-300 hover:text-purple-500 mt-1 transition-colors">
                    Continuar navegando
                </button>
            </div>
        </div>
    </div>

    {{-- ── Script de distância ── --}}
    @if($professional->latitude && $professional->longitude)
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

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const dist = haversine(
                    position.coords.latitude,
                    position.coords.longitude,
                    {{ $professional->latitude }},
                    {{ $professional->longitude }}
                );
                const label = document.getElementById('distance-label');
                const text  = document.getElementById('distance-text');
                text.textContent = dist < 1
                    ? Math.round(dist * 1000) + ' m de você'
                    : dist.toFixed(1) + ' km de você';
                label.classList.remove('hidden');
            });
        }
    </script>
    @endif

</x-app-layout>