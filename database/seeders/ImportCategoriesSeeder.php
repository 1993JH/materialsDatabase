<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImportCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $file = fopen(storage_path('app/categories.csv'), 'r');

        if ($file === false) {
            return;
        }

        fgetcsv($file);

        while (($data = fgetcsv($file, 1000, ',')) !== false) {
            if (! isset($data[0], $data[1])) {
                continue;
            }

            DB::table('categories')->updateOrInsert(
                ['id' => (int) $data[0]],
                [
                    'name' => trim((string) $data[1]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        fclose($file);
    }
}
