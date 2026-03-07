<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Perfil Profissional') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Status Messages -->
            @if ($message = Session::get('status'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:bg-green-900 dark:border-green-700 dark:text-green-200">
                    {{ $message }}
                </div>
            @endif

            @if ($message = Session::get('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg dark:bg-red-900 dark:border-red-700 dark:text-red-200">
                    {{ $message }}
                </div>
            @endif

            <!-- Formulário de Edição -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Informações Pessoais</h3>
                    <form action="{{ route('professional.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Nome do Profissional -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Seu Nome') }}
                            </label>
                            <input type="text" name="name" id="name" value="{{ Auth::user()->name }}" readonly
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                        </div>

                        <!-- Nome do Estabelecimento (Opcional) -->
                        <div>
                            <label for="establishment_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Nome do Estabelecimento (Opcional)') }}
                            </label>
                            <input type="text" name="establishment_name" id="establishment_name"
                                value="{{ old('establishment_name', $professional->establishment_name) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600"
                                placeholder="Ex: Salão de Beleza XYZ">
                            @error('establishment_name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Descrição -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Descrição') }}
                            </label>
                            <textarea name="description" id="description" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600"
                                placeholder="Fale sobre você e seus serviços">{{ old('description', $professional->description) }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Telefone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Telefone') }}
                            </label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone', $professional->phone) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600"
                                placeholder="(11) 99999-9999">
                            @error('phone')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Endereço -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Estado (UF)') }}
                                </label>
                                <input type="text" name="state" id="state" value="{{ old('state', $professional->state) }}" maxlength="2"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600"
                                    placeholder="SP">
                                @error('state')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Cidade') }}
                                </label>
                                <input type="text" name="city" id="city" value="{{ old('city', $professional->city) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600"
                                    placeholder="São Paulo">
                                @error('city')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="street" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Rua') }}
                                </label>
                                <input type="text" name="street" id="street" value="{{ old('street', $professional->street) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600"
                                    placeholder="Rua das Flores">
                                @error('street')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="house_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Número') }}
                                </label>
                                <input type="text" name="house_number" id="house_number" value="{{ old('house_number', $professional->house_number) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600"
                                    placeholder="123">
                                @error('house_number')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Instagram -->
                        <div>
                            <label for="instagram" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Instagram (Opcional)') }}
                            </label>
                            <input type="text" name="instagram" id="instagram" value="{{ old('instagram', $professional->instagram) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600"
                                placeholder="@seu_instagram">
                            @error('instagram')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Foto de Perfil -->
                        <div>
                            <label for="profile_photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Foto de Perfil') }}
                            </label>
                            @if ($professional->profile_photo)
                                <div class="mt-2 mb-4">
                                    <img src="{{ Storage::url($professional->profile_photo) }}" alt="Foto de perfil"
                                        class="h-32 w-32 object-cover rounded-lg">
                                </div>
                            @endif
                            <input type="file" name="profile_photo" id="profile_photo" accept="image/*"
                                class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-indigo-50 file:text-indigo-700
                                    hover:file:bg-indigo-100
                                    dark:file:bg-indigo-900 dark:file:text-indigo-200">
                            @error('profile_photo')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Botão Salvar -->
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Salvar Alterações') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Portfólio -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Portfólio de Fotos</h3>
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $professional->portfolioPhotos()->count() }}/10 fotos</span>
                    </div>

                    @if ($professional->portfolioPhotos()->count() < 10)
                        <form action="{{ route('professional.portfolio.add') }}" method="POST" enctype="multipart/form-data" class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            @csrf
                            <div class="mb-4">
                                <label for="photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Adicionar Foto ao Portfólio') }}
                                </label>
                                <input type="file" name="photo" id="photo" accept="image/*" required
                                    class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-indigo-50 file:text-indigo-700
                                        hover:file:bg-indigo-100
                                        dark:file:bg-indigo-900 dark:file:text-indigo-200">
                            </div>

                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Descrição (Opcional)') }}
                                </label>
                                <input type="text" name="description" id="description"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600"
                                    placeholder="Descreva este trabalho">
                            </div>

                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Adicionar Foto') }}
                            </button>
                        </form>
                    @endif

                    <!-- Galeria de Fotos -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse ($professional->portfolioPhotos as $photo)
                            <div class="relative group">
                                <img src="{{ Storage::url($photo->photo) }}" alt="Portfolio photo"
                                    class="h-48 w-full object-cover rounded-lg shadow-md">
                                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                    <form action="{{ route('professional.portfolio.delete', $photo) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Tem certeza que deseja deletar esta foto?')"
                                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                                            {{ __('Deletar') }}
                                        </button>
                                    </form>
                                </div>
                                @if ($photo->description)
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $photo->description }}</p>
                                @endif
                            </div>
                        @empty
                            <p class="col-span-3 text-center text-gray-500 dark:text-gray-400">
                                {{ __('Nenhuma foto no portfólio ainda') }}
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
