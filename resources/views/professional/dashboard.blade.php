<x-app-layout>
    <x-slot name="header"></x-slot>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif !important; }
    </style>

    <div class="min-h-screen" style="background-color: #EDE4F8;">
        <div class="max-w-5xl mx-auto px-4 sm:px-8 py-10 space-y-10">

            {{-- ── Hero / Logo + Saudação ── --}}
            <div class="flex flex-col items-center gap-1 pt-4 text-center">
                    Bem-vind@
                </p>
                <h2 class="text-3xl font-bold text-gray-900">
                    {{ auth()->user()->name }}
                </h2>
            </div>

            {{-- ── CTA ── --}}
            <div class="flex justify-center">
                <a href="{{ route('professional.appointments') }}"
                   class="inline-flex items-center gap-2 px-7 py-3 bg-purple-700 hover:bg-purple-800 text-white text-sm font-semibold rounded-full shadow-lg shadow-purple-300 transition-all duration-200 hover:-translate-y-0.5">
                    ✓ Verificar agendamentos para hoje
                </a>
            </div>

            {{-- ── Agendamentos ── --}}
            <div>
                <p class="text-xs font-bold tracking-widest uppercase text-purple-400 mb-4">Agendamentos</p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-5 text-center hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                        <p class="text-4xl font-extrabold text-purple-700">{{ $todayAppointments }}</p>
                        <p class="text-xs text-purple-300 font-medium mt-1">Hoje</p>
                    </div>
                    <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-5 text-center hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                        <p class="text-4xl font-extrabold text-violet-600">{{ $weekAppointments }}</p>
                        <p class="text-xs text-purple-300 font-medium mt-1">Essa semana</p>
                    </div>
                    <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-5 text-center hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                        <p class="text-4xl font-extrabold text-green-600">{{ $totalCompleted }}</p>
                        <p class="text-xs text-purple-300 font-medium mt-1">Concluídos</p>
                    </div>
                    <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-5 text-center hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                        <p class="text-4xl font-extrabold text-red-500">{{ $totalCancelled }}</p>
                        <p class="text-xs text-purple-300 font-medium mt-1">Cancelados</p>
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
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg flex-shrink-0"
                             style="background-color: #E3D0F9;">✏️</div>
                        <span class="text-sm font-semibold text-gray-800">Editar perfil</span>
                    </a>
                    <a href="{{ route('professional.show') }}"
                       class="flex items-center gap-4 bg-white rounded-2xl border border-purple-100 shadow-sm px-5 py-4 hover:border-purple-400 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg flex-shrink-0"
                             style="background-color: #E3D0F9;">📊</div>
                        <span class="text-sm font-semibold text-gray-800">Dados da minha loja</span>
                    </a>
                    <a href="{{ route('reviews.professional.index') }}"
                       class="flex items-center gap-4 bg-white rounded-2xl border border-purple-100 shadow-sm px-5 py-4 hover:border-purple-400 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg flex-shrink-0"
                             style="background-color: #E3D0F9;">⭐</div>
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
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg flex-shrink-0 mt-0.5"
                             style="background-color: #E3D0F9;">⭐</div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Beauty hub plus</p>
                            <p class="text-xs text-purple-400 mt-0.5">Conheça os benefícios</p>
                        </div>
                    </a>
                    <a href="#"
                       class="flex items-start gap-4 bg-white rounded-2xl border border-purple-100 shadow-sm px-5 py-4 hover:border-purple-400 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg flex-shrink-0 mt-0.5"
                             style="background-color: #E3D0F9;">💳</div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Confirmar novos meios de pagamentos</p>
                        </div>
                    </a>
                    <a href="#"
                       class="flex items-start gap-4 bg-white rounded-2xl border border-purple-100 shadow-sm px-5 py-4 hover:border-purple-400 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg flex-shrink-0 mt-0.5"
                             style="background-color: #E3D0F9;">⬆️</div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Dê um upgrade no seu estabelecimento</p>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>