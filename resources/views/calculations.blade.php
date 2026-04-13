<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ __('Calculations') }} - {{ config('app.name', 'Laravel') }}</title>

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
        <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_15%_15%,#0f766e20,transparent_35%),radial-gradient(circle_at_85%_20%,#f59e0b22,transparent_30%),radial-gradient(circle_at_50%_100%,#38bdf822,transparent_35%)]"></div>

        <header class="mx-auto flex w-full max-w-6xl items-center justify-between px-6 py-6">
            <a href="{{ route('home') }}" class="text-sm font-semibold tracking-wide text-zinc-700 transition hover:text-zinc-950 dark:text-zinc-300 dark:hover:text-white">
                {{ config('app.name', 'Materials Database') }}
            </a>

            <nav class="flex items-center gap-3 text-sm font-medium">
                <a href="{{ route('home') }}" class="rounded-md px-3 py-2 text-zinc-600 transition hover:bg-white/80 hover:text-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-900 dark:hover:text-white">
                    Home
                </a>
                <a href="{{ route('about') }}" class="rounded-md px-3 py-2 text-zinc-600 transition hover:bg-white/80 hover:text-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-900 dark:hover:text-white">
                    About
                </a>
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-md bg-zinc-900 px-3 py-2 text-white transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="rounded-md bg-zinc-900 px-3 py-2 text-white transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">
                        Log in
                    </a>
                @endauth
            </nav>
        </header>

        <main class="mx-auto w-full max-w-6xl px-6 pb-16 pt-4">
            <section class="overflow-hidden rounded-3xl border border-zinc-200/80 bg-white/85 shadow-sm backdrop-blur dark:border-zinc-800 dark:bg-zinc-900/75">
                <div class="border-b border-zinc-200/70 bg-linear-to-r from-emerald-100/80 via-cyan-100/80 to-amber-100/80 px-8 py-6 dark:border-zinc-800 dark:from-emerald-900/30 dark:via-cyan-900/20 dark:to-amber-900/20 md:px-12">
                    <p class="mb-2 text-xs font-semibold uppercase tracking-[0.24em] text-emerald-700 dark:text-emerald-300">Calculations</p>
                    <h1 class="max-w-3xl text-3xl font-semibold leading-tight sm:text-4xl">A focused place for the math behind wall decisions.</h1>
                    <p class="mt-3 max-w-2xl text-sm text-zinc-700 dark:text-zinc-300">
                        This page is intended to collect the calculations used to compare assemblies, estimate materials, and surface the numbers that drive better wall selections.
                    </p>
                </div>

                <div class="grid gap-4 p-8 md:grid-cols-[1.15fr_0.85fr] md:p-12">
                    <div class="grid gap-4">
                        <article class="rounded-2xl border border-emerald-200/80 bg-emerald-50/60 p-5 dark:border-emerald-800/50 dark:bg-emerald-950/25">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700 dark:text-emerald-300">Inputs</p>
                            <h2 class="mt-2 text-lg font-semibold text-zinc-900 dark:text-zinc-100">Dimensional values and assembly details</h2>
                            <p class="mt-2 text-sm leading-6 text-zinc-600 dark:text-zinc-300">
                                Length, height, layer count, framing spacing, and material thickness can all feed into a calculation flow.
                            </p>
                        </article>

                        <article class="rounded-2xl border border-cyan-200/80 bg-cyan-50/60 p-5 dark:border-cyan-800/50 dark:bg-cyan-950/25">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-cyan-700 dark:text-cyan-300">Process</p>
                            <h2 class="mt-2 text-lg font-semibold text-zinc-900 dark:text-zinc-100">Compare assemblies with transparent steps</h2>
                            <p class="mt-2 text-sm leading-6 text-zinc-600 dark:text-zinc-300">
                                Show each step clearly so users can see how area, volume, quantity, and allowance values are derived.
                            </p>
                        </article>

                        <article class="rounded-2xl border border-amber-200/80 bg-amber-50/70 p-5 dark:border-amber-800/50 dark:bg-amber-950/25">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-700 dark:text-amber-300">Outputs</p>
                            <h2 class="mt-2 text-lg font-semibold text-zinc-900 dark:text-zinc-100">Surface the result that matters most</h2>
                            <p class="mt-2 text-sm leading-6 text-zinc-600 dark:text-zinc-300">
                                Return totals, formatted summaries, and quick comparison values that can be reused across the app.
                            </p>
                        </article>
                    </div>

                    <aside class="rounded-2xl border border-zinc-200/80 bg-zinc-50 p-5 dark:border-zinc-800 dark:bg-zinc-950/40">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-zinc-500 dark:text-zinc-400">Calculation Snapshot</p>
                        <dl class="mt-4 grid gap-3 text-sm">
                            <div class="flex items-center justify-between rounded-xl bg-white px-4 py-3 shadow-sm dark:bg-zinc-900">
                                <dt class="text-zinc-600 dark:text-zinc-300">Wall area</dt>
                                <dd class="font-semibold text-zinc-950 dark:text-zinc-50">128 sq ft</dd>
                            </div>
                            <div class="flex items-center justify-between rounded-xl bg-white px-4 py-3 shadow-sm dark:bg-zinc-900">
                                <dt class="text-zinc-600 dark:text-zinc-300">Material layers</dt>
                                <dd class="font-semibold text-zinc-950 dark:text-zinc-50">5</dd>
                            </div>
                            <div class="flex items-center justify-between rounded-xl bg-white px-4 py-3 shadow-sm dark:bg-zinc-900">
                                <dt class="text-zinc-600 dark:text-zinc-300">Allowance</dt>
                                <dd class="font-semibold text-zinc-950 dark:text-zinc-50">10%</dd>
                            </div>
                        </dl>

                        <div class="mt-5 rounded-2xl border border-dashed border-zinc-300 bg-white/70 p-4 text-sm leading-6 text-zinc-600 dark:border-zinc-700 dark:bg-zinc-900/60 dark:text-zinc-300">
                            This page can grow into a dedicated calculator hub for material counts, costs, and wall-performance comparisons.
                        </div>
                    </aside>
                </div>
            </section>

            <section class="mt-8 grid gap-5 md:grid-cols-3">
                <article class="rounded-2xl border border-zinc-200/80 bg-white/85 p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/75">
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-emerald-700 dark:text-emerald-300">Estimate</p>
                    <h2 class="mt-3 text-lg font-semibold text-zinc-900 dark:text-zinc-100">Material Counts</h2>
                    <p class="mt-3 text-sm leading-6 text-zinc-600 dark:text-zinc-300">
                        Convert wall dimensions into quantities for sheet goods, framing, insulation, and finishing materials.
                    </p>
                </article>

                <article class="rounded-2xl border border-zinc-200/80 bg-white/85 p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/75">
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-cyan-700 dark:text-cyan-300">Compare</p>
                    <h2 class="mt-3 text-lg font-semibold text-zinc-900 dark:text-zinc-100">Assembly Tradeoffs</h2>
                    <p class="mt-3 text-sm leading-6 text-zinc-600 dark:text-zinc-300">
                        Show how different assemblies change cost, thickness, and thermal performance side by side.
                    </p>
                </article>

                <article class="rounded-2xl border border-zinc-200/80 bg-white/85 p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/75">
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-amber-700 dark:text-amber-300">Report</p>
                    <h2 class="mt-3 text-lg font-semibold text-zinc-900 dark:text-zinc-100">Readable Output</h2>
                    <p class="mt-3 text-sm leading-6 text-zinc-600 dark:text-zinc-300">
                        Present results in a simple format that can be exported, reused, or embedded elsewhere in the app.
                    </p>
                </article>
            </section>
        </main>
    </body>
</html>