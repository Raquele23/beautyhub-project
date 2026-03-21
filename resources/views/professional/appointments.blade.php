<x-app-layout>

    <div class="max-w-4xl mx-auto px-4 sm:px-8 py-10 space-y-6">

        {{-- ── Topo ── --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold tracking-widest uppercase text-purple-400">Beauty Hub</p>
                <h1 class="text-2xl font-bold text-purple-800 mt-0.5">Agendamentos</h1>
            </div>
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
                                <input type="radio" name="auto_complete" value="0"
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
                                <input type="radio" name="auto_complete" value="1"
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
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-4"
                class="fixed bottom-6 right-6 z-50 flex items-center gap-3 bg-white border border-purple-100 shadow-xl shadow-purple-100 rounded-2xl px-5 py-4 max-w-sm">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color: #E0F7F4;">
                    <svg class="w-4 h-4" style="color: #0D9488;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-800">{{ session('status') }}</p>
                <button @click="show = false" class="ml-2 text-purple-300 hover:text-purple-500 transition-colors flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- ── ABAS ── --}}
        <div x-data="{ tab: 'pendentes' }">

            {{-- Barra de abas --}}
            <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide">
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
                    @click="tab = 'confirmados'"
                    :class="tab === 'confirmados'
                        ? 'bg-purple-700 text-white shadow-lg shadow-purple-200'
                        : 'bg-white text-purple-500 border border-purple-100 hover:border-purple-300'"
                    class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-semibold transition-all duration-200">
                    Confirmados
                    @if($agenda->count())
                        <span
                            :class="tab === 'confirmados' ? 'bg-white/20 text-white' : 'bg-purple-100 text-purple-600'"
                            class="w-5 h-5 flex items-center justify-center rounded-full text-xs font-bold flex-shrink-0">
                            {{ $agenda->count() }}
                        </span>
                    @endif
                </button>

                <button
                    @click="tab = 'concluir'"
                    :class="tab === 'concluir'
                        ? 'bg-purple-700 text-white shadow-lg shadow-purple-200'
                        : 'bg-white text-purple-500 border border-purple-100 hover:border-purple-300'"
                    class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-semibold transition-all duration-200">
                    A concluir
                    @if($awaitingComplete->count())
                        <span
                            :class="tab === 'concluir' ? 'bg-white/20 text-white' : 'bg-purple-100 text-purple-600'"
                            class="w-5 h-5 flex items-center justify-center rounded-full text-xs font-bold flex-shrink-0">
                            {{ $awaitingComplete->count() }}
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
                <div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden">
                    @forelse($pending as $appointment)
                        <div x-data="{ open: false }" class="border-b border-purple-50 last:border-0">

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
                                    <p class="text-xs text-purple-400 mt-0.5">{{ $appointment->client->name }} · {{ $appointment->scheduled_at->format('H:i') }}</p>
                                </div>

                                {{-- Botões + seta --}}
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <form method="POST" action="{{ route('appointments.confirm', $appointment->id) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="flex items-center gap-1.5 px-4 py-2 text-white text-xs font-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5" style="background-color: #6A0DAD;">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            Confirmar
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('appointments.cancel', $appointment->id) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="flex items-center gap-1.5 px-4 py-2 text-xs font-semibold rounded-xl border transition-all duration-200 hover:-translate-y-0.5" style="background-color: #EDE4F8; color: #6A0DAD; border-color: #C4A8E8;">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Recusar
                                        </button>
                                    </form>
                                    <svg class="w-4 h-4 text-purple-300 flex-shrink-0 transition-transform duration-200 cursor-pointer" :class="open ? 'rotate-180' : ''" @click="open = !open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
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
                                        <p class="text-sm font-medium text-gray-800">{{ $appointment->client->name }}</p>
                                        <p class="text-xs text-purple-300 mt-0.5">{{ $appointment->client->email }}</p>
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

            {{-- ── ABA: CONFIRMADOS ── --}}
            <div x-show="tab === 'confirmados'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-4">
                <div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden">
                    @forelse($agenda as $appointment)
                        <div x-data="{ open: false }" class="border-b border-purple-50 last:border-0">
                            <div class="flex items-center gap-4 px-6 py-4 cursor-pointer hover:bg-purple-50/50 transition-colors duration-150" @click="open = !open">
                                <div class="w-12 flex-shrink-0 rounded-xl py-2 text-center" style="background-color: #EDE4F8;">
                                    <p class="text-lg font-semibold text-gray-900 leading-none">{{ $appointment->scheduled_at->format('d') }}</p>
                                    <p class="text-xs text-purple-400 uppercase font-medium mt-0.5">{{ $appointment->scheduled_at->isoFormat('MMM') }}</p>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $appointment->service->name }}</p>
                                    <p class="text-xs text-purple-400 mt-0.5">{{ $appointment->client->name }} · {{ $appointment->scheduled_at->format('H:i') }}</p>
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
                                        <p class="text-sm font-medium text-gray-800">{{ $appointment->client->name }}</p>
                                        <p class="text-xs text-purple-300 mt-0.5">{{ $appointment->client->email }}</p>
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
                                <form method="POST" action="{{ route('appointments.cancel', $appointment->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-xs font-semibold text-red-400 hover:text-red-600 transition-colors">Cancelar agendamento</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center text-sm text-purple-300">Nenhum agendamento confirmado futuro.</div>
                    @endforelse
                </div>
            </div>

            {{-- ── ABA: A CONCLUIR ── --}}
            <div x-show="tab === 'concluir'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-4">
                <div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden">
                    @forelse($awaitingComplete as $appointment)
                        <div x-data="{ open: false }" class="border-b border-purple-50 last:border-0">
                            <div class="flex items-center gap-4 px-6 py-4 cursor-pointer hover:bg-purple-50/50 transition-colors duration-150" @click="open = !open">
                                <div class="w-12 flex-shrink-0 rounded-xl py-2 text-center" style="background-color: #EDE4F8;">
                                    <p class="text-lg font-semibold text-gray-900 leading-none">{{ $appointment->scheduled_at->format('d') }}</p>
                                    <p class="text-xs text-purple-400 uppercase font-medium mt-0.5">{{ $appointment->scheduled_at->isoFormat('MMM') }}</p>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $appointment->service->name }}</p>
                                    <p class="text-xs text-purple-400 mt-0.5">{{ $appointment->client->name }} · {{ $appointment->scheduled_at->format('H:i') }}</p>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <form method="POST" action="{{ route('appointments.complete', $appointment->id) }}" @click.stop>
                                        @csrf @method('PATCH')
                                        <button type="submit" class="px-4 py-2 text-white text-xs font-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5 shadow-sm" style="background-color: #6A0DAD;">Concluir</button>
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
                                        <p class="text-sm font-medium text-gray-800">{{ $appointment->client->name }}</p>
                                        <p class="text-xs text-purple-300 mt-0.5">{{ $appointment->client->email }}</p>
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
                        <div class="px-6 py-12 text-center text-sm text-purple-300">Nenhum agendamento a concluir.</div>
                    @endforelse
                </div>
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
                                    <p class="text-xs text-purple-400 mt-0.5">{{ $appointment->client->name }}</p>
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
                                        <p class="text-sm font-medium text-gray-800">{{ $appointment->client->name }}</p>
                                        <p class="text-xs text-purple-300 mt-0.5">{{ $appointment->client->email }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-purple-400 uppercase tracking-wide mb-1">Data e hora</p>
                                        <p class="text-sm font-medium text-gray-800">{{ $appointment->scheduled_at->format('d/m/Y \à\s H:i') }}</p>
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
                                    <p class="text-xs text-purple-400 mt-0.5">{{ $appointment->client->name }}</p>
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
                                        <p class="text-sm font-medium text-gray-800">{{ $appointment->client->name }}</p>
                                        <p class="text-xs text-purple-300 mt-0.5">{{ $appointment->client->email }}</p>
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