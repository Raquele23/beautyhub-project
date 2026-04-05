<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight" style="color: #6A0DAD; font-family: 'Poppins', sans-serif;">
            {{ __('Meu Perfil') }}
        </h2>
    </x-slot>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @if(Auth::user()->isClient())
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css">
    @endif

    <div class="min-h-screen py-10 px-4 sm:px-6" style="background-color: #EDE4F8; font-family: 'Poppins', sans-serif;">
        <div class="max-w-2xl mx-auto">

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

            {{-- ── PAGE TITLE ── --}}
            <div class="mb-6">
                <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color: #A675D6;">Beauty Hub</p>
                <h1 class="text-3xl font-bold" style="color: #3b0764;">Meu Perfil</h1>
            </div>

            {{-- ── PROFILE CARD ── --}}
            <div class="bg-white rounded-3xl p-8 mb-5 shadow-sm">

                {{-- Avatar + nome --}}
                <div class="flex items-center gap-5 mb-8 pb-8 border-b" style="border-color: #F0E8FC;">

                    {{-- Avatar — apenas para clientes --}}
                    @if(Auth::user()->isClient())
                        <div class="relative flex-shrink-0">
                            <div class="w-20 h-20 rounded-full p-0.5" style="background: linear-gradient(135deg, #6A0DAD, #A675D6);">
                                @if(Auth::user()->profile_photo_path)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}"
                                         alt="Foto de perfil"
                                         class="w-full h-full rounded-full object-cover">
                                @else
                                    <div class="w-full h-full rounded-full flex items-center justify-center" style="background: #E3D0F9;">
                                        <svg class="w-9 h-9" viewBox="0 0 24 24" fill="none" stroke="#A675D6" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            {{-- Botão editar foto --}}
                            <label class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full flex items-center justify-center cursor-pointer border-2 border-white"
                                   style="background: #6A0DAD;"
                                   title="Alterar foto">
                                <svg class="w-3 h-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                                </svg>
                                <input type="file" id="photo-input" class="hidden" accept="image/jpeg,image/png,image/webp">
                            </label>
                        </div>
                    @endif

                    {{-- Nome, email, badge e botão remover foto --}}
                    <div class="flex-1">
                        <h2 class="text-lg font-bold" style="color: #3b0764;">{{ Auth::user()->name }}</h2>
                        <p class="text-sm" style="color: #A675D6;">{{ Auth::user()->email }}</p>

                        {{-- Badge dinâmica baseada no role --}}
                        <span class="inline-flex items-center gap-1 mt-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium"
                              style="background: #E3D0F9; color: #6A0DAD;">
                            {{ Auth::user()->isProfessional() ? 'Profissional' : 'Cliente' }}
                        </span>

                        {{-- Botão remover foto — apenas para clientes --}}
                        @if(Auth::user()->isClient() && Auth::user()->profile_photo_path)
                            <form method="POST" action="{{ route('profile.photo.remove') }}" class="mt-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center gap-1 text-xs font-medium text-red-400 hover:text-red-600 transition-colors duration-150"
                                        onclick="return confirm('Remover foto de perfil?')">
                                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                                    </svg>
                                    Remover foto
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                {{-- Informações pessoais --}}
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest mb-4" style="color: #A675D6;">Informações Pessoais</p>
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- ── PASSWORD CARD ── --}}
            <div class="bg-white rounded-3xl p-8 mb-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-widest mb-4" style="color: #A675D6;">Alterar Senha</p>
                @include('profile.partials.update-password-form')
            </div>

            {{-- ── DELETE CARD ── --}}
            <div class="bg-white rounded-3xl p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-widest mb-4" style="color: #A675D6;">Zona de Risco</p>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 rounded-2xl border border-red-100 bg-red-50 p-4">
                    <div>
                        <p class="text-sm font-semibold text-red-700">Excluir conta</p>
                        <p class="text-xs text-red-400 mt-0.5">Esta ação é permanente e não pode ser desfeita.</p>
                    </div>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>

    {{-- ── Modal do Cropper + Script — apenas para clientes ── --}}
    @if(Auth::user()->isClient())
        <div id="cropper_modal" class="fixed inset-0 z-[80] hidden">
            <div class="absolute inset-0 bg-black/70" id="cropper_backdrop"></div>
            <div class="relative z-10 min-h-full flex items-center justify-center p-4">
                <div class="w-full max-w-lg bg-white rounded-2xl overflow-hidden shadow-2xl">
                    <div class="px-5 py-4 border-b border-purple-100 flex items-center justify-between">
                        <h3 class="text-base font-bold text-purple-800">Ajustar foto de perfil</h3>
                        <button type="button" id="cropper_close" class="text-purple-300 hover:text-purple-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="p-5">
                        <div class="w-full max-h-[55vh] overflow-hidden rounded-xl bg-gray-100">
                            <img id="cropper_image" alt="Imagem para recorte" class="max-w-full block">
                        </div>
                        <p class="mt-3 text-xs text-purple-400">Arraste e ajuste para enquadrar sua foto.</p>
                    </div>
                    <div class="px-5 py-4 border-t border-purple-100 flex justify-end gap-2">
                        <button type="button" id="cropper_cancel"
                                class="px-4 py-2.5 rounded-xl border border-purple-100 text-xs font-semibold text-purple-600 hover:bg-purple-50 transition-colors">
                            Cancelar
                        </button>
                        <button type="button" id="cropper_confirm"
                                class="px-4 py-2.5 rounded-xl text-white text-xs font-semibold shadow-lg shadow-purple-200"
                                style="background-color: #6A0DAD;">
                            Salvar foto
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <form id="photo-form" method="POST" action="{{ route('profile.photo') }}" class="hidden">
            @csrf
            <input type="hidden" name="cropped_photo" id="cropped_photo_input">
        </form>

        <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const photoInput      = document.getElementById('photo-input');
                const cropperModal    = document.getElementById('cropper_modal');
                const cropperImage    = document.getElementById('cropper_image');
                const cropperClose    = document.getElementById('cropper_close');
                const cropperCancel   = document.getElementById('cropper_cancel');
                const cropperConfirm  = document.getElementById('cropper_confirm');
                const cropperBackdrop = document.getElementById('cropper_backdrop');
                const photoForm       = document.getElementById('photo-form');
                const croppedInput    = document.getElementById('cropped_photo_input');

                let cropper = null;

                photoInput.addEventListener('change', function () {
                    const file = this.files && this.files[0];
                    if (!file) return;

                    const objectUrl = URL.createObjectURL(file);
                    cropperImage.src = objectUrl;
                    cropperModal.classList.remove('hidden');

                    if (cropper) { cropper.destroy(); }

                    cropper = new Cropper(cropperImage, {
                        aspectRatio: 1,
                        viewMode: 1,
                        dragMode: 'move',
                        autoCropArea: 1,
                        responsive: true,
                        background: false,
                    });
                });

                function closeCropper() {
                    if (cropper) { cropper.destroy(); cropper = null; }
                    cropperModal.classList.add('hidden');
                    cropperImage.removeAttribute('src');
                    photoInput.value = '';
                }

                cropperConfirm.addEventListener('click', function () {
                    if (!cropper) return;

                    const canvas = cropper.getCroppedCanvas({
                        width: 400,
                        height: 400,
                        imageSmoothingQuality: 'high',
                    });

                    if (!canvas) { closeCropper(); return; }

                    canvas.toBlob(function (blob) {
                        const reader = new FileReader();
                        reader.onloadend = function () {
                            croppedInput.value = reader.result;
                            closeCropper();
                            photoForm.submit();
                        };
                        reader.readAsDataURL(blob);
                    }, 'image/jpeg', 0.92);
                });

                cropperClose.addEventListener('click', closeCropper);
                cropperCancel.addEventListener('click', closeCropper);
                cropperBackdrop.addEventListener('click', closeCropper);
            });
        </script>
    @endif

    <style>
        .min-h-screen, .min-h-screen * {
            font-family: 'Poppins', sans-serif !important;
        }
        .min-h-screen input[type="text"],
        .min-h-screen input[type="email"],
        .min-h-screen input[type="password"] {
            border: 1.5px solid #e0d5f5 !important;
            border-radius: .65rem !important;
            background: #faf7ff !important;
            transition: border-color .2s, box-shadow .2s !important;
        }
        .min-h-screen input[type="text"]:focus,
        .min-h-screen input[type="email"]:focus,
        .min-h-screen input[type="password"]:focus {
            border-color: #A675D6 !important;
            box-shadow: 0 0 0 3px rgba(166,117,214,.18) !important;
            outline: none !important;
        }
        .min-h-screen label {
            font-size: .8rem !important;
            font-weight: 600 !important;
            color: #555 !important;
        }
        .min-h-screen button[type="submit"] {
            background: #6A0DAD !important;
            border-color: #6A0DAD !important;
            border-radius: 999px !important;
            color: #fff !important;
            font-weight: 600 !important;
            font-size: .85rem !important;
            padding: .5rem 1.5rem !important;
            transition: background .2s, transform .15s, box-shadow .2s !important;
        }
        .min-h-screen button[type="submit"]:hover {
            background: #5a0b99 !important;
            box-shadow: 0 4px 16px rgba(106,13,173,.25) !important;
            transform: translateY(-1px) !important;
        }
        .min-h-screen .bg-red-50 button {
            background: #fff0f0 !important;
            color: #c0392b !important;
            border: 1.5px solid #fde0e0 !important;
            border-radius: 999px !important;
        }
        .min-h-screen .bg-red-50 button:hover {
            background: #ffe0e0 !important;
        }
    </style>
</x-app-layout>