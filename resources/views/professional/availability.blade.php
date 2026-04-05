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

            <form method="POST" action="{{ route('professional.availability.save') }}" class="mb-6">
                @csrf

                <div class="rounded-2xl border border-purple-200 bg-purple-50 px-5 py-4">
                    <label class="block text-[10px] text-purple-500 uppercase font-bold tracking-widest mb-1">
                        Tempo de preparação entre agendamentos
                    </label>
                    <p class="mb-2 text-[11px] text-purple-400">
                        Tempo reservado entre um atendimento e outro para organização, limpeza ou deslocamento.
                    </p>
                    <div class="flex flex-col gap-4">
                        <select
                            name="preparation_time_minutes"
                            class="w-full sm:w-80 rounded-xl border border-purple-200 bg-white text-purple-900 text-sm px-3 py-1.5 shadow-sm focus:ring-2 focus:ring-purple-600 focus:border-purple-600 outline-none transition"
                        >
                            @foreach([5, 10, 15, 20, 30, 45, 60, 90, 120] as $minutes)
                                <option
                                    value="{{ $minutes }}"
                                    {{ old('preparation_time_minutes', $professional->preparation_time_minutes ?? 15) == $minutes ? 'selected' : '' }}
                                >
                                    {{ $minutes }} min
                                </option>
                            @endforeach
                        </select>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 self-start px-4 py-2 text-white text-xs font-semibold rounded-xl shadow-sm hover:shadow-md transition-all duration-200 active:scale-95 hover:-translate-y-0.5"
                            style="background-color: #6A0DAD;"
                        >
                            Salvar tempo de preparação
                        </button>
                    </div>
                </div>
            </form>

            {{-- Main card --}}
            <form method="POST" action="{{ route('professional.availability.save') }}">
                @csrf

                <div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden p-6">

                    <div class="space-y-3">
                        @foreach($weekdays as $number => $name)
                            @php
                                $avail     = $availabilities->get($number);
                                $openTime  = $avail ? \Carbon\Carbon::parse($avail->open_time)->format('H:i')  : '09:00';
                                $closeTime = $avail ? \Carbon\Carbon::parse($avail->close_time)->format('H:i') : '18:00';
                                $breakItems = $avail
                                    ? $avail->breaks
                                        ->map(fn($break) => [
                                            'start' => \Carbon\Carbon::parse($break->start_time)->format('H:i'),
                                            'end' => \Carbon\Carbon::parse($break->end_time)->format('H:i'),
                                        ])
                                        ->values()
                                        ->all()
                                    : [];
                            @endphp

                            <div
                                class="rounded-2xl border transition-all duration-200"
                                :class="enabled
                                    ? 'border-purple-300 bg-purple-50 shadow-sm'
                                    : 'border-gray-200 bg-white'"
                                x-data="{
                                    enabled: {{ $avail ? 'true' : 'false' }},
                                    breakModalOpen: false,
                                    breakStart: '',
                                    breakEnd: '',
                                    breakError: '',
                                    breaks: @js(old("breaks.$number")
                                        ? collect(preg_split('/\r\n|\r|\n/', old("breaks.$number")))
                                            ->filter()
                                            ->map(function ($line) {
                                                [$start, $end] = array_pad(explode('-', $line, 2), 2, '');
                                                return ['start' => trim($start), 'end' => trim($end)];
                                            })
                                            ->values()
                                            ->all()
                                        : $breakItems),
                                    serializedBreaks() {
                                        return this.breaks.map((item) => `${item.start}-${item.end}`).join('\n');
                                    },
                                    removeBreak(index) {
                                        this.breaks.splice(index, 1);
                                    },
                                    addBreak() {
                                        this.breakError = '';
                                        if (!this.breakStart || !this.breakEnd) {
                                            this.breakError = 'Informe inicio e fim da pausa.';
                                            return;
                                        }

                                        if (this.breakEnd <= this.breakStart) {
                                            this.breakError = 'O fim deve ser maior que o inicio.';
                                            return;
                                        }

                                        const hasOverlap = this.breaks.some((item) => this.breakStart < item.end && this.breakEnd > item.start);
                                        if (hasOverlap) {
                                            this.breakError = 'Essa pausa se sobrepoe a uma pausa existente.';
                                            return;
                                        }

                                        this.breaks.push({ start: this.breakStart, end: this.breakEnd });
                                        this.breaks.sort((a, b) => a.start.localeCompare(b.start));
                                        this.breakStart = '';
                                        this.breakEnd = '';
                                        this.breakModalOpen = false;
                                    }
                                }"
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
                                      class="grid grid-cols-1 sm:grid-cols-2 gap-3 px-5 pb-5">

                                    <div>
                                        <label class="block text-[10px] text-purple-500 uppercase font-bold tracking-widest mb-1.5">
                                            Abertura
                                        </label>
                                        <input
                                            type="time"
                                            step="300"
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
                                            step="300"
                                            name="close_time[{{ $number }}]"
                                            value="{{ $closeTime }}"
                                            class="w-full rounded-xl border border-purple-200 bg-white text-purple-900 text-sm px-3 py-2 shadow-sm focus:ring-2 focus:ring-purple-600 focus:border-purple-600 outline-none transition"
                                        >
                                    </div>
                                </div>

                                <div x-show="enabled" x-cloak class="px-5 pb-5">
                                    <div class="mb-2 flex items-center justify-between gap-2">
                                        <label class="block text-[10px] text-purple-500 uppercase font-bold tracking-widest">
                                            Pausas do Dia
                                        </label>

                                        <button type="button"
                                                @click="breakError = ''; breakModalOpen = true"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-[11px] font-semibold text-purple-700 rounded-lg border border-purple-300 bg-white hover:bg-purple-100 transition">
                                            + Adicionar pausa
                                        </button>
                                    </div>

                                    <input
                                        type="hidden"
                                        name="breaks[{{ $number }}]"
                                        :value="serializedBreaks()"
                                    >

                                    <div class="space-y-2">
                                        <template x-if="breaks.length === 0">
                                            <p class="text-xs text-purple-400">Nenhuma pausa adicionada para este dia.</p>
                                        </template>

                                        <template x-for="(item, idx) in breaks" :key="`${item.start}-${item.end}-${idx}`">
                                            <div class="flex items-center justify-between gap-3 rounded-xl border border-purple-200 bg-white px-3 py-2">
                                                <p class="text-sm font-medium text-purple-800" x-text="`${item.start} - ${item.end}`"></p>
                                                <button type="button"
                                                        @click="removeBreak(idx)"
                                                        class="text-xs font-semibold text-red-500 hover:text-red-700 transition-colors">
                                                    Remover
                                                </button>
                                            </div>
                                        </template>
                                    </div>

                                    <div x-show="breakModalOpen"
                                         x-cloak
                                         @click.self="breakModalOpen = false"
                                         class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                         style="display:none; background-color: rgba(17, 24, 39, 0.35);">
                                        <div class="w-full max-w-sm rounded-2xl bg-white border border-purple-200 shadow-xl">
                                            <div class="flex items-center justify-between px-5 py-4 border-b border-purple-100">
                                                <h3 class="text-sm font-bold text-purple-900">Adicionar pausa</h3>
                                                <button type="button"
                                                        @click="breakModalOpen = false"
                                                        class="text-purple-400 hover:text-purple-700 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </div>

                                            <div class="px-5 py-4 space-y-3">
                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="block text-[10px] text-purple-500 uppercase font-bold tracking-widest mb-1">Inicio</label>
                                                        <input type="time" x-model="breakStart"
                                                               class="w-full rounded-xl border border-purple-200 px-3 py-2 text-sm text-purple-900 focus:ring-2 focus:ring-purple-600 focus:border-purple-600 outline-none transition">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] text-purple-500 uppercase font-bold tracking-widest mb-1">Fim</label>
                                                        <input type="time" x-model="breakEnd"
                                                               class="w-full rounded-xl border border-purple-200 px-3 py-2 text-sm text-purple-900 focus:ring-2 focus:ring-purple-600 focus:border-purple-600 outline-none transition">
                                                    </div>
                                                </div>

                                                <p x-show="breakError" x-text="breakError" class="text-xs text-red-500"></p>

                                                <div class="flex items-center justify-end gap-2 pt-1">
                                                    <button type="button"
                                                            @click="breakModalOpen = false"
                                                            class="px-3 py-2 text-xs font-semibold text-purple-600 border border-purple-200 rounded-xl hover:bg-purple-50 transition">
                                                        Cancelar
                                                    </button>
                                                    <button type="button"
                                                            @click="addBreak()"
                                                            class="px-3 py-2 text-xs font-semibold text-white rounded-xl bg-purple-700 hover:bg-purple-800 transition">
                                                        Adicionar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
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
                </div>
            </form>

        </div>
    </div>
</x-app-layout>