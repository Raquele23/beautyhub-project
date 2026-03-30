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
    <body class="bg-[url(assets/img/background.png)] bg-cover bg-center flex items-center justify-center min-h-screen px-4 py-10">

        <!-- Botão voltar -->
        <a href="{{ url('/') }}" class="absolute top-8 left-8 flex items-center gap-1 text-sm text-[#6A0DAD] hover:opacity-80 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Voltar
        </a>

        <div class="w-full flex flex-col items-center">

            <div class="w-full max-w-md lg:max-w-lg bg-violet-50 rounded-xl shadow-md p-8 md:p-10">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Nome')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        <div class="mt-1 min-h-[14px]">
                            <x-input-error :messages="$errors->get('name')" />
                        </div>
                    </div>

                    <!-- Email Address -->
                    <div class="mt-2">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                        <div class="mt-1 min-h-[14px]">
                            <x-input-error :messages="$errors->get('email')" />
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mt-2">
                        <x-input-label for="password" :value="__('Senha')" />
                        <x-text-input id="password" class="block mt-1 w-full"
                                        type="password"
                                        name="password"
                                        required autocomplete="new-password" />
                        <div class="mt-1 min-h-[14px]">
                            <x-input-error :messages="$errors->get('password')" />
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mt-2">
                        <x-input-label for="password_confirmation" :value="__('Confirmar Senha')" />
                        <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                        type="password"
                                        name="password_confirmation" required autocomplete="new-password" />
                        <div class="mt-1 min-h-[14px]">
                            <x-input-error :messages="$errors->get('password_confirmation')" />
                        </div>
                    </div>

                    <!-- Role -->
                    <div class="mt-2">
                        <x-input-label :value="__('Quero me cadastrar como')" />
                        <div class="mt-2 flex items-stretch rounded-lg overflow-hidden border border-purple-200 bg-violet-50">
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="role" value="client" class="peer sr-only" {{ old('role', 'client') === 'client' ? 'checked' : '' }}>
                                <div class="h-full min-h-20 flex flex-col items-center justify-center text-center py-3 px-2 text-sm font-medium text-gray-600 transition
                                            hover:bg-purple-50
                                            peer-checked:bg-[#A675D6] peer-checked:text-white peer-checked:hover:bg-[#A675D6]">
                                    <div>Cliente</div>
                                    <div class="text-[10px] font-normal opacity-80 mt-1">Quero agendar serviços</div>
                                </div>
                            </label>
                            <label class="flex-1 cursor-pointer border-l border-purple-200">
                                <input type="radio" name="role" value="professional" class="peer sr-only" {{ old('role') === 'professional' ? 'checked' : '' }}>
                                <div class="h-full min-h-20 flex flex-col items-center justify-center text-center py-3 px-2 text-sm font-medium text-gray-600 transition
                                            hover:bg-purple-50
                                            peer-checked:bg-[#A675D6] peer-checked:text-white peer-checked:hover:bg-[#A675D6]">
                                    <div>Profissional</div>
                                    <div class="text-[10px] font-normal opacity-80 mt-1">Quero oferecer serviços</div>
                                </div>
                            </label>
                        </div>
                        <div class="mt-1 min-h-[14px]">
                            <x-input-error :messages="$errors->get('role')" />
                        </div>
                    </div>

                    <!-- Botão cadastrar e link -->
                    <div class="mt-3">
                        <x-primary-button class="w-full justify-center">
                            {{ __('Cadastrar') }}
                        </x-primary-button>

                        <div class="mt-3 text-center">
                            <a class="underline text-sm text-[#6A0DAD] hover:opacity-80 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                                {{ __('Já tem conta?') }}
                            </a>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </body>
</html>