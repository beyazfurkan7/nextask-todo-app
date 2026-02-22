<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>NexTask - Elevate Your Productivity</title>
        
        <link rel="icon" href="{{ asset('NexTask_favicon.png') }}?v=3" type="image/png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark')
            } else {
                document.documentElement.classList.remove('dark')
            }
        </script>
    </head>
    <body class="antialiased bg-cyan-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-colors duration-300 selection:bg-cyan-500 selection:text-white font-sans">
        
        <div class="min-h-screen flex flex-col relative overflow-hidden">
            
            <nav class="w-full p-4 flex justify-between items-center max-w-6xl mx-auto relative z-10 absolute top-0 left-0 right-0">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('NexTask_favicon.png') }}" class="w-8 h-8 drop-shadow-sm" alt="NexTask Logo">
                    <span class="font-extrabold text-xl tracking-tight text-cyan-600 dark:text-cyan-400">NexTask</span>
                </div>
                <div>
                    @if (Route::has('login'))
                        <div class="flex items-center gap-3 text-sm">
                            
                            <button onclick="toggleTheme()" class="p-2 mr-2 text-slate-500 hover:text-cyan-600 dark:text-slate-400 dark:hover:text-cyan-400 focus:outline-none transition-colors duration-200 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800" aria-label="Toggle Dark Mode">
                                <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                            </button>

                            @auth
                                <a href="{{ url('/dashboard') }}" class="font-semibold text-slate-600 hover:text-cyan-600 dark:text-slate-300 dark:hover:text-cyan-400 transition">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="font-semibold text-slate-600 hover:text-cyan-600 dark:text-slate-300 dark:hover:text-cyan-400 transition py-2">Log In</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="font-semibold bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg shadow-md shadow-cyan-200/50 dark:shadow-none transition-all duration-200 hover:-translate-y-0.5">Register</a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </nav>

            <main class="w-full max-w-6xl mx-auto p-4 sm:p-8 relative z-10 pt-28 lg:pt-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-4 items-center w-full">
                    
                    <div class="flex flex-col gap-4 text-center lg:text-left order-2 lg:order-1 mt-4 lg:mt-0">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-100 dark:bg-cyan-900/30 text-cyan-700 dark:text-cyan-300 text-xs font-bold w-fit mx-auto lg:mx-0 border border-cyan-200 dark:border-cyan-800/50">
                            <span class="flex w-2 h-2 rounded-full bg-cyan-500 dark:bg-cyan-400 animate-pulse"></span>
                            Version 1.0 is Live
                        </div>
                        
                        <h1 class="text-3xl lg:text-5xl font-extrabold tracking-tight text-slate-900 dark:text-white leading-[1.1]">
                            Master your day with <br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-teal-500 dark:from-cyan-400 dark:to-teal-400">NexTask.</span>
                        </h1>
                        
                        <p class="text-base text-slate-600 dark:text-slate-400 max-w-xl mx-auto lg:mx-0 leading-relaxed font-medium">
                            The ultimate tool to organize your workflow, prioritize what truly matters, and achieve your goals faster. Simple enough for personal use, powerful enough for your biggest projects.
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start mt-2">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="bg-cyan-500 hover:bg-cyan-600 text-white text-base font-bold py-3 px-6 rounded-xl shadow-lg shadow-cyan-200/50 dark:shadow-none transition-all duration-200 transform hover:-translate-y-1">Go to Dashboard &rarr;</a>
                            @else
                                <a href="{{ route('register') }}" class="bg-cyan-500 hover:bg-cyan-600 text-white text-base font-bold py-3 px-6 rounded-xl shadow-lg shadow-cyan-200/50 dark:shadow-none transition-all duration-200 transform hover:-translate-y-1 flex items-center justify-center gap-2">
                                    Get Started for Free
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                                </a>
                                <a href="{{ route('login') }}" class="bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-base font-bold py-3 px-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-200 flex items-center justify-center">Log In</a>
                            @endauth
                        </div>
                    </div>

                    <div class="flex justify-center relative order-1 lg:order-2">
                        <div class="absolute inset-0 bg-gradient-to-tr from-cyan-200 to-teal-200 dark:from-cyan-900/40 dark:to-teal-900/40 rounded-[3rem] transform rotate-3 scale-95 blur-2xl -z-10 opacity-60"></div>
                        <img src="{{ asset('NT_1.png') }}" alt="NexTask App" class="w-full max-w-xs lg:max-w-md object-contain drop-shadow-2xl transition-transform duration-500 hover:scale-105">
                    </div>

                </div>
            </main>

            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 rounded-full bg-cyan-100 dark:bg-cyan-900/10 blur-3xl -z-10"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-64 h-64 rounded-full bg-teal-100 dark:bg-teal-900/10 blur-3xl -z-10"></div>
        </div>

        <script>
            function toggleTheme() {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.theme = 'light';
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.theme = 'dark';
                }
            }
        </script>
    </body>
</html>

