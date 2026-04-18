<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ __('About Us') }} - {{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen bg-zinc-50 text-zinc-900 antialiased">
        <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_10%_10%,#f59e0b20,transparent_40%),radial-gradient(circle_at_90%_20%,#0ea5e920,transparent_35%),radial-gradient(circle_at_50%_100%,#14b8a620,transparent_40%)]"></div>

        <x-site-navbar />

        <main class="mx-auto w-full max-w-6xl px-6 pb-16 pt-4">
            <section class="about-fade-up overflow-hidden rounded-3xl border border-zinc-200/80 bg-white/80 shadow-sm backdrop-blur">
                <div class="about-gradient-shift border-b border-zinc-200/70 bg-linear-to-r from-amber-100/70 via-cyan-100/70 to-teal-100/70 px-8 py-6 md:px-12">
                    <p class="mb-2 text-xs font-semibold uppercase tracking-[0.2em] text-cyan-700">About Us</p>
                    <h1 class="text-3xl font-semibold leading-tight sm:text-4xl">Wall-E</h1>
                    <p class="mt-2 text-sm text-zinc-700">Wall Encyclopedia</p>
                </div>

                <div class="about-fade-up about-delay-1 grid gap-4 p-8 text-base leading-7 text-zinc-600 md:p-12">
                    <p>Welcome to Wall Encyclopedia, or Wall-E.</p>
                    <p>
                        This is a tool that can be used by the most experienced members of the industry, to home DIY enthusiests looking to search for detailed wall assemblies used in the industry.
                    </p>
                    <p>
                        The tool is straightforward and simple use, and will return result based on user input in a few keyfields.
                    </p>
                    <p>
                        All walls present in the database are sourced from publically available sources online from accredited architects and organizations.
                    </p>
                    <p>
                        Please note, this tool is not intended to be a guidline to how to build a wall that meets code standards in regards to structural strength and energy performance.
                    </p>
                    <p>
                        Please reference our assumptions tab fro a detailed list of assumptions, estimations and generalizations made in order for the database to function.
                    </p>
                    <p>Created by Nicholas Asistores, Cameron Beech & Oskar Linkruus.</p>
                </div>
            </section>

            <section class="about-fade-up about-delay-2 mt-8 grid gap-5 md:grid-cols-3">
                <article class="about-fade-up about-delay-1 rounded-2xl border border-cyan-200/80 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-cyan-700">How It Works</p>
                    <h2 class="mt-3 text-lg font-semibold text-zinc-900">Simple Search Inputs</h2>
                    <p class="mt-3 text-sm leading-6 text-zinc-600">
                        Enter key wall details in a few fields and Wall-E returns relevant assembly results quickly.
                    </p>
                </article>

                <article class="about-fade-up about-delay-2 rounded-2xl border border-amber-200/80 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-amber-700">Data Source</p>
                    <h2 class="mt-3 text-lg font-semibold text-zinc-900">Public and Accredited</h2>
                    <p class="mt-3 text-sm leading-6 text-zinc-600">
                        Database wall entries are sourced from publicly available material from accredited architects and organizations.
                    </p>
                </article>

                <article class="about-fade-up about-delay-3 rounded-2xl border border-teal-200/80 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-teal-700">Important Note</p>
                    <h2 class="mt-3 text-lg font-semibold text-zinc-900">Reference Tool Only</h2>
                    <p class="mt-3 text-sm leading-6 text-zinc-600">
                        This tool is not a construction code guide. Check the assumptions tab for assumptions, estimations, and generalizations.
                    </p>
                </article>
            </section>

            <section class="about-fade-up about-delay-3 mt-8 overflow-hidden rounded-3xl border border-zinc-200/80 bg-white/90 p-4 shadow-sm md:p-6">
                <figure class="grid gap-5 md:grid-cols-[0.95fr_1.05fr] md:items-stretch">
                    <div class="relative h-[360px] w-full overflow-hidden rounded-2xl border border-zinc-200/80 bg-zinc-100/60 p-3 shadow-sm md:h-[390px] lg:h-[430px]">
                        <div class="group about-float-1 absolute left-2 top-2 h-[46%] w-[58%]">
                            <span aria-hidden="true" class="absolute -top-1 left-4 z-20 h-3 w-10 -rotate-6 rounded-sm bg-amber-100/85 shadow-sm"></span>
                            <span aria-hidden="true" class="absolute -top-1 right-4 z-20 h-3 w-10 rotate-6 rounded-sm bg-cyan-100/90 shadow-sm"></span>
                            <img
                                src="{{ asset('images/about-wall-assembly.svg') }}"
                                alt="Wall assembly overview illustration."
                                class="h-full w-full rounded-xl border border-zinc-200 bg-white object-cover shadow-md transition group-hover:z-20 group-hover:scale-[1.02]"
                                loading="lazy"
                            />
                            <span class="absolute bottom-2 left-2 rounded-md bg-white/90 px-2 py-1 text-[10px] font-semibold uppercase tracking-wide text-zinc-700 shadow-sm">Overview</span>
                        </div>

                        <div class="group about-float-2 absolute right-2 top-6 h-[50%] w-[54%] -rotate-2">
                            <span aria-hidden="true" class="absolute -top-1 left-5 z-20 h-3 w-10 -rotate-3 rounded-sm bg-teal-100/90 shadow-sm"></span>
                            <span aria-hidden="true" class="absolute -top-1 right-5 z-20 h-3 w-10 rotate-6 rounded-sm bg-amber-100/85 shadow-sm"></span>
                            <img
                                src="{{ asset('images/about-insulation-detail.svg') }}"
                                alt="Insulation detail illustration."
                                class="h-full w-full rounded-xl border border-zinc-200 bg-white object-cover shadow-md transition group-hover:z-20 group-hover:scale-[1.02]"
                                loading="lazy"
                            />
                            <span class="absolute bottom-2 left-2 rounded-md bg-white/90 px-2 py-1 text-[10px] font-semibold uppercase tracking-wide text-zinc-700 shadow-sm">Insulation</span>
                        </div>

                        <div class="group about-float-3 absolute bottom-6 left-6 h-[46%] w-[52%] rotate-2">
                            <span aria-hidden="true" class="absolute -top-1 left-4 z-20 h-3 w-10 -rotate-6 rounded-sm bg-cyan-100/90 shadow-sm"></span>
                            <span aria-hidden="true" class="absolute -top-1 right-4 z-20 h-3 w-10 rotate-3 rounded-sm bg-zinc-200/90 shadow-sm"></span>
                            <img
                                src="{{ asset('images/about-stud-elevation.svg') }}"
                                alt="Stud elevation illustration."
                                class="h-full w-full rounded-xl border border-zinc-200 bg-white object-cover shadow-md transition group-hover:z-20 group-hover:scale-[1.02]"
                                loading="lazy"
                            />
                            <span class="absolute bottom-2 left-2 rounded-md bg-white/90 px-2 py-1 text-[10px] font-semibold uppercase tracking-wide text-zinc-700 shadow-sm">Framing</span>
                        </div>

                        <div class="group about-float-4 absolute bottom-2 right-4 h-[44%] w-[48%] -rotate-1">
                            <span aria-hidden="true" class="absolute -top-1 left-4 z-20 h-3 w-10 -rotate-2 rounded-sm bg-amber-100/85 shadow-sm"></span>
                            <span aria-hidden="true" class="absolute -top-1 right-4 z-20 h-3 w-10 rotate-6 rounded-sm bg-teal-100/90 shadow-sm"></span>
                            <img
                                src="{{ asset('images/about-layer-callouts.svg') }}"
                                alt="Layer callouts illustration."
                                class="h-full w-full rounded-xl border border-zinc-200 bg-white object-cover shadow-md transition group-hover:z-20 group-hover:scale-[1.02]"
                                loading="lazy"
                            />
                            <span class="absolute bottom-2 left-2 rounded-md bg-white/90 px-2 py-1 text-[10px] font-semibold uppercase tracking-wide text-zinc-700 shadow-sm">Callouts</span>
                        </div>
                    </div>
                    <figcaption class="min-w-0 rounded-2xl border border-zinc-200/80 bg-zinc-50/80 p-6 text-sm leading-6 text-zinc-700 break-words text-pretty md:p-7">
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-cyan-700">Wall Context</p>
                        <p class="mt-2 max-w-prose">
                            A visual snapshot of a layered wall assembly: finish layers, insulation, and structural members working together.
                        </p>

                        <div class="mt-4 grid gap-2 rounded-xl border border-zinc-200/80 bg-white/80 p-3 text-xs">
                            <p class="font-semibold uppercase tracking-[0.12em] text-zinc-700">Quick Layers</p>
                            <p class="text-zinc-600">1. Interior finish and air control</p>
                            <p class="text-zinc-600">2. Stud framing and service cavity</p>
                            <p class="text-zinc-600">3. Insulation with exterior protection</p>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-2 text-[11px]">
                            <span class="rounded-full border border-cyan-200/80 bg-cyan-100/70 px-2.5 py-1 text-cyan-800">Reference Visual</span>
                            <span class="rounded-full border border-amber-200/80 bg-amber-100/70 px-2.5 py-1 text-amber-800">Not a Code Guide</span>
                        </div>
                    </figcaption>
                </figure>
            </section>

        </main>
    </body>
</html>

