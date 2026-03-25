<x-app-layout>

    <div class="max-w-2xl mx-auto px-4 sm:px-8 py-10 space-y-6">

        {{-- ── Topo ── --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold tracking-widest uppercase text-purple-400">Beauty Hub</p>
                <h1 class="text-2xl font-bold text-purple-800 mt-0.5">Editar Serviço</h1>
            </div>
            <a href="{{ route('services.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-purple-700 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
               style="background-color: #E3D0F9;">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
                Cancelar
            </a>
        </div>

        {{-- ── Erros ── --}}
        @if($errors->any())
            <div class="flex items-center gap-3 px-5 py-4 bg-white border border-red-100 rounded-2xl shadow-sm">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 bg-red-50">
                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-red-500">{{ $errors->first() }}</p>
            </div>
        @endif

        <form action="{{ route('services.update', $service) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PATCH')

            {{-- ── Categoria/Tipo ── --}}
            <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-6">
                <label for="category" class="block text-xs font-bold text-purple-400 uppercase tracking-wide mb-3">
                    Tipo do serviço <span class="text-red-400">*</span>
                </label>
                <select name="category" id="category"
                        class="w-full px-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm"
                        required>
                    <option value="">Selecione uma categoria</option>
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}" @selected(old('category', $service->category) === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('category')
                    <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── Nome ── --}}
            <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-6">
                <label for="name" class="block text-xs font-bold text-purple-400 uppercase tracking-wide mb-3">
                    Nome do serviço <span class="text-red-400">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $service->name) }}"
                       placeholder="Ex: Corte de Cabelo"
                       class="w-full px-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 placeholder-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm"
                       required>
                @error('name')
                    <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── Descrição ── --}}
            <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-6">
                <label for="description" class="block text-xs font-bold text-purple-400 uppercase tracking-wide mb-1">
                    Descrição
                    <span class="normal-case font-normal text-purple-300 ml-1">(opcional)</span>
                </label>
                <p class="text-xs text-purple-300 mb-3">Descreva os detalhes do seu serviço.</p>
                <textarea name="description" id="description" rows="3"
                          placeholder="Ex: Inclui lavagem, corte e finalização..."
                          class="w-full px-4 py-3 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 placeholder-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm resize-none">{{ old('description', $service->description) }}</textarea>
                @error('description')
                    <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── Duração + Preço ── --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-6">
                    <label for="duration" class="block text-xs font-bold text-purple-400 uppercase tracking-wide mb-3">
                        Duração <span class="text-red-400">*</span>
                    </label>
                    <input type="hidden" name="duration" id="duration" value="{{ old('duration', $service->duration) }}">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="relative">
                            <input type="number" id="duration_hours" value="0" min="0" max="12"
                                   class="w-full px-4 py-2.5 pr-10 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm"
                                   placeholder="0">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-purple-300">h</span>
                        </div>
                        <div class="relative">
                            <input type="number" id="duration_minutes" value="0" min="0" max="59"
                                   class="w-full px-4 py-2.5 pr-10 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm"
                                   placeholder="0">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-purple-300">min</span>
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-purple-300">Informe em horas e minutos (mínimo 15 minutos)</p>
                    @error('duration')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-6">
                    <label for="price" class="block text-xs font-bold text-purple-400 uppercase tracking-wide mb-3">
                        Preço <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs font-semibold text-purple-300">R$</span>
                        <input type="hidden" name="price" id="price" value="{{ old('price', $service->price) }}">
                        <input type="text" id="price_display" value=""
                               placeholder="0,00"
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 placeholder-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm"
                               required>
                    </div>
                    @error('price')
                        <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- ── Imagem ── --}}
            <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-6"
                 x-data="{ preview: {{ $service->image ? "'".Storage::url($service->image)."'" : 'null' }}, existing: {{ $service->image ? 'true' : 'false' }} }">
                <label class="block text-xs font-bold text-purple-400 uppercase tracking-wide mb-1">
                    Imagem do serviço
                    <span class="normal-case font-normal text-purple-300 ml-1">(opcional)</span>
                </label>
                <p class="text-xs text-purple-300 mb-4">Foto que aparecerá na listagem de serviços.</p>

                {{-- Preview / upload area --}}
                <label class="flex flex-col items-center justify-center gap-3 w-full h-36 rounded-xl border-2 border-dashed border-purple-100 cursor-pointer hover:border-purple-300 hover:bg-purple-50/50 transition-all duration-200"
                       x-show="!preview">
                    <svg class="w-7 h-7 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-xs text-purple-400 font-medium">Clique para selecionar uma imagem</span>
                    <input type="file" name="image" id="image" accept="image/*" class="sr-only"
                           @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null; existing = false">
                </label>

                <div x-show="preview" class="relative w-full h-36 rounded-xl overflow-hidden">
                    <img :src="preview" class="w-full h-full object-cover">
                    <button type="button" @click="preview = null; existing = false"
                            class="absolute top-2 right-2 w-7 h-7 rounded-full bg-white shadow-md flex items-center justify-center text-red-400 hover:text-red-600 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                @error('image')
                    <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── Botão ── --}}
            <button type="submit"
                    class="w-full py-3 text-white text-sm font-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5 shadow-lg shadow-purple-200"
                    style="background-color: #6A0DAD;">
                Salvar Alterações
            </button>

        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form[action="{{ route('services.update', $service) }}"]');
            const priceHidden = document.getElementById('price');
            const priceDisplay = document.getElementById('price_display');
            const durationHidden = document.getElementById('duration');
            const durationHours = document.getElementById('duration_hours');
            const durationMinutes = document.getElementById('duration_minutes');

            function toDigits(value) {
                return String(value || '').replace(/\D/g, '');
            }

            function formatCurrencyFromDigits(digits) {
                if (!digits) {
                    return '';
                }

                const amount = Number(digits) / 100;
                return amount.toLocaleString('pt-BR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                });
            }

            function updatePriceHidden() {
                const digits = toDigits(priceDisplay.value).slice(0, 9);
                priceDisplay.value = formatCurrencyFromDigits(digits);

                if (!digits) {
                    priceHidden.value = '';
                    return;
                }

                priceHidden.value = (Number(digits) / 100).toFixed(2);
            }

            function updateDurationHidden() {
                const hours = Math.max(0, parseInt(durationHours.value || '0', 10) || 0);
                const minutes = Math.max(0, Math.min(59, parseInt(durationMinutes.value || '0', 10) || 0));
                durationHours.value = String(hours);
                durationMinutes.value = String(minutes);

                durationHidden.value = String((hours * 60) + minutes);
            }

            function fillDurationFromTotal(totalMinutes) {
                const total = Math.max(0, parseInt(totalMinutes || '0', 10) || 0);
                durationHours.value = String(Math.floor(total / 60));
                durationMinutes.value = String(total % 60);
                durationHidden.value = String(total);
            }

            if (priceDisplay && priceHidden) {
                priceDisplay.value = formatCurrencyFromDigits(toDigits(priceHidden.value));
                priceDisplay.addEventListener('input', updatePriceHidden);
            }

            if (durationHidden && durationHours && durationMinutes) {
                fillDurationFromTotal(durationHidden.value);
                durationHours.addEventListener('input', updateDurationHidden);
                durationMinutes.addEventListener('input', updateDurationHidden);
            }

            if (form) {
                form.addEventListener('submit', function () {
                    updatePriceHidden();
                    updateDurationHidden();
                });
            }
        });
    </script>

</x-app-layout>