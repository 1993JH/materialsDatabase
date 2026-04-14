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

        <x-site-navbar />

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

            <section class="mt-8 overflow-hidden rounded-3xl border border-zinc-200/80 bg-white/90 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/80">
                <div class="border-b border-zinc-200/70 px-6 py-5 dark:border-zinc-800 md:px-8">
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-cyan-700 dark:text-cyan-300">Material Table</p>
                    <div class="mt-2 flex items-center justify-between gap-4">
                        <h2 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">Wall Layer Breakdown</h2>
                        <button
                            id="add-material-row"
                            type="button"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-zinc-300 bg-white text-xl leading-none text-zinc-800 transition hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100 dark:hover:bg-zinc-800"
                            aria-label="Add row"
                        >
                            +
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-zinc-100/80 text-zinc-700 dark:bg-zinc-800/80 dark:text-zinc-200">
                            <tr>
                                <th class="px-6 py-3 font-semibold md:px-8">Material location</th>
                                <th class="px-6 py-3 font-semibold md:px-8">Materials</th>
                                <th class="px-6 py-3 font-semibold md:px-8">Thickness</th>
                            </tr>
                        </thead>
                        <tbody id="materials-table-body" class="divide-y divide-zinc-200/70 dark:divide-zinc-800">
                            @for ($rowIndex = 0; $rowIndex < 3; $rowIndex++)
                                <tr>
                                    <td class="px-6 py-3 md:px-8">
                                        <select class="location-select w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-800 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100">
                                            <option value="">Select category</option>
                                            @foreach ($categoryNames as $categoryName)
                                                <option value="{{ $categoryName }}">{{ $categoryName }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-6 py-3 md:px-8">
                                        <select class="material-select w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-800 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100" disabled>
                                            <option value="">Select material</option>
                                        </select>
                                    </td>
                                    <td class="px-6 py-3 md:px-8">
                                        <div class="flex items-center gap-3">
                                            <input
                                                type="text"
                                                value=""
                                                placeholder="Enter thickness (in/mm)"
                                                class="thickness-input w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-800 shadow-sm outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100"
                                            >
                                            <button
                                                type="button"
                                                class="row-remove-button inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full border border-zinc-300 bg-white text-base leading-none text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100 dark:hover:bg-zinc-800"
                                                aria-label="Remove this layer"
                                            >
                                                -
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-zinc-200/70 px-6 py-5 dark:border-zinc-800 md:px-8">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <button
                            id="calculate-table-button"
                            type="button"
                            class="inline-flex items-center justify-center rounded-full bg-cyan-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/30"
                        >
                            create wall
                        </button>
                    </div>
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
                        <tbody id="wall-assemblies-table-body" class="divide-y divide-zinc-200/70 dark:divide-zinc-800"></tbody>
                    </table>
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

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const addRowButton = document.getElementById('add-material-row');
                const calculateButton = document.getElementById('calculate-table-button');
                const tableBody = document.getElementById('materials-table-body');
                const wallAssembliesTableBody = document.getElementById('wall-assemblies-table-body');
                const categoryNames = @json($categoryNames);
                const categoryMaterialMap = @json($categoryMaterialMap);
                const minRows = 3;
                const maxRows = 10;
                const createdWallAssemblies = [];

                if (!addRowButton || !calculateButton || !tableBody || !wallAssembliesTableBody) {
                    return;
                }

                const removeButtonClass = 'row-remove-button inline-flex h-7 w-7 items-center justify-center rounded-full border border-zinc-300 bg-white text-base leading-none text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100 dark:hover:bg-zinc-800';

                const escapeHtml = (value) => value
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#39;');

                const buildMaterialOptions = (categoryName) => {
                    const materialNames = categoryMaterialMap[categoryName] ?? [];

                    return materialNames
                        .map((material) => {
                            const escapedMaterialName = escapeHtml(material.name);
                            const escapedKgCo2e = escapeHtml(String(material.kgco2e));
                            const escapedConductivity = escapeHtml(String(material.conductivity));

                            return `<option value="${escapedMaterialName}" data-kgco2e="${escapedKgCo2e}" data-conductivity="${escapedConductivity}">${escapedMaterialName}</option>`;
                        })
                        .join('');
                };

                const updateMaterialSelect = (locationSelect) => {
                    const row = locationSelect.closest('tr');

                    if (!row) {
                        return;
                    }

                    const materialSelect = row.querySelector('.material-select');

                    if (!(materialSelect instanceof HTMLSelectElement)) {
                        return;
                    }

                    const selectedCategory = locationSelect.value;
                    const hasSelectedCategory = selectedCategory !== '';
                    const materialOptions = hasSelectedCategory ? buildMaterialOptions(selectedCategory) : '';

                    materialSelect.innerHTML = `<option value="">Select material</option>${materialOptions}`;
                    materialSelect.disabled = !hasSelectedCategory;
                };

                const parseThicknessValue = (value) => {
                    const trimmedValue = value.trim();
                    const mixedFractionMatch = trimmedValue.match(/^(\d+)\s*-\s*(\d+)\/(\d+)/);

                    if (mixedFractionMatch) {
                        const wholeNumber = Number(mixedFractionMatch[1]);
                        const numerator = Number(mixedFractionMatch[2]);
                        const denominator = Number(mixedFractionMatch[3]);

                        if (denominator === 0) {
                            return 0;
                        }

                        return wholeNumber + (numerator / denominator);
                    }

                    const fractionMatch = trimmedValue.match(/^(\d+)\/(\d+)/);

                    if (fractionMatch) {
                        const numerator = Number(fractionMatch[1]);
                        const denominator = Number(fractionMatch[2]);

                        if (denominator === 0) {
                            return 0;
                        }

                        return numerator / denominator;
                    }

                    const decimalMatch = trimmedValue.match(/^-?\d*\.?\d+/);

                    if (!decimalMatch) {
                        return 0;
                    }

                    const parsedThickness = Number(decimalMatch[0]);

                    return Number.isNaN(parsedThickness) ? 0 : parsedThickness;
                };

                const calculateThicknessTotal = () => {
                    const totalThickness = Array.from(tableBody.querySelectorAll('.thickness-input'))
                        .map((input) => parseThicknessValue(input.value))
                        .reduce((total, thickness) => total + thickness, 0);

                    return totalThickness;
                };

                const calculateEmbodiedCarbonTotal = () => {
                    const totalEmbodiedCarbon = Array.from(tableBody.querySelectorAll('.material-select'))
                        .map((select) => {
                            const selectedOption = select.selectedOptions[0];

                            if (!selectedOption) {
                                return 0;
                            }

                            const kgCo2e = Number(selectedOption.dataset.kgco2e ?? 0);

                            return Number.isNaN(kgCo2e) ? 0 : kgCo2e;
                        })
                        .reduce((total, embodiedCarbon) => total + embodiedCarbon, 0);

                    return totalEmbodiedCarbon;
                };

                const renderWallAssemblyRows = (wallAssemblies, emptyMessage = 'No walls created yet.') => {
                    if (!wallAssemblies.length) {
                        wallAssembliesTableBody.innerHTML = `
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400 md:px-8">
                                    ${escapeHtml(emptyMessage)}
                                </td>
                            </tr>
                        `;

                        return;
                    }

                    wallAssembliesTableBody.innerHTML = wallAssemblies
                        .map((wallAssembly) => {
                            const assemblyName = escapeHtml(String(wallAssembly.wall_assembly ?? ''));
                            const rValue = Number(wallAssembly.r_value ?? 0).toFixed(2);
                            const embodiedCarbon = Number(wallAssembly.embodied_carbon ?? 0).toFixed(2);
                            const thickness = Number(wallAssembly.thickness ?? 0).toFixed(2);
                            const fireRating = wallAssembly.fire_rating === null || wallAssembly.fire_rating === ''
                                ? 'N/A'
                                : String(wallAssembly.fire_rating);

                            return `
                                <tr>
                                    <td class="px-6 py-3 text-zinc-800 dark:text-zinc-100 md:px-8">${assemblyName}</td>
                                    <td class="px-6 py-3 text-zinc-700 dark:text-zinc-200 md:px-8">${rValue}</td>
                                    <td class="px-6 py-3 text-zinc-700 dark:text-zinc-200 md:px-8">${embodiedCarbon}</td>
                                    <td class="px-6 py-3 text-zinc-700 dark:text-zinc-200 md:px-8">${thickness}</td>
                                    <td class="px-6 py-3 text-zinc-700 dark:text-zinc-200 md:px-8">${fireRating}</td>
                                </tr>
                            `;
                        })
                        .join('');
                };

                const buildCreatedWallAssembly = () => {
                    const rows = Array.from(tableBody.querySelectorAll('tr'));
                    const selectedLayers = rows
                        .map((row) => {
                            const locationSelect = row.querySelector('.location-select');
                            const materialSelect = row.querySelector('.material-select');
                            const thicknessInput = row.querySelector('.thickness-input');

                            if (!(locationSelect instanceof HTMLSelectElement)) {
                                return null;
                            }

                            if (!(materialSelect instanceof HTMLSelectElement)) {
                                return null;
                            }

                            if (!(thicknessInput instanceof HTMLInputElement)) {
                                return null;
                            }

                            const selectedOption = materialSelect.selectedOptions[0];
                            const categoryName = locationSelect.value.trim();
                            const materialName = materialSelect.value.trim();

                            if (!categoryName || !materialName || !selectedOption) {
                                return null;
                            }

                            const conductivity = Number(selectedOption.dataset.conductivity ?? 0);
                            const embodiedCarbon = Number(selectedOption.dataset.kgco2e ?? 0);
                            const thickness = parseThicknessValue(thicknessInput.value);

                            if (thickness <= 0) {
                                return null;
                            }

                            return {
                                assemblySegment: `${categoryName}: ${materialName}`,
                                conductivity,
                                embodiedCarbon,
                                thickness,
                            };
                        })
                        .filter((layer) => layer !== null);

                    if (!selectedLayers.length) {
                        return null;
                    }

                    return {
                        wall_assembly: selectedLayers.map((layer) => layer.assemblySegment).join(' | '),
                        r_value: selectedLayers.reduce((total, layer) => total + (layer.conductivity > 0 ? layer.thickness / layer.conductivity : 0), 0),
                        embodied_carbon: selectedLayers.reduce((total, layer) => total + layer.embodiedCarbon, 0),
                        thickness: selectedLayers.reduce((total, layer) => total + layer.thickness, 0),
                        fire_rating: null,
                    };
                };

                const runCalculations = () => {
                    calculateThicknessTotal();
                    calculateEmbodiedCarbonTotal();

                    const createdWallAssembly = buildCreatedWallAssembly();

                    if (!createdWallAssembly) {
                        renderWallAssemblyRows([], 'Select a category, material, and thickness in the Material Table to create a wall.');

                        return;
                    }

                    createdWallAssemblies.push(createdWallAssembly);
                    renderWallAssemblyRows(createdWallAssemblies);
                };

                const updateButtonState = () => {
                    const hasReachedMax = tableBody.children.length >= maxRows;
                    const hasReachedMin = tableBody.children.length <= minRows;

                    addRowButton.disabled = hasReachedMax;
                    addRowButton.setAttribute('aria-disabled', hasReachedMax ? 'true' : 'false');
                    addRowButton.classList.toggle('opacity-40', hasReachedMax);
                    addRowButton.classList.toggle('cursor-not-allowed', hasReachedMax);

                    tableBody.querySelectorAll('.row-remove-button').forEach((button) => {
                        button.disabled = hasReachedMin;
                        button.setAttribute('aria-disabled', hasReachedMin ? 'true' : 'false');
                        button.classList.toggle('opacity-40', hasReachedMin);
                        button.classList.toggle('cursor-not-allowed', hasReachedMin);
                    });
                };

                updateButtonState();

                calculateButton.addEventListener('click', runCalculations);

                renderWallAssemblyRows([]);

                addRowButton.addEventListener('click', () => {
                    if (tableBody.children.length >= maxRows) {
                        return;
                    }

                    const row = document.createElement('tr');
                    const locationSelectOptions = categoryNames
                        .map((categoryName) => {
                            const escapedCategoryName = escapeHtml(categoryName);

                            return `<option value="${escapedCategoryName}">${escapedCategoryName}</option>`;
                        })
                        .join('');

                    row.innerHTML = `
                        <td class="px-6 py-3 md:px-8">
                            <select class="location-select w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-800 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100">
                                <option value="">Select category</option>
                                ${locationSelectOptions}
                            </select>
                        </td>
                        <td class="px-6 py-3 md:px-8">
                            <select class="material-select w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-800 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100" disabled>
                                <option value="">Select material</option>
                            </select>
                        </td>
                        <td class="px-6 py-3 md:px-8">
                            <div class="flex items-center gap-3">
                                <input
                                    type="text"
                                    value=""
                                    placeholder="Enter thickness (in/mm)"
                                    class="thickness-input w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-800 shadow-sm outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100"
                                >
                                <button
                                    type="button"
                                    class="${removeButtonClass} shrink-0"
                                    aria-label="Remove this layer"
                                >
                                    -
                                </button>
                            </div>
                        </td>
                    `;

                    tableBody.appendChild(row);
                    updateButtonState();
                });

                tableBody.addEventListener('change', (event) => {
                    const changedElement = event.target;

                    if (!(changedElement instanceof HTMLSelectElement)) {
                        return;
                    }

                    if (!changedElement.classList.contains('location-select')) {
                        return;
                    }

                    updateMaterialSelect(changedElement);
                });

                tableBody.addEventListener('click', (event) => {
                    const clickedElement = event.target;

                    if (!(clickedElement instanceof HTMLElement)) {
                        return;
                    }

                    const removeButton = clickedElement.closest('.row-remove-button');

                    if (!removeButton) {
                        return;
                    }

                    if (tableBody.children.length <= minRows) {
                        return;
                    }

                    removeButton.closest('tr')?.remove();
                    updateButtonState();
                });
            });
        </script>
    </body>
</html>