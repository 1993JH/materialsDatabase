<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImportMaterialsSeeder extends Seeder
{
    public function run(): void
    {
        $file = fopen(storage_path('app/materials.csv'), 'r');

        if ($file === false) {
            return;
        }

        fgetcsv($file);

        while (($data = fgetcsv($file, 2000, ',')) !== false) {
            if (! isset($data[0], $data[1], $data[2], $data[3], $data[4])) {
                continue;
            }

            DB::table('materials')->insert([
                'name' => trim((string) $data[0]),
                'category_id' => (int) $data[1],
                'Conductivity(W/mK)' => (float) $data[2],
                'KgCO2e' => (float) $data[3],
                'Stud_Spacing' => (float) $data[4],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        fclose($file);
    }
}
