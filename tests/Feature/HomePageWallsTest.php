<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('home page displays wall records from walls table', function () {
    DB::table('walls')->insert([
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

    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSeeText('Exterior insulated wall');
    $response->assertSeeText('3A');
    $response->assertSeeText('Wood Frame');
});
