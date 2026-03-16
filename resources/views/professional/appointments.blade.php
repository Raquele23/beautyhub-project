<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Agendamentos') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('status'))
                <div class="p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-xl text-sm font-medium">
                    {{ session('status') }}
                </div>
            @endif

            {{-- AGUARDANDO CONFIRMAÇÃO --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Aguardando confirmação</h3>
                    @if($pending->count())
                        <span class="text-xs font-semibold px-2 py-1 bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300 rounded-full">
                            {{ $pending->count() }} pendente(s)
                        </span>
                    @endif
                </div>

                @forelse($pending as $appointment)
                    <div x-data="{ open: false }" class="border-b last:border-0 dark:border-gray-700">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4 px-6 py-4 cursor-pointer" @click="open = !open">
                            <div class="w-12 text-center bg-yellow-50 dark:bg-yellow-900/20 rounded-xl py-2 flex-shrink-0">
                                <p class="text-lg font-bold text-gray-900 dark:text-white leading-none">{{ $appointment->scheduled_at->format('d') }}</p>
                                <p class="text-xs text-gray-400 uppercase">{{ $appointment->scheduled_at->isoFormat('MMM') }}</p>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $appointment->service->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $appointment->client->name }} · {{ $appointment->scheduled_at->format('H:i') }}
                                </p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 transition-transform flex-shrink-0" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>

                        <div x-show="open" x-transition class="px-6 pb-4 border-t border-gray-50 dark:border-gray-700 pt-4 space-y-4">
                            {{-- Detalhes em linha --}}
                            <div class="flex flex-wrap gap-x-8 gap-y-3 text-sm">
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Serviço</p>
                                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $appointment->service->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $appointment->service->duration_formatted }} · R$ {{ number_format($appointment->service->price, 2, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Cliente</p>
                                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $appointment->client->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $appointment->client->email }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Data e hora</p>
                                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $appointment->scheduled_at->format('d/m/Y \à\s H:i') }}</p>
                                </div>
                                @if($appointment->notes)
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Observações</p>
                                    <p class="text-gray-700 dark:text-gray-300 italic text-xs">{{ $appointment->notes }}</p>
                                </div>
                                @endif
                            </div>
                            <div class="flex gap-2 pt-1">
                                <form method="POST" action="{{ route('appointments.confirm', $appointment->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-4 py-2 bg-green-600 text-white text-xs font-semibold rounded-lg hover:bg-green-700 transition">
                                        Confirmar
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('appointments.cancel', $appointment->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-4 py-2 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 text-xs font-semibold rounded-lg hover:bg-red-200 transition">
                                        Recusar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-sm text-gray-400 dark:text-gray-500">
                        Nenhum agendamento pendente.
                    </div>
                @endforelse
            </div>

            {{-- AGENDA --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Agenda</h3>
                    @if($agenda->count())
                        <span class="text-xs font-semibold px-2 py-1 bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 rounded-full">
                            {{ $agenda->count() }} confirmado(s)
                        </span>
                    @endif
                </div>

                @forelse($agenda as $appointment)
                    <div x-data="{ open: false }" class="border-b last:border-0 dark:border-gray-700">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4 px-6 py-4 cursor-pointer" @click="open = !open">
                            <div class="w-12 text-center bg-green-50 dark:bg-green-900/20 rounded-xl py-2 flex-shrink-0">
                                <p class="text-lg font-bold text-gray-900 dark:text-white leading-none">{{ $appointment->scheduled_at->format('d') }}</p>
                                <p class="text-xs text-gray-400 uppercase">{{ $appointment->scheduled_at->isoFormat('MMM') }}</p>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $appointment->service->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $appointment->client->name }} · {{ $appointment->scheduled_at->format('H:i') }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <span class="text-xs font-semibold px-3 py-1 bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 rounded-full">
                                    Confirmado
                                </span>
                                <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>

                        <div x-show="open" x-transition class="px-6 pb-4 border-t border-gray-50 dark:border-gray-700 pt-4 space-y-4">
                            <div class="flex flex-wrap gap-x-8 gap-y-3 text-sm">
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Serviço</p>
                                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $appointment->service->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $appointment->service->duration_formatted }} · R$ {{ number_format($appointment->service->price, 2, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Cliente</p>
                                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $appointment->client->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $appointment->client->email }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Data e hora</p>
                                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $appointment->scheduled_at->format('d/m/Y \à\s H:i') }}</p>
                                </div>
                                @if($appointment->notes)
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Observações</p>
                                    <p class="text-gray-700 dark:text-gray-300 italic text-xs">{{ $appointment->notes }}</p>
                                </div>
                                @endif
                            </div>
                            <form method="POST" action="{{ route('appointments.cancel', $appointment->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-xs text-red-500 hover:text-red-700 transition">
                                    Cancelar
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-sm text-gray-400 dark:text-gray-500">
                        Nenhum agendamento confirmado futuro.
                    </div>
                @endforelse
            </div>

            {{-- AGUARDANDO CONCLUSÃO --}}
            @if($awaitingComplete->count())
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Aguardando conclusão</h3>
                    <span class="text-xs font-semibold px-2 py-1 bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300 rounded-full">
                        {{ $awaitingComplete->count() }}
                    </span>
                </div>

                @foreach($awaitingComplete as $appointment)
                    <div x-data="{ open: false }" class="border-b last:border-0 dark:border-gray-700">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4 px-6 py-4 cursor-pointer" @click="open = !open">
                            <div class="w-12 text-center bg-blue-50 dark:bg-blue-900/20 rounded-xl py-2 flex-shrink-0">
                                <p class="text-lg font-bold text-gray-900 dark:text-white leading-none">{{ $appointment->scheduled_at->format('d') }}</p>
                                <p class="text-xs text-gray-400 uppercase">{{ $appointment->scheduled_at->isoFormat('MMM') }}</p>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $appointment->service->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $appointment->client->name }} · {{ $appointment->scheduled_at->format('H:i') }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <form method="POST" action="{{ route('appointments.complete', $appointment->id) }}" @click.stop>
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition">
                                        Concluir
                                    </button>
                                </form>
                                <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>

                        <div x-show="open" x-transition class="px-6 pb-4 border-t border-gray-50 dark:border-gray-700 pt-4">
                            <div class="flex flex-wrap gap-x-8 gap-y-3 text-sm">
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Serviço</p>
                                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $appointment->service->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $appointment->service->duration_formatted }} · R$ {{ number_format($appointment->service->price, 2, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Cliente</p>
                                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $appointment->client->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $appointment->client->email }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Data e hora</p>
                                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $appointment->scheduled_at->format('d/m/Y \à\s H:i') }}</p>
                                </div>
                                @if($appointment->notes)
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Observações</p>
                                    <p class="text-gray-700 dark:text-gray-300 italic text-xs">{{ $appointment->notes }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif

            {{-- CONCLUÍDOS --}}
            <div x-data="{ shown: 10 }"
                 class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Concluídos</h3>
                </div>

                @forelse($completed as $index => $appointment)
                    <div x-data="{ open: false }"
                         x-show="{{ $index }} < shown"
                         class="border-b last:border-0 dark:border-gray-700">
                        <div class="flex items-center gap-4 px-6 py-4 cursor-pointer" @click="open = !open">
                            <div class="w-12 text-center bg-gray-50 dark:bg-gray-700 rounded-xl py-2 flex-shrink-0">
                                <p class="text-lg font-bold text-gray-900 dark:text-white leading-none">{{ $appointment->scheduled_at->format('d') }}</p>
                                <p class="text-xs text-gray-400 uppercase">{{ $appointment->scheduled_at->isoFormat('MMM') }}</p>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $appointment->service->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $appointment->client->name }}</p>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <span class="text-xs font-semibold px-3 py-1 bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400 rounded-full">Concluído</span>
                                <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                        <div x-show="open" x-transition class="px-6 pb-4 border-t border-gray-50 dark:border-gray-700 pt-4">
                            <div class="flex flex-wrap gap-x-8 gap-y-3 text-sm">
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Serviço</p>
                                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $appointment->service->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $appointment->service->duration_formatted }} · R$ {{ number_format($appointment->service->price, 2, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Cliente</p>
                                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $appointment->client->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $appointment->client->email }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Data e hora</p>
                                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $appointment->scheduled_at->format('d/m/Y \à\s H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-sm text-gray-400 dark:text-gray-500">
                        Nenhum atendimento concluído ainda.
                    </div>
                @endforelse

                @if($completed->count() > 10)
                    <div class="px-6 py-3 border-t border-gray-100 dark:border-gray-700 text-center"
                         x-show="shown < {{ $completed->count() }}">
                        <button @click="shown += 10"
                                class="text-sm font-semibold text-purple-600 hover:text-purple-700 dark:text-purple-400 transition">
                            Ver mais
                        </button>
                    </div>
                @endif
            </div>

            {{-- CANCELADOS --}}
            <div x-data="{ shown: 10 }"
                 class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Cancelados</h3>
                </div>

                @forelse($cancelled as $index => $appointment)
                    <div x-data="{ open: false }"
                         x-show="{{ $index }} < shown"
                         class="border-b last:border-0 dark:border-gray-700">
                        <div class="flex items-center gap-4 px-6 py-4 cursor-pointer" @click="open = !open">
                            <div class="w-12 text-center bg-gray-50 dark:bg-gray-700 rounded-xl py-2 flex-shrink-0">
                                <p class="text-lg font-bold text-gray-900 dark:text-white leading-none">{{ $appointment->scheduled_at->format('d') }}</p>
                                <p class="text-xs text-gray-400 uppercase">{{ $appointment->scheduled_at->isoFormat('MMM') }}</p>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $appointment->service->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $appointment->client->name }}</p>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <span class="text-xs font-semibold px-3 py-1 bg-red-100 text-red-500 dark:bg-red-900/30 dark:text-red-400 rounded-full">Cancelado</span>
                                <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                        <div x-show="open" x-transition class="px-6 pb-4 border-t border-gray-50 dark:border-gray-700 pt-4">
                            <div class="flex flex-wrap gap-x-8 gap-y-3 text-sm">
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Serviço</p>
                                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $appointment->service->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $appointment->service->duration_formatted }} · R$ {{ number_format($appointment->service->price, 2, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Cliente</p>
                                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $appointment->client->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $appointment->client->email }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Data e hora</p>
                                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $appointment->scheduled_at->format('d/m/Y \à\s H:i') }}</p>
                                </div>
                                @if($appointment->notes)
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Observações</p>
                                    <p class="text-gray-700 dark:text-gray-300 italic text-xs">{{ $appointment->notes }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-sm text-gray-400 dark:text-gray-500">
                        Nenhum agendamento cancelado.
                    </div>
                @endforelse

                @if($cancelled->count() > 10)
                    <div class="px-6 py-3 border-t border-gray-100 dark:border-gray-700 text-center"
                         x-show="shown < {{ $cancelled->count() }}">
                        <button @click="shown += 10"
                                class="text-sm font-semibold text-purple-600 hover:text-purple-700 dark:text-purple-400 transition">
                            Ver mais
                        </button>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>