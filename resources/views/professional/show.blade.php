<x-app-layout>

    <div class="max-w-4xl mx-auto px-4 sm:px-8 py-10 space-y-6">

        {{-- ── Topo ── --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold tracking-widest uppercase text-purple-400">Beauty Hub</p>
                <h1 class="text-2xl font-bold text-purple-800 mt-0.5">Minha Loja</h1>
            </div>
            <div class="flex items-center gap-2">
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

            {{-- Banner com foto centrada --}}
            <div class="relative h-36 w-full" style="background: linear-gradient(135deg, #6A0DAD 0%, #9B4DCA 100%);">
                <div class="absolute left-1/2 -bottom-16 -translate-x-1/2 w-32 h-32 rounded-full border-4 border-white shadow-lg overflow-hidden" style="background-color: #EDE4F8;">
                    @if($professional->profile_photo)
                        <img src="{{ Storage::url($professional->profile_photo) }}"
                             alt="Foto de perfil"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-4xl font-bold text-purple-300">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Nome + avaliação centrados --}}
            <div class="pt-20 pb-5 text-center px-6">
                <h2 class="text-xl font-bold text-gray-900">
                    {{ $professional->establishment_name ?? Auth::user()->name }}
                </h2>
                <p class="text-xs text-purple-400 mt-0.5">{{ Auth::user()->name }}</p>
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

            {{-- Faixa horizontal de infos --}}
            @php
                $address = $professional->full_address;
                $hasInfos = $professional->phone || $professional->instagram || $address;
            @endphp
            @if($hasInfos || $professional->description)
            <div class="border-t border-purple-50">

                {{-- Descrição se existir --}}
                @if($professional->description)
                <div class="px-6 py-4 border-b border-purple-50">
                    <p class="text-sm text-gray-600 text-center leading-relaxed">{{ $professional->description }}</p>
                </div>
                @endif

                {{-- Linha horizontal de contatos --}}
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
        <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-6">
            <p class="text-sm font-bold text-purple-400 uppercase tracking-wide mb-4">Portfólio</p>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach($professional->portfolioPhotos as $photo)
                <div>
                    <div class="rounded-xl overflow-hidden aspect-square">
                        <img src="{{ Storage::url($photo->photo) }}"
                             alt="{{ $photo->description ?? '' }}"
                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                    </div>
                    @if($photo->description)
                        <p class="mt-1 text-xs text-purple-300">{{ $photo->description }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

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

                {{-- Ações --}}
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

</x-app-layout>