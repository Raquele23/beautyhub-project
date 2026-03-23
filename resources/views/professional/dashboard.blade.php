<x-app-layout>
    <x-slot name="header"></x-slot>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif !important; }
    </style>

    <div class="min-h-screen" style="background-color: #EDE4F8;">
        <div class="max-w-5xl mx-auto px-4 sm:px-8 py-10 space-y-10">

            {{-- ── Hero / Saudação ── --}}
            <div class="flex flex-col items-center gap-1 pt-4 text-center">
                <p class="text-sm text-purple-400 font-medium">Bem-vind@</p>
                <h2 class="text-3xl font-bold text-gray-900">
                    {{ auth()->user()->name }}
                </h2>
            </div>

            {{-- ── CTA ── --}}
            <div class="flex justify-center">
                <a href="{{ route('professional.appointments') }}"
                   class="inline-flex items-center gap-2 px-7 py-3 bg-purple-700 hover:bg-purple-800 text-white text-sm font-semibold rounded-full shadow-lg shadow-purple-300 transition-all duration-200 hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    Verificar agendamentos para hoje
                </a>
            </div>

            {{-- ── Agendamentos ── --}}
            <div>
                <p class="text-xs font-bold tracking-widest uppercase text-purple-400 mb-4">Agendamentos</p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-5 text-center hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                        <p class="text-4xl font-extrabold" style="color: #6A0DAD;">{{ $todayAppointments }}</p>
                        <p class="text-xs font-medium mt-1" style="color: #C4A8E8;">Hoje</p>
                    </div>
                    <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-5 text-center hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                        <p class="text-4xl font-extrabold" style="color: #7B1FA2;">{{ $weekAppointments }}</p>
                        <p class="text-xs font-medium mt-1" style="color: #C4A8E8;">Essa semana</p>
                    </div>
                    <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-5 text-center hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                        <p class="text-4xl font-extrabold" style="color: #A675D6;">{{ $totalCompleted }}</p>
                        <p class="text-xs font-medium mt-1" style="color: #C4A8E8;">Concluídos</p>
                    </div>
                    <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-5 text-center hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                        <p class="text-4xl font-extrabold" style="color: #A675D6;">{{ $totalCancelled }}</p>
                        <p class="text-xs font-medium mt-1" style="color: #C4A8E8;">Cancelados</p>
                    </div>
                </div>
            </div>

            {{-- ── Financeiro ── --}}
            <div>
                <p class="text-xs font-bold tracking-widest uppercase text-purple-400 mb-4">Financeiro</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="rounded-2xl p-6 text-center text-white shadow-lg shadow-purple-300 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200"
                         style="background: linear-gradient(135deg, #6A0DAD 0%, #9333EA 100%);">
                        <p class="text-2xl font-bold">R$ {{ number_format($revenueToday, 2, ',', '.') }}</p>
                        <p class="text-xs font-medium opacity-80 mt-1">Faturamento hoje</p>
                    </div>
                    <div class="rounded-2xl p-6 text-center text-white shadow-lg shadow-purple-300 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200"
                         style="background: linear-gradient(135deg, #6A0DAD 0%, #9333EA 100%);">
                        <p class="text-2xl font-bold">R$ {{ number_format($revenueWeek, 2, ',', '.') }}</p>
                        <p class="text-xs font-medium opacity-80 mt-1">Faturamento essa semana</p>
                    </div>
                    <div class="rounded-2xl p-6 text-center text-white shadow-lg shadow-purple-300 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200"
                         style="background: linear-gradient(135deg, #6A0DAD 0%, #9333EA 100%);">
                        <p class="text-2xl font-bold">R$ {{ number_format($revenueTotal, 2, ',', '.') }}</p>
                        <p class="text-xs font-medium opacity-80 mt-1">Faturamento total</p>
                    </div>
                </div>
            </div>

            {{-- ── Avaliações recentes ── --}}
            @if($recentReviews->count())
            <div>
                <div class="flex items-center justify-between mb-4">
                    <p class="text-xs font-bold tracking-widest uppercase text-purple-400">Avaliações recentes</p>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1.5 bg-purple-100 rounded-full px-3 py-1">
                            <x-star-rating :rating="$averageRating" size="sm" />
                            <span class="text-xs font-bold text-purple-700">{{ number_format($averageRating, 1) }}</span>
                        </div>
                        <a href="{{ route('reviews.professional.index') }}"
                           class="text-xs font-semibold text-purple-600 hover:text-purple-800 transition-colors">
                            Ver todas
                        </a>
                    </div>
                </div>
                <div class="space-y-3">
                    @foreach($recentReviews as $review)
                    <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-5 hover:shadow-md transition-all duration-200">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-semibold text-gray-800">{{ $review->client->name }}</p>
                            <x-star-rating :rating="$review->rating" size="sm" />
                        </div>
                        @if($review->comment)
                            <p class="text-sm text-gray-500 leading-relaxed">{{ $review->comment }}</p>
                        @endif
                        <p class="text-xs text-purple-300 mt-2">{{ $review->created_at->diffForHumans() }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ── Menu principal ── --}}
            <div>
                <p class="text-xs font-bold tracking-widest uppercase text-purple-400 mb-4">Menu</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <a href="{{ route('professional.edit') }}"
                       class="flex items-center gap-4 bg-white rounded-2xl border border-purple-100 shadow-sm px-5 py-4 hover:border-purple-400 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background-color: #E3D0F9;">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-800">Editar perfil</span>
                    </a>
                    <a href="{{ route('professional.show') }}"
                       class="flex items-center gap-4 bg-white rounded-2xl border border-purple-100 shadow-sm px-5 py-4 hover:border-purple-400 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background-color: #E3D0F9;">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-800">Dados da minha loja</span>
                    </a>
                    <a href="{{ route('reviews.professional.index') }}"
                       class="flex items-center gap-4 bg-white rounded-2xl border border-purple-100 shadow-sm px-5 py-4 hover:border-purple-400 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background-color: #E3D0F9;">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-800">Minhas avaliações</span>
                    </a>
                </div>
            </div>

            {{-- ── Outros ── --}}
            <div class="border-t border-purple-200 pt-8">
                <p class="text-xs font-bold tracking-widest uppercase text-purple-400 mb-4">Outros</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <a href="#"
                       class="flex items-start gap-4 bg-white rounded-2xl border border-purple-100 shadow-sm px-5 py-4 hover:border-purple-400 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5"
                             style="background-color: #E3D0F9;">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Beauty hub plus</p>
                            <p class="text-xs text-purple-400 mt-0.5">Conheça os benefícios</p>
                        </div>
                    </a>
                    <a href="#"
                       class="flex items-start gap-4 bg-white rounded-2xl border border-purple-100 shadow-sm px-5 py-4 hover:border-purple-400 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5"
                             style="background-color: #E3D0F9;">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Confirmar novos meios de pagamentos</p>
                        </div>
                    </a>
                    <a href="#"
                       class="flex items-start gap-4 bg-white rounded-2xl border border-purple-100 shadow-sm px-5 py-4 hover:border-purple-400 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5"
                             style="background-color: #E3D0F9;">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Dê um upgrade no seu estabelecimento</p>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>