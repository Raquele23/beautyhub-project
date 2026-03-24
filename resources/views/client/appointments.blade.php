<x-app-layout>

    <div class="max-w-4xl mx-auto px-4 sm:px-8 py-10 space-y-6">

        {{-- ── Topo ── --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold tracking-widest uppercase text-purple-400">Beauty Hub</p>
                <h1 class="text-2xl font-bold text-purple-800 mt-0.5">Meus Agendamentos</h1>
            </div>
            <a href="{{ route('explore') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-purple-700 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
               style="background-color: #E3D0F9;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Novo agendamento
            </a>
        </div>

        {{-- ── Toast ── --}}
        @if(session('status'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 5000)"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-4"
                class="fixed top-20 left-1/2 -translate-x-1/2 z-50 flex items-center gap-3 bg-white border border-purple-200 shadow-xl shadow-purple-100 rounded-2xl px-5 py-4 w-max max-w-sm">
                <div class="w-8 h-8 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-purple-800">{{ session('status') }}</p>
                <button @click="show = false" class="ml-2 text-purple-300 hover:text-purple-600 transition-colors flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- ── Próximo agendamento destaque ── --}}
        @if($nextAppointment)
            <div class="rounded-2xl p-6 text-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4" style="background-color: #6A0DAD;">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest mb-1" style="color: #D4AEFF;">Próximo agendamento</p>
                    <p class="text-xl font-bold">{{ $nextAppointment->service->name }}</p>
                    <p class="text-sm mt-0.5" style="color: #D4AEFF;">
                        com {{ $nextAppointment->professional->establishment_name ?? $nextAppointment->professional->user->name }}
                    </p>
                </div>
                <div class="sm:text-right flex-shrink-0 flex sm:flex-col items-center sm:items-end gap-3 sm:gap-0">
                    <div class="w-16 h-16 rounded-2xl flex flex-col items-center justify-center flex-shrink-0" style="background-color: rgba(255,255,255,0.15);">
                        <p class="text-2xl font-bold leading-none">{{ $nextAppointment->scheduled_at->format('d') }}</p>
                        <p class="text-xs uppercase tracking-wide mt-0.5" style="color: #D4AEFF;">{{ $nextAppointment->scheduled_at->isoFormat('MMM') }}</p>
                    </div>
                    <p class="text-sm font-semibold sm:mt-2" style="color: #D4AEFF;">{{ $nextAppointment->scheduled_at->format('H:i') }}</p>
                </div>
            </div>
        @endif

        {{-- ── ABAS ── --}}
        <div x-data="{ tab: 'proximos' }">

            {{-- Barra de abas --}}
            <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide">
                <button
                    @click="tab = 'proximos'"
                    :class="tab === 'proximos'
                        ? 'bg-purple-700 text-white shadow-lg shadow-purple-200'
                        : 'bg-white text-purple-500 border border-purple-100 hover:border-purple-300'"
                    class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-semibold transition-all duration-200">
                    Próximos
                    @if($upcomingAppointments->count())
                        <span
                            :class="tab === 'proximos' ? 'bg-white/20 text-white' : 'bg-purple-100 text-purple-600'"
                            class="w-5 h-5 flex items-center justify-center rounded-full text-xs font-bold flex-shrink-0">
                            {{ $upcomingAppointments->count() }}
                        </span>
                    @endif
                </button>

                <button
                    @click="tab = 'historico'"
                    :class="tab === 'historico'
                        ? 'bg-purple-700 text-white shadow-lg shadow-purple-200'
                        : 'bg-white text-purple-500 border border-purple-100 hover:border-purple-300'"
                    class="flex-shrink-0 px-4 py-2 rounded-full text-xs font-semibold transition-all duration-200">
                    Histórico
                </button>
            </div>

            {{-- ── ABA: PRÓXIMOS ── --}}
            <div x-show="tab === 'proximos'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-4">
                <div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden">
                    @forelse($upcomingAppointments as $appt)
                        <div x-data="{ open: false }" class="border-b border-purple-50 last:border-0">
                            <div class="flex items-center gap-4 px-6 py-4 cursor-pointer hover:bg-purple-50/50 transition-colors duration-150" @click="open = !open">
                                <div class="w-12 flex-shrink-0 rounded-xl py-2 text-center" style="background-color: #EDE4F8;">
                                    <p class="text-lg font-semibold text-gray-900 leading-none">{{ $appt->scheduled_at->format('d') }}</p>
                                    <p class="text-xs text-purple-400 uppercase font-medium mt-0.5">{{ $appt->scheduled_at->isoFormat('MMM') }}</p>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $appt->service->name }}</p>
                                    <p class="text-xs text-purple-400 mt-0.5 truncate">
                                        {{ $appt->professional->establishment_name ?? $appt->professional->user->name }} · {{ $appt->scheduled_at->format('H:i') }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    @if($appt->status === 'confirmed')
                                        <span class="text-xs font-semibold px-3 py-1 rounded-full border bg-green-50 text-green-700 border-green-200">
                                            {{ $appt->status_label }}
                                        </span>
                                    @else
                                        <span class="text-xs font-semibold px-3 py-1 rounded-full border bg-yellow-50 text-yellow-700 border-yellow-200">
                                            {{ $appt->status_label }}
                                        </span>
                                    @endif
                                    <svg class="w-4 h-4 text-purple-300 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>

                            <div x-show="open" x-transition class="px-6 pb-5 pt-4 bg-purple-50/40 border-t border-purple-50 space-y-4">
                                <div class="flex flex-wrap gap-x-10 gap-y-3">
                                    <div>
                                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Serviço</p>
                                        <p class="text-sm font-medium text-gray-800">{{ $appt->service->name }}</p>
                                        <p class="text-xs text-purple-300 mt-0.5">{{ $appt->service->duration_formatted }} · R$ {{ number_format($appt->service->price, 2, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Profissional</p>
                                        <a href="{{ route('professional.public', $appt->professional) }}"
                                           class="text-sm font-medium text-gray-800 hover:text-purple-600 transition-colors">
                                            {{ $appt->professional->establishment_name ?? $appt->professional->user->name }}
                                        </a>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Data e hora</p>
                                        <p class="text-sm font-medium text-gray-800">{{ $appt->scheduled_at->format('d/m/Y \à\s H:i') }}</p>
                                    </div>
                                    @if($appt->professional->full_address)
                                    <div>
                                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Localização</p>
                                        <p class="text-sm font-medium text-gray-800">{{ $appt->professional->full_address }}</p>
                                    </div>
                                    @endif
                                    @if($appt->notes)
                                    <div>
                                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Observações</p>
                                        <p class="text-xs text-purple-300 italic mt-0.5">{{ $appt->notes }}</p>
                                    </div>
                                    @endif
                                </div>
                                <div class="pt-2 border-t border-purple-50">
                                    <form method="POST" action="{{ route('appointments.cancel', $appt->id) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-xs font-semibold text-red-400 hover:text-red-600 transition-colors">
                                            Cancelar agendamento
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center text-sm text-purple-300">Nenhum agendamento futuro por aqui.</div>
                    @endforelse
                </div>
            </div>

            {{-- ── ABA: HISTÓRICO ── --}}
            <div x-data="{ shown: 10 }" x-show="tab === 'historico'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-4">
                <div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden">
                    @forelse($pastAppointments as $index => $appt)
                        <div x-data="{ open: false }" x-show="{{ $index }} < shown" class="border-b border-purple-50 last:border-0">
                            <div class="flex items-center gap-4 px-6 py-4 cursor-pointer hover:bg-purple-50/50 transition-colors duration-150" @click="open = !open">
                                <div class="w-12 flex-shrink-0 rounded-xl py-2 text-center" style="background-color: #EDE4F8;">
                                    <p class="text-lg font-semibold text-gray-900 leading-none">{{ $appt->scheduled_at->format('d') }}</p>
                                    <p class="text-xs text-purple-400 uppercase font-medium mt-0.5">{{ $appt->scheduled_at->isoFormat('MMM') }}</p>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $appt->service->name }}</p>
                                    <p class="text-xs text-purple-400 mt-0.5 truncate">
                                        {{ $appt->professional->establishment_name ?? $appt->professional->user->name }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    @if($appt->status === 'completed')
                                        <span class="text-xs font-semibold px-3 py-1 rounded-full border" style="background-color: #E0F7F4; color: #0D9488; border-color: #99E6DE;">
                                            {{ $appt->status_label }}
                                        </span>
                                    @elseif($appt->status === 'cancelled')
                                        <span class="text-xs font-semibold px-3 py-1 bg-red-50 text-red-500 rounded-full border border-red-100">
                                            {{ $appt->status_label }}
                                        </span>
                                    @else
                                        <span class="text-xs font-semibold px-3 py-1 rounded-full border" style="background-color: #EDE4F8; color: #6A0DAD; border-color: #C4A8E8;">
                                            {{ $appt->status_label }}
                                        </span>
                                    @endif

                                    @if($appt->status === 'completed')
                                        @if($appt->review)
                                            <span class="text-xs text-purple-300">Avaliado</span>
                                        @else
                                            <a href="{{ route('reviews.create', $appt->id) }}"
                                               class="text-xs font-semibold px-3 py-1 rounded-xl transition-all duration-200 hover:-translate-y-0.5"
                                               style="background-color: #EDE4F8; color: #6A0DAD;"
                                               @click.stop>
                                                Avaliar
                                            </a>
                                        @endif
                                    @endif

                                    <svg class="w-4 h-4 text-purple-300 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>

                            <div x-show="open" x-transition class="px-6 pb-5 pt-4 bg-purple-50/40 border-t border-purple-50">
                                <div class="flex flex-wrap gap-x-10 gap-y-3">
                                    <div>
                                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Serviço</p>
                                        <p class="text-sm font-medium text-gray-800">{{ $appt->service->name }}</p>
                                        <p class="text-xs text-purple-300 mt-0.5">{{ $appt->service->duration_formatted }} · R$ {{ number_format($appt->service->price, 2, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Profissional</p>
                                        <a href="{{ route('professional.public', $appt->professional) }}"
                                           class="text-sm font-medium text-gray-800 hover:text-purple-600 transition-colors">
                                            {{ $appt->professional->establishment_name ?? $appt->professional->user->name }}
                                        </a>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Data e hora</p>
                                        <p class="text-sm font-medium text-gray-800">{{ $appt->scheduled_at->format('d/m/Y \à\s H:i') }}</p>
                                    </div>
                                    @if($appt->professional->full_address)
                                    <div>
                                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Localização</p>
                                        <p class="text-sm font-medium text-gray-800">{{ $appt->professional->full_address }}</p>
                                    </div>
                                    @endif
                                    @if($appt->notes)
                                    <div>
                                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Observações</p>
                                        <p class="text-xs text-purple-300 italic mt-0.5">{{ $appt->notes }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center text-sm text-purple-300">Seu histórico aparecerá aqui.</div>
                    @endforelse

                    @if($pastAppointments->count() > 10)
                        <div class="px-6 py-3 border-t border-purple-50 text-center" x-show="shown < {{ $pastAppointments->count() }}">
                            <button @click="shown += 10" class="text-sm font-semibold text-purple-600 hover:text-purple-800 transition-colors">Ver mais</button>
                        </div>
                    @endif
                </div>
            </div>

        </div>{{-- fim x-data tabs --}}

    </div>

</x-app-layout>