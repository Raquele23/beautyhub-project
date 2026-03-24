<x-app-layout>

    {{-- ── Page ── --}}
    <div class="min-h-screen relative">

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

        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-10 space-y-6">

            {{-- ── Topo ── --}}
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold tracking-widest uppercase text-purple-400">Beauty Hub</p>
                    <h1 class="text-2xl font-bold text-purple-800 mt-0.5">Minha Disponibilidade</h1>
                    <p class="text-xs text-purple-400 mt-1">Selecione os dias em que você atende e defina os horários de cada período.</p>
                </div>
            </div>

            {{-- Main card --}}
            <div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden p-6">

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
                            class="inline-flex items-center gap-2 px-7 py-3 text-white text-sm font-bold rounded-2xl shadow-md hover:shadow-lg transition-all duration-200 active:scale-95 hover:-translate-y-0.5"
                            style="background-color: #6A0DAD;"
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