<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ __('Acesso negado') }} - {{ config('app.name') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center px-4 py-8 bg-gradient-to-br from-santacasa-dark to-santacasa">
            <div class="w-full max-w-md bg-white shadow-2xl overflow-hidden rounded-lg text-center">
                <div class="px-8 py-10">
                    <div class="mb-6 flex flex-col items-center">
                        <x-application-logo class="w-32 h-auto" />
                    </div>

                    <p class="text-santacasa font-semibold text-sm tracking-widest uppercase">{{ __('Erro 403') }}</p>
                    <h1 class="mt-2 text-xl font-semibold text-gray-900">{{ __('Acesso negado') }}</h1>
                    <p class="mt-3 text-sm text-gray-600">
                        {{ __('Você não tem permissão para acessar esta página. Se acredita que isso é um engano, procure o administrador do sistema.') }}
                    </p>

                    <a href="{{ route('dashboard') }}" class="mt-6 inline-flex items-center px-4 py-2 bg-santacasa border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-santacasa-dark focus:outline-none focus:ring-2 focus:ring-santacasa-light focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Voltar ao início') }}
                    </a>
                </div>
            </div>

            <p class="mt-8 text-white/80 text-xs font-medium tracking-wide">
                &copy; {{ date('Y') }} Santa Casa de Misericórdia de Porto Alegre
            </p>
        </div>
    </body>
</html>
