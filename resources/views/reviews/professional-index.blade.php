<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Avaliações Recebidas') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-xl text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Resumo --}}
            @if ($reviews->total() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex items-center gap-6">
                        <div class="text-center">
                            <p class="text-4xl font-bold text-gray-900 dark:text-white">
                                {{ number_format(auth()->user()->average_rating, 1) }}
                            </p>
                            <x-star-rating :rating="auth()->user()->average_rating" size="md" />
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                {{ $reviews->total() }} {{ Str::plural('avaliação', $reviews->total()) }}
                            </p>
                        </div>

                        <div class="flex-1 space-y-1.5">
                            @for ($star = 5; $star >= 1; $star--)
                                @php
                                    $count = $starCounts[$star] ?? 0;
                                    $pct   = $reviews->total() > 0 ? ($count / $reviews->total()) * 100 : 0;
                                @endphp
                                <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                    <span class="w-3 text-right">{{ $star }}</span>
                                    <svg class="w-3.5 h-3.5 text-yellow-400 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                        <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/>
                                    </svg>
                                    <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <span class="w-5 text-right">{{ $count }}</span>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            @endif

            {{-- Lista --}}
            @forelse ($reviews as $review)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">

                        <div class="flex items-start justify-between gap-4 mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                                    <span class="text-sm font-semibold text-gray-600 dark:text-gray-300">
                                        {{ strtoupper(substr($review->client->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="font-semibold text-sm text-gray-800 dark:text-gray-200">
                                        {{ $review->client->name }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $review->appointment->service->name }} · {{ $review->appointment->scheduled_at->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 flex-shrink-0">
                                <x-star-rating :rating="$review->rating" size="sm" />
                                <span class="text-xs text-gray-400 dark:text-gray-500">
                                    {{ $review->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>

                        @if ($review->comment)
                            <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">
                                {{ $review->comment }}
                            </p>
                        @endif

                        @if ($review->hasReply())
                            <div class="mt-3 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border-l-4 border-purple-400">
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">
                                    Sua resposta
                                    <span class="font-normal normal-case ml-1">· {{ $review->replied_at->diffForHumans() }}</span>
                                </p>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $review->professional_reply }}</p>
                            </div>
                        @else
                            <div x-data="{ open: false }" class="mt-4">
                                <button type="button" @click="open = !open"
                                        class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 transition">
                                    <span x-text="open ? 'Cancelar' : 'Responder'"></span>
                                </button>

                                <div x-show="open" x-transition class="mt-3">
                                    <form method="POST" action="{{ route('reviews.reply', $review) }}" class="space-y-3">
                                        @csrf
                                        @method('PATCH')
                                        <textarea name="professional_reply" rows="3" maxlength="500"
                                            placeholder="Escreva sua resposta..."
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 text-sm"
                                            required></textarea>
                                        <div class="flex justify-end">
                                            <button type="submit"
                                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
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
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 flex flex-col items-center justify-center text-gray-400 dark:text-gray-600">
                        <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        <p class="text-sm">Você ainda não recebeu nenhuma avaliação.</p>
                    </div>
                </div>
            @endforelse

            @if ($reviews->hasPages())
                <div>{{ $reviews->links() }}</div>
            @endif

        </div>
    </div>
</x-app-layout>