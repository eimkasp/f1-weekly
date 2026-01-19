<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'F1 Weekly') }} - Sign In</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-white antialiased bg-black">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-black bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-gray-900 via-black to-black">
            <div>
                <a href="/" class="flex flex-col items-center gap-2">
                    <div class="w-16 h-16 bg-red-600 rounded-2xl flex items-center justify-center shadow-[0_0_20px_rgba(220,38,38,0.5)]">
                        <span class="text-white font-bold text-3xl">F1</span>
                    </div>
                    <span class="text-xl font-bold text-white tracking-widest uppercase">Weekly</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-8 px-8 py-8 bg-gray-900 border border-gray-800 shadow-2xl overflow-hidden sm:rounded-2xl ring-1 ring-white/5">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
