<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Minhas Avaliações') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if(session('success'))
                <div class="p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-xl text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            @forelse ($reviews as $review)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">

                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full overflow-hidden bg-yellow-100 dark:bg-yellow-900 flex-shrink-0 flex items-center justify-center">
                                    @if ($review->appointment->professional->profile_photo)
                                        <img src="{{ Storage::url($review->appointment->professional->profile_photo) }}"
                                             alt="" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-sm font-bold text-yellow-600 dark:text-yellow-300">
                                            {{ strtoupper(substr($review->appointment->professional->user->name, 0, 1)) }}
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800 dark:text-gray-200">
                                        {{ $review->appointment->professional->establishment_name ?? $review->appointment->professional->user->name }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $review->appointment->service->name }} · {{ $review->appointment->scheduled_at->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 flex-shrink-0">
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
                            <div class="mt-3 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border-l-4 border-indigo-400">
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">
                                    Resposta do profissional
                                    <span class="font-normal normal-case ml-1">· {{ $review->replied_at->diffForHumans() }}</span>
                                </p>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $review->professional_reply }}</p>
                            </div>
                        @endif

                        @if ($review->created_at->diffInHours(now()) <= 24)
                            <div class="mt-4 flex justify-end">
                                <form method="POST" action="{{ route('reviews.destroy', $review) }}"
                                      onsubmit="return confirm('Tem certeza que deseja excluir esta avaliação?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-xs text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition">
                                        Excluir avaliação
                                    </button>
                                </form>
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
                        <p class="text-sm">Você ainda não fez nenhuma avaliação.</p>
                    </div>
                </div>
            @endforelse

            @if ($reviews->hasPages())
                <div>{{ $reviews->links() }}</div>
            @endif

        </div>
    </div>
</x-app-layout>