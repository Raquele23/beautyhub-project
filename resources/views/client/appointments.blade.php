<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Meus Agendamentos') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('status'))
                <div class="p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-xl text-sm font-medium">
                    {{ session('status') }}
                </div>
            @endif

            {{-- PRÓXIMO AGENDAMENTO --}}
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

            {{-- PRÓXIMOS --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Próximos agendamentos</h3>
                    <a href="{{ route('explore') }}"
                       class="text-xs font-semibold text-purple-600 hover:text-purple-700 dark:text-purple-400 transition">
                        + Novo
                    </a>
                </div>

                @forelse($upcomingAppointments as $appt)
                    <div x-data="{ open: false }" class="border-b last:border-0 dark:border-gray-700">
                        <div class="flex items-center gap-4 px-6 py-4 cursor-pointer" @click="open = !open">
                            <div class="w-12 text-center bg-gray-50 dark:bg-gray-700 rounded-xl py-2 flex-shrink-0">
                                <p class="text-lg font-bold text-gray-900 dark:text-white leading-none">{{ $appt->scheduled_at->format('d') }}</p>
                                <p class="text-xs text-gray-400 uppercase">{{ $appt->scheduled_at->isoFormat('MMM') }}</p>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $appt->service->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                    {{ $appt->professional->establishment_name ?? $appt->professional->user->name }} · {{ $appt->scheduled_at->format('H:i') }}
                                </p>
                            </div>
                            <div class="flex items-center gap-3 flex-shrink-0">
                                <span class="text-xs font-semibold px-3 py-1 rounded-full
                                    {{ $appt->status === 'confirmed'
                                        ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300'
                                        : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300' }}">
                                    {{ $appt->status_label }}
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
                                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $appt->service->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $appt->service->duration_formatted }} · R$ {{ number_format($appt->service->price, 2, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Profissional</p>
                                    <a href="{{ route('professional.public', $appt->professional) }}"
                                       class="font-medium text-purple-600 hover:underline dark:text-purple-400">
                                        {{ $appt->professional->establishment_name ?? $appt->professional->user->name }}
                                    </a>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Data e hora</p>
                                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $appt->scheduled_at->format('d/m/Y \à\s H:i') }}</p>
                                </div>
                                @if($appt->professional->full_address)
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Localização</p>
                                    <p class="text-gray-700 dark:text-gray-300 text-xs">{{ $appt->professional->full_address }}</p>
                                </div>
                                @endif
                                @if($appt->notes)
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Observações</p>
                                    <p class="text-gray-700 dark:text-gray-300 italic text-xs">{{ $appt->notes }}</p>
                                </div>
                                @endif
                            </div>
                            <div class="flex justify-end">
                                <form method="POST" action="{{ route('appointments.cancel', $appt->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-xs text-red-500 hover:text-red-700 transition">
                                        Cancelar agendamento
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-sm text-gray-400 dark:text-gray-500">
                        Nenhum agendamento futuro por aqui.
                    </div>
                @endforelse
            </div>

            {{-- HISTÓRICO --}}
            <div x-data="{ shown: 10 }"
                 class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Histórico</h3>
                </div>

                @forelse($pastAppointments as $index => $appt)
                    <div x-data="{ open: false }"
                         x-show="{{ $index }} < shown"
                         class="border-b last:border-0 dark:border-gray-700">
                        <div class="flex items-center gap-4 px-6 py-4 cursor-pointer" @click="open = !open">
                            <div class="w-12 text-center bg-gray-50 dark:bg-gray-700 rounded-xl py-2 flex-shrink-0">
                                <p class="text-lg font-bold text-gray-900 dark:text-white leading-none">{{ $appt->scheduled_at->format('d') }}</p>
                                <p class="text-xs text-gray-400 uppercase">{{ $appt->scheduled_at->isoFormat('MMM') }}</p>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $appt->service->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                    {{ $appt->professional->establishment_name ?? $appt->professional->user->name }}
                                </p>
                            </div>
                            <div class="flex items-center gap-3 flex-shrink-0">
                                <span class="text-xs font-semibold px-3 py-1 bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400 rounded-full">
                                    {{ $appt->status_label }}
                                </span>
                                @if($appt->status === 'completed')
                                    @if($appt->review)
                                        <span class="text-xs text-gray-400 dark:text-gray-500">Avaliado</span>
                                    @else
                                        <a href="{{ route('reviews.create', $appt->id) }}"
                                           class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 transition"
                                           @click.stop>
                                            Avaliar
                                        </a>
                                    @endif
                                @endif
                                <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>

                        <div x-show="open" x-transition class="px-6 pb-4 border-t border-gray-50 dark:border-gray-700 pt-4">
                            <div class="flex flex-wrap gap-x-8 gap-y-3 text-sm">
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Serviço</p>
                                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $appt->service->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $appt->service->duration_formatted }} · R$ {{ number_format($appt->service->price, 2, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Profissional</p>
                                    <a href="{{ route('professional.public', $appt->professional) }}"
                                       class="font-medium text-purple-600 hover:underline dark:text-purple-400">
                                        {{ $appt->professional->establishment_name ?? $appt->professional->user->name }}
                                    </a>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Data e hora</p>
                                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $appt->scheduled_at->format('d/m/Y \à\s H:i') }}</p>
                                </div>
                                @if($appt->professional->full_address)
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Localização</p>
                                    <p class="text-gray-700 dark:text-gray-300 text-xs">{{ $appt->professional->full_address }}</p>
                                </div>
                                @endif
                                @if($appt->notes)
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Observações</p>
                                    <p class="text-gray-700 dark:text-gray-300 italic text-xs">{{ $appt->notes }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-sm text-gray-400 dark:text-gray-500">
                        Seu histórico aparecerá aqui.
                    </div>
                @endforelse

                @if($pastAppointments->count() > 10)
                    <div class="px-6 py-3 border-t border-gray-100 dark:border-gray-700 text-center"
                         x-show="shown < {{ $pastAppointments->count() }}">
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