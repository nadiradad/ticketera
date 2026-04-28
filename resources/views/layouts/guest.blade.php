<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

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
    <body class="font-sans antialiased text-slate-800 dark:text-slate-200">
        <div class="flex min-h-screen flex-col justify-center bg-slate-50 dark:bg-slate-900 px-4 py-10 sm:px-6 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <a
                    href="{{ route('login') }}"
                    class="block text-center text-2xl font-bold tracking-tight text-slate-900 dark:text-white transition hover:text-indigo-700 dark:hover:text-indigo-300"
                >
                    Ticketera
                </a>
                <p class="mt-1 text-center text-sm text-slate-500 dark:text-slate-400">
                    Acceso al sistema de tickets
                </p>
            </div>

            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
                <div class="rounded-2xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-6 py-8 shadow-sm dark:shadow-none sm:px-10">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
