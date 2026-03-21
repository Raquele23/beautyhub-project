<x-app-layout>

    <div class="max-w-2xl mx-auto px-4 sm:px-8 py-10 space-y-6">

        {{-- ── Topo ── --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold tracking-widest uppercase text-purple-400">Beauty Hub</p>
                <h1 class="text-2xl font-bold text-purple-800 mt-0.5">Portfólio</h1>
            </div>
            <a href="{{ url()->previous() }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-purple-700 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
               style="background-color: #E3D0F9;">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
                Voltar
            </a>
        </div>

        {{-- ── Toast ── --}}
        @if(Session::get('status'))
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
                <p class="text-sm font-medium text-gray-800">{{ Session::get('status') }}</p>
                <button @click="show = false" class="ml-2 text-purple-300 hover:text-purple-500 transition-colors flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- ── Adicionar foto ── --}}
        @if($professional->portfolioPhotos()->count() < 10)
        <div id="form-adicionar" class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-purple-50">
                <p class="text-sm font-bold text-purple-400 uppercase tracking-wide">Adicionar foto</p>
                <span class="text-xs font-semibold px-3 py-1 rounded-full"
                      style="background-color: #EDE4F8; color: #6A0DAD;">
                    {{ $professional->portfolioPhotos()->count() }}/10
                </span>
            </div>

            <div id="form-adicionar" class="p-6" x-data="{ preview: null }">
                <form action="{{ route('professional.portfolio.add') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <label class="flex flex-col items-center justify-center gap-3 w-full h-36 rounded-xl border-2 border-dashed border-purple-100 cursor-pointer hover:border-purple-300 hover:bg-purple-50/50 transition-all duration-200"
                           x-show="!preview">
                        <svg class="w-7 h-7 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-xs text-purple-400 font-medium">Clique para selecionar uma foto</span>
                        <input type="file" name="photo" accept="image/*" class="sr-only" required
                               @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">
                    </label>

                    <div x-show="preview" class="relative w-full h-36 rounded-xl overflow-hidden">
                        <img :src="preview" class="w-full h-full object-cover">
                        <button type="button" @click="preview = null"
                                class="absolute top-2 right-2 w-7 h-7 rounded-full bg-white shadow-md flex items-center justify-center text-red-400 hover:text-red-600 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <input type="text" name="description"
                           placeholder="Descrição (opcional)"
                           class="w-full px-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 placeholder-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm">

                    <button type="submit"
                            class="w-full py-2.5 text-white text-xs font-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5 shadow-lg shadow-purple-200"
                            style="background-color: #6A0DAD;">
                        Adicionar foto
                    </button>
                </form>
            </div>
        </div>
        @else
        <div class="flex items-center gap-3 px-5 py-4 bg-white border border-purple-100 rounded-2xl shadow-sm">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color: #EDE4F8;">
                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-sm text-gray-600">Limite de <span class="font-semibold text-purple-700">10 fotos</span> atingido. Exclua uma para adicionar outra.</p>
        </div>
        @endif

        {{-- ── Grid de fotos ── --}}
        <div class="bg-white rounded-2xl border border-purple-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-purple-50">
                <p class="text-sm font-bold text-purple-400 uppercase tracking-wide">Suas fotos</p>
                @if($professional->portfolioPhotos()->count() < 10)
                <a href="#adicionar" onclick="document.getElementById('form-adicionar').scrollIntoView({behavior:'smooth'}); return false;"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-purple-600 hover:text-purple-800 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Adicionar
                </a>
                @endif
            </div>

            <div class="p-6">
                @if($professional->portfolioPhotos->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($professional->portfolioPhotos as $photo)
                    <div class="group relative">
                        <div class="rounded-xl overflow-hidden aspect-square">
                            <img src="{{ Storage::url($photo->photo) }}"
                                 alt="{{ $photo->description ?? '' }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        {{-- Overlay de deletar --}}
                        <div class="absolute inset-0 rounded-xl bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                            <form action="{{ route('professional.portfolio.delete', $photo) }}" method="POST"
                                  onsubmit="return confirm('Tem certeza que deseja excluir esta foto?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-white text-xs font-semibold text-red-500 rounded-xl hover:bg-red-50 transition-colors shadow-lg">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Excluir
                                </button>
                            </form>
                        </div>
                        @if($photo->description)
                            <p class="mt-1 text-xs text-purple-300 truncate">{{ $photo->description }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4" style="background-color: #EDE4F8;">
                        <svg class="w-6 h-6 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-800">Nenhuma foto ainda.</p>
                    <p class="text-xs text-purple-400 mt-1">Adicione fotos dos seus trabalhos para atrair mais clientes.</p>
                </div>
                @endif
            </div>
        </div>

    </div>

</x-app-layout>