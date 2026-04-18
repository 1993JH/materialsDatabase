<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImportFireRatingFramesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = fopen(storage_path('app/fire_rating_frame.csv'), 'r');

        if ($file === false) {
            return;
        }

        fgetcsv($file);

        while (($data = fgetcsv($file, 1000, ',')) !== false) {
            if (! isset($data[0], $data[1], $data[2])) {
                continue;
            }

            DB::table('fire_rating_frames')->insert([
                'frame' => trim((string) $data[0]),
                'loadbearing_minutes' => (int) $data[1],
                'non_loadbearing_minutes' => (int) $data[2],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        fclose($file);
    }
}
