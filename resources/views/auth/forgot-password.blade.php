<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeautyHub</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-[url(assets/img/background.png)] bg-cover bg-center flex items-center justify-center min-h-screen px-4 py-10">

    <a href="{{ route('login') }}" class="absolute top-8 left-8 flex items-center gap-1 text-sm text-[#6A0DAD] hover:opacity-80 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Voltar
    </a>

    <div class="w-full flex flex-col items-center">
        <div class="w-full max-w-lg bg-violet-50 rounded-xl shadow-md p-8 md:p-10">
            <div class="mb-4 text-sm text-gray-600">
                Esqueceu sua senha? Informe seu email e enviaremos um link para redefinir.
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                    <div class="mt-1 min-h-[14px]">
                        <x-input-error :messages="$errors->get('email')" />
                    </div>
                </div>

                <div class="mt-3">
                    <x-primary-button class="w-full justify-center">
                        {{ __('Enviar link de redefinição') }}
                    </x-primary-button>

                </div>
            </form>
        </div>
    </div>
</body>
</html>
