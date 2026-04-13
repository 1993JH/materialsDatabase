<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ __('Welcome') }} - {{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen bg-zinc-50 text-zinc-900 antialiased dark:bg-zinc-950 dark:text-zinc-100">
        <x-site-navbar />

        <main class="mx-auto w-full max-w-6xl px-6 pb-16 pt-4">
            <section class="overflow-hidden rounded-3xl border border-zinc-200/80 bg-white/90 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/80">
                <div class="border-b border-zinc-200/80 px-8 py-6 dark:border-zinc-800">
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-zinc-500 dark:text-zinc-400">Database</p>
                    <h1 class="mt-1 text-3xl font-semibold leading-tight">Wall Assemblies</h1>
                    <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">Walls loaded from the walls table.</p>
                </div>

                <div class="grid gap-4 p-6 md:grid-cols-2 lg:grid-cols-3">
                    @forelse ($walls as $wall)
                        <button
                            type="button"
                            class="wall-card text-left rounded-2xl border border-zinc-200/80 bg-zinc-50/70 p-4 transition hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 dark:border-zinc-700 dark:bg-zinc-800/40"
                            data-wall-id="{{ $wall->id }}"
                            data-wall-name="{{ $wall->assembly_description }}"
                        >
                            <h2 class="text-base font-semibold text-zinc-900 dark:text-zinc-100">{{ $wall->assembly_description }}</h2>

                            <div class="mt-3 flex flex-wrap gap-2 text-xs">
                                <span class="rounded-full border border-cyan-200/80 bg-cyan-100/70 px-2.5 py-1 text-cyan-800 dark:border-cyan-800/70 dark:bg-cyan-900/30 dark:text-cyan-200">
                                    Climate: {{ $wall->climate_zone }}
                                </span>
                                <span class="rounded-full border border-amber-200/80 bg-amber-100/70 px-2.5 py-1 text-amber-800 dark:border-amber-800/70 dark:bg-amber-900/30 dark:text-amber-200">
                                    Wall Type: {{ $wall->wall_type }}
                                </span>
                                <span class="rounded-full border border-teal-200/80 bg-teal-100/70 px-2.5 py-1 text-teal-800 dark:border-teal-800/70 dark:bg-teal-900/30 dark:text-teal-200">
                                    R-Value: {{ $wall->r_value }}
                                </span>
                            </div>
                        </button>
                    @empty
                        <p class="text-sm text-zinc-600 dark:text-zinc-300">No walls found in the database.</p>
                    @endforelse
                </div>
            </section>
        </main>

        <dialog id="wallLayersDialog" class="w-[min(900px,95vw)] rounded-2xl border border-zinc-200 p-0 shadow-2xl backdrop:bg-zinc-900/60 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="p-5 sm:p-6">
                <div class="mb-4 flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-zinc-500 dark:text-zinc-400">Wall Layers</p>
                        <h2 id="wallLayersDialogTitle" class="mt-1 text-lg font-semibold text-zinc-900 dark:text-zinc-100"></h2>
                    </div>
                    <button type="button" id="closeWallLayersDialog" class="rounded-full border border-zinc-300 px-3 py-1 text-sm dark:border-zinc-600">Close</button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-zinc-200 dark:border-zinc-700">
                                <th class="px-3 py-2 text-left font-semibold">Layer</th>
                                <th class="px-3 py-2 text-left font-semibold">Material Name</th>
                                <th class="px-3 py-2 text-left font-semibold">Thickness</th>
                                <th class="px-3 py-2 text-left font-semibold">Embodied Carbon</th>
                            </tr>
                        </thead>
                        <tbody id="wallLayersDialogBody" class="divide-y divide-zinc-200 dark:divide-zinc-700"></tbody>
                    </table>
                </div>
            </div>
        </dialog>

        <script id="wallLayersData" type="application/json">@json($wallLayers)</script>
    </body>
</html>
