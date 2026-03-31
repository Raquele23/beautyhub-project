<x-app-layout>

    <div class="max-w-4xl mx-auto px-4 sm:px-8 pt-10 pb-4">
        <div>
            <p class="text-xs font-bold tracking-widest uppercase text-purple-400">Beauty Hub</p>
            <h1 class="text-2xl font-bold text-purple-800 mt-0.5">Avaliações Recebidas</h1>
        </div>
    </div>

    {{-- ── Toast ── --}}
    @if(session('success'))
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 5000)"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            class="fixed top-20 left-1/2 -translate-x-1/2 z-50 flex items-center gap-3 bg-white border border-purple-200 shadow-xl shadow-purple-100 rounded-2xl px-5 py-4 w-max max-w-sm">
            <div class="w-8 h-8 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-purple-800">{{ session('success') }}</p>
            <button @click="show = false" class="ml-2 text-purple-300 hover:text-purple-600 transition-colors flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    <div class="max-w-4xl mx-auto px-4 sm:px-8 pt-4 pb-10 space-y-4">
        <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-thin-soft">
            <a href="{{ route('reviews.professional.index', ['tab' => 'all']) }}"
               class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-semibold transition-all duration-200 {{ $activeTab === 'all' ? 'bg-purple-700 text-white shadow-lg shadow-purple-200' : 'bg-white text-purple-500 border border-purple-100 hover:border-purple-300' }}">
                Todos
                @if($totalReviewsCount)
                    <span class="w-5 h-5 flex items-center justify-center rounded-full text-xs font-bold flex-shrink-0 {{ $activeTab === 'all' ? 'bg-white/20 text-white' : 'bg-purple-100 text-purple-600' }}">
                        {{ $totalReviewsCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('reviews.professional.index', ['tab' => 'pending']) }}"
               class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-semibold transition-all duration-200 {{ $activeTab === 'pending' ? 'bg-purple-700 text-white shadow-lg shadow-purple-200' : 'bg-white text-purple-500 border border-purple-100 hover:border-purple-300' }}">
                Sem resposta
                @if($pendingRepliesCount)
                    <span class="w-5 h-5 flex items-center justify-center rounded-full text-xs font-bold flex-shrink-0 {{ $activeTab === 'pending' ? 'bg-white/20 text-white' : 'bg-purple-100 text-purple-600' }}">
                        {{ $pendingRepliesCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('reviews.professional.index', ['tab' => 'replied']) }}"
               class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-semibold transition-all duration-200 {{ $activeTab === 'replied' ? 'bg-purple-700 text-white shadow-lg shadow-purple-200' : 'bg-white text-purple-500 border border-purple-100 hover:border-purple-300' }}">
                Respondidas
                @if($repliedCount)
                    <span class="w-5 h-5 flex items-center justify-center rounded-full text-xs font-bold flex-shrink-0 {{ $activeTab === 'replied' ? 'bg-white/20 text-white' : 'bg-purple-100 text-purple-600' }}">
                        {{ $repliedCount }}
                    </span>
                @endif
            </a>
        </div>

        @if ($totalReviewsCount > 0)
        <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-6 flex items-center gap-6">

            {{-- Nota geral --}}
            <div class="text-center flex-shrink-0">
                <p class="text-5xl font-bold text-purple-800">
                    {{ number_format(auth()->user()->average_rating, 1) }}
                </p>
                <x-star-rating :rating="auth()->user()->average_rating" size="md" />
                <p class="text-xs text-purple-400 mt-1.5">
                    {{ $totalReviewsCount }} {{ $totalReviewsCount === 1 ? 'avaliação' : 'avaliações' }}
                </p>
            </div>

            <div class="w-px self-stretch" style="background-color: #EDE4F8;"></div>

            {{-- Barras --}}
            <div class="flex-1 space-y-1.5">
                @for ($star = 5; $star >= 1; $star--)
                    @php
                        $count = $starCounts[$star] ?? 0;
                        $pct   = $totalReviewsCount > 0 ? ($count / $totalReviewsCount) * 100 : 0;
                    @endphp
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <span class="w-3 text-right font-medium">{{ $star }}</span>
                        <svg class="w-3 h-3 text-yellow-400 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/>
                        </svg>
                        <div class="flex-1 rounded-full h-1.5" style="background-color: #EDE4F8;">
                            <div class="h-1.5 rounded-full" style="width: {{ $pct }}%; background-color: #9B4DCA;"></div>
                        </div>
                        <span class="w-4 text-right text-purple-300">{{ $count }}</span>
                    </div>
                @endfor
            </div>
        </div>
        @endif

        {{-- Lista de avaliações --}}
        @forelse ($reviews as $review)
        <div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden">
            <div class="p-5">

                {{-- Cabeçalho: cliente + estrelas --}}
                <div class="flex items-start justify-between gap-4 mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 text-sm font-bold text-purple-500"
                             style="background-color: #EDE4F8;">
                            {{ strtoupper(substr($review->client->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ $review->client->name }}</p>
                            <p class="text-xs text-purple-400 mt-1.5">
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

                {{-- Resposta existente --}}
                @if ($review->hasReply())
                    <div class="mt-3 p-3 rounded-xl border-l-4 border-purple-300" style="background-color: #F7F0FD;">
                        <p class="text-xs font-bold text-purple-400 uppercase tracking-wide mb-1">
                            Sua resposta
                            <span class="font-normal normal-case ml-1 text-purple-300">· {{ $review->replied_at->diffForHumans() }}</span>
                        </p>
                        <p class="text-sm text-gray-600">{{ $review->professional_reply }}</p>
                    </div>

                {{-- Formulário de resposta --}}
                @else
                    <div x-data="{ open: false }" class="mt-3">
                        <button type="button" @click="open = !open"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-purple-600 hover:text-purple-800 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                            </svg>
                            <span x-text="open ? 'Cancelar' : 'Responder'"></span>
                        </button>

                        <div x-show="open" x-transition class="mt-3 space-y-3">
                            <form method="POST" action="{{ route('reviews.reply', $review) }}">
                                @csrf
                                @method('PATCH')
                                <textarea name="professional_reply" rows="3" maxlength="500"
                                    placeholder="Escreva sua resposta..."
                                    class="block w-full rounded-xl border border-purple-100 bg-purple-50 text-sm text-gray-700 placeholder-purple-300 px-4 py-3 focus:outline-none focus:ring-2 focus:border-transparent transition"
                                    required></textarea>
                                <div class="flex justify-end mt-2">
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 text-white text-xs font-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5 shadow-lg shadow-purple-200"
                                            style="background-color: #6A0DAD;">
                                        Publicar resposta
                                    </button>
                                </div>
                            </form>
                        </div>
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
                @if($activeTab === 'pending')
                    <p class="text-sm font-medium text-gray-800">Você não tem avaliações sem resposta.</p>
                    <p class="text-xs text-purple-400 mt-1">Quando um cliente avaliar, você poderá responder por aqui.</p>
                @elseif($activeTab === 'replied')
                    <p class="text-sm font-medium text-gray-800">Você ainda não respondeu nenhuma avaliação.</p>
                    <p class="text-xs text-purple-400 mt-1">As avaliações respondidas aparecerão nesta aba.</p>
                @else
                    <p class="text-sm font-medium text-gray-800">Você ainda não recebeu nenhuma avaliação.</p>
                    <p class="text-xs text-purple-400 mt-1">As avaliações dos clientes aparecerão aqui.</p>
                @endif
            </div>
        </div>
        @endforelse

        @if ($reviews->hasPages())
            <div>{{ $reviews->links() }}</div>
        @endif

    </div>

</x-app-layout>