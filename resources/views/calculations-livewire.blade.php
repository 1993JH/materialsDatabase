<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Calculations - {{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        @livewireStyles
    </head>
    <body class="min-h-screen bg-zinc-50 text-zinc-900 antialiased dark:bg-zinc-950 dark:text-zinc-100">
        <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_15%_15%,#0f766e20,transparent_35%),radial-gradient(circle_at_85%_20%,#f59e0b22,transparent_30%),radial-gradient(circle_at_50%_100%,#38bdf822,transparent_35%)]"></div>

        <x-site-navbar />

        <main class="mx-auto w-full max-w-6xl px-6 pb-16 pt-4">
            <livewire:calculations />
        </main>

        @livewireScripts
    </body>
</html>
