<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('home page displays wall records from walls table', function () {
    DB::table('categories')->insert([
        'id' => 1,
        'name' => 'Test Category',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('materials')->insert([
        'id' => 1,
        'name' => 'Test Insulation',
        'category_id' => 1,
        'Conductivity(W/mK)' => 0.04,
        'KgCO2e' => 12.34,
        'Stud_Spacing' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('walls')->insert([
        'id' => 1,
        'Assembly_Description' => 'Exterior insulated wall',
        'Climate_Zone' => '3A',
        'Wall_Type' => 'Wood Frame',
        'R_Value_U_Value' => 18.5,
        'Embodied_Carbon' => 42.75,
        'Fire_Resistance_Rating' => 2.0,
        'Wall_Thickness(m/in)' => 6.0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('layers')->insert([
        'wall_id' => 1,
        'material_id' => 1,
        'layer_number' => 1,
        'layer_thickness' => 25.4,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSeeText('Exterior insulated wall');
    $response->assertSeeText('3A');
    $response->assertSeeText('Wood Frame');
    $response->assertSeeText('Material Name');
    $response->assertSeeText('Embodied Carbon');
    $response->assertSeeText('R Value');
    $response->assertSee('"material_name":"Test Insulation"', false);
    $response->assertSee('"r_value":"0.6350"', false);
});
