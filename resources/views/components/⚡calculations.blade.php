<?php

use App\Models\categories;
use App\Models\materials;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

new class extends Component
{
    public array $categoryNames = [];

    public array $categoryMaterialMap = [];

    public array $rows = [];

    public array $createdWallAssemblies = [];

    public array $protectiveMembranes = [];

    public array $frames = [];

    public string $selectedProtectiveMembrane = '';

    public string $selectedFrame = '';

    public string $wallType = 'loadbearing';

    public int $minRows = 3;

    public int $maxRows = 10;

    public bool $showAddWallsConfirmation = false;

    public string $wallAddedMessage = '';

    public function mount(): void
    {
        $categories = categories::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        $this->categoryNames = $categories
            ->pluck('name')
            ->values()
            ->all();

        $materialsByCategory = materials::query()
            ->selectRaw('id, name, category_id, KgCO2e, "Conductivity(W/mK)" as conductivity')
            ->orderBy('name')
            ->get()
            ->groupBy('category_id')
            ->map(fn ($materialGroup) => $materialGroup
                ->map(fn ($material) => [
                    'id' => (int) $material->id,
                    'name' => $material->name,
                    'kgco2e' => (float) $material->KgCO2e,
                    'conductivity' => (float) $material->conductivity,
                ])
                ->values()
                ->all());

        $this->categoryMaterialMap = $categories
            ->mapWithKeys(fn ($category) => [
                $category->name => $materialsByCategory->get($category->id, []),
            ])
            ->all();

        $this->protectiveMembranes = DB::table('fire_rating_wall_types')
            ->orderBy('wall_type')
            ->pluck('wall_type')
            ->values()
            ->all();

        $this->frames = DB::table('fire_rating_frames')
            ->orderBy('frame')
            ->pluck('frame')
            ->values()
            ->all();

        $this->rows = [
            $this->blankRow(),
            $this->blankRow(),
            $this->blankRow(),
        ];
    }

    public function addRow(): void
    {
        if (count($this->rows) >= $this->maxRows) {
            return;
        }

        $this->rows[] = $this->blankRow();
    }

    public function removeRow(int $index): void
    {
        if (count($this->rows) <= $this->minRows || ! array_key_exists($index, $this->rows)) {
            return;
        }

        unset($this->rows[$index]);

        $this->rows = array_values($this->rows);
    }

    public function calculate(): void
    {
        $createdWallAssembly = $this->buildCreatedWallAssembly();

        if ($createdWallAssembly === null) {
            return;
        }

        $this->createdWallAssemblies[] = $createdWallAssembly;
    }

    public function requestAddWalls(): void
    {
        if (! Gate::allows('access-admin')) {
            return;
        }

        $this->resetErrorBag('duplicateWall');
        $this->wallAddedMessage = '';
        $this->showAddWallsConfirmation = true;
    }

    public function cancelAddWalls(): void
    {
        $this->showAddWallsConfirmation = false;
    }

    public function addWalls(): void
    {
        if (! Gate::allows('access-admin')) {
            return;
        }

        $this->resetErrorBag('duplicateWall');
        $this->showAddWallsConfirmation = false;

        $addedWallCount = 0;

        foreach ($this->createdWallAssemblies as $createdWallAssembly) {
            if ($this->persistWallAssembly($createdWallAssembly)) {
                $addedWallCount++;
            }
        }

        if ($addedWallCount > 0) {
            $this->wallAddedMessage = 'Wall added.';
        }
    }

    public function materialOptionsForCategory(string $categoryName): array
    {
        return $this->categoryMaterialMap[$categoryName] ?? [];
    }

    public function getCalculatedFireRating(): int
    {
        $minuteColumn = $this->wallType === 'loadbearing' ? 'loadbearing_minutes' : 'non_loadbearing_minutes';
        $membraneRating = 0;
        $frameRating = 0;

        if ($this->selectedProtectiveMembrane !== '') {
            $membraneRating = (int) DB::table('fire_rating_wall_types')
                ->where('wall_type', $this->selectedProtectiveMembrane)
                ->value($minuteColumn);
        }

        if ($this->selectedFrame === '') {
            return $membraneRating;
        }

        $frameRating = (int) DB::table('fire_rating_frames')
            ->where('frame', $this->selectedFrame)
            ->value($minuteColumn);

        return $membraneRating + $frameRating;
    }

    private function blankRow(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'category' => '',
            'material' => '',
            'thickness' => '',
        ];
    }

    private function parseThicknessValue(string $value): float
    {
        $trimmedValue = trim($value);

        if (preg_match('/^(\d+)\s*-\s*(\d+)\/(\d+)$/', $trimmedValue, $matches) === 1) {
            $wholeNumber = (float) $matches[1];
            $numerator = (float) $matches[2];
            $denominator = (float) $matches[3];

            if ($denominator === 0.0) {
                return 0.0;
            }

            return $wholeNumber + ($numerator / $denominator);
        }

        if (preg_match('/^(\d+)\/(\d+)$/', $trimmedValue, $matches) === 1) {
            $numerator = (float) $matches[1];
            $denominator = (float) $matches[2];

            if ($denominator === 0.0) {
                return 0.0;
            }

            return $numerator / $denominator;
        }

        if (preg_match('/^-?\d*\.?\d+/', $trimmedValue, $matches) !== 1) {
            return 0.0;
        }

        $parsedThickness = (float) $matches[0];

        return is_nan($parsedThickness) ? 0.0 : $parsedThickness;
    }

    private function buildCreatedWallAssembly(): ?array
    {
        $selectedLayers = [];

        foreach ($this->rows as $row) {
            $categoryName = trim((string) ($row['category'] ?? ''));
            $materialName = trim((string) ($row['material'] ?? ''));
            $thickness = $this->parseThicknessValue((string) ($row['thickness'] ?? ''));

            if ($categoryName === '' || $materialName === '' || $thickness <= 0) {
                continue;
            }

            $availableMaterial = collect($this->materialOptionsForCategory($categoryName))
                ->firstWhere('name', $materialName);

            if ($availableMaterial === null) {
                continue;
            }

            $conductivity = (float) ($availableMaterial['conductivity'] ?? 0);
            $embodiedCarbon = (float) ($availableMaterial['kgco2e'] ?? 0);

            $selectedLayers[] = [
                'material_id' => (int) ($availableMaterial['id'] ?? 0),
                'category_name' => $categoryName,
                'assembly_segment' => $categoryName.': '.$materialName,
                'conductivity' => $conductivity,
                'embodied_carbon' => $embodiedCarbon,
                'thickness' => $thickness,
            ];
        }

        if ($selectedLayers === []) {
            return null;
        }

        $wallAssemblyName = $this->buildWallAssemblyName($selectedLayers);

        return [
            'wall_assembly' => $wallAssemblyName,
            'r_value' => array_reduce($selectedLayers, fn (float $total, array $layer): float => $total + ($layer['conductivity'] > 0 ? (($layer['thickness'] / 1000) / $layer['conductivity']) : 0), 0.0),
            'embodied_carbon' => array_reduce($selectedLayers, fn (float $total, array $layer): float => $total + $layer['embodied_carbon'], 0.0),
            'thickness' => array_reduce($selectedLayers, fn (float $total, array $layer): float => $total + $layer['thickness'], 0.0),
            'fire_rating' => $this->getCalculatedFireRating(),
            'layers' => $selectedLayers,
        ];
    }

    /**
     * @param  array<int, array{category_name: string, assembly_segment: string}>  $selectedLayers
     */
    private function buildWallAssemblyName(array $selectedLayers): string
    {
        $labelByCategory = [
            'exterior' => 'Exterior',
            'intermediate' => 'Structure',
            'structure' => 'Structure',
            'insulation' => 'Insulation',
        ];

        $segmentByLabel = collect($selectedLayers)
            ->map(function (array $layer) use ($labelByCategory): ?array {
                $category = mb_strtolower(trim($layer['category_name']));
                $label = $labelByCategory[$category] ?? null;

                if ($label === null) {
                    return null;
                }

                $parts = explode(':', (string) $layer['assembly_segment'], 2);
                $materialName = isset($parts[1]) ? trim($parts[1]) : trim($parts[0]);

                if ($materialName === '') {
                    return null;
                }

                return [
                    'label' => $label,
                    'segment' => $label.': '.$materialName,
                ];
            })
            ->filter()
            ->unique('label')
            ->mapWithKeys(fn (array $entry): array => [$entry['label'] => $entry['segment']]);

        $orderedSegments = collect(['Exterior', 'Structure', 'Insulation'])
            ->map(fn (string $label): ?string => $segmentByLabel->get($label))
            ->filter()
            ->values();

        return $orderedSegments->isNotEmpty()
            ? $orderedSegments->implode(' | ')
            : 'Calculated Wall';
    }

    private function persistWallAssembly(array $wallAssembly): bool
    {
        DB::transaction(function () use ($wallAssembly): void {
            if ($this->matchingWallExists($wallAssembly)) {
                $this->addError('duplicateWall', 'This wall already exists in the database.');

                return;
            }

            $wallTypeParts = [];

            if ($this->selectedProtectiveMembrane !== '') {
                $wallTypeParts[] = $this->selectedProtectiveMembrane;
            }

            if ($this->selectedFrame !== '') {
                $wallTypeParts[] = $this->selectedFrame;
            }

            $wallType = implode(' and ', $wallTypeParts) ?: 'Calculated Wall';

            $wallId = (int) DB::table('walls')->insertGetId([
                'Assembly_Description' => (string) $wallAssembly['wall_assembly'],
                'Climate_Zone' => 'Calculated',
                'Wall_Type' => $wallType,
                'R_Value_U_Value' => (float) $wallAssembly['r_value'],
                'Embodied_Carbon' => (float) $wallAssembly['embodied_carbon'],
                'Fire_Resistance_Rating' => 0,
                'Wall_Thickness(m/in)' => (float) $wallAssembly['thickness'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $layerRows = collect($wallAssembly['layers'] ?? [])
                ->values()
                ->map(function (array $layer, int $index) use ($wallId): array {
                    return [
                        'wall_id' => $wallId,
                        'material_id' => (int) $layer['material_id'],
                        'layer_number' => $index + 1,
                        'layer_thickness' => (float) $layer['thickness'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })
                ->all();

            if ($layerRows !== []) {
                DB::table('layers')->insert($layerRows);
            }
        });

        return ! $this->getErrorBag()->has('duplicateWall');
    }

    /**
     * @param  array{wall_assembly: string, layers: array<int, array{material_id:int, thickness: float|int}>}  $wallAssembly
     */
    private function matchingWallExists(array $wallAssembly): bool
    {
        $candidateWallIds = DB::table('walls')
            ->where('Assembly_Description', (string) $wallAssembly['wall_assembly'])
            ->pluck('id');

        if ($candidateWallIds->isEmpty()) {
            return false;
        }

        $expectedLayers = collect($wallAssembly['layers'])
            ->values()
            ->map(function (array $layer, int $index): array {
                return [
                    'layer_number' => $index + 1,
                    'material_id' => (int) ($layer['material_id'] ?? 0),
                    'thickness' => (float) ($layer['thickness'] ?? 0),
                ];
            })
            ->all();

        foreach ($candidateWallIds as $wallId) {
            $existingLayers = DB::table('layers')
                ->where('wall_id', $wallId)
                ->orderBy('layer_number')
                ->get(['layer_number', 'material_id', 'layer_thickness'])
                ->map(fn (object $layer): array => [
                    'layer_number' => (int) $layer->layer_number,
                    'material_id' => (int) $layer->material_id,
                    'thickness' => (float) $layer->layer_thickness,
                ])
                ->all();

            if ($this->layersMatch($expectedLayers, $existingLayers)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  array<int, array{layer_number:int, material_id:int, thickness:float}>  $expectedLayers
     * @param  array<int, array{layer_number:int, material_id:int, thickness:float}>  $existingLayers
     */
    private function layersMatch(array $expectedLayers, array $existingLayers): bool
    {
        if (count($expectedLayers) !== count($existingLayers)) {
            return false;
        }

        foreach ($expectedLayers as $index => $expectedLayer) {
            $existingLayer = $existingLayers[$index] ?? null;

            if ($existingLayer === null) {
                return false;
            }

            if ($expectedLayer['layer_number'] !== $existingLayer['layer_number']) {
                return false;
            }

            if ($expectedLayer['material_id'] !== $existingLayer['material_id']) {
                return false;
            }

            if (abs($expectedLayer['thickness'] - $existingLayer['thickness']) > 0.0001) {
                return false;
            }
        }

        return true;
    }
};
?>

<div class="space-y-8">
<section class="mt-8 overflow-hidden rounded-3xl border border-zinc-200/80 bg-white/90 shadow-sm">
    <div class="border-b border-zinc-200/70 px-6 py-5 md:px-8">
        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-cyan-700">Material Table</p>
        <div class="mt-2 flex items-center justify-between gap-4">
            <h2 class="text-xl font-semibold text-zinc-900">Wall Layer Breakdown</h2>
            <button
                type="button"
                wire:click="addRow"
                @disabled(count($rows) >= $maxRows)
                class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-zinc-300 bg-white text-xl leading-none text-zinc-800 transition hover:bg-zinc-100 disabled:cursor-not-allowed disabled:opacity-40"
                aria-label="Add row"
            >
                +
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm">
            <thead class="bg-zinc-100/80 text-zinc-700">
                <tr>
                    <th class="w-14 px-2 py-3 text-center font-semibold md:px-3">N.O</th>
                    <th class="px-6 py-3 font-semibold md:px-8">Material location</th>
                    <th class="px-6 py-3 font-semibold md:px-8">Materials</th>
                    <th class="px-6 py-3 font-semibold md:px-8">Thickness</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200/70">
                @foreach ($rows as $index => $row)
                    <tr wire:key="material-row-{{ $row['id'] }}">
                        <td class="w-14 px-2 py-3 text-center md:px-3">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-zinc-100 text-[11px] font-semibold text-zinc-700">{{ $index + 1 }}</span>
                        </td>
                        <td class="px-6 py-3 md:px-8">
                            <select
                                wire:model.live="rows.{{ $index }}.category"
                                class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-800"
                            >
                                <option value="">Select category</option>
                                @foreach ($categoryNames as $categoryName)
                                    <option value="{{ $categoryName }}">{{ $categoryName }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-6 py-3 md:px-8">
                            <select
                                wire:model.live="rows.{{ $index }}.material"
                                class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-800 disabled:cursor-not-allowed disabled:bg-zinc-100"
                                @disabled(($row['category'] ?? '') === '')
                            >
                                <option value="">Select material</option>
                                @foreach ($this->materialOptionsForCategory($row['category'] ?? '') as $material)
                                    <option value="{{ $material['name'] }}">{{ $material['name'] }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-6 py-3 md:px-8">
                            <div class="flex items-center gap-3">
                                <input
                                    type="number"
                                    wire:model.blur="rows.{{ $index }}.thickness"
                                    placeholder="Enter thickness (in/mm)"
                                    min="0"
                                    step="any"
                                    inputmode="decimal"
                                    class="w-44 shrink-0 rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-800 shadow-sm outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20"
                                >
                                <button
                                    type="button"
                                    wire:click="removeRow({{ $index }})"
                                    @disabled(count($rows) <= $minRows)
                                    class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full border border-zinc-300 bg-white text-base leading-none text-zinc-700 transition hover:bg-zinc-100 disabled:cursor-not-allowed disabled:opacity-40"
                                    aria-label="Remove this layer"
                                >
                                    -
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="border-t border-zinc-200/70 px-6 py-5 md:px-8">
        <div class="space-y-4">
            <div>
                <p class="text-sm font-medium text-zinc-700">Wall Type Classification</p>
                <div class="mt-3 flex items-center gap-6">
                    <label class="flex cursor-pointer items-center gap-2">
                        <input
                            type="radio"
                            wire:model="wallType"
                            value="loadbearing"
                            class="h-4 w-4 border-zinc-300 text-cyan-600 transition focus:ring-2 focus:ring-cyan-500/30"
                        >
                        <span class="text-sm text-zinc-700">Loadbearing</span>
                    </label>
                    <label class="flex cursor-pointer items-center gap-2">
                        <input
                            type="radio"
                            wire:model="wallType"
                            value="non-loadbearing"
                            class="h-4 w-4 border-zinc-300 text-cyan-600 transition focus:ring-2 focus:ring-cyan-500/30"
                        >
                        <span class="text-sm text-zinc-700">Non-Loadbearing</span>
                    </label>
                </div>
            </div>

            <div class="border-t border-zinc-200/70 pt-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700">Protective Membranes</label>
                        <select
                            wire:model="selectedProtectiveMembrane"
                            class="mt-2 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-800"
                        >
                            <option value="">Select protective membrane</option>
                            @foreach ($protectiveMembranes as $membrane)
                                <option value="{{ $membrane }}">{{ $membrane }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700">Frame</label>
                        <select
                            wire:model="selectedFrame"
                            class="mt-2 w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-800"
                        >
                            <option value="">Select frame</option>
                            @foreach ($frames as $frame)
                                <option value="{{ $frame }}">{{ $frame }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="border-t border-zinc-200/70 px-6 py-5 md:px-8">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <button
                type="button"
                wire:click="calculate"
                wire:loading.attr="disabled"
                class="inline-flex items-center justify-center rounded-full bg-cyan-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/30 disabled:cursor-not-allowed disabled:opacity-70"
            >
                create wall
            </button>

            @can('access-admin')
                @if ($createdWallAssemblies !== [])
                    <button
                        type="button"
                        wire:click="requestAddWalls"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center justify-center rounded-full border border-zinc-300 bg-white px-5 py-2.5 text-sm font-semibold text-zinc-900 transition hover:bg-zinc-100 focus:outline-none focus:ring-2 focus:ring-zinc-500/30 disabled:cursor-not-allowed disabled:opacity-70"
                    >
                        add walls
                    </button>
                @endif
            @endcan
        </div>

        @if ($showAddWallsConfirmation)
            <div class="mt-4 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-4 text-amber-900">
                <p class="text-sm font-semibold">Are you sure you want to add this wall?</p>
                <div class="mt-3 flex flex-wrap gap-3">
                    <button
                        type="button"
                        wire:click="addWalls"
                        class="inline-flex items-center justify-center rounded-full bg-amber-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-amber-500"
                    >
                        confirm add
                    </button>
                    <button
                        type="button"
                        wire:click="cancelAddWalls"
                        class="inline-flex items-center justify-center rounded-full border border-amber-300 bg-white px-4 py-2 text-sm font-semibold text-amber-900 transition hover:bg-amber-100"
                    >
                        cancel
                    </button>
                </div>
            </div>
        @endif

        @if ($wallAddedMessage !== '')
            <p class="mt-4 text-sm font-semibold text-emerald-600">{{ $wallAddedMessage }}</p>
        @endif

            @error('duplicateWall')
                <p class="mt-3 text-sm font-medium text-red-600">{{ $message }}</p>
            @enderror
    </div>
</section>

<section class="overflow-hidden rounded-3xl border border-zinc-200/80 bg-white/90 shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm">
            <thead class="bg-zinc-100/80 text-zinc-700">
                <tr>
                    <th class="px-6 py-3 font-semibold md:px-8">Wall Assembly</th>
                    <th class="px-6 py-3 font-semibold md:px-8">Thickness</th>
                    <th class="px-6 py-3 font-semibold md:px-8">Embodied Carbon</th>
                    <th class="px-6 py-3 font-semibold md:px-8">R Value</th>
                    <th class="px-6 py-3 font-semibold md:px-8">Fire Rating</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200/70">
                @forelse ($createdWallAssemblies as $wallAssembly)
                    <tr>
                        <td class="px-6 py-3 text-zinc-800 md:px-8">{{ preg_replace('/\bIntermediate\b/i', 'Structure', (string) $wallAssembly['wall_assembly']) }}</td>
                        <td class="px-6 py-3 text-zinc-700 md:px-8">{{ number_format((float) $wallAssembly['thickness'], 2) }}</td>
                        <td class="px-6 py-3 text-zinc-700 md:px-8">{{ number_format((float) $wallAssembly['embodied_carbon'], 2) }}</td>
                        <td class="px-6 py-3 text-zinc-700 md:px-8">{{ number_format((float) $wallAssembly['r_value'], 3, '.', '') }}</td>
                        <td class="px-6 py-3 text-zinc-700 md:px-8">{{ $wallAssembly['fire_rating'] > 0 ? $wallAssembly['fire_rating'].' minutes' : '0' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-sm text-zinc-500 md:px-8">
                            No walls created yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
</div>
