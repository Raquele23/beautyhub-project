<x-app-layout>

    @php
        $prefillDateValue = old('date', $prefillDate ?? null);
        $createAppointmentHasErrors = $errors->hasAny([
            'service_id',
            'date',
            'time',
            'client_mode',
            'known_client_id',
            'external_name',
            'external_email',
            'external_phone',
            'notes',
        ]);
    @endphp

    <div class="max-w-4xl mx-auto px-4 sm:px-8 py-10 space-y-6">

        {{-- ── Topo ── --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold tracking-widest uppercase text-purple-400">Beauty Hub</p>
                <h1 class="text-2xl font-bold text-purple-800 mt-0.5">Agendamentos</h1>
            </div>
            <div class="flex items-center gap-2">
                <!-- Desktop: botão com texto -->
                <a
                    href="{{ route('professional.appointments.create') }}"
                    class="hidden sm:inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white rounded-xl transition-all duration-200 hover:-translate-y-0.5 shadow-lg shadow-purple-200"
                    style="background-color: #6A0DAD;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Novo agendamento
                </a>

                <!-- Mobile: ícone apenas -->
                <a
                    href="{{ route('professional.appointments.create') }}"
                    class="inline-flex sm:hidden w-10 h-10 rounded-xl items-center justify-center transition-all duration-200 hover:-translate-y-0.5 shadow-sm"
                    style="background-color: #6A0DAD;"
                    title="Novo agendamento">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                </a>
                <button
                    onclick="window.dispatchEvent(new CustomEvent('open-settings-modal'))"
                    class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
                    style="background-color: #E3D0F9;"
                    title="Configurações">
                    <svg class="w-5 h-5 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Agora a criação de agendamento é feita em página dedicada. Use a rota de criação. --}}

        {{-- ── Modal de Configurações ── --}}
        <div x-data="{ open: false }" @open-settings-modal.window="open = true">
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click.self="open = false"
                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                style="display: none; background-color: rgba(146, 64, 204, 0.15);">

                <div class="bg-white rounded-2xl shadow-xl w-full max-w-md border border-purple-100">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-purple-50">
                        <h3 class="font-bold text-purple-800">Configurações de Agendamento</h3>
                        <button @click="open = false" class="text-purple-300 hover:text-purple-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-purple-400 mb-5">
                            Defina como seus atendimentos são marcados como concluídos.
                        </p>
                        <form action="{{ route('professional.settings.update') }}" method="POST" class="space-y-3">
                            @csrf
                            @method('PATCH')

                            <label class="flex items-start gap-4 p-4 border-2 rounded-xl cursor-pointer transition-all duration-200
                                {{ !auth()->user()->professional->auto_complete
                                    ? 'border-purple-500 bg-purple-50'
                                    : 'border-purple-100 hover:border-purple-300' }}">
                                <input type="radio" name="auto_complete" value="0" required
                                    {{ !auth()->user()->professional->auto_complete ? 'checked' : '' }}
                                    class="mt-0.5 text-purple-600 focus:ring-purple-500">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Manual</p>
                                    <p class="text-xs text-purple-400 mt-0.5">Você marca o atendimento como concluído manualmente após realizá-lo.</p>
                                </div>
                            </label>

                            <label class="flex items-start gap-4 p-4 border-2 rounded-xl cursor-pointer transition-all duration-200
                                {{ auth()->user()->professional->auto_complete
                                    ? 'border-purple-500 bg-purple-50'
                                    : 'border-purple-100 hover:border-purple-300' }}">
                                <input type="radio" name="auto_complete" value="1" required
                                    {{ auth()->user()->professional->auto_complete ? 'checked' : '' }}
                                    class="mt-0.5 text-purple-600 focus:ring-purple-500">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Automático</p>
                                    <p class="text-xs text-purple-400 mt-0.5">O sistema marca automaticamente como concluído após o horário somado à duração do serviço.</p>
                                </div>
                            </label>

                            <div class="pt-2">
                                <button type="submit"
                                    class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-purple-700 hover:bg-purple-800 text-white text-xs font-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5 shadow-lg shadow-purple-200">
                                    Salvar Configurações
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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
       
        {{-- ── ABAS ── --}}
        <div x-data="{ tab: @js(request('tab', 'pendentes')) }">

            {{-- Barra de abas --}}
            <div class="flex gap-2 overflow-x-auto pb-3 scrollbar-thin-soft">
                <button
                    @click="tab = 'pendentes'"
                    :class="tab === 'pendentes'
                        ? 'bg-purple-700 text-white shadow-lg shadow-purple-200'
                        : 'bg-white text-purple-500 border border-purple-100 hover:border-purple-300'"
                    class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-semibold transition-all duration-200">
                    Pendentes
                    @if($pending->count())
                        <span
                            :class="tab === 'pendentes' ? 'bg-white/20 text-white' : 'bg-purple-100 text-purple-600'"
                            class="w-5 h-5 flex items-center justify-center rounded-full text-xs font-bold flex-shrink-0">
                            {{ $pending->count() }}
                        </span>
                    @endif
                </button>

                <button
                    @click="tab = 'em-andamento'"
                    :class="tab === 'em-andamento'
                        ? 'bg-purple-700 text-white shadow-lg shadow-purple-200'
                        : 'bg-white text-purple-500 border border-purple-100 hover:border-purple-300'"
                    class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-semibold transition-all duration-200">
                    Em andamento
                    @if(($agenda->count() + $awaitingComplete->count()) > 0)
                        <span
                            :class="tab === 'em-andamento' ? 'bg-white/20 text-white' : 'bg-purple-100 text-purple-600'"
                            class="w-5 h-5 flex items-center justify-center rounded-full text-xs font-bold flex-shrink-0">
                            {{ $agenda->count() + $awaitingComplete->count() }}
                        </span>
                    @endif
                </button>

                <button
                    @click="tab = 'concluidos'"
                    :class="tab === 'concluidos'
                        ? 'bg-purple-700 text-white shadow-lg shadow-purple-200'
                        : 'bg-white text-purple-500 border border-purple-100 hover:border-purple-300'"
                    class="flex-shrink-0 px-4 py-2 rounded-full text-xs font-semibold transition-all duration-200">
                    Concluídos
                </button>

                <button
                    @click="tab = 'cancelados'"
                    :class="tab === 'cancelados'
                        ? 'bg-purple-700 text-white shadow-lg shadow-purple-200'
                        : 'bg-white text-purple-500 border border-purple-100 hover:border-purple-300'"
                    class="flex-shrink-0 px-4 py-2 rounded-full text-xs font-semibold transition-all duration-200">
                    Cancelados
                </button>
            </div>

            {{-- ── ABA: PENDENTES ── --}}
            <div x-show="tab === 'pendentes'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-4">
                <div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-visible">
                    @forelse($pending as $appointment)
                        <div x-data="{ open: false, infoOpen: false }" class="border-b border-purple-50 last:border-0">
                            @php
                                $isOverdue = $appointment->isPendingOverdue();
                                $isDueSoon = $appointment->isPendingDueSoon();
                            @endphp

                            {{-- Linha principal com botões à direita --}}
                            <div class="flex items-center gap-4 px-6 py-4">
                                {{-- Bloco de data --}}
                                <div class="w-12 flex-shrink-0 rounded-xl py-2 text-center" style="background-color: #EDE4F8;">
                                    <p class="text-lg font-semibold text-gray-900 leading-none">{{ $appointment->scheduled_at->format('d') }}</p>
                                    <p class="text-xs text-purple-400 uppercase font-medium mt-0.5">{{ $appointment->scheduled_at->isoFormat('MMM') }}</p>
                                </div>

                                {{-- Info clicável para expandir --}}
                                <div class="flex-1 min-w-0 cursor-pointer" @click="open = !open">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $appointment->service->name }}</p>
                                    <p class="text-xs text-purple-400 mt-0.5">{{ $appointment->display_client_name }} · {{ $appointment->scheduled_at->format('H:i') }}</p>
                                </div>

                                @if($isOverdue || $isDueSoon)
                                    <div class="relative flex-shrink-0 inline-flex items-center">
                                        <button type="button" @click.stop="infoOpen = !infoOpen" aria-label="Mais informações" class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $isOverdue ? 'bg-red-100 text-red-600 hover:bg-red-200' : 'bg-amber-100 text-amber-600 hover:bg-amber-200' }} transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                        <div x-show="infoOpen" x-cloak @click.outside="infoOpen = false" class="absolute right-0 mt-2 w-80 p-3 rounded-xl border {{ $isOverdue ? 'border-red-100' : 'border-amber-100' }} bg-white shadow-xl z-50" style="display: none;">
                                            @if($isOverdue)
                                                <p class="text-xs font-semibold text-red-700">Esse agendamento passou do horário sem confirmação.</p>
                                                <p class="text-xs text-red-500 mt-1">Como profissional, você não pode mais confirmar este horário. A ação disponível agora é recusar para registrar a decisão e seguir com sua agenda.</p>
                                            @else
                                                <p class="text-xs font-semibold text-amber-700">Esse agendamento ainda está dentro da janela de confirmação.</p>
                                                <p class="text-xs text-amber-600 mt-1">Faltam {{ $appointment->timeUntilScheduledFormatted() }} para o horário. Como profissional, você ainda pode confirmar ou recusar normalmente.</p>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Ações: inline no desktop, empilhadas no mobile --}}
                                <div class="hidden sm:flex items-center gap-2 flex-shrink-0">
                                    @if(! $isOverdue)
                                        <form method="POST" action="{{ route('appointments.confirm', $appointment->id) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="flex items-center gap-1.5 px-4 py-2 text-white text-xs font-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5" style="background-color: #6A0DAD;">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                Confirmar
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('appointments.cancel', $appointment->id) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="flex items-center gap-1.5 px-4 py-2 text-xs font-semibold rounded-xl border transition-all duration-200" style="background-color: #EDE4F8; color: #6A0DAD; border-color: #C4A8E8;">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Recusar
                                        </button>
                                    </form>
                                </div>

                                {{-- Seta para expandir detalhes --}}
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <svg class="w-4 h-4 text-purple-300 transition-transform duration-200 cursor-pointer" :class="open ? 'rotate-180' : ''" @click="open = !open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>

                            <div class="px-6 pb-4 sm:hidden">
                                <div class="flex items-center gap-2">
                                    @if(! $isOverdue)
                                        <form method="POST" action="{{ route('appointments.confirm', $appointment->id) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="w-28 text-center px-3 py-1.5 text-white text-xs font-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5 shadow-sm" style="background-color: #6A0DAD;">
                                                Confirmar
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('appointments.cancel', $appointment->id) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="w-28 text-center px-3 py-1.5 text-xs font-semibold rounded-xl border transition-all duration-200" style="background-color: #EDE4F8; color: #6A0DAD; border-color: #C4A8E8;">
                                            Recusar
                                        </button>
                                    </form>
                                </div>
                            </div>

                            {{-- Accordion de detalhes (sem botões) --}}
                            <div x-show="open" x-transition class="px-6 pb-5 pt-4 bg-purple-50/40 border-t border-purple-50">
                                <div class="flex flex-wrap gap-x-10 gap-y-3">
                                    <div>
                                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Serviço</p>
                                        <p class="text-sm font-medium text-gray-800">{{ $appointment->service->name }}</p>
                                        <p class="text-xs text-purple-300 mt-0.5">{{ $appointment->service->duration_formatted }} · R$ {{ number_format($appointment->service->price, 2, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Cliente</p>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <p class="text-sm font-medium text-gray-800">{{ $appointment->display_client_name }}</p>
                                            @if($appointment->is_external_client)
                                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 border border-amber-200">Externo</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-purple-300 mt-0.5">{{ $appointment->display_client_contact ?? 'Sem contato' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Data e hora</p>
                                        <p class="text-sm font-medium text-gray-800">{{ $appointment->scheduled_at->format('d/m/Y \à\s H:i') }}</p>
                                    </div>
                                    @if($appointment->notes)
                                    <div>
                                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Observações</p>
                                        <p class="text-xs text-gray-500 italic">{{ $appointment->notes }}</p>
                                    </div>
                                    @endif
                                </div>

                                
                            </div>

                        </div>
                    @empty
                        <div class="px-6 py-12 text-center text-sm text-purple-300">Nenhum agendamento pendente.</div>
                    @endforelse
                </div>
            </div>

            {{-- ── ABA: EM ANDAMENTO (CONFIRMADOS) ── --}}
            <div x-show="tab === 'em-andamento'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-4">
                @if($agenda->count() === 0 && $awaitingComplete->count() === 0)
                    <div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden">
                        <div class="px-6 py-12 text-center text-sm text-purple-300">Nenhum agendamento em andamento.</div>
                    </div>
                @endif

                @if($agenda->count())
                    <p class="text-xs font-bold tracking-widest uppercase text-purple-400 mb-3">Confirmados</p>
                    <div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden">
                        @foreach($agenda as $appointment)
                        <div x-data="{ open: false }" class="border-b border-purple-50 last:border-0">
                            <div class="flex items-center gap-4 px-6 py-4 cursor-pointer hover:bg-purple-50/50 transition-colors duration-150" @click="open = !open">
                                <div class="w-12 flex-shrink-0 rounded-xl py-2 text-center" style="background-color: #EDE4F8;">
                                    <p class="text-lg font-semibold text-gray-900 leading-none">{{ $appointment->scheduled_at->format('d') }}</p>
                                    <p class="text-xs text-purple-400 uppercase font-medium mt-0.5">{{ $appointment->scheduled_at->isoFormat('MMM') }}</p>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $appointment->service->name }}</p>
                                    <p class="text-xs text-purple-400 mt-0.5">{{ $appointment->display_client_name }} · {{ $appointment->scheduled_at->format('H:i') }}</p>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <span class="text-xs font-semibold px-3 py-1 bg-green-50 text-green-700 rounded-full border border-green-200">Confirmado</span>
                                    <svg class="w-4 h-4 text-purple-300 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                            <div x-show="open" x-transition class="px-6 pb-5 pt-4 bg-purple-50/40 border-t border-purple-50 space-y-4">
                                <div class="flex flex-wrap gap-x-10 gap-y-3">
                                    <div>
                                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Serviço</p>
                                        <p class="text-sm font-medium text-gray-800">{{ $appointment->service->name }}</p>
                                        <p class="text-xs text-purple-300 mt-0.5">{{ $appointment->service->duration_formatted }} · R$ {{ number_format($appointment->service->price, 2, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Cliente</p>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <p class="text-sm font-medium text-gray-800">{{ $appointment->display_client_name }}</p>
                                            @if($appointment->is_external_client)
                                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 border border-amber-200">Externo</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-purple-300 mt-0.5">{{ $appointment->display_client_contact ?? 'Sem contato' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Data e hora</p>
                                        <p class="text-sm font-medium text-gray-800">{{ $appointment->scheduled_at->format('d/m/Y \à\s H:i') }}</p>
                                    </div>
                                    @if($appointment->notes)
                                    <div>
                                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Observações</p>
                                        <p class="text-xs text-gray-500 italic">{{ $appointment->notes }}</p>
                                    </div>
                                    @endif
                                </div>
                                <form method="POST" action="{{ route('appointments.cancel', $appointment->id) }}" class="flex items-center">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="inline-flex items-center gap-2 text-xs font-semibold leading-none text-red-400 hover:text-red-600 transition-colors">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Cancelar agendamento
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ── ABA: EM ANDAMENTO (A CONCLUIR) ── --}}
            <div x-show="tab === 'em-andamento'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-4">
                @if($awaitingComplete->count())
                    <p class="text-xs font-bold tracking-widest uppercase text-purple-400 mb-3">A concluir</p>
                    <div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden">
                        @foreach($awaitingComplete as $appointment)
                        <div x-data="{ open: false }" class="border-b border-purple-50 last:border-0">
                            <div class="flex items-center gap-4 px-6 py-4 cursor-pointer hover:bg-purple-50/50 transition-colors duration-150" @click="open = !open">
                                <div class="w-12 flex-shrink-0 rounded-xl py-2 text-center" style="background-color: #EDE4F8;">
                                    <p class="text-lg font-semibold text-gray-900 leading-none">{{ $appointment->scheduled_at->format('d') }}</p>
                                    <p class="text-xs text-purple-400 uppercase font-medium mt-0.5">{{ $appointment->scheduled_at->isoFormat('MMM') }}</p>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $appointment->service->name }}</p>
                                    <p class="text-xs text-purple-400 mt-0.5">{{ $appointment->display_client_name }} · {{ $appointment->scheduled_at->format('H:i') }}</p>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    @php
                                        // Desabilita o botão apenas quando o agendamento já estiver concluído.
                                        // Antes: o botão ficava desabilitado se o profissional tinha auto_complete
                                        // ativado e o sistema determinava que deveria auto-completar.
                                        // Agora: considerar apenas quando o status já for 'completed',
                                        // permitindo que o profissional conclua manualmente antes da job rodar.
                                        $isAutoCompleted = $appointment->status === 'completed';
                                    @endphp
                                    <form method="POST" action="{{ route('appointments.complete', $appointment->id) }}" @click.stop>
                                        @csrf @method('PATCH')
                                        <button 
                                            type="submit" 
                                            @if($isAutoCompleted) disabled @endif
                                            class="px-4 py-2 text-white text-xs font-semibold rounded-xl transition-all duration-200 {{ $isAutoCompleted ? 'opacity-50 cursor-not-allowed' : 'hover:-translate-y-0.5' }} shadow-sm" 
                                            style="background-color: #6A0DAD;"
                                            title="{{ $isAutoCompleted ? 'Concluído automaticamente' : 'Marcar como concluído' }}">
                                            {{ $isAutoCompleted ? '✓ Concluído' : 'Concluir' }}
                                        </button>
                                    </form>
                                    <svg class="w-4 h-4 text-purple-300 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                            <div x-show="open" x-transition class="px-6 pb-5 pt-4 bg-purple-50/40 border-t border-purple-50">
                                <div class="flex flex-wrap gap-x-10 gap-y-3">
                                    <div>
                                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Serviço</p>
                                        <p class="text-sm font-medium text-gray-800">{{ $appointment->service->name }}</p>
                                        <p class="text-xs text-purple-300 mt-0.5">{{ $appointment->service->duration_formatted }} · R$ {{ number_format($appointment->service->price, 2, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Cliente</p>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <p class="text-sm font-medium text-gray-800">{{ $appointment->display_client_name }}</p>
                                            @if($appointment->is_external_client)
                                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 border border-amber-200">Externo</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-purple-300 mt-0.5">{{ $appointment->display_client_contact ?? 'Sem contato' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Data e hora</p>
                                        <p class="text-sm font-medium text-gray-800">{{ $appointment->scheduled_at->format('d/m/Y \à\s H:i') }}</p>
                                    </div>
                                    @if($appointment->notes)
                                    <div>
                                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Observações</p>
                                        <p class="text-xs text-gray-500 italic">{{ $appointment->notes }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ── ABA: CONCLUÍDOS ── --}}
            <div x-data="{ shown: 10 }" x-show="tab === 'concluidos'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-4">
                <div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden">
                    @forelse($completed as $index => $appointment)
                        <div x-data="{ open: false }" x-show="{{ $index }} < shown" class="border-b border-purple-50 last:border-0">
                            <div class="flex items-center gap-4 px-6 py-4 cursor-pointer hover:bg-purple-50/50 transition-colors duration-150" @click="open = !open">
                                <div class="w-12 flex-shrink-0 rounded-xl py-2 text-center" style="background-color: #EDE4F8;">
                                    <p class="text-lg font-semibold text-gray-900 leading-none">{{ $appointment->scheduled_at->format('d') }}</p>
                                    <p class="text-xs text-purple-400 uppercase font-medium mt-0.5">{{ $appointment->scheduled_at->isoFormat('MMM') }}</p>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $appointment->service->name }}</p>
                                    <p class="text-xs text-purple-400 mt-0.5">{{ $appointment->display_client_name }}</p>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <span class="text-xs font-semibold px-3 py-1 rounded-full border" style="background-color: #E0F7F4; color: #0D9488; border-color: #99E6DE;">Concluído</span>
                                    <svg class="w-4 h-4 text-purple-300 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                            <div x-show="open" x-transition class="px-6 pb-5 pt-4 bg-purple-50/40 border-t border-purple-50">
                                <div class="flex flex-wrap gap-x-10 gap-y-3">
                                    <div>
                                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Serviço</p>
                                        <p class="text-sm font-medium text-gray-800">{{ $appointment->service->name }}</p>
                                        <p class="text-xs text-purple-300 mt-0.5">{{ $appointment->service->duration_formatted }} · R$ {{ number_format($appointment->service->price, 2, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Cliente</p>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <p class="text-sm font-medium text-gray-800">{{ $appointment->display_client_name }}</p>
                                            @if($appointment->is_external_client)
                                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 border border-amber-200">Externo</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-purple-300 mt-0.5">{{ $appointment->display_client_contact ?? 'Sem contato' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Data e hora</p>
                                        <p class="text-sm font-medium text-gray-800">{{ $appointment->scheduled_at->format('d/m/Y \à\s H:i') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Próximo retorno</p>
                                        <button type="button"
                                           onclick="window.dispatchEvent(new CustomEvent('open-create-appointment-modal'))"
                                           class="text-xs font-semibold text-purple-600 hover:text-purple-800 transition-colors">
                                            Agendar retorno (+30 dias)
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center text-sm text-purple-300">Nenhum atendimento concluído ainda.</div>
                    @endforelse
                    @if($completed->count() > 10)
                        <div class="px-6 py-3 border-t border-purple-50 text-center" x-show="shown < {{ $completed->count() }}">
                            <button @click="shown += 10" class="text-sm font-semibold text-purple-600 hover:text-purple-800 transition-colors">Ver mais</button>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ── ABA: CANCELADOS ── --}}
            <div x-data="{ shown: 10 }" x-show="tab === 'cancelados'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-4">
                <div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden">
                    @forelse($cancelled as $index => $appointment)
                        <div x-data="{ open: false }" x-show="{{ $index }} < shown" class="border-b border-purple-50 last:border-0">
                            <div class="flex items-center gap-4 px-6 py-4 cursor-pointer hover:bg-purple-50/50 transition-colors duration-150" @click="open = !open">
                                <div class="w-12 flex-shrink-0 rounded-xl py-2 text-center" style="background-color: #EDE4F8;">
                                    <p class="text-lg font-semibold text-gray-900 leading-none">{{ $appointment->scheduled_at->format('d') }}</p>
                                    <p class="text-xs text-purple-400 uppercase font-medium mt-0.5">{{ $appointment->scheduled_at->isoFormat('MMM') }}</p>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $appointment->service->name }}</p>
                                    <p class="text-xs text-purple-400 mt-0.5">{{ $appointment->display_client_name }}</p>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <span class="text-xs font-semibold px-3 py-1 bg-red-50 text-red-500 rounded-full border border-red-100">Cancelado</span>
                                    <svg class="w-4 h-4 text-purple-300 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                            <div x-show="open" x-transition class="px-6 pb-5 pt-4 bg-purple-50/40 border-t border-purple-50">
                                <div class="flex flex-wrap gap-x-10 gap-y-3">
                                    <div>
                                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Serviço</p>
                                        <p class="text-sm font-medium text-gray-800">{{ $appointment->service->name }}</p>
                                        <p class="text-xs text-purple-300 mt-0.5">{{ $appointment->service->duration_formatted }} · R$ {{ number_format($appointment->service->price, 2, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Cliente</p>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <p class="text-sm font-medium text-gray-800">{{ $appointment->display_client_name }}</p>
                                            @if($appointment->is_external_client)
                                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 border border-amber-200">Externo</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-purple-300 mt-0.5">{{ $appointment->display_client_contact ?? 'Sem contato' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Data e hora</p>
                                        <p class="text-sm font-medium text-gray-800">{{ $appointment->scheduled_at->format('d/m/Y \à\s H:i') }}</p>
                                    </div>
                                    @if($appointment->notes)
                                    <div>
                                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Observações</p>
                                        <p class="text-xs text-gray-500 italic">{{ $appointment->notes }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center text-sm text-purple-300">Nenhum agendamento cancelado.</div>
                    @endforelse
                    @if($cancelled->count() > 10)
                        <div class="px-6 py-3 border-t border-purple-50 text-center" x-show="shown < {{ $cancelled->count() }}">
                            <button @click="shown += 10" class="text-sm font-semibold text-purple-600 hover:text-purple-800 transition-colors">Ver mais</button>
                        </div>
                    @endif
                </div>
            </div>

        </div>{{-- fim x-data tabs --}}

    </div>

</x-app-layout>

<script>
    function professionalAppointmentForm() {
        return {
            open: @json($createAppointmentHasErrors || ($openCreateModal ?? false)),
            clientMode: '{{ old('client_mode', 'known') }}',
            serviceId: '{{ old('service_id') }}',
            selectedDate: '{{ $prefillDateValue }}',
            selectedTime: '{{ old('time') }}',
            slots: [],
            loading: false,
            knownSearch: '',
            knownResults: [],
            selectedKnownClientId: '{{ old('known_client_id') }}',
            selectedKnownClient: null,

            init() {
                if (this.selectedDate) {
                    this.fetchSlots();
                }

                const selectedClientId = @json(old('known_client_id'));
                if (selectedClientId) {
                    this.knownSearch = 'Cliente selecionado';
                    this.selectedKnownClient = {
                        id: selectedClientId,
                        name: 'Cliente selecionado',
                        email: '',
                    };
                }

                // Watch clientMode to avoid leaking data between known/external
                if (this.$watch) {
                    this.$watch('clientMode', (value) => {
                        if (value === 'known') {
                            // clear external inputs
                            this.clearExternalFields();
                        } else {
                            // clear known client selection
                            this.knownSearch = '';
                            this.knownResults = [];
                            this.selectedKnownClientId = '';
                            this.selectedKnownClient = null;
                        }
                    });
                }
            },

            closeAndReset() {
                this.open = false;
                // wait for leave transition
                setTimeout(() => this.resetForm(), 120);
            },

            resetForm() {
                // reset JS state
                this.clientMode = 'known';
                this.knownSearch = '';
                this.knownResults = [];
                this.selectedKnownClientId = '';
                this.selectedKnownClient = null;
                this.serviceId = '';
                this.selectedDate = '';
                this.selectedTime = '';
                this.slots = [];
                this.loading = false;

                // reset form DOM values inside modal if present
                try {
                    const root = this.$refs?.createModal;
                    if (root) {
                        const form = root.querySelector('form');
                        if (form) form.reset();
                    }
                } catch (e) {
                    // ignore
                }
            },

            clearExternalFields() {
                this.$nextTick?.(() => {
                    try {
                        const root = this.$refs?.createModal;
                        if (!root) return;
                        const name = root.querySelector('[name="external_name"]');
                        const email = root.querySelector('[name="external_email"]');
                        const phone = root.querySelector('[name="external_phone"]');
                        if (name) name.value = '';
                        if (email) email.value = '';
                        if (phone) phone.value = '';
                    } catch (e) {
                        // ignore
                    }
                });
            },

            async fetchSlots() {
                if (!this.selectedDate || !this.serviceId) {
                    this.slots = [];
                    this.selectedTime = '';
                    return;
                }

                this.loading = true;
                this.slots = [];

                try {
                    const res = await fetch(`{{ route('appointments.slots', $professional->id) }}?date=${this.selectedDate}&service_id=${this.serviceId}`);
                    this.slots = await res.json();

                    if (this.selectedTime && !this.slots.includes(this.selectedTime)) {
                        this.selectedTime = '';
                    }
                } catch (e) {
                    this.slots = [];
                } finally {
                    this.loading = false;
                }
            },

            async searchKnownClients() {
                const term = this.knownSearch.trim();

                if (term.length < 2) {
                    this.knownResults = [];
                    return;
                }

                try {
                    const res = await fetch(`{{ route('professional.clients.search') }}?q=${encodeURIComponent(term)}`);
                    this.knownResults = await res.json();
                } catch (e) {
                    this.knownResults = [];
                }
            },

            pickKnownClient(client) {
                this.selectedKnownClient = client;
                this.selectedKnownClientId = client.id;
                this.knownSearch = `${client.name} (${client.email})`;
                this.knownResults = [];
            },

            applyPhoneMask(value) {
                const digits = String(value || '').replace(/\D/g, '').slice(0, 11);

                if (!digits) {
                    return '';
                }

                if (digits.length <= 2) {
                    return `(${digits}`;
                }

                if (digits.length <= 6) {
                    return `(${digits.slice(0, 2)}) ${digits.slice(2)}`;
                }

                if (digits.length <= 10) {
                    return `(${digits.slice(0, 2)}) ${digits.slice(2, 6)}-${digits.slice(6)}`;
                }

                return `(${digits.slice(0, 2)}) ${digits.slice(2, 7)}-${digits.slice(7)}`;
            },

            formatPhoneInput(event) {
                event.target.value = this.applyPhoneMask(event.target.value);
            },
        }
    }
</script>