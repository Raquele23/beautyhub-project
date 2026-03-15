<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Criar Perfil Profissional') }}
        </h2>
    </x-slot>

    <div class="min-h-screen py-12" style="background-image: url('{{ ('/assets/img/background.png') }}'); background-size: cover; background-position: center;">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <!-- Logo -->
            <div class="flex justify-center mb-6">
                <div class="text-white text-5xl font-serif font-bold tracking-widest drop-shadow-lg">
                    BH
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl rounded-3xl">
                <div class="p-8">
                    <h3 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Criar Perfil Profissional</h3>

                    <form action="{{ route('professional.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf

                        <!-- Nome do Profissional -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-600 mb-1">
                                {{ __('Seu Nome') }}
                            </label>
                            <input type="text" name="name" id="name" value="{{ Auth::user()->name }}" readonly
                                class="block w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400">
                        </div>

                        <!-- Nome do Estabelecimento -->
                        <div>
                            <label for="establishment_name" class="block text-sm font-medium text-gray-600 mb-1">
                                {{ __('Nome do Estabelecimento (Opcional)') }}
                            </label>
                            <input type="text" name="establishment_name" id="establishment_name"
                                value="{{ old('establishment_name') }}"
                                class="block w-full rounded-xl border border-gray-200 px-4 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400"
                                placeholder="Ex: Salão de Beleza XYZ">
                            @error('establishment_name')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Descrição -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-600 mb-1">
                                {{ __('Descrição') }}
                            </label>
                            <textarea name="description" id="description" rows="4"
                                class="block w-full rounded-xl border border-gray-200 px-4 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400"
                                placeholder="Fale sobre você e seus serviços">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Telefone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-600 mb-1">
                                {{ __('Telefone') }}
                            </label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                                class="block w-full rounded-xl border border-gray-200 px-4 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400"
                                placeholder="(11) 99999-9999">
                            @error('phone')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Estado e Cidade -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-600 mb-1">{{ __('Estado (UF)') }}</label>
                                <input type="text" name="state" id="state" value="{{ old('state') }}" maxlength="2"
                                    class="block w-full rounded-xl border border-gray-200 px-4 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400"
                                    placeholder="SP">
                                @error('state')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-600 mb-1">{{ __('Cidade') }}</label>
                                <input type="text" name="city" id="city" value="{{ old('city') }}"
                                    class="block w-full rounded-xl border border-gray-200 px-4 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400"
                                    placeholder="São Paulo">
                                @error('city')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <!-- Rua e Número -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="street" class="block text-sm font-medium text-gray-600 mb-1">{{ __('Rua') }}</label>
                                <input type="text" name="street" id="street" value="{{ old('street') }}"
                                    class="block w-full rounded-xl border border-gray-200 px-4 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400"
                                    placeholder="Rua das Flores">
                                @error('street')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="house_number" class="block text-sm font-medium text-gray-600 mb-1">{{ __('Número') }}</label>
                                <input type="text" name="house_number" id="house_number" value="{{ old('house_number') }}"
                                    class="block w-full rounded-xl border border-gray-200 px-4 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400"
                                    placeholder="123">
                                @error('house_number')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <!-- Instagram -->
                        <div>
                            <label for="instagram" class="block text-sm font-medium text-gray-600 mb-1">
                                {{ __('Instagram (Opcional)') }}
                            </label>
                            <input type="text" name="instagram" id="instagram" value="{{ old('instagram') }}"
                                class="block w-full rounded-xl border border-gray-200 px-4 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-400"
                                placeholder="@seu_instagram">
                            @error('instagram')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <!-- Foto de Perfil -->
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">{{ __('Foto de Perfil') }}</label>
                            <input type="file" name="profile_photo" id="profile_photo" accept="image/*"
                                class="block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-xl file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-purple-100 file:text-purple-700
                                    hover:file:bg-purple-200 cursor-pointer">
                            @error('profile_photo')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <!-- Portfólio -->
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">
                                {{ __('Adicionar Fotos do Portfólio (Máximo 10)') }}
                            </label>
                            <input type="file" name="portfolio_photos[]" id="portfolio_photos" accept="image/*" multiple
                                class="block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-xl file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-purple-100 file:text-purple-700
                                    hover:file:bg-purple-200 cursor-pointer">
                            <p class="mt-1 text-xs text-gray-400">Você pode selecionar até 10 imagens</p>
                            @error('portfolio_photos')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <!-- Botões -->
                        <div class="flex gap-4 pt-2">
                            <button type="submit"
                                class="flex-1 py-3 rounded-xl font-semibold text-white transition duration-200"
                                style="background: linear-gradient(135deg, #c084fc, #a855f7);">
                                {{ __('Criar Perfil') }}
                            </button>
                            <a href="{{ route('dashboard') }}"
                                class="flex-1 py-3 rounded-xl font-semibold text-center text-gray-600 bg-gray-100 hover:bg-gray-200 transition duration-200">
                                {{ __('Cancelar') }}
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
