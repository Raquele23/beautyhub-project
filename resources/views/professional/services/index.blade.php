<x-app-layout>

    <div class="max-w-4xl mx-auto px-4 sm:px-8 py-10 space-y-6">

        {{-- ── Topo ── --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold tracking-widest uppercase text-purple-400">Beauty Hub</p>
                <h1 class="text-2xl font-bold text-purple-800 mt-0.5">Meus Serviços</h1>
            </div>
            <a href="{{ route('services.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-white text-xs font-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5 shadow-lg shadow-purple-200"
               style="background-color: #6A0DAD;">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Novo Serviço
            </a>
        </div>

        {{-- ── Toast ── --}}
        @if(Session::get('status'))
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
                <p class="text-sm font-semibold text-purple-800">{{ Session::get('status') }}</p>
                <button @click="show = false" class="ml-2 text-purple-300 hover:text-purple-600 transition-colors flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- ── Lista de Serviços ── --}}
        @if($services->count() > 0)
        <div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden">
            @foreach($services as $service)
            <div class="flex items-center gap-4 px-6 py-4 border-b border-purple-50 last:border-0">

                {{-- Imagem --}}
                <div class="w-14 h-14 rounded-xl overflow-hidden flex-shrink-0" style="background-color: #EDE4F8;">
                    @if($service->image)
                        <img src="{{ Storage::url($service->image) }}"
                             alt="{{ $service->name }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $service->name }}</p>
                    @if($service->description)
                        <p class="text-xs text-purple-300 mt-0.5 truncate">{{ Str::limit($service->description, 60) }}</p>
                    @endif
                    <div class="flex items-center gap-3 mt-1">
                        <span class="text-xs font-bold text-purple-700">R$ {{ number_format($service->price, 2, ',', '.') }}</span>
                        <span class="text-xs text-purple-300">⏱ {{ $service->duration_formatted }}</span>
                    </div>
                </div>

                {{-- Ações --}}
                <div class="flex items-center gap-2 flex-shrink-0">
                    <a href="{{ route('services.edit', $service) }}"
                       class="inline-flex items-center gap-1 px-3 py-2 text-xs font-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5"
                       style="background-color: #EDE4F8; color: #6A0DAD;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </a>
                    <form method="POST" action="{{ route('services.destroy', $service) }}"
                          onsubmit="return confirm('Tem certeza que deseja excluir este serviço?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-1 px-3 py-2 text-xs font-semibold rounded-xl bg-red-50 text-red-400 hover:bg-red-100 transition-all duration-200 hover:-translate-y-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Excluir
                        </button>
                    </form>
                </div>

            </div>
            @endforeach
        </div>

        @if($services->hasPages())
            <div>{{ $services->links() }}</div>
        @endif

        @else

        {{-- Empty state --}}
        <div class="bg-white rounded-2xl border border-purple-100 shadow-sm">
            <div class="flex flex-col items-center justify-center py-14 text-center">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4" style="background-color: #EDE4F8;">
                    <svg class="w-6 h-6 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-800">Nenhum serviço cadastrado ainda.</p>
                <p class="text-xs text-purple-400 mt-1 mb-5">Adicione seus serviços para que clientes possam agendar.</p>
                <a href="{{ route('services.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 text-white text-xs font-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5 shadow-lg shadow-purple-200"
                   style="background-color: #6A0DAD;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Criar primeiro serviço
                </a>
            </div>
        </div>

        @endif

    </div>

</x-app-layout>