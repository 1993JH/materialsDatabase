<div>
    <main class="mx-auto w-full px-2 sm:px-3 pb-16 pt-4">
        <section class="overflow-hidden rounded-3xl border border-zinc-200/80 bg-white/90 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/80">
            <div class="flex flex-col gap-6 lg:flex-row">
                <div class="w-full border-b border-zinc-200/80 px-3 sm:px-5 py-6 lg:w-72 lg:border-b-0 lg:border-r dark:border-zinc-800">
                    <div class="mb-4">
                        <h1 class="text-3xl font-semibold leading-tight">Wall-E</h1>
                        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                            {{ $this->walls->total() }} wall{{ $this->walls->total() !== 1 ? 's' : '' }} available
                        </p>
                    </div>

                    <div class="mt-6">
                        <label for="wallSearch" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Search Walls</label>
                        <input
                            id="wallSearch"
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search by name, climate, or type..."
                            class="mt-2 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm placeholder-zinc-400 outline-none transition focus:border-teal-500 focus:ring-1 focus:ring-teal-500 dark:border-zinc-600 dark:bg-zinc-800 dark:placeholder-zinc-500 dark:focus:border-teal-500"
                        />

                        <div class="mt-3">
                            <div class="flex items-center justify-between gap-3">
                                <p class="text-xs font-medium uppercase tracking-[0.14em] text-zinc-500 dark:text-zinc-400">Selected Filters</p>
                                <button type="button" wire:click="clearFilters" class="text-xs font-medium text-teal-600 transition hover:text-teal-500 dark:text-teal-400 dark:hover:text-teal-300">Clear all</button>
                            </div>

                            <div class="mt-2 flex flex-wrap gap-2 text-sm text-zinc-700 dark:text-zinc-300">
                                @if ($search !== '')
                                    <span class="inline-flex items-center gap-2 rounded-full bg-zinc-100 px-3 py-1 text-xs font-medium text-zinc-700 dark:bg-zinc-800 dark:text-zinc-200">
                                        Search: {{ $search }}
                                    </span>
                                @endif

                                @foreach ($selectedClimateZones as $zone)
                                    <span wire:key="selected-climate-{{ $zone }}" class="inline-flex items-center gap-2 rounded-full bg-zinc-100 px-3 py-1 text-xs font-medium text-zinc-700 dark:bg-zinc-800 dark:text-zinc-200">
                                        Climate: {{ $zone === 'LT4' ? '<4' : $zone }}
                                    </span>
                                @endforeach

                                @foreach ($selectedInsulationMaterials as $material)
                                    <span wire:key="selected-material-{{ $material }}" class="inline-flex items-center gap-2 rounded-full bg-zinc-100 px-3 py-1 text-xs font-medium text-zinc-700 dark:bg-zinc-800 dark:text-zinc-200">
                                        Insulation: {{ $material }}
                                    </span>
                                @endforeach

                                @foreach ($selectedStructures as $structure)
                                    <span wire:key="selected-structure-{{ $structure }}" class="inline-flex items-center gap-2 rounded-full bg-zinc-100 px-3 py-1 text-xs font-medium text-zinc-700 dark:bg-zinc-800 dark:text-zinc-200">
                                        Structure: {{ $structure }}
                                    </span>
                                @endforeach

                                @foreach ($selectedExteriors as $exterior)
                                    <span wire:key="selected-exterior-{{ $exterior }}" class="inline-flex items-center gap-2 rounded-full bg-zinc-100 px-3 py-1 text-xs font-medium text-zinc-700 dark:bg-zinc-800 dark:text-zinc-200">
                                        Exterior: {{ $exterior }}
                                    </span>
                                @endforeach

                                @if ($search === '' && $selectedClimateZones === [] && $selectedInsulationMaterials === [] && $selectedStructures === [] && $selectedExteriors === [])
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">No filters selected.</p>
                                @endif
                            </div>
                        </div>

                        <details class="mt-4 block w-full" @if($isClimateSectionOpen) open @endif>
                            <summary wire:click.prevent="toggleClimateSection" class="cursor-pointer text-sm font-medium text-zinc-700 dark:text-zinc-300">Climate Zone</summary>
                            <div class="mt-3 flex flex-wrap gap-3">
                                @foreach (['LT4' => '<4', '4' => '4', '5' => '5', '6' => '6', '7A' => '7A', '7B' => '7B', '8' => '8'] as $zoneValue => $zoneLabel)
                                    <label wire:key="climate-zone-{{ $zoneValue }}" class="inline-flex items-center gap-2 text-sm text-zinc-800 dark:text-zinc-100">
                                        <input
                                            type="checkbox"
                                            value="{{ $zoneValue }}"
                                            wire:model.live="selectedClimateZones"
                                            class="h-4 w-4 rounded border-zinc-400 text-teal-600 focus:ring-teal-500"
                                        />
                                        <span>Zone {{ $zoneLabel }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </details>

                        <details class="mt-4 block w-full" @if($isInsulationSectionOpen) open @endif>
                            <summary wire:click.prevent="toggleInsulationSection" class="cursor-pointer text-sm font-medium text-zinc-700 dark:text-zinc-300">Insulation</summary>
                            <div class="mt-3 flex flex-wrap gap-3">
                                @foreach ($this->insulationMaterials as $insulationMaterial)
                                    <label wire:key="insulation-{{ $insulationMaterial }}" class="inline-flex items-center gap-2 text-sm text-zinc-800 dark:text-zinc-100">
                                        <input
                                            type="checkbox"
                                            value="{{ $insulationMaterial }}"
                                            wire:model.live="selectedInsulationMaterials"
                                            class="h-4 w-4 rounded border-zinc-400 text-teal-600 focus:ring-teal-500"
                                        />
                                        <span>{{ $insulationMaterial }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </details>

                        <details class="mt-4 block w-full" @if($isStructureSectionOpen) open @endif>
                            <summary wire:click.prevent="toggleStructureSection" class="cursor-pointer text-sm font-medium text-zinc-700 dark:text-zinc-300">Structure</summary>
                            <div class="mt-3 flex flex-wrap gap-3">
                                @foreach ($this->structureOptions as $structureOption)
                                    <label wire:key="structure-{{ $structureOption }}" class="inline-flex items-center gap-2 text-sm text-zinc-800 dark:text-zinc-100">
                                        <input
                                            type="checkbox"
                                            value="{{ $structureOption }}"
                                            wire:model.live="selectedStructures"
                                            class="h-4 w-4 rounded border-zinc-400 text-teal-600 focus:ring-teal-500"
                                        />
                                        <span>{{ $structureOption }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </details>

                        <details class="mt-4 block w-full" @if($isExteriorSectionOpen) open @endif>
                            <summary wire:click.prevent="toggleExteriorSection" class="cursor-pointer text-sm font-medium text-zinc-700 dark:text-zinc-300">Exterior</summary>
                            <div class="mt-3 flex flex-col gap-2">
                                @foreach ($this->exteriorOptions as $exteriorOption)
                                    <label wire:key="exterior-{{ $exteriorOption }}" class="inline-flex w-full items-center gap-2 text-sm text-zinc-800 dark:text-zinc-100">
                                        <input
                                            type="checkbox"
                                            value="{{ $exteriorOption }}"
                                            wire:model.live="selectedExteriors"
                                            class="h-4 w-4 rounded border-zinc-400 text-teal-600 focus:ring-teal-500"
                                        />
                                        <span>{{ $exteriorOption }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </details>
                    </div>
                </div>

                <div class="flex-1 p-3 sm:p-5">
                    <div wire:loading.delay class="mb-3 text-sm text-zinc-500 dark:text-zinc-400">Updating results...</div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                        @forelse ($this->walls as $wall)
                            <button
                                type="button"
                                wire:key="wall-{{ $wall->id }}"
                                wire:click="openWallLayers({{ $wall->id }})"
                                class="text-left rounded-2xl border border-zinc-200/80 bg-zinc-50/70 p-4 transition duration-200 cursor-pointer hover:-translate-y-0.5 hover:border-teal-400 hover:bg-white hover:shadow-md focus:outline-none focus:ring-2 focus:ring-teal-500 dark:border-zinc-700 dark:bg-zinc-800/40 dark:hover:border-teal-500 dark:hover:bg-zinc-800/70"
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

                    <div class="mt-5">
                        {{ $this->walls->links() }}
                    </div>
                </div>
            </div>
        </section>
    </main>

    @if ($activeWallId !== null)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-900/60 p-4" wire:click="closeWallLayers">
            <div class="w-[min(900px,95vw)] rounded-2xl border border-zinc-200 bg-white p-5 shadow-2xl dark:border-zinc-700 dark:bg-zinc-900 sm:p-6" wire:click.stop>
                <div class="mb-4 flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-zinc-500 dark:text-zinc-400">Wall Layers</p>
                        <h2 class="mt-1 text-lg font-semibold text-zinc-900 dark:text-zinc-100">{{ $activeWallName !== '' ? $activeWallName : 'Wall' }}</h2>
                    </div>
                    <button type="button" wire:click="closeWallLayers" class="rounded-full border border-zinc-300 px-3 py-1 text-sm dark:border-zinc-600">Close</button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-zinc-200 dark:border-zinc-700">
                                <th class="px-3 py-2 text-left font-semibold">Layer</th>
                                <th class="px-3 py-2 text-left font-semibold">Material Name</th>
                                <th class="px-3 py-2 text-left font-semibold">Thickness</th>
                                <th class="px-3 py-2 text-left font-semibold">Embodied Carbon</th>
                                <th class="px-3 py-2 text-left font-semibold">R Value</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @forelse ($this->activeWallLayers as $layer)
                                <tr wire:key="layer-{{ $activeWallId }}-{{ $layer['layer_number'] }}">
                                    <td class="px-3 py-2">{{ $layer['layer_number'] }}</td>
                                    <td class="px-3 py-2">{{ $layer['material_name'] }}</td>
                                    <td class="px-3 py-2">{{ $layer['layer_thickness'] }}</td>
                                    <td class="px-3 py-2">{{ $layer['embodied_carbon'] }}</td>
                                    <td class="px-3 py-2">{{ $layer['r_value'] ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-3 py-4 text-zinc-500 dark:text-zinc-400">No layer records found for this wall.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>