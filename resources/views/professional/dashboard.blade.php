<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="min-h-screen px-8 py-12">
        <div class="max-w-5xl mx-auto">

            <!-- Logo -->
            <div class="mb-8 text-center">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white">BP</h1>
            </div>

            <!-- Boas-vindas -->
            <div class="mb-8 text-center">
                <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">Bem-vind@</p>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">{{ auth()->user()->name }}</h2>
            </div>

            <!-- Botão de agendamentos -->
            <div class="mb-10 text-center">
                <a href="{{ route('professional.appointments') }}"
                   class="px-6 py-3 font-semibold rounded-lg bg-purple-600 text-white hover:bg-purple-700 transition">
                    ✓ Verificar agendamentos para hoje
                </a>
            </div>

            <!-- Menu principal em grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <a href="{{ route('professional.edit') }}" class="flex items-center justify-center p-6 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    <span class="text-xl mr-2">✏️</span>
                    <span class="text-gray-800 dark:text-gray-200">Editar perfil</span>
                </a>

                <a href="{{ route('professional.show') }}" class="flex items-center justify-center p-6 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    <span class="text-xl mr-2">📊</span>
                    <span class="text-gray-800 dark:text-gray-200">Dados da minha loja</span>
                </a>

                <a href="#avaliacoes" class="flex items-center justify-center p-6 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    <span class="text-xl mr-2">⭐</span>
                    <span class="text-gray-800 dark:text-gray-200">Minhas avaliações</span>
                </a>
            </div>

            <!-- Outros -->
            <div class="border-t border-gray-300 dark:border-gray-700 pt-8">
                <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-6">Outros</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <a href="#" class="p-6 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <span class="block text-lg mb-2">⭐</span>
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Beauty hub plus</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Conheça os benefícios</p>
                    </a>

                    <a href="#" class="p-6 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <span class="block text-lg mb-2">💳</span>
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Confirmar novos meios de pagamentos</p>
                    </a>

                    <a href="#" class="p-6 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <span class="block text-lg mb-2">⬆️</span>
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Dê um upgrade no seu estabelecimento</p>
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>