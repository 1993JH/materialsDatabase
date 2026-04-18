<?php

use App\Models\categories;
use App\Models\materials;
use Illuminate\Support\Str;
use Livewire\Component;

new class extends Component
{
    public array $categoryNames = [];

    public array $categoryMaterialMap = [];

    public array $rows = [];

    public array $createdWallAssemblies = [];

    public int $minRows = 3;

    public int $maxRows = 10;

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
            ->selectRaw('name, category_id, KgCO2e, "Conductivity(W/mK)" as conductivity')
            ->orderBy('name')
            ->get()
            ->groupBy('category_id')
            ->map(fn ($materialGroup) => $materialGroup
                ->map(fn ($material) => [
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

    public function materialOptionsForCategory(string $categoryName): array
    {
        return $this->categoryMaterialMap[$categoryName] ?? [];
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
                'assembly_segment' => $categoryName.': '.$materialName,
                'conductivity' => $conductivity,
                'embodied_carbon' => $embodiedCarbon,
                'thickness' => $thickness,
            ];
        }

        if ($selectedLayers === []) {
            return null;
        }

        return [
            'wall_assembly' => implode(' | ', array_column($selectedLayers, 'assembly_segment')),
            'r_value' => array_reduce($selectedLayers, fn (float $total, array $layer): float => $total + ($layer['conductivity'] > 0 ? $layer['thickness'] / $layer['conductivity'] : 0), 0.0),
            'embodied_carbon' => array_reduce($selectedLayers, fn (float $total, array $layer): float => $total + $layer['embodied_carbon'], 0.0),
            'thickness' => array_reduce($selectedLayers, fn (float $total, array $layer): float => $total + $layer['thickness'], 0.0),
            'fire_rating' => null,
        ];
    }
};
?>

<div class="space-y-8">
<section class="mt-8 overflow-hidden rounded-3xl border border-zinc-200/80 bg-white/90 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/80">
    <div class="border-b border-zinc-200/70 px-6 py-5 dark:border-zinc-800 md:px-8">
        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-cyan-700 dark:text-cyan-300">Material Table</p>
        <div class="mt-2 flex items-center justify-between gap-4">
            <h2 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">Wall Layer Breakdown</h2>
            <button
                type="button"
                wire:click="addRow"
                @disabled(count($rows) >= $maxRows)
                class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-zinc-300 bg-white text-xl leading-none text-zinc-800 transition hover:bg-zinc-100 disabled:cursor-not-allowed disabled:opacity-40 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100 dark:hover:bg-zinc-800"
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
            <tbody class="divide-y divide-zinc-200/70 dark:divide-zinc-800">
                @foreach ($rows as $index => $row)
                    <tr wire:key="material-row-{{ $row['id'] }}">
                        <td class="px-6 py-3 md:px-8">
                            <select
                                wire:model.live="rows.{{ $index }}.category"
                                class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-800 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100"
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
                                class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-800 disabled:cursor-not-allowed disabled:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100 dark:disabled:bg-zinc-800"
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
                                    class="w-44 shrink-0 rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-800 shadow-sm outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100"
                                >
                                <button
                                    type="button"
                                    wire:click="removeRow({{ $index }})"
                                    @disabled(count($rows) <= $minRows)
                                    class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full border border-zinc-300 bg-white text-base leading-none text-zinc-700 transition hover:bg-zinc-100 disabled:cursor-not-allowed disabled:opacity-40 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100 dark:hover:bg-zinc-800"
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

    <div class="border-t border-zinc-200/70 px-6 py-5 dark:border-zinc-800 md:px-8">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <button
                type="button"
                wire:click="calculate"
                wire:loading.attr="disabled"
                class="inline-flex items-center justify-center rounded-full bg-cyan-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/30 disabled:cursor-not-allowed disabled:opacity-70"
            >
                create wall
            </button>
        </div>
    </div>
</section>

<section class="overflow-hidden rounded-3xl border border-zinc-200/80 bg-white/90 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/80">
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
            <tbody class="divide-y divide-zinc-200/70 dark:divide-zinc-800">
                @forelse ($createdWallAssemblies as $wallAssembly)
                    <tr>
                        <td class="px-6 py-3 text-zinc-800 dark:text-zinc-100 md:px-8">{{ preg_replace('/\bIntermediate\b/i', 'Structure', (string) $wallAssembly['wall_assembly']) }}</td>
                        <td class="px-6 py-3 text-zinc-700 dark:text-zinc-200 md:px-8">{{ number_format((float) $wallAssembly['r_value'], 2) }}</td>
                        <td class="px-6 py-3 text-zinc-700 dark:text-zinc-200 md:px-8">{{ number_format((float) $wallAssembly['embodied_carbon'], 2) }}</td>
                        <td class="px-6 py-3 text-zinc-700 dark:text-zinc-200 md:px-8">{{ number_format((float) $wallAssembly['thickness'], 2) }}</td>
                        <td class="px-6 py-3 text-zinc-700 dark:text-zinc-200 md:px-8">{{ $wallAssembly['fire_rating'] ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400 md:px-8">
                            No walls created yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
</div>
