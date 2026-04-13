<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-50 text-zinc-900 antialiased dark:bg-zinc-950 dark:text-zinc-100">
        <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_10%_10%,#f59e0b20,transparent_40%),radial-gradient(circle_at_90%_20%,#0ea5e920,transparent_35%),radial-gradient(circle_at_50%_100%,#14b8a620,transparent_40%)]"></div>

        <div class="relative flex min-h-screen flex-col">
            <x-site-navbar class="shrink-0" wrapper-class="max-w-4xl" />

            <main class="flex flex-1 items-start justify-center px-6 pb-16 pt-4 md:pt-6">
                <div class="flex w-full max-w-sm flex-col gap-6">
                    {{ $slot }}
                </div>
            </main>
        </div>
        @fluxScripts
    </body>
</html>
