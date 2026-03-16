<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Agendamentos') }}
            </h2>
            <button onclick="window.dispatchEvent(new CustomEvent('open-settings-modal'))"
                    class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                    title="Configurações">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </button>
        </div>
    </x-slot>

    {{-- Modal de Configurações --}}
    <div x-data="{ open: false }"
         x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click.self="open = false"
         class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4"
         style="display: none;">

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-md">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                <h3 class="font-bold text-gray-900 dark:text-white">Configurações de Agendamento</h3>
                <button @click="open = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="p-6">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                    Defina como seus atendimentos são marcados como concluídos.
                </p>

                <form action="{{ route('professional.settings.update') }}" method="POST" class="space-y-3">
                    @csrf
                    @method('PATCH')

                    <label class="flex items-start gap-4 p-4 border rounded-lg cursor-pointer transition
                        {{ !auth()->user()->professional->auto_complete
                            ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20'
                            : 'border-gray-200 dark:border-gray-700 hover:border-gray-300' }}">
                        <input type="radio" name="auto_complete" value="0"
                            {{ !auth()->user()->professional->auto_complete ? 'checked' : '' }}
                            class="mt-0.5 text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Manual</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                Você marca o atendimento como concluído manualmente após realizá-lo.
                            </p>
                        </div>
                    </label>

                    <label class="flex items-start gap-4 p-4 border rounded-lg cursor-pointer transition
                        {{ auth()->user()->professional->auto_complete
                            ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20'
                            : 'border-gray-200 dark:border-gray-700 hover:border-gray-300' }}">
                        <input type="radio" name="auto_complete" value="1"
                            {{ auth()->user()->professional->auto_complete ? 'checked' : '' }}
                            class="mt-0.5 text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Automático</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                O sistema marca automaticamente como concluído após o horário do agendamento somado à duração do serviço.
                            </p>
                        </div>
                    </label>

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Salvar Configurações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Botão de configurações --}}
            <div x-data="{ open: false }" @open-settings-modal.window="open = true">

                {{-- Modal --}}
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click.self="open = false"
                     class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4"
                     style="display: none;">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-md">
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="font-bold text-gray-900 dark:text-white">Configurações de Agendamento</h3>
                            <button @click="open = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        <div class="p-6">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                Defina como seus atendimentos são marcados como concluídos.
                            </p>
                            <form action="{{ route('professional.settings.update') }}" method="POST" class="space-y-3">
                                @csrf
                                @method('PATCH')
                                <label class="flex items-start gap-4 p-4 border rounded-lg cursor-pointer transition
                                    {{ !auth()->user()->professional->auto_complete
                                        ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20'
                                        : 'border-gray-200 dark:border-gray-700 hover:border-gray-300' }}">
                                    <input type="radio" name="auto_complete" value="0"
                                        {{ !auth()->user()->professional->auto_complete ? 'checked' : '' }}
                                        class="mt-0.5 text-indigo-600 focus:ring-indigo-500">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Manual</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Você marca o atendimento como concluído manualmente após realizá-lo.</p>
                                    </div>
                                </label>
                                <label class="flex items-start gap-4 p-4 border rounded-lg cursor-pointer transition
                                    {{ auth()->user()->professional->auto_complete
                                        ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20'
                                        : 'border-gray-200 dark:border-gray-700 hover:border-gray-300' }}">
                                    <input type="radio" name="auto_complete" value="1"
                                        {{ auth()->user()->professional->auto_complete ? 'checked' : '' }}
                                        class="mt-0.5 text-indigo-600 focus:ring-indigo-500">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Automático</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">O sistema marca automaticamente como concluído após o horário do agendamento somado à duração do serviço.</p>
                                    </div>
                                </label>
                                <div class="pt-2">
                                    <button type="submit"
                                        class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none transition ease-in-out duration-150">
                                        Salvar Configurações
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

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
                                    <button type="submit" class="px-4 py-2 bg-green-600 text-white text-xs font-semibold rounded-lg hover:bg-green-700 transition">Confirmar</button>
                                </form>
                                <form method="POST" action="{{ route('appointments.cancel', $appointment->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-4 py-2 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 text-xs font-semibold rounded-lg hover:bg-red-200 transition">Recusar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-sm text-gray-400 dark:text-gray-500">Nenhum agendamento pendente.</div>
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
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $appointment->client->name }} · {{ $appointment->scheduled_at->format('H:i') }}</p>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <span class="text-xs font-semibold px-3 py-1 bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 rounded-full">Confirmado</span>
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
                                <button type="submit" class="text-xs text-red-500 hover:text-red-700 transition">Cancelar</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-sm text-gray-400 dark:text-gray-500">Nenhum agendamento confirmado futuro.</div>
                @endforelse
            </div>

            {{-- AGUARDANDO CONCLUSÃO --}}
            @if($awaitingComplete->count())
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Aguardando conclusão</h3>
                    <span class="text-xs font-semibold px-2 py-1 bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300 rounded-full">{{ $awaitingComplete->count() }}</span>
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
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $appointment->client->name }} · {{ $appointment->scheduled_at->format('H:i') }}</p>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <form method="POST" action="{{ route('appointments.complete', $appointment->id) }}" @click.stop>
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition">Concluir</button>
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
            <div x-data="{ shown: 10 }" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Concluídos</h3>
                </div>
                @forelse($completed as $index => $appointment)
                    <div x-data="{ open: false }" x-show="{{ $index }} < shown" class="border-b last:border-0 dark:border-gray-700">
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
                    <div class="px-6 py-8 text-center text-sm text-gray-400 dark:text-gray-500">Nenhum atendimento concluído ainda.</div>
                @endforelse
                @if($completed->count() > 10)
                    <div class="px-6 py-3 border-t border-gray-100 dark:border-gray-700 text-center" x-show="shown < {{ $completed->count() }}">
                        <button @click="shown += 10" class="text-sm font-semibold text-purple-600 hover:text-purple-700 dark:text-purple-400 transition">Ver mais</button>
                    </div>
                @endif
            </div>

            {{-- CANCELADOS --}}
            <div x-data="{ shown: 10 }" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Cancelados</h3>
                </div>
                @forelse($cancelled as $index => $appointment)
                    <div x-data="{ open: false }" x-show="{{ $index }} < shown" class="border-b last:border-0 dark:border-gray-700">
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
                    <div class="px-6 py-8 text-center text-sm text-gray-400 dark:text-gray-500">Nenhum agendamento cancelado.</div>
                @endforelse
                @if($cancelled->count() > 10)
                    <div class="px-6 py-3 border-t border-gray-100 dark:border-gray-700 text-center" x-show="shown < {{ $cancelled->count() }}">
                        <button @click="shown += 10" class="text-sm font-semibold text-purple-600 hover:text-purple-700 dark:text-purple-400 transition">Ver mais</button>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>