<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SIMS') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" style="background-color: #f8fafc; color: #181d26;">
        <div class="min-h-screen flex flex-col">
            <!-- Top bar -->
            <div class="flex items-center justify-between px-6 py-4" style="border-bottom: 1px solid #e0e2e6; background: #fff;">
                <a href="/" class="flex items-center gap-2.5 no-underline">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg text-white text-sm font-bold" style="background-color: #1b61c9;">S</div>
                    <span class="font-semibold text-sm" style="color: #181d26; letter-spacing: 0.08px;">SIMS</span>
                </a>
            </div>

            <!-- Centered auth content -->
            <div class="flex-1 flex flex-col items-center justify-center px-4 py-12">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
