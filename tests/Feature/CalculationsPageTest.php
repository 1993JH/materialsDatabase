<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('calculations page is accessible to guests', function () {
    $response = $this->get(route('calculations'));

    $response->assertOk();
    $response->assertSee('Calculations');
    $response->assertSee('Wall Layer Breakdown');
    $response->assertSee('Wall Assembly');
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
        ->assertSee('Structure: Gypsum Board');
});
