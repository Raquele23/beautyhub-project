<x-app-layout>

    <div class="max-w-2xl mx-auto px-4 sm:px-8 pt-10 pb-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold tracking-widest uppercase text-purple-400">Beauty Hub</p>
                <h1 class="text-2xl font-bold text-purple-800 mt-0.5">Avaliar Atendimento</h1>
            </div>
            <a href="{{ route('client.appointments') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-purple-700 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
               style="background-color: #E3D0F9;">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
                Cancelar
            </a>
        </div>
    </div>

    {{-- ── Toast ── --}}
    @if(session('success'))
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 5000)"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-4"
            class="fixed bottom-6 right-6 z-50 flex items-center gap-3 bg-white border border-purple-100 shadow-xl shadow-purple-100 rounded-2xl px-5 py-4 max-w-sm">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color: #E0F7F4;">
                <svg class="w-4 h-4" style="color: #0D9488;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-800">{{ session('success') }}</p>
            <button @click="show = false" class="ml-2 text-purple-300 hover:text-purple-500 transition-colors flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    <div class="max-w-2xl mx-auto px-4 sm:px-8 pt-4 pb-10 space-y-6">

        {{-- Card de contexto do agendamento --}}
        <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color: #EDE4F8;">
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs text-purple-400 font-medium mb-0.5">Você está avaliando</p>
                <p class="text-sm font-semibold text-gray-900 truncate">
                    {{ $appointment->professional->establishment_name ?? $appointment->professional->user->name }}
                </p>
                <p class="text-xs text-gray-500 mt-0.5">
                    {{ $appointment->service->name }}
                    <span class="text-purple-300 mx-1">·</span>
                    {{ $appointment->scheduled_at->format('d/m/Y \à\s H:i') }}
                </p>
            </div>
        </div>

        {{-- Formulário --}}
        <div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden">
            <form action="{{ route('reviews.store', $appointment) }}" method="POST" class="px-6 pt-1 pb-6 space-y-5">
                @csrf

                <p class="text-sm font-bold text-purple-400 uppercase tracking-wide mb-1">Sua avaliação</p>

                {{-- Nota --}}
                <div>
                    <label class="block text-xs font-bold text-purple-400 uppercase tracking-wide mb-3">
                        Nota <span class="text-red-400">*</span>
                    </label>
                    <x-star-picker name="rating" :value="old('rating', 0)" />
                    @error('rating')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Comentário --}}
                <div>
                    <label for="comment" class="block text-xs font-bold text-purple-400 uppercase tracking-wide mb-3">
                        Comentário
                        <span class="text-purple-300 font-normal normal-case ml-1">(opcional)</span>
                    </label>
                    <textarea name="comment" id="comment" rows="4" maxlength="1000"
                        placeholder="Descreva sua experiência..."
                        class="block w-full rounded-xl border border-purple-100 bg-purple-50 text-sm text-gray-700 placeholder-purple-300 px-4 py-3 focus:outline-none focus:ring-2 focus:border-transparent transition"
                        style="focus-ring-color: #6A0DAD;">{{ old('comment') }}</textarea>
                    @error('comment')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Botão --}}
                <div class="flex justify-end pt-2">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 text-white text-xs font-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5 shadow-lg shadow-purple-200"
                            style="background-color: #6A0DAD;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        Publicar avaliação
                    </button>
                </div>

            </form>
        </div>

    </div>

</x-app-layout>