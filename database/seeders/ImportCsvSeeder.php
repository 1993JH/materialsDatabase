<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImportCsvSeeder extends Seeder
{
    public function run(): void
    {
        $file = fopen(storage_path('app/walls.csv'), 'r');

        if ($file === false) {
            return;
        }

        fgetcsv($file);

        while (($data = fgetcsv($file, 1000, ',')) !== false) {
            DB::table('walls')->insert([
                'Assembly_Description' => $data[0],
                'Climate_Zone' => $data[1],
                'Wall_Type' => $data[2],
                'R_Value_U_Value' => $data[3],
                'Embodied_Carbon' => $data[4],
                'Fire_Resistance_Rating' => $data[5],
                'Wall_Thickness(m/in)' => $data[6],
                'created_at' => now(),
                'updated_at' => now(),

            ]);
        }

        fclose($file);
    }
}
