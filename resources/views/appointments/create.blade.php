<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Agendar — {{ $service->name }}
            </h2>
            <a href="{{ route('professional.public', $professional->id) }}"
               class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm font-semibold rounded-lg hover:bg-gray-200 transition">
                ← Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Resumo do serviço --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <p class="text-xs text-gray-400 uppercase font-semibold tracking-wide mb-3">Serviço selecionado</p>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-base font-bold text-gray-900 dark:text-white">{{ $service->name }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            com {{ $professional->establishment_name ?? $professional->user->name }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-base font-bold text-purple-600 dark:text-purple-400">
                            R$ {{ number_format($service->price, 2, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-400">⏱ {{ $service->duration_formatted }}</p>
                    </div>
                </div>
            </div>

            {{-- Formulário de agendamento --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">

                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-xl text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST"
                      action="{{ route('appointments.store', [$professional->id, $service->id]) }}"
                      x-data="appointmentForm()" x-init="init()">
                    @csrf

                    {{-- Data --}}
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            Escolha a data
                        </label>
                        <input type="date"
                               name="date"
                               x-model="selectedDate"
                               @change="fetchSlots()"
                               min="{{ now()->toDateString() }}"
                               value="{{ old('date') }}"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 text-sm shadow-sm focus:ring-purple-500 focus:border-purple-500">
                        <x-input-error :messages="$errors->get('date')" class="mt-1" />
                    </div>

                    {{-- Horários disponíveis --}}
                    <div class="mb-5" x-show="selectedDate">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Horários disponíveis
                        </label>

                        <div x-show="loading" class="text-sm text-gray-400">Carregando horários...</div>

                        <div x-show="!loading && slots.length === 0 && selectedDate"
                             class="text-sm text-gray-400 italic">
                            Nenhum horário disponível nesta data.
                        </div>

                        <div x-show="!loading && slots.length > 0"
                             class="grid grid-cols-4 gap-2">
                            <template x-for="slot in slots" :key="slot">
                                <label class="cursor-pointer">
                                    <input type="radio" name="time" :value="slot" class="peer sr-only">
                                    <div class="text-center py-2 px-1 rounded-lg border border-gray-200 dark:border-gray-700 text-sm
                                                peer-checked:border-purple-600 peer-checked:bg-purple-50 dark:peer-checked:bg-purple-900/30 peer-checked:text-purple-700
                                                hover:border-purple-300 transition cursor-pointer text-gray-700 dark:text-gray-300"
                                         x-text="slot">
                                    </div>
                                </label>
                            </template>
                        </div>
                        <x-input-error :messages="$errors->get('time')" class="mt-1" />
                    </div>

                    {{-- Observações --}}
                    <div class="mb-6" x-show="selectedDate">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            Observações <span class="text-gray-400 font-normal">(opcional)</span>
                        </label>
                        <textarea name="notes"
                                  rows="3"
                                  placeholder="Ex: prefiro tintura sem amônia..."
                                  class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 text-sm shadow-sm focus:ring-purple-500 focus:border-purple-500">{{ old('notes') }}</textarea>
                    </div>

                    <button type="submit"
                            x-show="selectedDate && slots.length > 0"
                            class="w-full py-3 bg-purple-600 text-white text-sm font-semibold rounded-lg hover:bg-purple-700 transition">
                        Confirmar agendamento
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function appointmentForm() {
            return {
                selectedDate: '',
                slots: [],
                loading: false,

                init() {
                    // Se voltou com erro, recarrega os slots
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