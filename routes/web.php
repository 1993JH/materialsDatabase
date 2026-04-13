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

    $wallLayers = DB::table('layers')
        ->join('materials', 'materials.id', '=', 'layers.material_id')
        ->select([
            'layers.wall_id',
            'layers.layer_number',
            'layers.layer_thickness',
            'materials.name as material_name',
            'materials.KgCO2e as embodied_carbon',
        ])
        ->orderBy('layers.wall_id')
        ->orderBy('layers.layer_number')
        ->get()
        ->groupBy('wall_id')
        ->map(fn ($rows) => $rows->map(fn ($row) => [
            'layer_number' => $row->layer_number,
            'material_name' => $row->material_name,
            'layer_thickness' => $row->layer_thickness,
            'embodied_carbon' => $row->embodied_carbon,
        ])->values())
        ->toArray();

    return view('welcome', [
        'walls' => $walls,
        'wallLayers' => $wallLayers,
    ]);
})->name('home');
Route::view('/about', 'about')->name('about');
Route::view('/calculations', 'calculations')->name('calculations');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
