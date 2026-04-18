<?php

namespace App\Livewire;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class HomeWalls extends Component
{
    use WithPagination;

    private const float AIR_GAP_R_VALUE = 0.16;

    public string $search = '';

    /**
     * @var array<int, string>
     */
    public array $selectedClimateZones = [];

    /**
     * @var array<int, string>
     */
    public array $selectedInsulationMaterials = [];

    /**
     * @var array<int, string>
     */
    public array $selectedStructures = [];

    /**
     * @var array<int, string>
     */
    public array $selectedExteriors = [];

    public bool $isClimateSectionOpen = true;

    public bool $isInsulationSectionOpen = true;

    public bool $isStructureSectionOpen = true;

    public bool $isExteriorSectionOpen = true;

    public ?int $activeWallId = null;

    public string $activeWallName = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedClimateZones(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedInsulationMaterials(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedStructures(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedExteriors(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset([
            'search',
            'selectedClimateZones',
            'selectedInsulationMaterials',
            'selectedStructures',
            'selectedExteriors',
        ]);

        $this->resetPage();
    }

    public function toggleClimateSection(): void
    {
        $this->isClimateSectionOpen = ! $this->isClimateSectionOpen;
    }

    public function toggleInsulationSection(): void
    {
        $this->isInsulationSectionOpen = ! $this->isInsulationSectionOpen;
    }

    public function toggleStructureSection(): void
    {
        $this->isStructureSectionOpen = ! $this->isStructureSectionOpen;
    }

    public function toggleExteriorSection(): void
    {
        $this->isExteriorSectionOpen = ! $this->isExteriorSectionOpen;
    }

    public function openWallLayers(int $wallId): void
    {
        $this->activeWallId = $wallId;
        $this->activeWallName = (string) DB::table('walls')
            ->where('id', $wallId)
            ->value('Assembly_Description');
    }

    public function closeWallLayers(): void
    {
        $this->activeWallId = null;
        $this->activeWallName = '';
    }

    #[Computed]
    public function insulationMaterials(): Collection
    {
        return DB::table('layers')
            ->join('materials', 'materials.id', '=', 'layers.material_id')
            ->join('categories', 'categories.id', '=', 'materials.category_id')
            ->whereRaw("LOWER(categories.name) = 'insulation'")
            ->select('materials.name')
            ->orderBy('materials.name')
            ->get()
            ->pluck('name')
            ->map(fn (mixed $materialName) => mb_strtolower(trim((string) $materialName)))
            ->filter()
            ->unique()
            ->values();
    }

    #[Computed]
    public function structureOptions(): Collection
    {
        return DB::table('walls')
            ->select('Wall_Type as wall_type')
            ->orderBy('wall_type')
            ->get()
            ->pluck('wall_type')
            ->map(fn (mixed $wallType) => trim((string) $wallType))
            ->filter()
            ->unique()
            ->values();
    }

    #[Computed]
    public function exteriorOptions(): Collection
    {
        return DB::table('walls')
            ->select('Assembly_Description as assembly_description')
            ->orderBy('assembly_description')
            ->get()
            ->pluck('assembly_description')
            ->map(function (mixed $assemblyDescription): ?string {
                $description = trim((string) $assemblyDescription);

                if (preg_match('/Exterior:\s*([^|]+)/i', $description, $matches) !== 1) {
                    return null;
                }

                return trim((string) $matches[1]);
            })
            ->filter()
            ->unique()
            ->values();
    }

    #[Computed]
    public function walls(): LengthAwarePaginator
    {
        $query = DB::table('walls')
            ->select([
                'walls.id',
                DB::raw('walls.Assembly_Description as assembly_description'),
                DB::raw('walls.Climate_Zone as climate_zone'),
                DB::raw('walls.Wall_Type as wall_type'),
                DB::raw('walls.R_Value_U_Value as r_value'),
            ])
            ->orderBy('walls.id');

        if ($this->search !== '') {
            $searchTerm = mb_strtolower(trim($this->search));

            $query->where(function ($builder) use ($searchTerm) {
                $builder
                    ->whereRaw('LOWER(walls.Assembly_Description) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(walls.Climate_Zone) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(walls.Wall_Type) LIKE ?', ["%{$searchTerm}%"]);
            });
        }

        if ($this->selectedClimateZones !== []) {
            $query->where(function ($builder) {
                foreach ($this->selectedClimateZones as $selectedClimateZone) {
                    $zone = mb_strtoupper(trim((string) $selectedClimateZone));

                    if ($zone === '') {
                        continue;
                    }

                    if ($zone === 'LT4') {
                        $builder
                            ->orWhereRaw("LOWER(walls.Climate_Zone) LIKE '%<4%'")
                            ->orWhereRaw("LOWER(walls.Climate_Zone) LIKE '%1%'")
                            ->orWhereRaw("LOWER(walls.Climate_Zone) LIKE '%2%'")
                            ->orWhereRaw("LOWER(walls.Climate_Zone) LIKE '%3%'");

                        continue;
                    }

                    $builder->orWhereRaw('UPPER(walls.Climate_Zone) LIKE ?', ["%{$zone}%"]);
                }
            });
        }

        if ($this->selectedInsulationMaterials !== []) {
            $selectedMaterials = collect($this->selectedInsulationMaterials)
                ->map(fn (mixed $name) => mb_strtolower(trim((string) $name)))
                ->filter()
                ->unique()
                ->values();

            if ($selectedMaterials->isNotEmpty()) {
                $query->whereExists(function ($builder) use ($selectedMaterials) {
                    $builder->selectRaw('1')
                        ->from('layers')
                        ->join('materials', 'materials.id', '=', 'layers.material_id')
                        ->join('categories', 'categories.id', '=', 'materials.category_id')
                        ->whereColumn('layers.wall_id', 'walls.id')
                        ->whereRaw("LOWER(categories.name) = 'insulation'")
                        ->whereIn(DB::raw('LOWER(materials.name)'), $selectedMaterials->all());
                });
            }
        }

        if ($this->selectedStructures !== []) {
            $selectedStructures = collect($this->selectedStructures)
                ->map(fn (mixed $structure) => mb_strtolower(trim((string) $structure)))
                ->filter()
                ->unique()
                ->values();

            if ($selectedStructures->isNotEmpty()) {
                $query->whereIn(DB::raw('LOWER(walls.Wall_Type)'), $selectedStructures->all());
            }
        }

        if ($this->selectedExteriors !== []) {
            $selectedExteriors = collect($this->selectedExteriors)
                ->map(fn (mixed $exterior) => mb_strtolower(trim((string) $exterior)))
                ->filter()
                ->unique()
                ->values();

            if ($selectedExteriors->isNotEmpty()) {
                $query->where(function ($builder) use ($selectedExteriors) {
                    foreach ($selectedExteriors as $selectedExterior) {
                        $builder->orWhereRaw('LOWER(walls.Assembly_Description) LIKE ?', ["%exterior: {$selectedExterior}%"]);
                    }
                });
            }
        }

        return $query->paginate(16);
    }

    #[Computed]
    public function activeWallLayers(): Collection
    {
        if ($this->activeWallId === null) {
            return collect();
        }

        return DB::table('layers')
            ->join('materials', 'materials.id', '=', 'layers.material_id')
            ->where('layers.wall_id', $this->activeWallId)
            ->select([
                'layers.layer_number',
                'layers.layer_thickness',
                'materials.name as material_name',
                'materials.KgCO2e as embodied_carbon',
                DB::raw('"materials"."Conductivity(W/mK)" as conductivity'),
            ])
            ->orderBy('layers.layer_number')
            ->get()
            ->map(function (object $row): array {
                $thicknessInMeters = ((float) $row->layer_thickness) / 1000;
                $conductivity = (float) $row->conductivity;
                $materialName = mb_strtolower(trim((string) $row->material_name));
                $rValue = null;

                if (str_contains($materialName, 'air gap')) {
                    $rValue = self::AIR_GAP_R_VALUE;
                } elseif ($conductivity > 0) {
                    $rValue = floor(($thicknessInMeters / $conductivity) * 10000) / 10000;
                }

                return [
                    'layer_number' => $row->layer_number,
                    'material_name' => $row->material_name,
                    'layer_thickness' => $row->layer_thickness,
                    'embodied_carbon' => $row->embodied_carbon,
                    'r_value' => $rValue !== null ? number_format($rValue, 4, '.', '') : null,
                ];
            });
    }

    public function render()
    {
        return view('livewire.home-walls');
    }
}
