<?php

use App\Livewire\HomeWalls;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function makeHomeAdminUser(): User
{
    return User::factory()->admin()->create();
}

function makeHomeRegularUser(): User
{
    return User::factory()->create();
}

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
    $response->assertSeeText('Search Walls');
    $response->assertDontSee('class="dark"', false);
    $response->assertDontSee('"material_name":"Test Insulation"', false);
});

test('home page paginates wall records with light-only controls', function () {
    for ($index = 1; $index <= 17; $index++) {
        DB::table('walls')->insert([
            'id' => $index,
            'Assembly_Description' => 'Wall assembly '.$index,
            'Climate_Zone' => '3A',
            'Wall_Type' => 'Wood Frame',
            'R_Value_U_Value' => 12.5 + $index,
            'Embodied_Carbon' => 8.2 + $index,
            'Fire_Resistance_Rating' => 1.0,
            'Wall_Thickness(m/in)' => 6.0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('Previous');
    $response->assertSee('Next');
});

test('home walls component loads layers on demand and supports filtering', function () {
    DB::table('categories')->insert([
        'id' => 1,
        'name' => 'Insulation',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('materials')->insert([
        [
            'id' => 1,
            'name' => 'Test Insulation',
            'category_id' => 1,
            'Conductivity(W/mK)' => 0.04,
            'KgCO2e' => 12.34,
            'Stud_Spacing' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'id' => 2,
            'name' => 'Air gap 20mm',
            'category_id' => 1,
            'Conductivity(W/mK)' => 0.026,
            'KgCO2e' => 0,
            'Stud_Spacing' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);

    DB::table('walls')->insert([
        [
            'id' => 1,
            'Assembly_Description' => 'Exterior: Vinyl Siding | Structure: Wood Frame | Insulation: Test Insulation',
            'Climate_Zone' => '3A',
            'Wall_Type' => 'Wood Frame',
            'R_Value_U_Value' => 18.5,
            'Embodied_Carbon' => 42.75,
            'Fire_Resistance_Rating' => 2.0,
            'Wall_Thickness(m/in)' => 6.0,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'id' => 2,
            'Assembly_Description' => 'Exterior: Clay Brick | Structure: Concrete | Insulation: Test Insulation',
            'Climate_Zone' => '7A',
            'Wall_Type' => 'Concrete',
            'R_Value_U_Value' => 5.2,
            'Embodied_Carbon' => 12.00,
            'Fire_Resistance_Rating' => 2.0,
            'Wall_Thickness(m/in)' => 8.0,
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);

    DB::table('layers')->insert([
        [
            'wall_id' => 1,
            'material_id' => 1,
            'layer_number' => 1,
            'layer_thickness' => 25.4,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'wall_id' => 1,
            'material_id' => 2,
            'layer_number' => 2,
            'layer_thickness' => 20,
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);

    Livewire::test(HomeWalls::class)
        ->assertSee('Exterior: Vinyl Siding | Structure: Wood Frame | Insulation: Test Insulation')
        ->assertSee('Exterior: Clay Brick | Structure: Concrete | Insulation: Test Insulation')
        ->assertSet('isExteriorSectionOpen', true)
        ->call('toggleExteriorSection')
        ->assertSet('isExteriorSectionOpen', false)
        ->call('toggleExteriorSection')
        ->assertSet('isExteriorSectionOpen', true)
        ->set('selectedStructures', ['Wood Frame'])
        ->assertSee('Exterior: Vinyl Siding | Structure: Wood Frame | Insulation: Test Insulation')
        ->assertDontSee('Exterior: Clay Brick | Structure: Concrete | Insulation: Test Insulation')
        ->set('selectedStructures', [])
        ->set('selectedExteriors', ['Clay Brick'])
        ->assertSee('Exterior: Clay Brick | Structure: Concrete | Insulation: Test Insulation')
        ->assertDontSee('Exterior: Vinyl Siding | Structure: Wood Frame | Insulation: Test Insulation')
        ->call('openWallLayers', 1)
        ->assertSet('activeWallId', 1)
        ->assertSee('Test Insulation')
        ->assertSee('0.635')
        ->assertSee('Air gap 20mm')
        ->assertSee('0.160');
});

test('admin users can delete a wall from the home page', function () {
    $user = makeHomeAdminUser();
    $this->actingAs($user);

    DB::table('categories')->insert([
        'id' => 1,
        'name' => 'Test Category',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('materials')->insert([
        'id' => 1,
        'name' => 'Test Material',
        'category_id' => 1,
        'Conductivity(W/mK)' => 0.04,
        'KgCO2e' => 1.2,
        'Stud_Spacing' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('walls')->insert([
        'id' => 1,
        'Assembly_Description' => 'Test wall assembly',
        'Climate_Zone' => '3A',
        'Wall_Type' => 'Wood Frame',
        'R_Value_U_Value' => 12.5,
        'Embodied_Carbon' => 8.2,
        'Fire_Resistance_Rating' => 1.0,
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

    Livewire::test(HomeWalls::class)
        ->call('openWallLayers', 1)
        ->assertSee('Are you sure you want to delete this wall?', false)
        ->call('deleteWall', 1);

    expect(DB::table('walls')->where('id', 1)->exists())->toBeFalse();
    expect(DB::table('layers')->where('wall_id', 1)->exists())->toBeFalse();
});

test('non-admin users do not see the delete wall button on the home page', function () {
    $user = makeHomeRegularUser();
    $this->actingAs($user);

    DB::table('categories')->insert([
        'id' => 1,
        'name' => 'Test Category',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('materials')->insert([
        'id' => 1,
        'name' => 'Test Material',
        'category_id' => 1,
        'Conductivity(W/mK)' => 0.04,
        'KgCO2e' => 1.2,
        'Stud_Spacing' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('walls')->insert([
        'id' => 1,
        'Assembly_Description' => 'Test wall assembly',
        'Climate_Zone' => '3A',
        'Wall_Type' => 'Wood Frame',
        'R_Value_U_Value' => 12.5,
        'Embodied_Carbon' => 8.2,
        'Fire_Resistance_Rating' => 1.0,
        'Wall_Thickness(m/in)' => 6.0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    Livewire::test(HomeWalls::class)
        ->call('openWallLayers', 1)
        ->assertDontSee('Delete wall');
});
