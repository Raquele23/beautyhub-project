<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-8 py-10 space-y-6"
         x-data="professionalAppointmentForm()"
         x-init="init()">

        <div class="flex items-center justify-between gap-3">
            <div>
                <p class="text-xs font-bold tracking-widest uppercase text-purple-400">Beauty Hub</p>
                <h1 class="text-2xl font-bold text-purple-800 mt-0.5">Novo agendamento</h1>
                <p class="text-xs text-purple-400 mt-1">Agende cliente da plataforma (já atendido) ou cliente externo.</p>
            </div>
            <a href="{{ route('professional.appointments') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-purple-700 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
               style="background-color: #E3D0F9;">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
                Voltar
            </a>
        </div>

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

        <form method="POST" action="{{ route('professional.appointments.store') }}" class="space-y-5">
            @csrf

            <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-6 space-y-4">
                <p class="text-xs font-bold text-purple-400 uppercase tracking-wide">Cliente</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="border rounded-xl p-4 cursor-pointer transition-all"
                           :class="clientMode === 'known' ? 'border-purple-500 bg-purple-50' : 'border-purple-100 hover:border-purple-300'">
                        <input type="radio" name="client_mode" value="known" x-model="clientMode" class="sr-only">
                        <p class="text-sm font-semibold text-gray-800">Cliente da plataforma</p>
                        <p class="text-xs text-purple-400 mt-1">Busca por nome ou email.</p>
                    </label>
                    <label class="border rounded-xl p-4 cursor-pointer transition-all"
                           :class="clientMode === 'external' ? 'border-purple-500 bg-purple-50' : 'border-purple-100 hover:border-purple-300'">
                        <input type="radio" name="client_mode" value="external" x-model="clientMode" class="sr-only">
                        <p class="text-sm font-semibold text-gray-800">Cliente externo</p>
                        <p class="text-xs text-purple-400 mt-1">Agende para quem ainda não está na plataforma.</p>
                    </label>
                </div>

                <div x-show="clientMode === 'known'" class="space-y-3" style="display: none;">
                    <div class="relative">
                        <label class="text-xs font-semibold text-purple-400 uppercase tracking-wide">Buscar cliente</label>
                        <input type="text"
                               x-model="knownSearch"
                               @input.debounce.300ms="searchKnownClients()"
                               placeholder="Digite nome ou email"
                               class="mt-1 w-full px-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm">

                        <input type="hidden" name="known_client_id" :value="selectedKnownClientId">

                        <div x-show="knownResults.length > 0" class="absolute z-20 mt-2 w-full bg-white border border-purple-100 rounded-xl shadow-lg overflow-hidden" style="display:none;">
                            <template x-for="client in knownResults" :key="client.id">
                                <button type="button"
                                        @click="pickKnownClient(client)"
                                        class="w-full px-4 py-2.5 text-left hover:bg-purple-50 transition-colors">
                                    <p class="text-sm font-semibold text-gray-900" x-text="client.name"></p>
                                    <p class="text-xs text-purple-400" x-text="client.email"></p>
                                </button>
                            </template>
                        </div>
                    </div>

                    <div x-show="selectedKnownClient" class="rounded-xl border border-purple-200 bg-purple-50 px-4 py-3" style="display:none;">
                        <p class="text-xs text-purple-500 uppercase tracking-wide font-semibold">Selecionado</p>
                        <p class="text-sm font-semibold text-gray-900" x-text="selectedKnownClient?.name"></p>
                        <p class="text-xs text-purple-400" x-text="selectedKnownClient?.email"></p>
                    </div>
                </div>

                <div x-show="clientMode === 'external'" class="grid grid-cols-1 sm:grid-cols-2 gap-3" style="display:none;">
                    <div class="sm:col-span-2">
                        <label class="text-xs font-semibold text-purple-400 uppercase tracking-wide">Nome do cliente</label>
                        <input type="text" name="external_name" value="{{ old('external_name', $prefillName) }}"
                               class="mt-1 w-full px-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-purple-400 uppercase tracking-wide">Email (opcional)</label>
                        <input type="email" name="external_email" value="{{ old('external_email', $prefillEmail) }}"
                               class="mt-1 w-full px-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-purple-400 uppercase tracking-wide">Telefone</label>
                        <input type="text" name="external_phone" value="{{ old('external_phone', $prefillPhone) }}"
                               :required="clientMode === 'external'"
                               class="mt-1 w-full px-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-6 space-y-4">
                <p class="text-xs font-bold text-purple-400 uppercase tracking-wide">Serviço e data</p>

                <div>
                    <label class="text-xs font-semibold text-purple-400 uppercase tracking-wide">Serviço</label>
                    <select name="service_id"
                            x-model="serviceId"
                            @change="fetchSlots()"
                            class="mt-1 w-full px-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm">
                        <option value="">Selecione...</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ (int) old('service_id', $prefillService) === $service->id ? 'selected' : '' }}>
                                {{ $service->name }} · {{ $service->duration_formatted }} · R$ {{ number_format($service->price, 2, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-semibold text-purple-400 uppercase tracking-wide">Data</label>
                        <input type="date"
                               name="date"
                               x-model="selectedDate"
                               @change="fetchSlots()"
                               min="{{ now()->toDateString() }}"
                               value="{{ old('date', $suggestedDate) }}"
                               class="mt-1 w-full px-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-purple-400 uppercase tracking-wide">Horário</label>
                        <select name="time" x-model="selectedTime"
                                class="mt-1 w-full px-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm">
                            <option value="" x-text="loading ? 'Carregando...' : 'Selecione...'">Selecione...</option>
                            <template x-for="slot in slots" :key="slot">
                                <option :value="slot" x-text="slot"></option>
                            </template>
                        </select>
                    </div>
                </div>

                <p class="text-xs text-purple-400">Horários ocupados não aparecem para evitar conflito na agenda.</p>

                <div>
                    <label class="text-xs font-semibold text-purple-400 uppercase tracking-wide">Observações (opcional)</label>
                    <textarea name="notes" rows="3"
                              class="mt-1 w-full px-4 py-3 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 placeholder-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm resize-none">{{ old('notes') }}</textarea>
                </div>
            </div>

            <button type="submit"
                    class="w-full py-3 text-white text-sm font-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5 shadow-lg shadow-purple-200"
                    style="background-color: #6A0DAD;">
                Salvar agendamento
            </button>
        </form>
    </div>

    <script>
        function professionalAppointmentForm() {
            return {
                clientMode: '{{ old('client_mode', $prefillClient ? 'known' : ($prefillName ? 'external' : 'known')) }}',
                serviceId: '{{ old('service_id', $prefillService) }}',
                selectedDate: '{{ old('date', $suggestedDate) }}',
                selectedTime: '{{ old('time') }}',
                slots: [],
                loading: false,
                knownSearch: '',
                knownResults: [],
                selectedKnownClientId: '{{ old('known_client_id', $prefillClient) }}',
                selectedKnownClient: null,

                init() {
                    if (this.selectedDate) {
                        this.fetchSlots();
                    }

                    const prefillName = @js($prefillName);
                    const prefillEmail = @js($prefillEmail);
                    const prefillClientId = @js($prefillClient);

                    if (prefillClientId) {
                        this.selectedKnownClient = {
                            id: prefillClientId,
                            name: prefillName || 'Cliente da plataforma',
                            email: prefillEmail || '',
                        };
                    }
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
                        const res = await fetch(
                            `{{ route('appointments.slots', $professional->id) }}?date=${this.selectedDate}&service_id=${this.serviceId}`
                        );
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
            }
        }
    </script>
</x-app-layout>
