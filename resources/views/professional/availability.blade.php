<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Minha Disponibilidade') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('status'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-xl text-sm font-medium">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    Selecione os dias em que você atende e defina o horário de abertura, fechamento e o intervalo entre os agendamentos.
                </p>

                <form method="POST" action="{{ route('professional.availability.save') }}">
                    @csrf

                    <div class="space-y-4">
                        @foreach($weekdays as $number => $name)
                            @php $avail = $availabilities->get($number); @endphp

                            <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4" x-data="{ enabled: {{ $avail ? 'true' : 'false' }} }">
                                <div class="flex items-center justify-between mb-3">
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox"
                                               name="days[]"
                                               value="{{ $number }}"
                                               x-model="enabled"
                                               {{ $avail ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                        <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $name }}</span>
                                    </label>
                                </div>

                                <div x-show="enabled" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs text-gray-400 uppercase font-semibold mb-1">Abertura</label>
                                        <input type="time"
                                               name="open_time[{{ $number }}]"
                                               value="{{ $avail?->open_time ?? '09:00' }}"
                                               class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 text-sm shadow-sm focus:ring-purple-500 focus:border-purple-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-400 uppercase font-semibold mb-1">Fechamento</label>
                                        <input type="time"
                                               name="close_time[{{ $number }}]"
                                               value="{{ $avail?->close_time ?? '18:00' }}"
                                               class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 text-sm shadow-sm focus:ring-purple-500 focus:border-purple-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-400 uppercase font-semibold mb-1">Intervalo (min)</label>
                                        <select name="slot_interval[{{ $number }}]"
                                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 text-sm shadow-sm focus:ring-purple-500 focus:border-purple-500">
                                            @foreach([15, 30, 45, 60, 90, 120] as $interval)
                                                <option value="{{ $interval }}" {{ ($avail?->slot_interval ?? 60) == $interval ? 'selected' : '' }}>
                                                    {{ $interval }} min
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                                class="px-6 py-2 bg-purple-600 text-white text-sm font-semibold rounded-lg hover:bg-purple-700 transition">
                            Salvar disponibilidade
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>