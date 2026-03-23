<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-purple-800 leading-tight tracking-tight">
            {{ __('Minha Disponibilidade') }}
        </h2>
    </x-slot>

    {{-- ── Page ── --}}
    <div class="min-h-screen relative">

        {{-- ── Toast (flutua dentro da página, não cobre navbar nem conteúdo) ── --}}
        @if(session('status'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 4000)"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="absolute top-4 left-1/2 -translate-x-1/2 z-20
                       flex items-center gap-2.5
                       bg-purple-300/40 backdrop-blur-md
                       text-purple-900 text-xs font-semibold
                       rounded-2xl px-4 py-2.5
                       border border-purple-300/50
                       w-max max-w-xs">

                <span class="relative flex h-2 w-2 shrink-0">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-purple-500 opacity-50"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-purple-600"></span>
                </span>

                <p class="leading-snug">{{ session('status') }}</p>

                <button @click="show = false"
                        class="ml-1 text-purple-400 hover:text-purple-700 transition-colors shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        <div class="pt-16 pb-10 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header card --}}
            <div class="bg-purple-800 text-white rounded-3xl shadow-lg p-6 mb-6 flex items-center gap-4">
                <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-lg leading-tight">Configure sua agenda</h3>
                    <p class="text-purple-200 text-sm mt-0.5">
                        Selecione os dias em que você atende e defina os horários de cada período.
                    </p>
                </div>
            </div>

            {{-- Main card --}}
            <div class="bg-white rounded-3xl shadow-sm border border-purple-100 p-6">

                <form method="POST" action="{{ route('professional.availability.save') }}">
                    @csrf

                    <div class="space-y-3">
                        @foreach($weekdays as $number => $name)
                            @php
                                $avail     = $availabilities->get($number);
                                $openTime  = $avail ? \Carbon\Carbon::parse($avail->open_time)->format('H:i')  : '09:00';
                                $closeTime = $avail ? \Carbon\Carbon::parse($avail->close_time)->format('H:i') : '18:00';
                            @endphp

                            <div
                                class="rounded-2xl border transition-all duration-200"
                                :class="enabled
                                    ? 'border-purple-300 bg-purple-50 shadow-sm'
                                    : 'border-gray-200 bg-white'"
                                x-data="{ enabled: {{ $avail ? 'true' : 'false' }} }"
                            >
                                {{-- Day row --}}
                                <div class="flex items-center justify-between px-5 py-4">
                                    <label class="flex items-center gap-3 cursor-pointer select-none">
                                        <div class="relative">
                                            <input
                                                type="checkbox"
                                                name="days[]"
                                                value="{{ $number }}"
                                                x-model="enabled"
                                                {{ $avail ? 'checked' : '' }}
                                                class="sr-only peer"
                                            >
                                            <div class="w-11 h-6 rounded-full transition-colors duration-200 bg-gray-200 peer-checked:bg-purple-700"></div>
                                            <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200 peer-checked:translate-x-5"></div>
                                        </div>

                                        <span class="text-sm font-bold tracking-wide"
                                              :class="enabled ? 'text-purple-800' : 'text-gray-400'">
                                            {{ $name }}
                                        </span>
                                    </label>

                                    <span x-show="!enabled" class="text-xs text-gray-400 font-medium">Inativo</span>
                                    <span x-show="enabled" class="text-xs text-purple-600 font-semibold bg-purple-100 px-2 py-0.5 rounded-full">Ativo</span>
                                </div>

                                {{-- Time fields --}}
                                <div x-show="enabled" x-cloak
                                     class="grid grid-cols-1 sm:grid-cols-3 gap-3 px-5 pb-5">

                                    <div>
                                        <label class="block text-[10px] text-purple-500 uppercase font-bold tracking-widest mb-1.5">
                                            Abertura
                                        </label>
                                        <input
                                            type="time"
                                            name="open_time[{{ $number }}]"
                                            value="{{ $openTime }}"
                                            class="w-full rounded-xl border border-purple-200 bg-white text-purple-900 text-sm px-3 py-2 shadow-sm focus:ring-2 focus:ring-purple-600 focus:border-purple-600 outline-none transition"
                                        >
                                    </div>

                                    <div>
                                        <label class="block text-[10px] text-purple-500 uppercase font-bold tracking-widest mb-1.5">
                                            Fechamento
                                        </label>
                                        <input
                                            type="time"
                                            name="close_time[{{ $number }}]"
                                            value="{{ $closeTime }}"
                                            class="w-full rounded-xl border border-purple-200 bg-white text-purple-900 text-sm px-3 py-2 shadow-sm focus:ring-2 focus:ring-purple-600 focus:border-purple-600 outline-none transition"
                                        >
                                    </div>

                                    <div>
                                        <label class="block text-[10px] text-purple-500 uppercase font-bold tracking-widest mb-1.5">
                                            Intervalo
                                        </label>
                                        <select
                                            name="slot_interval[{{ $number }}]"
                                            class="w-full rounded-xl border border-purple-200 bg-white text-purple-900 text-sm px-3 py-2 shadow-sm focus:ring-2 focus:ring-purple-600 focus:border-purple-600 outline-none transition"
                                        >
                                            @foreach([15, 30, 45, 60, 90, 120] as $interval)
                                                <option
                                                    value="{{ $interval }}"
                                                    {{ ($avail?->slot_interval ?? 60) == $interval ? 'selected' : '' }}
                                                >
                                                    {{ $interval }} min
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Save button --}}
                    <div class="mt-8 flex justify-end">
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 px-7 py-3 bg-purple-800 hover:bg-purple-900 text-white text-sm font-bold rounded-2xl shadow-md hover:shadow-lg transition-all duration-200 active:scale-95"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                            Salvar disponibilidade
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>