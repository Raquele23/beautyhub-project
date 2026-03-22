<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BeautyHub</title>

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

<body class="bg-[url(assets/img/background.png)] bg-cover bg-center flex items-center justify-center min-h-screen">

@if (Route::has('login'))

<div class="flex flex-col items-center">

    <!-- Logo pequena -->
    <img src="{{ asset('assets/img/Beauty_Hub.png') }}"
        class="w-40 mb-6"
        alt="Logo BeautyHub">

    <div class="bg-white/90 shadow-xl rounded-2xl p-10 w-[420px] text-center border border-violet-100">

        <p class="text-2xl font-medium text-gray-700 mb-6 tracking-tight">
            Seja bem-vind@!
        </p>

        <div class="flex flex-col gap-4">

            <a href="{{ route('login') }}"
               class="px-6 py-3 bg-violet-400 rounded-lg text-white font-medium hover:bg-violet-500 transition-all duration-200">
                Entrar
            </a>

            @if (Route::has('register'))
                <a href="{{ route('register') }}"
                   class="px-6 py-3 bg-violet-200 text-violet-800 rounded-lg font-medium hover:bg-violet-300 transition-all duration-200">
                    Cadastro
                </a>
            @endif

        </div>

    </div>

</div>

@endif

</body>
</html>