<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Início') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- PERFIL --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex flex-col sm:flex-row sm:items-center gap-5">
                    {{-- Avatar --}}
                    <div class="w-16 h-16 rounded-full bg-purple-100 dark:bg-purple-900 flex items-center justify-center flex-shrink-0 text-2xl font-bold text-purple-600 dark:text-purple-300">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-lg font-bold text-gray-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ auth()->user()->email }}</p>
                    </div>

                    <a href="{{ route('profile.edit') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition flex-shrink-0">
                        Editar perfil
                    </a>
                </div>
            </div>

            {{-- PRÓXIMO AGENDAMENTO CONFIRMADO --}}
            @if($nextAppointment)
                <div class="bg-purple-600 rounded-2xl p-6 text-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-purple-200 mb-1">Próximo agendamento</p>
                        <p class="text-xl font-bold">{{ $nextAppointment->service->name }}</p>
                        <p class="text-sm text-purple-200">
                            com {{ $nextAppointment->professional->establishment_name ?? $nextAppointment->professional->user->name }}
                        </p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-3xl font-bold">{{ $nextAppointment->scheduled_at->format('d') }}</p>
                        <p class="text-sm text-purple-200 uppercase tracking-wide">{{ $nextAppointment->scheduled_at->isoFormat('MMM') }}</p>
                        <p class="text-sm text-purple-200">{{ $nextAppointment->scheduled_at->format('H:i') }}</p>
                    </div>
                </div>
            @endif

            {{-- ATALHOS --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="{{ route('client.appointments') }}"
                   class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-5 flex items-center gap-4 hover:shadow-md transition">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Meus Agendamentos</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Ver histórico e próximos</p>
                    </div>
                </a>

                <a href="{{ route('explore') }}"
                   class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-5 flex items-center gap-4 hover:shadow-md transition">
                    <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Explorar</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Encontrar profissionais</p>
                    </div>
                </a>

                <a href="{{ route('reviews.client.index') }}"
                   class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-5 flex items-center gap-4 hover:shadow-md transition">
                    <div class="w-10 h-10 rounded-xl bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-yellow-500 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Minhas Avaliações</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Ver avaliações feitas</p>
                    </div>
                </a>
            </div>

            {{-- INDICAÇÕES --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Indicações</h3>
                    <span class="text-xs text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-full">Em breve</span>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>