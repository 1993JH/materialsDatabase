<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function makeCalculationsAdminUser(): User
{
    return User::factory()->admin()->create();
}

function makeCalculationsRegularUser(): User
{
    return User::factory()->create();
}

test('calculations page is accessible to guests', function () {
    $response = $this->get(route('calculations'));

    $response->assertOk();
    $response->assertSee('Calculations');
    $response->assertSee('Wall Layer Breakdown');
    $response->assertSee('Wall Assembly');
    $response->assertSee('N.O');
    $response->assertSee('Material location');
    $response->assertSee('Materials');
    $response->assertSee('Thickness');
    $response->assertSee('type="number"', false);
    $response->assertSee('Select category');
    $response->assertSee('Select material');
});

test('calculations results display structure instead of intermediate', function () {
    DB::table('categories')->insert([
        'id' => 1,
        'name' => 'Intermediate',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('materials')->insert([
        'id' => 1,
        'name' => 'Gypsum Board',
        'category_id' => 1,
        'Conductivity(W/mK)' => 0.16,
        'KgCO2e' => 2.5,
        'Stud_Spacing' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    Livewire::test('calculations')
        ->set('rows', [
            [
                'id' => 'row-1',
                'category' => 'Intermediate',
                'material' => 'Gypsum Board',
                'thickness' => '25',
            ],
            [
                'id' => 'row-2',
                'category' => '',
                'material' => '',
                'thickness' => '',
            ],
            [
                'id' => 'row-3',
                'category' => '',
                'material' => '',
                'thickness' => '',
            ],
        ])
        ->call('calculate')
        ->assertSee('Structure: Gypsum Board')
        ->assertSee('0.156');
});

test('calculations fire rating uses the membrane rating when no frame is selected', function () {
    DB::table('categories')->insert([
        'id' => 1,
        'name' => 'Intermediate',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('materials')->insert([
        'id' => 1,
        'name' => 'Gypsum Board',
        'category_id' => 1,
        'Conductivity(W/mK)' => 0.16,
        'KgCO2e' => 2.5,
        'Stud_Spacing' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('fire_rating_wall_types')->insert([
        'wall_type' => '12.7 mm Type X gypsum board',
        'loadbearing_minutes' => 25,
        'non_loadbearing_minutes' => 25,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    Livewire::test('calculations')
        ->set('wallType', 'loadbearing')
        ->set('selectedProtectiveMembrane', '12.7 mm Type X gypsum board')
        ->set('selectedFrame', '')
        ->set('rows', [
            [
                'id' => 'row-1',
                'category' => 'Intermediate',
                'material' => 'Gypsum Board',
                'thickness' => '25',
            ],
            [
                'id' => 'row-2',
                'category' => '',
                'material' => '',
                'thickness' => '',
            ],
            [
                'id' => 'row-3',
                'category' => '',
                'material' => '',
                'thickness' => '',
            ],
        ])
        ->call('calculate')
        ->assertSee('25 minutes');
});

test('admin users can save calculated walls and layers to the database', function () {
    $user = makeCalculationsAdminUser();
    $this->actingAs($user);

    DB::table('categories')->insert([
        'id' => 1,
        'name' => 'Intermediate',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('materials')->insert([
        'id' => 1,
        'name' => 'Gypsum Board',
        'category_id' => 1,
        'Conductivity(W/mK)' => 0.16,
        'KgCO2e' => 2.5,
        'Stud_Spacing' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $component = Livewire::test('calculations')
        ->set('rows', [
            [
                'id' => 'row-1',
                'category' => 'Intermediate',
                'material' => 'Gypsum Board',
                'thickness' => '25',
            ],
            [
                'id' => 'row-2',
                'category' => '',
                'material' => '',
                'thickness' => '',
            ],
            [
                'id' => 'row-3',
                'category' => '',
                'material' => '',
                'thickness' => '',
            ],
        ])
        ->call('calculate')
        ->assertSee('Structure: Gypsum Board');

    expect(DB::table('walls')->count())->toBe(0);
    expect(DB::table('layers')->count())->toBe(0);

    $component->call('addWalls');

    expect(DB::table('walls')->count())->toBe(1);
    expect(DB::table('layers')->count())->toBe(1);

    $wall = DB::table('walls')->first();

    expect($wall?->Assembly_Description)->toBe('Structure: Gypsum Board');
    expect($wall?->Climate_Zone)->toBe('Calculated');
    expect($wall?->Wall_Type)->toBe('Calculated Wall');
    expect((float) $wall?->R_Value_U_Value)->toBe(0.15625);
    expect((float) $wall?->Embodied_Carbon)->toBe(2.5);
    expect((float) $wall?->{'Wall_Thickness(m/in)'})->toBe(25.0);

    $layer = DB::table('layers')->first();

    expect($layer?->wall_id)->toBe((int) $wall?->id);
    expect($layer?->material_id)->toBe(1);
    expect($layer?->layer_number)->toBe(1);
    expect((float) $layer?->layer_thickness)->toBe(25.0);
});

test('admin users must confirm before saving calculated walls and see a success message after saving', function () {
    $user = makeCalculationsAdminUser();
    $this->actingAs($user);

    DB::table('categories')->insert([
        'id' => 1,
        'name' => 'Intermediate',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('materials')->insert([
        'id' => 1,
        'name' => 'Gypsum Board',
        'category_id' => 1,
        'Conductivity(W/mK)' => 0.16,
        'KgCO2e' => 2.5,
        'Stud_Spacing' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $component = Livewire::test('calculations')
        ->set('rows', [
            [
                'id' => 'row-1',
                'category' => 'Intermediate',
                'material' => 'Gypsum Board',
                'thickness' => '25',
            ],
            [
                'id' => 'row-2',
                'category' => '',
                'material' => '',
                'thickness' => '',
            ],
            [
                'id' => 'row-3',
                'category' => '',
                'material' => '',
                'thickness' => '',
            ],
        ])
        ->call('calculate')
        ->call('requestAddWalls')
        ->assertSee('Are you sure you want to add this wall?');

    expect(DB::table('walls')->count())->toBe(0);

    $component->call('addWalls')->assertSee('Wall added.');

    expect(DB::table('walls')->count())->toBe(1);
    expect(DB::table('layers')->count())->toBe(1);
});

test('admin users do not create duplicate walls when calculating the same assembly twice', function () {
    $user = makeCalculationsAdminUser();
    $this->actingAs($user);

    DB::table('categories')->insert([
        'id' => 1,
        'name' => 'Intermediate',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('materials')->insert([
        'id' => 1,
        'name' => 'Gypsum Board',
        'category_id' => 1,
        'Conductivity(W/mK)' => 0.16,
        'KgCO2e' => 2.5,
        'Stud_Spacing' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $component = Livewire::test('calculations')
        ->set('rows', [
            [
                'id' => 'row-1',
                'category' => 'Intermediate',
                'material' => 'Gypsum Board',
                'thickness' => '25',
            ],
            [
                'id' => 'row-2',
                'category' => '',
                'material' => '',
                'thickness' => '',
            ],
            [
                'id' => 'row-3',
                'category' => '',
                'material' => '',
                'thickness' => '',
            ],
        ])
        ->call('calculate');

    $component->call('calculate');
    $component->call('addWalls');

    $component->assertSee('This wall already exists in the database.');

    expect(DB::table('walls')->count())->toBe(1);
    expect(DB::table('layers')->count())->toBe(1);
});

test('admin users can save walls with the same name when layer composition differs', function () {
    $user = makeCalculationsAdminUser();
    $this->actingAs($user);

    DB::table('categories')->insert([
        'id' => 1,
        'name' => 'Intermediate',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('materials')->insert([
        'id' => 1,
        'name' => 'Gypsum Board',
        'category_id' => 1,
        'Conductivity(W/mK)' => 0.16,
        'KgCO2e' => 2.5,
        'Stud_Spacing' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $component = Livewire::test('calculations')
        ->set('rows', [
            [
                'id' => 'row-1',
                'category' => 'Intermediate',
                'material' => 'Gypsum Board',
                'thickness' => '25',
            ],
            [
                'id' => 'row-2',
                'category' => '',
                'material' => '',
                'thickness' => '',
            ],
            [
                'id' => 'row-3',
                'category' => '',
                'material' => '',
                'thickness' => '',
            ],
        ])
        ->call('calculate');

    $component
        ->set('rows', [
            [
                'id' => 'row-1',
                'category' => 'Intermediate',
                'material' => 'Gypsum Board',
                'thickness' => '30',
            ],
            [
                'id' => 'row-2',
                'category' => '',
                'material' => '',
                'thickness' => '',
            ],
            [
                'id' => 'row-3',
                'category' => '',
                'material' => '',
                'thickness' => '',
            ],
        ])
        ->call('calculate');

    $component->call('addWalls');

    expect(DB::table('walls')->count())->toBe(2);
    expect(DB::table('layers')->count())->toBe(2);
});

test('non-admin users do not save calculated walls to the database', function () {
    $user = makeCalculationsRegularUser();
    $this->actingAs($user);

    DB::table('categories')->insert([
        'id' => 1,
        'name' => 'Intermediate',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('materials')->insert([
        'id' => 1,
        'name' => 'Gypsum Board',
        'category_id' => 1,
        'Conductivity(W/mK)' => 0.16,
        'KgCO2e' => 2.5,
        'Stud_Spacing' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    Livewire::test('calculations')
        ->set('rows', [
            [
                'id' => 'row-1',
                'category' => 'Intermediate',
                'material' => 'Gypsum Board',
                'thickness' => '25',
            ],
            [
                'id' => 'row-2',
                'category' => '',
                'material' => '',
                'thickness' => '',
            ],
            [
                'id' => 'row-3',
                'category' => '',
                'material' => '',
                'thickness' => '',
            ],
        ])
        ->call('calculate')
        ->assertSee('Structure: Gypsum Board');

    expect(DB::table('walls')->count())->toBe(0);
    expect(DB::table('layers')->count())->toBe(0);
});

test('add walls button only appears after creating a wall', function () {
    $user = makeCalculationsAdminUser();
    $this->actingAs($user);

    DB::table('categories')->insert([
        'id' => 1,
        'name' => 'Intermediate',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('materials')->insert([
        'id' => 1,
        'name' => 'Gypsum Board',
        'category_id' => 1,
        'Conductivity(W/mK)' => 0.16,
        'KgCO2e' => 2.5,
        'Stud_Spacing' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    Livewire::test('calculations')
        ->assertDontSee('add walls')
        ->set('rows', [
            [
                'id' => 'row-1',
                'category' => 'Intermediate',
                'material' => 'Gypsum Board',
                'thickness' => '25',
            ],
            [
                'id' => 'row-2',
                'category' => '',
                'material' => '',
                'thickness' => '',
            ],
            [
                'id' => 'row-3',
                'category' => '',
                'material' => '',
                'thickness' => '',
            ],
        ])
        ->call('calculate')
        ->assertSee('add walls');
});
