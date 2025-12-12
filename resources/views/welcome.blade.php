<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans">
        <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
            <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
                <div class="">
                    <div class="flex flex-col justify-center">
                        <x-img_ewe_logo class="h-40" />
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="text-black hover:text-black/70 focus:outline-no text-right" >
                            anmelden
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
