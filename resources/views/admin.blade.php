<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head', ['title' => 'Admin'])
    </head>
    <body class="min-h-screen bg-zinc-50 text-zinc-900 antialiased dark:bg-zinc-950 dark:text-zinc-100">
        <x-site-navbar />

        <main class="mx-auto w-full max-w-6xl px-6 pb-16 pt-4">
            <section class="overflow-hidden rounded-3xl border border-zinc-200/80 bg-white/90 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/80">
                <div class="border-b border-zinc-200/70 bg-linear-to-r from-amber-100/70 via-cyan-100/70 to-teal-100/70 px-8 py-6 dark:border-zinc-800 dark:from-amber-900/30 dark:via-cyan-900/20 dark:to-teal-900/30 md:px-12">
                    <p class="mb-2 text-xs font-semibold uppercase tracking-[0.2em] text-cyan-700 dark:text-cyan-300">Admin</p>
                    <h1 class="text-3xl font-semibold leading-tight sm:text-4xl">Admin dashboard</h1>
                    <p class="mt-2 text-sm text-zinc-700 dark:text-zinc-300">Manage high-level tools and internal actions from one place.</p>
                </div>

                <div class="grid gap-4 p-8 text-sm leading-6 text-zinc-600 dark:text-zinc-300 md:grid-cols-3 md:p-12">
                    <article class="rounded-2xl border border-zinc-200/80 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-cyan-700 dark:text-cyan-300">Users</p>
                        <h2 class="mt-2 text-base font-semibold text-zinc-900 dark:text-zinc-100">Account management</h2>
                        <p class="mt-2">Review and control account-level access for your team.</p>
                    </article>

                    <article class="rounded-2xl border border-zinc-200/80 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-amber-700 dark:text-amber-300">Data</p>
                        <h2 class="mt-2 text-base font-semibold text-zinc-900 dark:text-zinc-100">System health</h2>
                        <p class="mt-2">Monitor imports, database quality, and critical background tasks.</p>
                    </article>

                    <article class="rounded-2xl border border-zinc-200/80 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-teal-700 dark:text-teal-300">Configuration</p>
                        <h2 class="mt-2 text-base font-semibold text-zinc-900 dark:text-zinc-100">Global settings</h2>
                        <p class="mt-2">Centralize environment-sensitive options for site operations.</p>
                    </article>
                </div>
            </section>
        </main>

        @fluxScripts
    </body>
</html>
