<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Calendário') }}
        </h2>
    </x-slot>

    <div class="py-8" style="background: linear-gradient(160deg, #f3effe 0%, #e8d9fc 100%); min-height: 100vh;">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                x-data="calendar({{ $appointmentsJson }})"
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
                                }"
                                class="min-h-[82px] p-2 border-b border-r border-purple-300 relative">

                                <span x-show="cell.date"
                                      :class="isToday(cell.date)
                                        ? 'bg-purple-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold'
                                        : 'text-xs text-purple-800'"
                                      class="inline-block mb-1"
                                      x-text="cell.day"></span>

                                <template x-for="appt in appointmentsForDay(cell.date).slice(0,2)" :key="appt.id">
                                    <div :class="appt.status === 'confirmed'
                                            ? 'bg-emerald-100 text-emerald-700'
                                            : 'bg-amber-100 text-amber-700'"
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
                                    <div class="flex items-center gap-3 mb-2 p-2 rounded-lg hover:bg-purple-50 transition duration-150">
                                        <span class="text-xs font-semibold text-purple-400 w-12 flex-shrink-0"
                                              x-text="appt.time"></span>
                                        <div :class="appt.status === 'confirmed' ? 'border-emerald-400' : 'border-amber-400'"
                                             class="flex-1 border-l-2 pl-3">
                                            <p class="text-sm font-semibold text-purple-900" x-text="appt.service"></p>
                                            <p class="text-xs text-purple-400" x-text="appt.client"></p>
                                        </div>
                                        <span :class="appt.status === 'confirmed'
                                                ? 'bg-emerald-100 text-emerald-700'
                                                : 'bg-amber-100 text-amber-700'"
                                              class="text-xs font-semibold px-2.5 py-0.5 rounded-full flex-shrink-0"
                                              x-text="appt.status === 'confirmed' ? 'Confirmado' : 'Pendente'"></span>
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
                <div x-show="selectedDay"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click.self="selectedDay = null"
                     class="fixed inset-0 bg-purple-900/30 z-50 flex items-end sm:items-center justify-center p-4"
                     style="display: none;">

                    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md max-h-[80vh] overflow-y-auto border border-purple-100">
                        <div class="flex items-center justify-between px-6 py-4 border-b border-purple-100 bg-gradient-to-r from-purple-50 to-violet-50">
                            <h4 class="font-semibold text-purple-900" x-text="selectedDayLabel()"></h4>
                            <button @click="selectedDay = null"
                                    class="w-7 h-7 rounded-lg border border-purple-200 text-purple-400 flex items-center justify-center hover:bg-purple-500 hover:text-white hover:border-purple-500 transition duration-150">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <div class="p-4 space-y-2">
                            <template x-for="appt in appointmentsForDay(selectedDay)" :key="appt.id">
                                <div class="flex items-start gap-3 p-3 rounded-xl bg-purple-50/60 border border-purple-100 hover:bg-purple-100/60 hover:border-purple-200 transition duration-150">
                                    <div :class="appt.status === 'confirmed' ? 'bg-emerald-400' : 'bg-amber-400'"
                                         class="w-2 h-2 rounded-full mt-1.5 flex-shrink-0"></div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-purple-900" x-text="appt.service"></p>
                                        <p class="text-xs text-purple-400" x-text="appt.client"></p>
                                        <p class="text-xs text-purple-300 mt-0.5" x-text="appt.time"></p>
                                    </div>
                                    <span :class="appt.status === 'confirmed'
                                            ? 'bg-emerald-100 text-emerald-700'
                                            : 'bg-amber-100 text-amber-700'"
                                          class="text-xs font-semibold px-2.5 py-0.5 rounded-full flex-shrink-0"
                                          x-text="appt.status === 'confirmed' ? 'Confirmado' : 'Pendente'"></span>
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
        </div>
    </div>

    <script>
        function calendar(appointments) {
            const today = new Date();
            return {
                view: 'month',
                current: new Date(today.getFullYear(), today.getMonth(), 1),
                selectedDay: null,
                appointments: appointments,

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
                        .filter(a => a.date === dateStr)
                        .sort((a, b) => a.time.localeCompare(b.time));
                },

                selectDay(dateStr) {
                    this.selectedDay = dateStr;
                },

                selectedDayLabel() {
                    if (!this.selectedDay) return '';
                    const [y, m, d] = this.selectedDay.split('-');
                    const months = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
                    return `${parseInt(d)} de ${months[parseInt(m)-1]} de ${y}`;
                },
            };
        }
    </script>
</x-app-layout>
