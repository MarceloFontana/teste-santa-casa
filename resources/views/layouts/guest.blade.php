<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center px-4 py-8 bg-gradient-to-br from-santacasa-dark to-santacasa">
            <div class="w-full max-w-md bg-white shadow-2xl overflow-hidden rounded-lg">
                <div class="px-8 py-10">
                    <div class="mb-6 flex flex-col items-center">
                        <a href="/">
                            <x-application-logo class="w-40 h-auto" />
                        </a>
                        <p class="mt-3 text-sm text-gray-500">{{ __('Módulo de Acessos') }}</p>
                    </div>

                    {{ $slot }}
                </div>
            </div>

            <p class="mt-8 text-white/80 text-xs font-medium tracking-wide">
                &copy; {{ date('Y') }} Santa Casa de Misericórdia de Porto Alegre
            </p>
        </div>
    </body>
</html>
