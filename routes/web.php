<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $walls = DB::table('walls')
        ->select([
            'id',
            'Assembly_Description as assembly_description',
            'Climate_Zone as climate_zone',
            'Wall_Type as wall_type',
            'R_Value_U_Value as r_value',
        ])
        ->orderBy('id')
        ->get();

    $wallFeatureFlags = DB::table('layers')
        ->join('materials', 'materials.id', '=', 'layers.material_id')
        ->join('categories', 'categories.id', '=', 'materials.category_id')
        ->select([
            'layers.wall_id',
            DB::raw("MAX(CASE WHEN LOWER(materials.name) LIKE '%air barrier%' OR LOWER(materials.name) LIKE '%vapour barrier%' OR LOWER(materials.name) LIKE '%vapor barrier%' THEN 1 ELSE 0 END) as has_air_barrier"),
        ])
        ->groupBy('layers.wall_id')
        ->get()
        ->keyBy('wall_id');

    $wallInsulationMaterials = DB::table('layers')
        ->join('materials', 'materials.id', '=', 'layers.material_id')
        ->join('categories', 'categories.id', '=', 'materials.category_id')
        ->whereRaw("LOWER(categories.name) = 'insulation'")
        ->select([
            'layers.wall_id',
            'materials.name as material_name',
        ])
        ->orderBy('materials.name')
        ->get()
        ->groupBy('wall_id')
        ->map(fn ($rows) => $rows->pluck('material_name')
            ->map(fn ($materialName) => mb_strtolower(trim((string) $materialName))
            )
            ->unique()
            ->values()
        );

    $insulationMaterials = $wallInsulationMaterials
        ->flatten()
        ->unique()
        ->sort()
        ->values();

    $walls = $walls->map(function ($wall) use ($wallFeatureFlags, $wallInsulationMaterials) {
        $features = $wallFeatureFlags->get($wall->id);

        $wall->has_air_barrier = (bool) ($features->has_air_barrier ?? 0);
        $wall->insulation_materials = $wallInsulationMaterials->get($wall->id, collect())->implode('|');

        return $wall;
    });

    $wallLayers = DB::table('layers')
        ->join('materials', 'materials.id', '=', 'layers.material_id')
        ->select([
            'layers.wall_id',
            'layers.layer_number',
            'layers.layer_thickness',
            'materials.name as material_name',
            'materials.KgCO2e as embodied_carbon',
            DB::raw('"materials"."Conductivity(W/mK)" as conductivity'),
        ])
        ->orderBy('layers.wall_id')
        ->orderBy('layers.layer_number')
        ->get()
        ->groupBy('wall_id')
        ->map(fn ($rows) => $rows->map(function ($row) {
            $thicknessInMeters = ((float) $row->layer_thickness) / 1000;
            $conductivity = (float) $row->conductivity;
            $rValue = null;

            if ($conductivity > 0) {
                $rValue = floor(($thicknessInMeters / $conductivity) * 10000) / 10000;
            }

            return [
                'layer_number' => $row->layer_number,
                'material_name' => $row->material_name,
                'layer_thickness' => $row->layer_thickness,
                'embodied_carbon' => $row->embodied_carbon,
                'r_value' => $rValue !== null ? number_format($rValue, 4, '.', '') : null,
            ];
        })->values())
        ->toArray();

    return view('welcome', [
        'walls' => $walls,
        'wallLayers' => $wallLayers,
        'insulationMaterials' => $insulationMaterials,
    ]);
})->name('home');
Route::view('/about', 'about')->name('about');
Route::view('/calculations', 'calculations')->name('calculations');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
