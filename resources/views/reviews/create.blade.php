<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Avaliar Atendimento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Você está avaliando:</p>
                        <p class="font-semibold text-gray-800 dark:text-gray-200">
                            {{ $appointment->professional->establishment_name ?? $appointment->professional->user->name }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            {{ $appointment->service->name }} —
                            {{ $appointment->scheduled_at->format('d/m/Y \à\s H:i') }}
                        </p>
                    </div>

                    <form action="{{ route('reviews.store', $appointment) }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nota <span class="text-red-500">*</span>
                            </label>
                            <x-star-picker name="rating" :value="old('rating', 0)" />
                        </div>

                        <div>
                            <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Comentário <span class="text-gray-400 font-normal">(opcional)</span>
                            </label>
                            <textarea name="comment" id="comment" rows="4" maxlength="1000"
                                placeholder="Descreva sua experiência..."
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">{{ old('comment') }}</textarea>
                            @error('comment')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-4">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Publicar Avaliação
                            </button>
                            <a href="{{ route('client.appointments') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Cancelar
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>