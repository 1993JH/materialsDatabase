<?php

use Database\Seeders\ImportCategoriesSeeder;
use Database\Seeders\ImportLayersSeeder;
use Database\Seeders\ImportMaterialsSeeder;
use Database\Seeders\ImportWallsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('layers csv data is imported into layers table', function () {
    $this->seed([
        ImportCategoriesSeeder::class,
        ImportMaterialsSeeder::class,
        ImportWallsSeeder::class,
    ]);

    $this->seed(ImportLayersSeeder::class);

    expect(DB::table('layers')->count())->toBeGreaterThan(0);

    $this->assertDatabaseHas('layers', [
        'wall_id' => 1,
        'material_id' => 1,
        'layer_number' => 1,
        'layer_thickness' => 101.6,
    ]);
});
