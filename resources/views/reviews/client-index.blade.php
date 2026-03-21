<x-app-layout>

    <div class="max-w-4xl mx-auto px-4 sm:px-8 pt-10 pb-4">
        <div>
            <p class="text-xs font-bold tracking-widest uppercase text-purple-400">Beauty Hub</p>
            <h1 class="text-2xl font-bold text-purple-800 mt-0.5">Minhas Avaliações</h1>
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

    <div class="max-w-4xl mx-auto px-4 sm:px-8 pt-4 pb-10 space-y-4">

        @forelse ($reviews as $review)
        <div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden">
            <div class="p-5">

                {{-- Cabeçalho: profissional + estrelas --}}
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl overflow-hidden flex-shrink-0" style="background-color: #EDE4F8;">
                            @if ($review->appointment->professional->profile_photo)
                                <img src="{{ Storage::url($review->appointment->professional->profile_photo) }}"
                                     alt="" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-base font-bold text-purple-400">
                                    {{ strtoupper(substr($review->appointment->professional->user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">
                                {{ $review->appointment->professional->establishment_name ?? $review->appointment->professional->user->name }}
                            </p>
                            <p class="text-xs text-purple-400 mt-0.5">
                                {{ $review->appointment->service->name }}
                                <span class="text-purple-300 mx-1">·</span>
                                {{ $review->appointment->scheduled_at->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col items-end gap-1 flex-shrink-0">
                        <x-star-rating :rating="$review->rating" size="sm" />
                        <span class="text-xs text-purple-300">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                {{-- Comentário --}}
                @if ($review->comment)
                    <p class="text-sm text-gray-600 leading-relaxed mb-3">{{ $review->comment }}</p>
                @endif

                {{-- Resposta do profissional --}}
                @if ($review->hasReply())
                    <div class="mt-3 p-3 rounded-xl border-l-4 border-purple-300" style="background-color: #F7F0FD;">
                        <p class="text-xs font-bold text-purple-400 uppercase tracking-wide mb-1">
                            Resposta do profissional
                            <span class="font-normal normal-case ml-1 text-purple-300">· {{ $review->replied_at->diffForHumans() }}</span>
                        </p>
                        <p class="text-sm text-gray-600">{{ $review->professional_reply }}</p>
                    </div>
                @endif

                {{-- Excluir (dentro de 24h) --}}
                @if ($review->created_at->diffInHours(now()) <= 24)
                    <div class="mt-4 flex justify-end">
                        <form method="POST" action="{{ route('reviews.destroy', $review) }}"
                              onsubmit="return confirm('Tem certeza que deseja excluir esta avaliação?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-red-400 hover:text-red-600 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Excluir avaliação
                            </button>
                        </form>
                    </div>
                @endif

            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl border border-purple-100 shadow-sm">
            <div class="flex flex-col items-center justify-center py-14 text-center">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4" style="background-color: #EDE4F8;">
                    <svg class="w-6 h-6 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-800">Você ainda não fez nenhuma avaliação.</p>
                <p class="text-xs text-purple-400 mt-1">Após um atendimento, você poderá avaliar o profissional.</p>
            </div>
        </div>
        @endforelse

        @if ($reviews->hasPages())
            <div>{{ $reviews->links() }}</div>
        @endif

    </div>

</x-app-layout>