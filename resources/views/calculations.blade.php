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
        <main class="mx-auto w-full max-w-6xl px-6 py-8">
            <section class="overflow-hidden rounded-3xl border border-zinc-200/80 bg-white/90 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/80">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-zinc-100/80 text-zinc-700 dark:bg-zinc-800/80 dark:text-zinc-200">
                            <tr>
                                <th class="px-6 py-3 font-semibold md:px-8">Material location</th>
                                <th class="px-6 py-3 font-semibold md:px-8">Materials</th>
                                <th class="px-6 py-3 font-semibold md:px-8">Thickness</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200/70 dark:divide-zinc-800">
                            @for ($rowIndex = 0; $rowIndex < 3; $rowIndex++)
                                <tr>
                                    <td class="px-6 py-3 md:px-8">
                                        <select class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-800 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100">
                                            <option value="">Select category</option>
                                            @foreach ($categoryNames as $categoryName)
                                                <option value="{{ $categoryName }}">{{ $categoryName }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-6 py-3 md:px-8">
                                        <select class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-800 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100">
                                            <option value="">Select material</option>
                                        </select>
                                    </td>
                                    <td class="px-6 py-3 md:px-8">
                                        <input
                                            type="text"
                                            value=""
                                            placeholder="Enter thickness (in/mm)"
                                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-800 shadow-sm outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100"
                                        >
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="mt-8 overflow-hidden rounded-3xl border border-zinc-200/80 bg-white/90 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/80">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-zinc-100/80 text-zinc-700 dark:bg-zinc-800/80 dark:text-zinc-200">
                            <tr>
                                <th class="px-6 py-3 font-semibold md:px-8">Wall Assembly</th>
                                <th class="px-6 py-3 font-semibold md:px-8">R Value</th>
                                <th class="px-6 py-3 font-semibold md:px-8">Embodied Carbon</th>
                                <th class="px-6 py-3 font-semibold md:px-8">Thickness</th>
                                <th class="px-6 py-3 font-semibold md:px-8">Fire Rating</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200/70 dark:divide-zinc-800"></tbody>
                    </table>
                </div>
            </section>
        </main>
    </body>
</html>