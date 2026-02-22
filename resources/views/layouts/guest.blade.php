<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'NexTask') }}</title>
        <link rel="icon" href="{{ asset('NexTask_favicon.png') }}?v=3" type="image/png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark')
            } else {
                document.documentElement.classList.remove('dark')
            }
        </script>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-cyan-50 dark:bg-slate-900 transition-colors duration-300">
            <div>
                <a href="/">
                    <img src="{{ asset('NexTask_favicon.png') }}" class="w-20 h-20 drop-shadow-md transition-transform duration-300 hover:scale-105" alt="NexTask Logo">
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-slate-800 shadow-xl overflow-hidden sm:rounded-2xl transition-colors duration-300 border border-slate-100 dark:border-slate-700/50">
                {{ $slot }}
            </div>
        </div>
        
    </body>
</html>
