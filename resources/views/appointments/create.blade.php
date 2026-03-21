<x-app-layout>

    <div class="max-w-2xl mx-auto px-4 sm:px-8 py-10 space-y-8">

        {{-- ── Topo ── --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold tracking-widest uppercase text-purple-400">Beauty Hub</p>
                <h1 class="text-2xl font-bold text-purple-800 mt-0.5">Agendar</h1>
            </div>
            <a href="{{ route('professional.public', $professional->id) }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-purple-700 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
               style="background-color: #E3D0F9;">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
                Voltar
            </a>
        </div>

        {{-- ── Erro ── --}}
        @if($errors->any())
            <div class="flex items-center gap-3 px-5 py-4 bg-white border border-red-100 rounded-2xl shadow-sm">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 bg-red-50">
                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-red-500">{{ $errors->first() }}</p>
            </div>
        @endif

        <form method="POST"
              action="{{ route('appointments.store', [$professional->id, $service->id]) }}"
              x-data="appointmentForm()" x-init="init()"
              class="space-y-5">
            @csrf

            {{-- ── Card: Serviço ── --}}
            <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-6">
                <p class="text-xs font-bold text-purple-400 uppercase tracking-wide mb-4">Serviço selecionado</p>
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-base font-semibold text-gray-900">{{ $service->name }}</p>
                        <p class="text-xs text-purple-400 mt-1">
                            com {{ $professional->establishment_name ?? $professional->user->name }}
                        </p>
                        <div class="flex items-center gap-2 mt-3">
                            <span class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-full border" style="background-color: #F5EFFE; color: #6A0DAD; border-color: #E3D0F9;">
                                ⏱ {{ $service->duration_formatted }}
                            </span>
                        </div>
                    </div>
                    <div class="flex-shrink-0 text-right">
                        <p class="text-xs text-purple-400 mb-0.5">Valor</p>
                        <p class="text-xl font-semibold text-purple-700">R$ {{ number_format($service->price, 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- ── Card: Selecionar Data ── --}}
            <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-6">
                <p class="text-xs font-bold text-purple-400 uppercase tracking-wide mb-4">Escolha a data</p>
                <input type="date"
                       name="date"
                       x-model="selectedDate"
                       @change="fetchSlots()"
                       min="{{ now()->toDateString() }}"
                       value="{{ old('date') }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm">
                <x-input-error :messages="$errors->get('date')" class="mt-2 text-xs text-red-400" />
            </div>

            {{-- ── Card: Horários (accordion) ── --}}
            <div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden"
                 x-data="{ open: false }"
                 x-effect="if (slots.length > 0) open = true">

                {{-- Header clicável --}}
                <button type="button"
                        class="w-full flex items-center justify-between px-6 py-4 hover:bg-purple-50/50 transition-colors duration-150"
                        @click="open = !open">
                    <div class="flex items-center gap-3">
                        <p class="text-xs font-bold text-purple-400 uppercase tracking-wide">Horário disponível</p>

                        {{-- Horário selecionado --}}
                        <span x-show="selectedTime" x-text="selectedTime"
                              class="text-xs font-semibold px-3 py-1 rounded-full"
                              style="background-color: #EDE4F8; color: #6A0DAD;">
                        </span>
                    </div>
                    <svg class="w-4 h-4 text-purple-300 flex-shrink-0 transition-transform duration-200"
                         :class="open ? 'rotate-180' : ''"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Conteúdo --}}
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="px-6 pb-6 pt-2 border-t border-purple-50">

                    {{-- Sem data selecionada --}}
                    <div x-show="!selectedDate" class="text-xs text-purple-300 italic pt-2">
                        Selecione uma data primeiro.
                    </div>

                    {{-- Loading --}}
                    <div x-show="selectedDate && loading" class="flex items-center gap-2 text-xs text-purple-400 pt-2">
                        <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                        </svg>
                        Carregando horários...
                    </div>

                    {{-- Sem horários --}}
                    <div x-show="selectedDate && !loading && slots.length === 0" class="text-xs text-purple-300 italic pt-2">
                        Nenhum horário disponível nesta data.
                    </div>

                    <div x-show="!loading && slots.length > 0" class="flex flex-wrap gap-2 pt-2">
                        <template x-for="slot in slots" :key="slot">
                            <label class="cursor-pointer">
                                <input type="radio" name="time" :value="slot"
                                       x-on:change="selectedTime = slot; open = false"
                                       class="peer sr-only">
                                <span class="slot-pill inline-flex items-center px-4 py-2 rounded-xl border border-purple-100 text-xs font-semibold text-purple-500 bg-white transition-all duration-150 cursor-pointer hover:border-purple-300"
                                      x-text="slot">
                                </span>
                            </label>
                        </template>
                    </div>
                    <x-input-error :messages="$errors->get('time')" class="mt-2 text-xs text-red-400" />
                </div>
            </div>

            {{-- ── Card: Observações ── --}}
            <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-6">
                <p class="text-xs font-bold text-purple-400 uppercase tracking-wide mb-1">
                    Observações
                    <span class="normal-case font-normal text-purple-300 ml-1">(opcional)</span>
                </p>
                <p class="text-xs text-purple-300 mb-4">Informe preferências ou detalhes para o profissional.</p>
                <textarea name="notes"
                          rows="3"
                          placeholder="Ex: prefiro tintura sem amônia..."
                          class="w-full px-4 py-3 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 placeholder-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm resize-none">{{ old('notes') }}</textarea>
            </div>

            {{-- ── Botão confirmar ── --}}
            <button type="submit"
                    class="w-full py-3 text-white text-sm font-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5 shadow-lg shadow-purple-200"
                    style="background-color: #6A0DAD;">
                Confirmar agendamento
            </button>

        </form>
    </div>

    <style>
        input[type="radio"].peer:checked ~ .slot-pill,
        input[type="radio"].peer:checked + .slot-pill {
            background-color: #6A0DAD;
            border-color: #6A0DAD;
            color: white;
        }
        label:has(input[type="radio"]:checked) .slot-pill {
            background-color: #6A0DAD;
            border-color: #6A0DAD;
            color: white;
        }
    </style>

    <script>
        function appointmentForm() {
            return {
                selectedDate: '',
                selectedTime: '',
                slots: [],
                loading: false,

                init() {
                    const oldDate = '{{ old('date') }}';
                    if (oldDate) {
                        this.selectedDate = oldDate;
                        this.fetchSlots();
                    }
                },

                async fetchSlots() {
                    if (!this.selectedDate) return;

                    this.loading = true;
                    this.slots = [];
                    this.selectedTime = '';

                    try {
                        const res = await fetch(
                            `{{ route('appointments.slots', $professional->id) }}?date=${this.selectedDate}`
                        );
                        this.slots = await res.json();
                    } catch (e) {
                        this.slots = [];
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>

</x-app-layout>