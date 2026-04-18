<?php

use Database\Seeders\ImportFireRatingFramesSeeder;
use Database\Seeders\ImportFireRatingWallTypesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('fire rating csv data is imported into fire rating tables', function () {
    $this->seed([
        ImportFireRatingWallTypesSeeder::class,
        ImportFireRatingFramesSeeder::class,
    ]);

    expect(DB::table('fire_rating_wall_types')->count())->toBe(5);
    expect(DB::table('fire_rating_frames')->count())->toBe(4);

    $this->assertDatabaseHas('fire_rating_wall_types', [
        'wall_type' => '12.7 mm Type X gypsum board',
        'loadbearing_minutes' => 25,
        'non_loadbearing_minutes' => 25,
    ]);

    $this->assertDatabaseHas('fire_rating_frames', [
        'frame' => 'Wood studs spaced ≤ 400 mm o.c.',
        'loadbearing_minutes' => 20,
        'non_loadbearing_minutes' => 20,
    ]);
});
