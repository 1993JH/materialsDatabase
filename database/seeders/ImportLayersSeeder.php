<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImportLayersSeeder extends Seeder
{
    public function run(): void
    {
        $file = fopen(storage_path('app/layers.csv'), 'r');

        if ($file === false) {
            return;
        }

        fgetcsv($file);

        while (($data = fgetcsv($file, 1000, ',')) !== false) {
            if (! isset($data[0], $data[1], $data[2], $data[3])) {
                continue;
            }

            $wallId = (int) $data[0];
            $materialId = (int) $data[1];

            if (! DB::table('walls')->where('id', $wallId)->exists()) {
                continue;
            }

            if (! DB::table('materials')->where('id', $materialId)->exists()) {
                continue;
            }

            DB::table('layers')->insert([
                'wall_id' => $wallId,
                'material_id' => $materialId,
                'layer_number' => (int) $data[2],
                'layer_thickness' => (float) $data[3],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        fclose($file);
    }
}
