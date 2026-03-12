<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BeautyHub</title>

    <!-- Tailwind -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-[url(/assets/img/background.png)] bg-cover flex items-center justify-center min-h-screen">

@if (Route::has('login'))

<div class="flex flex-col items-center">

    <!-- Logo pequena -->
    <img src="{{ asset('assets/img/Beauty_Hub.png') }}"
         class="w-40 mb-6"
         alt="Logo BeautyHub">

    <!-- Card -->
    <div class="bg-violet-50 shadow-xl rounded-xl p-10 w-[420px] text-center">

        <p class="text-2xl font-semibold mb-6">
            Seja bem vind@!
        </p>

        <div class="flex flex-col gap-4">

            <a href="{{ route('login') }}"
               class="px-6 py-3 bg-violet-400 rounded-lg text-white font-medium hover:bg-violet-500 transition">
                Entrar
            </a>

            @if (Route::has('register'))
                <a href="{{ route('register') }}"
                   class="px-6 py-3 bg-violet-300 rounded-lg font-medium hover:bg-violet-400 transition">
                    Cadastro
                </a>
            @endif

        </div>

    </div>

</div>

@endif

</body>
</html>