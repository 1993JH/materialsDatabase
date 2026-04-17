<main class="mx-auto w-full max-w-6xl px-6 pb-16 pt-4">
    <section class="overflow-hidden rounded-3xl border border-zinc-200/80 bg-white/90 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/80">
        <div class="border-b border-zinc-200/70 bg-linear-to-r from-amber-100/70 via-cyan-100/70 to-teal-100/70 px-8 py-6 dark:border-zinc-800 dark:from-amber-900/30 dark:via-cyan-900/20 dark:to-teal-900/30 md:px-12">
            <p class="mb-2 text-xs font-semibold uppercase tracking-[0.2em] text-cyan-700 dark:text-cyan-300">Admin</p>
            <h1 class="text-3xl font-semibold leading-tight sm:text-4xl">Admin dashboard</h1>
            <p class="mt-2 text-sm text-zinc-700 dark:text-zinc-300">Use filters to quickly target admin workflows and run focused actions.</p>
        </div>

        <div class="p-8 md:p-12">
            @if ($showSuccessBanner)
                <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50/90 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-800/70 dark:bg-emerald-900/30 dark:text-emerald-200">
                    <div class="flex items-start justify-between gap-3">
                        <p>{{ $lastActionMessage }}</p>
                        <button type="button" wire:click="dismissSuccessBanner" class="rounded-full border border-emerald-300 px-2 py-0.5 text-xs transition hover:bg-emerald-100 dark:border-emerald-700 dark:hover:bg-emerald-800/40">
                            Dismiss
                        </button>
                    </div>
                </div>
            @endif

            <div class="grid gap-4 md:grid-cols-[2fr_1fr]">
                <label class="block">
                    <span class="text-xs font-semibold uppercase tracking-[0.14em] text-zinc-500 dark:text-zinc-400">Search tools</span>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search by tool name or function..."
                        class="mt-2 w-full rounded-xl border border-zinc-300 bg-white px-3 py-2 text-sm outline-none transition focus:border-teal-500 focus:ring-1 focus:ring-teal-500 dark:border-zinc-700 dark:bg-zinc-900"
                    />
                </label>

                <label class="block">
                    <span class="text-xs font-semibold uppercase tracking-[0.14em] text-zinc-500 dark:text-zinc-400">Category</span>
                    <select
                        wire:model.live="category"
                        class="mt-2 w-full rounded-xl border border-zinc-300 bg-white px-3 py-2 text-sm outline-none transition focus:border-teal-500 focus:ring-1 focus:ring-teal-500 dark:border-zinc-700 dark:bg-zinc-900"
                    >
                        <option value="all">All</option>
                        <option value="security">Security</option>
                        <option value="operations">Operations</option>
                        <option value="configuration">Configuration</option>
                    </select>
                </label>
            </div>

            <div wire:loading.delay class="mt-3 text-sm text-zinc-500 dark:text-zinc-400">Refreshing admin tools...</div>

            <div class="mt-6 grid gap-5 lg:grid-cols-[1.4fr_1fr]">
                <div class="grid gap-4 sm:grid-cols-2">
                    @forelse ($this->filteredPanels as $panel)
                        <button
                            type="button"
                            wire:key="admin-panel-{{ $panel['id'] }}"
                            wire:click="selectPanel('{{ $panel['id'] }}')"
                            @class([
                                'rounded-2xl border p-5 text-left transition duration-200',
                                'border-teal-500 bg-teal-50/70 shadow-sm dark:border-teal-500/70 dark:bg-teal-900/20' => $selectedPanel === $panel['id'],
                                'border-zinc-200/80 bg-white hover:-translate-y-0.5 hover:border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:hover:border-zinc-600' => $selectedPanel !== $panel['id'],
                            ])
                        >
                            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-zinc-500 dark:text-zinc-400">{{ $panel['category'] }}</p>
                            <h2 class="mt-2 text-base font-semibold text-zinc-900 dark:text-zinc-100">{{ $panel['title'] }}</h2>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">{{ $panel['blurb'] }}</p>
                            <p class="mt-3 text-xs font-medium text-zinc-500 dark:text-zinc-400">Impact: {{ $panel['impact'] }}</p>
                        </button>
                    @empty
                        <p class="rounded-2xl border border-zinc-200/80 bg-zinc-50/80 p-4 text-sm text-zinc-600 dark:border-zinc-700 dark:bg-zinc-900/60 dark:text-zinc-300">
                            No admin tools match your current filters.
                        </p>
                    @endforelse
                </div>

                @php
                    $activePanel = $this->activePanel;
                @endphp

                <aside class="rounded-2xl border border-zinc-200/80 bg-zinc-50/70 p-5 dark:border-zinc-700 dark:bg-zinc-900/60">
                    @if ($activePanel)
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-zinc-500 dark:text-zinc-400">Selected tool</p>
                        <h3 class="mt-2 text-lg font-semibold text-zinc-900 dark:text-zinc-100">{{ $activePanel['title'] }}</h3>
                        <p class="mt-2 text-sm leading-6 text-zinc-600 dark:text-zinc-300">{{ $activePanel['blurb'] }}</p>

                        <div class="mt-4 rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900">
                            <p><span class="font-semibold text-zinc-800 dark:text-zinc-100">Category:</span> {{ ucfirst($activePanel['category']) }}</p>
                            <p class="mt-1"><span class="font-semibold text-zinc-800 dark:text-zinc-100">Operational impact:</span> {{ $activePanel['impact'] }}</p>
                        </div>

                        <button
                            type="button"
                            wire:click="runPanelAction('{{ $activePanel['id'] }}')"
                            wire:loading.attr="disabled"
                            class="mt-4 inline-flex w-full items-center justify-center rounded-xl bg-zinc-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-zinc-800 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                        >
                            {{ $activePanel['action_label'] }}
                        </button>
                    @else
                        <p class="text-sm text-zinc-600 dark:text-zinc-300">Select a tool to view details and run an action.</p>
                    @endif
                </aside>
            </div>
        </div>
    </section>
</main>
