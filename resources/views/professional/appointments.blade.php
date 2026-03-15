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

            {{-- PENDENTES --}}
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
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 px-6 py-4 border-b last:border-0 dark:border-gray-700">
                        <div class="w-12 text-center bg-yellow-50 dark:bg-yellow-900/20 rounded-xl py-2 flex-shrink-0">
                            <p class="text-lg font-bold text-gray-900 dark:text-white leading-none">{{ $appointment->scheduled_at->format('d') }}</p>
                            <p class="text-xs text-gray-400 uppercase">{{ $appointment->scheduled_at->isoFormat('MMM') }}</p>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $appointment->service->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $appointment->client->name }} · {{ $appointment->scheduled_at->format('H:i') }}
                            </p>
                            @if($appointment->notes)
                                <p class="text-xs text-gray-400 mt-1 italic">"{{ $appointment->notes }}"</p>
                            @endif
                        </div>
                        <div class="flex gap-2 flex-wrap">
                            <form method="POST" action="{{ route('appointments.confirm', $appointment->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="px-4 py-2 bg-green-600 text-white text-xs font-semibold rounded-lg hover:bg-green-700 transition">
                                    Confirmar
                                </button>
                            </form>
                            <form method="POST" action="{{ route('appointments.cancel', $appointment->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="px-4 py-2 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 text-xs font-semibold rounded-lg hover:bg-red-200 transition">
                                    Recusar
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-sm text-gray-400 dark:text-gray-500">
                        Nenhum agendamento pendente.
                    </div>
                @endforelse
            </div>

            {{-- CONFIRMADOS --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Confirmados</h3>
                </div>

                @forelse($confirmed as $appointment)
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 px-6 py-4 border-b last:border-0 dark:border-gray-700">
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
                        <div class="flex items-center gap-2 flex-wrap">
                            @if($appointment->scheduled_at->isPast())
                                <form method="POST" action="{{ route('appointments.complete', $appointment->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition">
                                        Concluir
                                    </button>
                                </form>
                            @else
                                <span class="text-xs font-semibold px-3 py-1 bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 rounded-full">
                                    Confirmado
                                </span>
                            @endif
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
                        Nenhum agendamento confirmado.
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>