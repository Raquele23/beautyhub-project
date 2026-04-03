<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Calendário') }}
        </h2>
    </x-slot>

   <div class="py-8 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                x-data="calendar({{ $appointmentsJson }}, {{ $servicesJson }})"
                x-init="init()"
                class="space-y-4"
            >
                {{-- Controles --}}
                <div class="bg-white/80 backdrop-blur border border-purple-200 rounded-2xl px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 shadow-sm">
                    <div class="flex items-center gap-2">
                        <button @click="prev()"
                                class="w-8 h-8 rounded-lg border border-purple-300 text-purple-800 flex items-center justify-center hover:bg-purple-100 hover:text-purple-700 transition duration-150 active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <h3 class="text-base font-semibold text-purple-900 min-w-[160px] text-center"
                            x-text="title()"></h3>
                        <button @click="next()"
                                class="w-8 h-8 rounded-lg border border-purple-300 text-purple-500 flex items-center justify-center hover:bg-purple-100 hover:text-purple-700 transition duration-150 active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        <button @click="goToday()"
                                class="text-xs font-semibold text-purple-800 border border-purple-300 px-3 py-1.5 rounded-lg ml-1 hover:bg-purple-800 hover:text-white transition duration-150">
                            Hoje
                        </button>
                    </div>

                    {{-- Toggle --}}
                    <div class="flex rounded-xl overflow-hidden border border-purple-300 self-start sm:self-auto">
                        <button @click="view = 'month'"
                                :class="view === 'month' ? 'bg-purple-800 text-white' : 'bg-white text-purple-500 hover:bg-purple-100'"
                                class="px-4 py-2 text-xs font-semibold transition duration-150">
                            Mensal
                        </button>
                        <button @click="view = 'week'"
                                :class="view === 'week' ? 'bg-purple-800 text-white' : 'bg-white text-purple-500 hover:bg-purple-100'"
                                class="px-4 py-2 text-xs font-semibold transition duration-150 border-l border-purple-300">
                            Semanal
                        </button>
                    </div>
                </div>

                {{-- Visão Mensal --}}
                <div x-show="view === 'month'"
                     class="bg-white/90 rounded-2xl border border-purple-300 overflow-hidden shadow-sm">

                    <div class="grid grid-cols-7 border-b border-purple-300">
                        <template x-for="day in ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb']">
                            <div class="py-3 text-center text-xs font-semibold text-purple-400"
                                 x-text="day"></div>
                        </template>
                    </div>

                    <div class="grid grid-cols-7">
                        <template x-for="(cell, idx) in monthCells()" :key="idx">
                            <div
                                @click="cell.date && selectDay(cell.date)"
                                :class="{
                                    'bg-purple-50/50 cursor-default': !cell.date,
                                    'cursor-pointer hover:bg-purple-50 transition duration-150': cell.date,
                                    'bg-purple-100 hover:bg-purple-200': cell.date && isPastDate(cell.date),
                                }"
                                class="min-h-[82px] p-2 border-b border-r border-purple-300 relative">

                                <span x-show="cell.date"
                                      :class="isToday(cell.date)
                                        ? 'bg-purple-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold'
                                        : 'text-xs text-purple-800'"
                                      class="inline-block mb-1"
                                      x-text="cell.day"></span>

                                <template x-for="appt in appointmentsForDay(cell.date).slice(0,2)" :key="appt.id">
                                    <div :class="appointmentTagClass(appt)"
                                         class="text-[10px] font-medium px-1.5 py-0.5 rounded-md mb-0.5 truncate hover:opacity-80 transition duration-150">
                                        <span x-text="appt.time + ' ' + appt.service"></span>
                                    </div>
                                </template>

                                <div x-show="appointmentsForDay(cell.date).length > 2"
                                     class="text-[10px] font-semibold text-purple-500 hover:text-purple-700 transition duration-150 cursor-pointer"
                                     x-text="'+' + (appointmentsForDay(cell.date).length - 2) + ' mais'">
                                </div>


                            </div>
                        </template>
                    </div>
                </div>

                {{-- Visão Semanal --}}
                <div x-show="view === 'week'"
                     class="bg-white/90 rounded-2xl border border-purple-100 overflow-hidden shadow-sm">

                    <div class="grid grid-cols-7 border-b border-purple-100">
                        <template x-for="day in weekDays()" :key="day.date">
                            <div @click="selectDay(day.date)"
                                 class="py-3 text-center cursor-pointer hover:bg-purple-50 transition duration-150">
                                <p class="text-xs text-purple-400 font-medium" x-text="day.label"></p>
                                <p :class="isToday(day.date)
                                        ? 'bg-purple-500 text-white rounded-full w-7 h-7 flex items-center justify-center mx-auto font-bold'
                                        : 'text-purple-800 font-semibold'"
                                   class="text-sm mt-1"
                                   x-text="day.num"></p>
                                <span x-show="appointmentsForDay(day.date).length > 0"
                                      class="inline-block w-1.5 h-1.5 bg-purple-400 rounded-full mt-1"></span>
                            </div>
                        </template>
                    </div>

                    <div class="divide-y divide-purple-100">
                        <template x-for="day in weekDays()" :key="day.date">
                            <div x-show="appointmentsForDay(day.date).length > 0" class="px-6 py-4">
                                <p class="text-xs font-bold text-purple-400 uppercase tracking-wide mb-3"
                                   x-text="day.fullLabel"></p>
                                <template x-for="appt in appointmentsForDay(day.date)" :key="appt.id">
                                     <div :class="isPastAppointment(appt) ? 'opacity-65' : ''"
                                         class="flex items-center gap-3 mb-2 p-2 rounded-lg hover:bg-purple-50 transition duration-150">
                                        <span class="text-xs font-semibold text-purple-400 w-12 flex-shrink-0"
                                              x-text="appt.time"></span>
                                        <div :class="appointmentBorderClass(appt)"
                                             class="flex-1 border-l-2 pl-3">
                                            <p class="text-sm font-semibold text-purple-900" x-text="appt.service"></p>
                                            <p class="text-xs text-purple-400" x-text="appt.client"></p>
                                        </div>
                                        <span :class="appointmentTagClass(appt)"
                                              class="text-xs font-semibold px-2.5 py-0.5 rounded-full flex-shrink-0"
                                              x-text="statusLabel(appt.status)"></span>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <div x-show="weekDays().every(d => appointmentsForDay(d.date).length === 0)"
                             class="px-6 py-10 text-center text-sm text-purple-300">
                            Nenhum agendamento nessa semana.
                        </div>
                    </div>
                </div>

                {{-- Modal --}}
                <template x-teleport="body">
                    <div x-show="dayModalOpen"
                         x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 z-50"
                         style="display: none;">
                        <div class="absolute inset-0" @click="closeSelectedDayModal()" style="background-color: rgba(146, 64, 204, 0.18);"></div>

                        <div class="relative h-full flex items-end sm:items-center justify-center p-4">
                            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md max-h-[80vh] overflow-y-auto border border-purple-100">
                                <div class="flex items-center justify-between px-6 py-4 border-b border-purple-100 bg-gradient-to-r from-purple-50 to-violet-50">
                                    <h4 class="font-semibold text-purple-900" x-text="selectedDayLabel()"></h4>
                                    <button @click="closeSelectedDayModal()"
                                        class="w-7 h-7 rounded-lg border border-purple-200 text-purple-400 flex items-center justify-center hover:bg-purple-500 hover:text-white hover:border-purple-500 transition duration-150">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                <div class="p-4 space-y-2">
                                    <div class="pb-2" x-show="selectedDay && !isPastDate(selectedDay)">
                                        <button type="button"
                                                @click="openCreateForSelectedDay()"
                                                class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-purple-700 hover:bg-purple-800 text-white text-xs font-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5 shadow-lg shadow-purple-200">
                                            Adicionar agendamento nesta data
                                        </button>
                                    </div>

                                    <div class="pb-2" x-show="selectedDay && isPastDate(selectedDay)">
                                        <p class="text-xs text-slate-500 bg-slate-100 border border-slate-200 rounded-lg px-3 py-2">
                                            Esta data já passou. Você pode apenas visualizar os agendamentos já registrados.
                                        </p>
                                    </div>

                                    <template x-for="appt in appointmentsForDay(selectedDay)" :key="appt.id">
                                        <div :class="isPastAppointment(appt) ? 'opacity-65' : ''"
                                            class="flex items-start gap-3 p-3 rounded-xl bg-purple-50/60 border border-purple-100 hover:bg-purple-100/60 hover:border-purple-200 transition duration-150">
                                            <div :class="appointmentDotClass(appt)"
                                                class="w-2 h-2 rounded-full mt-1.5 flex-shrink-0"></div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-purple-900" x-text="appt.service"></p>
                                                <p class="text-xs text-purple-400" x-text="appt.client"></p>
                                                <p class="text-xs text-purple-300 mt-0.5" x-text="appt.time"></p>
                                            </div>
                                            <span :class="appointmentTagClass(appt)"
                                                  class="text-xs font-semibold px-2.5 py-0.5 rounded-full flex-shrink-0"
                                                x-text="statusLabel(appt.status)"></span>
                                        </div>
                                    </template>
                                    <div x-show="appointmentsForDay(selectedDay).length === 0"
                                         class="text-center text-sm text-purple-300 py-8">
                                        Nenhum agendamento neste dia.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <template x-teleport="body">
                    <div x-show="createOpen"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 z-50"
                         style="display: none;">
                        <div class="absolute inset-0" @click="createOpen = false" style="background-color: rgba(146, 64, 204, 0.18);"></div>

                        <div class="relative h-full flex items-center justify-center p-4">
                            <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl border border-purple-100 max-h-[90vh] overflow-y-auto">
                                <div class="flex items-center justify-between px-6 py-4 border-b border-purple-50 sticky top-0 z-30 bg-white">
                                    <h3 class="font-bold text-purple-800">Novo agendamento</h3>
                                    <button @click="createOpen = false" class="text-purple-300 hover:text-purple-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                @if($errors->any())
                                    <div class="mx-6 mt-4 flex items-center gap-3 px-4 py-3 bg-white border border-red-100 rounded-xl">
                                        <p class="text-sm font-medium text-red-500">{{ $errors->first() }}</p>
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('professional.appointments.store') }}" class="px-6 pb-6 pt-2 space-y-5">
                                    @csrf
                                    <input type="hidden" name="source" value="calendar">

                            <div class="space-y-4">
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
                                        <p class="text-xs text-purple-400 mt-1">Cadastro rápido sem conta na plataforma.</p>
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
                                </div>

                                <template x-if="clientMode === 'external'">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div class="sm:col-span-2">
                                            <label class="text-xs font-semibold text-purple-400 uppercase tracking-wide">Nome do cliente</label>
                                            <input type="text" name="external_name" value="{{ old('external_name') }}"
                                                   class="mt-1 w-full px-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm">
                                        </div>
                                        <div>
                                            <label class="text-xs font-semibold text-purple-400 uppercase tracking-wide">Email (opcional)</label>
                                            <input type="email" name="external_email" value="{{ old('external_email') }}"
                                                   class="mt-1 w-full px-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm">
                                        </div>
                                        <div>
                                            <label class="text-xs font-semibold text-purple-400 uppercase tracking-wide">Telefone</label>
                                              <input type="tel" name="external_phone" value="{{ old('external_phone') }}"
                                                  required
                                                  maxlength="15"
                                                  placeholder="(11) 99999-9999"
                                                  x-init="$el.value = applyPhoneMask($el.value)"
                                                  @input="formatPhoneInput($event)"
                                                  class="mt-1 w-full px-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm">
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <div class="space-y-4 border-t border-purple-50 pt-4">
                                <p class="text-xs font-bold text-purple-400 uppercase tracking-wide">Serviço e data</p>

                                <div>
                                    <label class="text-xs font-semibold text-purple-400 uppercase tracking-wide">Serviço</label>
                                    <select name="service_id"
                                            x-model="serviceId"
                                            @change="fetchCreateSlots()"
                                            class="mt-1 w-full px-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm">
                                        <option value="">Selecione...</option>
                                        <template x-for="service in services" :key="service.id">
                                            <option :value="service.id"
                                                    x-text="`${service.name} · ${formatDuration(service.duration)} · R$ ${formatPrice(service.price)}`"></option>
                                        </template>
                                    </select>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="text-xs font-semibold text-purple-400 uppercase tracking-wide">Data</label>
                                        <input type="date"
                                               name="date"
                                               x-model="createDate"
                                               @change="fetchCreateSlots()"
                                               min="{{ now()->toDateString() }}"
                                               value="{{ old('date') }}"
                                               class="mt-1 w-full px-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm">
                                    </div>
                                    <div>
                                        <label class="text-xs font-semibold text-purple-400 uppercase tracking-wide">Horário</label>
                                        <select name="time" x-model="createTime"
                                                class="mt-1 w-full px-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm">
                                            <option value="" x-text="loadingCreateSlots ? 'Carregando...' : 'Selecione...'">Selecione...</option>
                                            <template x-for="slot in createSlots" :key="slot">
                                                <option :value="slot" x-text="slot"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>

                                <div x-show="!loadingCreateSlots && createDate && serviceId && createSlots.length === 0"
                                     class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3"
                                     style="display:none;">
                                    <p class="text-xs font-semibold text-amber-700">
                                        Não há horários disponíveis para essa data. Revise sua disponibilidade para liberar novos horários.
                                    </p>
                                </div>

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
                        </div>
                    </div>
                </template>

            </div>
        </div>
    </div>

    <script>
        function calendar(appointments, services) {
            const today = new Date();
            return {
                view: 'month',
                current: new Date(today.getFullYear(), today.getMonth(), 1),
                selectedDay: null,
                dayModalOpen: false,
                appointments: appointments,
                services: services,

                createOpen: @json($errors->any()),
                clientMode: '{{ old('client_mode', 'known') }}',
                knownSearch: '',
                knownResults: [],
                selectedKnownClientId: '{{ old('known_client_id') }}',
                serviceId: '{{ old('service_id') }}',
                createDate: '{{ old('date') }}',
                createTime: '{{ old('time') }}',
                createSlots: [],
                loadingCreateSlots: false,
init() {
                    if (this.createDate) {
                        this.fetchCreateSlots();
                    }
                },

                
                title() {
                    const months = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
                    if (this.view === 'month') {
                        return months[this.current.getMonth()] + ' ' + this.current.getFullYear();
                    }
                    const week = this.weekDays();
                    return week[0].num + ' – ' + week[6].num + ' de ' + months[this.current.getMonth()];
                },

                prev() {
                    if (this.view === 'month') {
                        this.current = new Date(this.current.getFullYear(), this.current.getMonth() - 1, 1);
                    } else {
                        this.current = new Date(this.current.getFullYear(), this.current.getMonth(), this.current.getDate() - 7);
                    }
                },

                next() {
                    if (this.view === 'month') {
                        this.current = new Date(this.current.getFullYear(), this.current.getMonth() + 1, 1);
                    } else {
                        this.current = new Date(this.current.getFullYear(), this.current.getMonth(), this.current.getDate() + 7);
                    }
                },

                goToday() {
                    this.current = new Date(today.getFullYear(), today.getMonth(), this.view === 'month' ? 1 : today.getDate());
                },

                isToday(dateStr) {
                    if (!dateStr) return false;
                    const t = today;
                    return dateStr === `${t.getFullYear()}-${String(t.getMonth()+1).padStart(2,'0')}-${String(t.getDate()).padStart(2,'0')}`;
                },

                isPastDate(dateStr) {
                    if (!dateStr) return false;
                    const nowDate = new Date();
                    nowDate.setHours(0, 0, 0, 0);

                    const [year, month, day] = dateStr.split('-').map(Number);
                    const check = new Date(year, month - 1, day);
                    check.setHours(0, 0, 0, 0);

                    return check < nowDate;
                },

                monthCells() {
                    const year = this.current.getFullYear();
                    const month = this.current.getMonth();
                    const firstDay = new Date(year, month, 1).getDay();
                    const daysInMonth = new Date(year, month + 1, 0).getDate();
                    const cells = [];
                    for (let i = 0; i < firstDay; i++) cells.push({ date: null, day: '' });
                    for (let d = 1; d <= daysInMonth; d++) {
                        const dateStr = `${year}-${String(month+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
                        cells.push({ date: dateStr, day: d });
                    }
                    while (cells.length % 7 !== 0) cells.push({ date: null, day: '' });
                    return cells;
                },

                weekDays() {
                    const days = [];
                    const labels = ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'];
                    const fullLabels = ['Domingo','Segunda-feira','Terça-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sábado'];
                    const base = this.view === 'week' ? new Date(this.current) : new Date(today);
                    const startOfWeek = new Date(base);
                    startOfWeek.setDate(base.getDate() - base.getDay());
                    for (let i = 0; i < 7; i++) {
                        const d = new Date(startOfWeek);
                        d.setDate(startOfWeek.getDate() + i);
                        const dateStr = `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
                        days.push({
                            date: dateStr,
                            label: labels[d.getDay()],
                            fullLabel: fullLabels[d.getDay()] + ', ' + d.getDate(),
                            num: d.getDate(),
                        });
                    }
                    return days;
                },

                appointmentsForDay(dateStr) {
                    if (!dateStr) return [];
                    return this.appointments
                        .filter(a => a.date === dateStr && (a.status === 'confirmed' || a.status === 'pending' || a.status === 'completed'))
                        .sort((a, b) => a.time.localeCompare(b.time));
                },

                selectDay(dateStr) {
                    this.selectedDay = dateStr;
                    this.dayModalOpen = true;
                },

                closeSelectedDayModal() {
                    this.dayModalOpen = false;

                    // Limpa o dia selecionado após a animação de saída para evitar flicker.
                    setTimeout(() => {
                        if (!this.dayModalOpen) {
                            this.selectedDay = null;
                        }
                    }, 110);
                },

                selectedDayLabel() {
                    if (!this.selectedDay) return '';
                    const [y, m, d] = this.selectedDay.split('-');
                    const months = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
                    return `${parseInt(d)} de ${months[parseInt(m)-1]} de ${y}`;
                },

                isPastAppointment(appt) {
                    const apptDate = new Date(`${appt.date}T${appt.time}:00`);
                    return apptDate < new Date();
                },

                statusLabel(status) {
                    return {
                        confirmed: 'Confirmado',
                        pending: 'Pendente',
                        completed: 'Concluído',
                        cancelled: 'Cancelado',
                    }[status] || status;
                },

                appointmentTagClass(appt) {
                    if (appt.status === 'cancelled') {
                        return 'bg-red-100 text-red-700 border border-red-200';
                    }

                    if (appt.status === 'completed') {
                        return 'bg-purple-100 text-purple-700 border border-purple-200';
                    }

                    if (this.isPastAppointment(appt)) {
                        return 'bg-transparent text-purple-500 border border-purple-200';
                    }

                    return appt.status === 'confirmed'
                        ? 'bg-emerald-100 text-emerald-700'
                        : 'bg-amber-100 text-amber-700';
                },

                appointmentBorderClass(appt) {
                    if (appt.status === 'cancelled') {
                        return 'border-red-300';
                    }

                    if (appt.status === 'completed') {
                        return 'border-purple-300';
                    }

                    if (this.isPastAppointment(appt)) {
                        return 'border-purple-200';
                    }

                    return appt.status === 'confirmed' ? 'border-emerald-400' : 'border-amber-400';
                },

                appointmentDotClass(appt) {
                    if (appt.status === 'cancelled') {
                        return 'bg-red-400';
                    }

                    if (appt.status === 'completed') {
                        return 'bg-purple-400';
                    }

                    if (this.isPastAppointment(appt)) {
                        return 'bg-purple-300';
                    }

                    return appt.status === 'confirmed' ? 'bg-emerald-400' : 'bg-amber-400';
                },

                openCreateForSelectedDay() {
                    if (!this.selectedDay || this.isPastDate(this.selectedDay)) {
                        return;
                    }

                    const selectedDay = this.selectedDay;
                    this.closeSelectedDayModal();
                    this.createDate = selectedDay;
                    this.createTime = '';

                    setTimeout(() => {
                        this.createOpen = true;
                        this.fetchCreateSlots();
                    }, 120);
                },

                async fetchCreateSlots() {
                    if (!this.createDate || !this.serviceId) {
                        this.createSlots = [];
                        this.createTime = '';
                        return;
                    }

                    this.loadingCreateSlots = true;
                    this.createSlots = [];

                    try {
                        const res = await fetch(`{{ route('appointments.slots', auth()->user()->professional->id) }}?date=${this.createDate}&service_id=${this.serviceId}`);
                        this.createSlots = await res.json();

                        if (this.createTime && !this.createSlots.includes(this.createTime)) {
                            this.createTime = '';
                        }
                    } catch (e) {
                        this.createSlots = [];
                    } finally {
                        this.loadingCreateSlots = false;
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

                formatDuration(duration) {
                    const mins = Number(duration || 0);
                    if (mins >= 60) {
                        const hours = Math.floor(mins / 60);
                        const rest = mins % 60;
                        return rest === 0 ? `${hours}h` : `${hours}h ${rest}min`;
                    }

                    return `${mins}min`;
                },

                formatPrice(value) {
                    return Number(value || 0).toFixed(2).replace('.', ',');
                },
            };
        }
    </script>
</x-app-layout>
