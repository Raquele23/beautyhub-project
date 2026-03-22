<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <style>
        /* Aplicando Poppins globalmente */
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
    <body class="bg-[url(build/assets/img/background.png)] bg-cover bg-center flex items-center justify-center min-h-screen">
            
        <div class="bg-violet-50 rounded-lg p-10">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Nome')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Senha')" />
                    <x-text-input id="password" class="block mt-1 w-full"
                                    type="password"
                                    name="password"
                                    required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirmar Senha')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Role -->
                <div class="mt-4">
                    <x-input-label :value="__('Quero me cadastrar como')" />
                    <div class="mt-2 grid grid-cols-2 gap-3">

                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="client"
                                class="peer sr-only"
                                {{ old('role', 'client') === 'client' ? 'checked' : '' }}>
                            <div class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 text-center transition
                                        peer-checked:border-purple-600 peer-checked:bg-purple-50 dark:peer-checked:bg-purple-900/20
                                        hover:border-purple-300">
                                <span class="text-2xl">💅</span>
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-50">Cliente</span>
                                <span class="text-xs text-gray-800 dark:text-gray-800">Quero agendar serviços</span>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="professional"
                                class="peer sr-only"
                                {{ old('role') === 'professional' ? 'checked' : '' }}>
                            <div class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 text-center transition
                                        peer-checked:border-purple-600 peer-checked:bg-purple-50 dark:peer-checked:bg-purple-900/20
                                        hover:border-purple-300">
                                <span class="text-2xl">✂️</span>
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-700">Profissional</span>
                                <span class="text-xs text-gray-800 dark:text-gray-800">Quero oferecer serviços</span>
                            </div>
                        </label>

                    </div>
                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-6">
                    <a class="underline text-sm text-violet-600 dark:text-violet-400 hover:text-violet-900 dark:hover:text-violet-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                        {{ __('Já tem conta?') }}
                    </a>

                    <x-primary-button class="ms-4">
                        {{ __('Cadastrar') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </body>
</html>