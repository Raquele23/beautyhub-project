<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Criar Perfil Profissional') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('professional.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Nome do Profissional -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Seu Nome') }}
                            </label>
                            <input type="text" name="name" id="name" value="{{ Auth::user()->name }}" readonly
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                        </div>

                        <!-- Nome do Estabelecimento (Opcional) -->
                        <div>
                            <label for="establishment_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Nome do Estabelecimento (Opcional)') }}
                            </label>
                            <input type="text" name="establishment_name" id="establishment_name"
                                value="{{ old('establishment_name') }}"
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
                                placeholder="Fale sobre você e seus serviços">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Telefone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Telefone') }}
                            </label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
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
                                <input type="text" name="state" id="state" value="{{ old('state') }}" maxlength="2"
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
                                <input type="text" name="city" id="city" value="{{ old('city') }}"
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
                                <input type="text" name="street" id="street" value="{{ old('street') }}"
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
                                <input type="text" name="house_number" id="house_number" value="{{ old('house_number') }}"
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
                            <input type="text" name="instagram" id="instagram" value="{{ old('instagram') }}"
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

                        <!-- Portfólio de Fotos (Opcional) -->
                        <div>
                            <label for="portfolio_photos" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Adicionar Fotos do Portfólio (Máximo 10)') }}
                            </label>
                            <input type="file" name="portfolio_photos[]" id="portfolio_photos" accept="image/*" multiple
                                class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-indigo-50 file:text-indigo-700
                                    hover:file:bg-indigo-100
                                    dark:file:bg-indigo-900 dark:file:text-indigo-200">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Você pode selecionar até 10 imagens</p>
                            @error('portfolio_photos')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Conclusão de Agendamentos -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Conclusão de Agendamentos
                            </label>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                                Como seus atendimentos devem ser marcados como concluídos?
                            </p>
                            <div class="space-y-3">
                                <label class="flex items-start gap-4 p-4 border rounded-lg cursor-pointer border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20" id="label-manual">
                                    <input type="radio" name="auto_complete" value="0" checked
                                        class="mt-0.5 text-indigo-600 focus:ring-indigo-500"
                                        onchange="document.getElementById('label-manual').classList.add('border-indigo-500','bg-indigo-50','dark:bg-indigo-900/20'); document.getElementById('label-auto').classList.remove('border-indigo-500','bg-indigo-50','dark:bg-indigo-900/20'); document.getElementById('label-auto').classList.add('border-gray-200','dark:border-gray-700');">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Manual</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                            Você marca o atendimento como concluído manualmente após realizá-lo.
                                        </p>
                                    </div>
                                </label>

                                <label class="flex items-start gap-4 p-4 border rounded-lg cursor-pointer border-gray-200 dark:border-gray-700" id="label-auto">
                                    <input type="radio" name="auto_complete" value="1"
                                        class="mt-0.5 text-indigo-600 focus:ring-indigo-500"
                                        onchange="document.getElementById('label-auto').classList.add('border-indigo-500','bg-indigo-50','dark:bg-indigo-900/20'); document.getElementById('label-manual').classList.remove('border-indigo-500','bg-indigo-50','dark:bg-indigo-900/20'); document.getElementById('label-manual').classList.add('border-gray-200','dark:border-gray-700');">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Automático</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                            O sistema marca automaticamente como concluído após o horário do agendamento somado à duração do serviço.
                                        </p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="flex gap-4">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Criar Perfil') }}
                            </button>
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Cancelar') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
