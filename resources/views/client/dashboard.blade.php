<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Meus Agendamentos') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('status'))
                <div class="p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-xl text-sm font-medium">
                    {{ session('status') }}
                </div>
            @endif

            {{-- BOAS VINDAS --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Bem-vindo(a) de volta,</p>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ auth()->user()->name }}</h2>
                </div>
                <a href="{{ route('explore') }}"
                   class="inline-flex items-center px-5 py-2 bg-purple-600 text-white text-sm font-semibold rounded-lg hover:bg-purple-700 transition">
                    + Novo agendamento
                </a>
            </div>

            {{-- PRÓXIMO AGENDAMENTO --}}
            @if($nextAppointment)
                <div class="bg-purple-600 rounded-2xl p-6 text-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-purple-200 mb-1">Próximo agendamento</p>
                        <p class="text-xl font-bold">{{ $nextAppointment->service->name }}</p>
                        <p class="text-sm text-purple-200">
                            com {{ $nextAppointment->professional->establishment_name ?? $nextAppointment->professional->user->name }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold">{{ $nextAppointment->scheduled_at->format('d') }}</p>
                        <p class="text-sm text-purple-200 uppercase tracking-wide">{{ $nextAppointment->scheduled_at->isoFormat('MMM') }}</p>
                        <p class="text-sm text-purple-200">{{ $nextAppointment->scheduled_at->format('H:i') }}</p>
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-dashed border-gray-200 dark:border-gray-700 p-8 text-center">
                    <p class="text-3xl mb-3">📅</p>
                    <p class="text-base font-medium text-gray-700 dark:text-gray-300">Nenhum agendamento próximo</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1 mb-4">Que tal encontrar um profissional?</p>
                    <a href="{{ route('explore') }}"
                       class="inline-flex items-center px-5 py-2 bg-purple-600 text-white text-sm font-semibold rounded-lg hover:bg-purple-700 transition">
                        Explorar profissionais
                    </a>
                </div>
            @endif

            {{-- PRÓXIMOS --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Próximos agendamentos</h3>
                </div>

                @forelse($upcomingAppointments as $appt)
                    <div class="flex items-center gap-4 px-6 py-4 border-b last:border-0 dark:border-gray-700">
                        <div class="w-12 text-center bg-gray-50 dark:bg-gray-700 rounded-xl py-2">
                            <p class="text-lg font-bold text-gray-900 dark:text-white leading-none">{{ $appt->scheduled_at->format('d') }}</p>
                            <p class="text-xs text-gray-400 uppercase">{{ $appt->scheduled_at->isoFormat('MMM') }}</p>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $appt->service->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $appt->professional->establishment_name ?? $appt->professional->user->name }} · {{ $appt->scheduled_at->format('H:i') }}
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-semibold px-3 py-1 rounded-full
                                {{ $appt->status === 'confirmed'
                                    ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300'
                                    : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300' }}">
                                {{ $appt->status_label }}
                            </span>
                            <form method="POST" action="{{ route('appointments.cancel', $appt->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-xs text-red-500 hover:text-red-700 transition">
                                    Cancelar
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-sm text-gray-400 dark:text-gray-500">
                        Nenhum agendamento futuro por aqui.
                    </div>
                @endforelse
            </div>

            {{-- HISTÓRICO --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Histórico</h3>
                </div>

                @forelse($pastAppointments as $appt)
                    <div class="flex items-center gap-4 px-6 py-4 border-b last:border-0 dark:border-gray-700">
                        <div class="w-12 text-center bg-gray-50 dark:bg-gray-700 rounded-xl py-2">
                            <p class="text-lg font-bold text-gray-900 dark:text-white leading-none">{{ $appt->scheduled_at->format('d') }}</p>
                            <p class="text-xs text-gray-400 uppercase">{{ $appt->scheduled_at->isoFormat('MMM') }}</p>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $appt->service->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $appt->professional->establishment_name ?? $appt->professional->user->name }}
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-semibold px-3 py-1 bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400 rounded-full">
                                {{ $appt->status_label }}
                            </span>
                            @if($appt->status === 'completed')
                                @if($appt->review)
                                    <span class="text-xs text-gray-400 dark:text-gray-500">Avaliado</span>
                                @else
                                    <a href="{{ route('reviews.create', $appt->id) }}"
                                       class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 transition">
                                        Avaliar
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-sm text-gray-400 dark:text-gray-500">
                        Seu histórico aparecerá aqui.
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>