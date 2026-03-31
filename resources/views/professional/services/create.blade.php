<x-app-layout>

    <style>
        @import url('https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css');
    </style>

    <div class="max-w-2xl mx-auto px-4 sm:px-8 py-10 space-y-6">

        {{-- ── Topo ── --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold tracking-widest uppercase text-purple-400">Beauty Hub</p>
                <h1 class="text-2xl font-bold text-purple-800 mt-0.5">Novo Serviço</h1>
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

        <form action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

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
                        <option value="{{ $key }}" @selected(old('category') === $key)>{{ $label }}</option>
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
                <input type="text" name="name" id="name" value="{{ old('name') }}"
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
                          class="w-full px-4 py-3 rounded-xl border border-purple-100 bg-white text-sm text-gray-800 placeholder-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent shadow-sm resize-none">{{ old('description') }}</textarea>
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
                    <input type="hidden" name="duration" id="duration" value="{{ old('duration') }}">
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
                    <p class="mt-2 text-xs text-purple-300">Informe em horas e minutos (mínimo 5 minutos e máximo 12 horas)</p>
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
                        <input type="hidden" name="price" id="price" value="{{ old('price') }}">
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
            <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-6">
                <label class="block text-xs font-bold text-purple-400 uppercase tracking-wide mb-1">
                    Imagem do serviço
                    <span class="normal-case font-normal text-purple-300 ml-1">(opcional)</span>
                </label>
                <p class="text-xs text-purple-300 mb-4">Foto que aparecerá na listagem de serviços.</p>

                <div class="grid grid-cols-[minmax(0,1fr)_auto] gap-4 items-start">
                                          <label class="mt-6 flex flex-col items-center justify-center gap-2 w-full h-20 rounded-xl border-2 border-dashed border-purple-100 cursor-pointer hover:border-purple-300 hover:bg-purple-50/50 transition-all duration-200"
                           id="service-upload-label">
                        <svg class="w-6 h-6 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-xs text-purple-400 font-medium text-center px-2">Clique para selecionar e recortar</span>
                        <input type="file" name="image" id="service_image_input" accept="image/*" class="sr-only"
                               data-crop-target="service"
                               data-preview-wrapper="service_preview_container"
                               data-preview-img="service_preview_img"
                               data-hidden-input="service_cropped_image"
                               data-original-hidden-input="service_original_image_base64">
                    </label>

                    <div class="w-20 flex-shrink-0">
                        <p class="text-[11px] font-semibold text-purple-300 mb-2 text-center">Prévia</p>
                        <div id="service_preview_container" class="relative w-20 h-20 rounded-xl overflow-hidden border border-purple-100" style="background-color: #EDE4F8;">
                            <img id="service_preview_img" class="w-full h-full object-cover hidden" alt="Prévia da imagem recortada">
                            <div id="service_preview_placeholder" class="absolute inset-0 flex items-center justify-center text-[10px] font-semibold text-purple-300 text-center px-2">
                                Sem foto
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3 flex items-center gap-3">
                    <button type="button"
                            id="service_recrop_button"
                            data-recrop-input="service_image_input"
                            class="hidden text-xs font-semibold text-purple-600 hover:text-purple-800 transition-colors">
                        Recortar novamente
                    </button>
                    <button type="button" id="service_remove_preview"
                            class="hidden text-xs font-semibold text-red-500 hover:text-red-700 transition-colors">
                        Remover foto
                    </button>
                </div>

                <input type="hidden" name="cropped_image" id="service_cropped_image">
                <input type="hidden" name="original_image_base64" id="service_original_image_base64">

                @error('image')
                    <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── Botão ── --}}
            <button type="submit"
                    class="w-full py-3 text-white text-sm font-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5 shadow-lg shadow-purple-200"
                    style="background-color: #6A0DAD;">
                Criar Serviço
            </button>

        </form>
    </div>

    <div id="cropper_modal" class="fixed inset-0 z-[80] hidden">
        <div class="absolute inset-0 bg-black/70" id="cropper_backdrop"></div>
        <div class="relative z-10 min-h-full flex items-center justify-center p-4">
            <div class="w-full max-w-3xl bg-white rounded-2xl overflow-hidden shadow-2xl">
                <div class="px-5 py-4 border-b border-purple-100 flex items-center justify-between">
                    <h3 class="text-base font-bold text-purple-800">Ajustar recorte 4:5</h3>
                    <button type="button" id="cropper_close" class="text-purple-300 hover:text-purple-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="p-5">
                    <div class="w-full max-h-[65vh] overflow-hidden rounded-xl bg-gray-100">
                        <img id="cropper_image" alt="Imagem para recorte" class="max-w-full block">
                    </div>
                    <p class="mt-3 text-xs text-purple-400">Arraste e ajuste a área para escolher o corte da foto.</p>
                </div>
                <div class="px-5 py-4 border-t border-purple-100 flex justify-end gap-2">
                    <button type="button" id="cropper_cancel" class="px-4 py-2.5 rounded-xl border border-purple-100 text-xs font-semibold text-purple-600 hover:bg-purple-50 transition-colors">
                        Cancelar
                    </button>
                    <button type="button" id="cropper_confirm" class="px-4 py-2.5 rounded-xl text-white text-xs font-semibold shadow-lg shadow-purple-200" style="background-color: #6A0DAD;">
                        Usar recorte
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form[action="{{ route('services.store') }}"]');
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

            const cropperModal = document.getElementById('cropper_modal');
            const cropperImage = document.getElementById('cropper_image');
            const cropperClose = document.getElementById('cropper_close');
            const cropperCancel = document.getElementById('cropper_cancel');
            const cropperConfirm = document.getElementById('cropper_confirm');
            const cropperBackdrop = document.getElementById('cropper_backdrop');

            let cropper = null;
            let activeInput = null;
            const selectedFiles = new window.Map();
            const selectedOriginalBase64 = new window.Map();

            function blobToDataUrl(blob) {
                return new window.Promise(function (resolve, reject) {
                    const reader = new window.FileReader();
                    reader.onloadend = function () { resolve(reader.result); };
                    reader.onerror = reject;
                    reader.readAsDataURL(blob);
                });
            }

            function setOriginalBase64ForInput(input, dataUrl) {
                const hiddenOriginalId = input.dataset.originalHiddenInput;
                if (!hiddenOriginalId || !dataUrl) {
                    return;
                }

                selectedOriginalBase64.set(input.id, dataUrl);
                const hiddenOriginalInput = document.getElementById(hiddenOriginalId);
                if (hiddenOriginalInput) {
                    hiddenOriginalInput.value = dataUrl;
                }
            }

            function openCropper(file, input) {
                if (!file) {
                    return;
                }

                activeInput = input;
                const objectUrl = URL.createObjectURL(file);
                cropperImage.src = objectUrl;
                cropperModal.classList.remove('hidden');

                if (cropper) {
                    cropper.destroy();
                }

                cropper = new window.Cropper(cropperImage, {
                    aspectRatio: 4 / 5,
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 1,
                    responsive: true,
                    background: false,
                });
            }

            function closeCropper(clearPendingSelection) {
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }

                cropperModal.classList.add('hidden');
                cropperImage.removeAttribute('src');

                if (clearPendingSelection && activeInput) {
                    activeInput.value = '';
                }

                activeInput = null;
            }

            document.querySelectorAll('input[data-crop-target]').forEach(function (input) {
                input.addEventListener('change', async function (event) {
                    const file = event.target.files && event.target.files[0] ? event.target.files[0] : null;
                    if (file) {
                        selectedFiles.set(input.id, file);
                        try {
                            const originalBase64 = await blobToDataUrl(file);
                            setOriginalBase64ForInput(input, originalBase64);
                        } catch (error) {
                            console.error('Falha ao preparar foto original para envio:', error);
                        }
                    }
                    openCropper(file, input);
                });
            });

            document.querySelectorAll('[data-recrop-input]').forEach(function (button) {
                button.addEventListener('click', function () {
                    const inputId = button.dataset.recropInput;
                    const input = document.getElementById(inputId);

                    if (!input) {
                        return;
                    }

                    let fileToUse = selectedFiles.get(input.id);

                    if (!fileToUse && input.files && input.files[0]) {
                        fileToUse = input.files[0];
                        selectedFiles.set(input.id, fileToUse);
                    }

                    if (fileToUse) {
                        openCropper(fileToUse, input);
                        return;
                    }

                    const originalSrc = input.dataset.originalSrc;
                    if (originalSrc) {
                        fetch(originalSrc, { cache: 'no-store' })
                            .then(response => response.blob())
                            .then(async blob => {
                                const originalBase64 = await blobToDataUrl(blob);
                                setOriginalBase64ForInput(input, originalBase64);
                                openCropper(blob, input);
                            })
                            .catch(error => {
                                console.error('Falha ao carregar imagem original:', error);
                                input.click();
                            });
                        return;
                    }

                    input.click();
                });
            });

            function handleCropConfirm() {
                if (!cropper || !activeInput) {
                    closeCropper(true);
                    return;
                }

                const canvas = cropper.getCroppedCanvas({
                    width: 1200,
                    height: 1500,
                    imageSmoothingQuality: 'high',
                });

                if (!canvas) {
                    closeCropper(true);
                    return;
                }

                const hiddenInputId = activeInput.dataset.hiddenInput;
                const previewImgId = activeInput.dataset.previewImg;

                canvas.toBlob(function (blob) {
                    if (!blob) {
                        closeCropper(true);
                        return;
                    }

                    const reader = new window.FileReader();
                    reader.onloadend = function () {
                        const hiddenInput = document.getElementById(hiddenInputId);
                        if (hiddenInput) {
                            hiddenInput.value = reader.result;
                        }

                        const fallbackOriginalBase64 = selectedOriginalBase64.get(activeInput.id);
                        if (fallbackOriginalBase64) {
                            setOriginalBase64ForInput(activeInput, fallbackOriginalBase64);
                        }

                        const previewImg = document.getElementById(previewImgId);
                        if (previewImg) {
                            previewImg.src = URL.createObjectURL(blob);
                        }

                        const placeholder = document.getElementById('service_preview_placeholder');
                        const recropButton = document.getElementById('service_recrop_button');
                        const removeButton = document.getElementById('service_remove_preview');

                        if (previewImg) {
                            previewImg.classList.remove('hidden');
                        }
                        if (placeholder) {
                            placeholder.classList.add('hidden');
                        }
                        if (recropButton) {
                            recropButton.classList.remove('hidden');
                        }
                        if (removeButton) {
                            removeButton.classList.remove('hidden');
                        }

                        activeInput.value = '';
                        closeCropper(false);
                    };

                    reader.readAsDataURL(blob);
                }, 'image/jpeg', 0.92);
            }

            cropperConfirm.addEventListener('click', handleCropConfirm);
            cropperClose.addEventListener('click', function () { closeCropper(true); });
            cropperCancel.addEventListener('click', function () { closeCropper(true); });
            cropperBackdrop.addEventListener('click', function () { closeCropper(true); });

            const removePreview = document.getElementById('service_remove_preview');
            if (removePreview) {
                removePreview.addEventListener('click', function () {
                    const hiddenInput = document.getElementById('service_cropped_image');
                    const hiddenOriginalInput = document.getElementById('service_original_image_base64');
                    const previewImg = document.getElementById('service_preview_img');
                    const placeholder = document.getElementById('service_preview_placeholder');
                    const recropButton = document.getElementById('service_recrop_button');

                    if (hiddenInput) {
                        hiddenInput.value = '';
                    }
                    if (hiddenOriginalInput) {
                        hiddenOriginalInput.value = '';
                    }
                    if (previewImg) {
                        previewImg.removeAttribute('src');
                        previewImg.classList.add('hidden');
                    }
                    if (placeholder) {
                        placeholder.classList.remove('hidden');
                    }
                    if (recropButton) {
                        recropButton.classList.add('hidden');
                    }
                    if (removePreview) {
                        removePreview.classList.add('hidden');
                    }

                    selectedFiles.delete('service_image_input');
                    selectedOriginalBase64.delete('service_image_input');
                });
            }
        });
    </script>

</x-app-layout>