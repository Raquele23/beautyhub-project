<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="min-h-screen px-4 sm:px-8 py-12">
        <div class="max-w-5xl mx-auto space-y-10">

            {{-- Logo e boas vindas --}}
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white">BP</h1>
                <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 mt-4">Bem-vind@</p>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">{{ auth()->user()->name }}</h2>
            </div>

            {{-- Botão agendamentos --}}
            <div class="text-center">
                <a href="{{ route('professional.appointments') }}"
                   class="px-6 py-3 font-semibold rounded-lg bg-purple-600 text-white hover:bg-purple-700 transition">
                    ✓ Verificar agendamentos para hoje
                </a>
            </div>

            {{-- Cards de agendamentos --}}
            <div>
                <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-4">Agendamentos</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 text-center">
                        <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $todayAppointments }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Hoje</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 text-center">
                        <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $weekAppointments }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Essa semana</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 text-center">
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $totalCompleted }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Concluídos</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 text-center">
                        <p class="text-3xl font-bold text-red-500 dark:text-red-400">{{ $totalCancelled }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Cancelados</p>
                    </div>
                </div>
            </div>

            {{-- Cards financeiros --}}
            <div>
                <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-4">Financeiro</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 text-center">
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                            R$ {{ number_format($revenueToday, 2, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Faturamento hoje</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 text-center">
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                            R$ {{ number_format($revenueWeek, 2, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Faturamento essa semana</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 text-center">
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                            R$ {{ number_format($revenueTotal, 2, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Faturamento total</p>
                    </div>
                </div>
            </div>

            {{-- Avaliações recentes --}}
            @if($recentReviews->count())
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Avaliações recentes</h3>
                    <div class="flex items-center gap-2">
                        <x-star-rating :rating="$averageRating" size="sm" />
                        <span class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ number_format($averageRating, 1) }}</span>
                        <a href="{{ route('reviews.professional.index') }}"
                           class="text-xs text-purple-600 hover:text-purple-700 dark:text-purple-400 ml-2">
                            Ver todas
                        </a>
                    </div>
                </div>
                <div class="space-y-3">
                    @foreach($recentReviews as $review)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $review->client->name }}</p>
                                <x-star-rating :rating="$review->rating" size="sm" />
                            </div>
                            @if($review->comment)
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $review->comment }}</p>
                            @endif
                            <p class="text-xs text-gray-400 mt-1">{{ $review->created_at->diffForHumans() }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Menu principal --}}
            <div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <a href="{{ route('professional.edit') }}" class="flex items-center justify-center p-6 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <span class="text-xl mr-2">✏️</span>
                        <span class="text-gray-800 dark:text-gray-200">Editar perfil</span>
                    </a>
                    <a href="{{ route('professional.show') }}" class="flex items-center justify-center p-6 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <span class="text-xl mr-2">📊</span>
                        <span class="text-gray-800 dark:text-gray-200">Dados da minha loja</span>
                    </a>
                    <a href="{{ route('reviews.professional.index') }}" class="flex items-center justify-center p-6 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <span class="text-xl mr-2">⭐</span>
                        <span class="text-gray-800 dark:text-gray-200">Minhas avaliações</span>
                    </a>
                </div>
            </div>

            {{-- Outros --}}
            <div class="border-t border-gray-300 dark:border-gray-700 pt-8">
                <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-6">Outros</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <a href="#" class="p-6 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <span class="block text-lg mb-2">⭐</span>
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Beauty hub plus</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Conheça os benefícios</p>
                    </a>
                    <a href="#" class="p-6 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <span class="block text-lg mb-2">💳</span>
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Confirmar novos meios de pagamentos</p>
                    </a>
                    <a href="#" class="p-6 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <span class="block text-lg mb-2">⬆️</span>
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Dê um upgrade no seu estabelecimento</p>
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>