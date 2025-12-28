<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hanya 2 unit: UPW2 dan UPW3
        $units = [
            ['code' => 'UPW2', 'name' => 'Unit Pelayanan Wilayah 2', 'description' => 'Unit wilayah 2'],
            ['code' => 'UPW3', 'name' => 'Unit Pelayanan Wilayah 3', 'description' => 'Unit wilayah 3'],
        ];

        foreach ($units as $unit) {
            \App\Models\Unit::firstOrCreate(['code' => $unit['code']], $unit);
        }

        $this->command->info('✅ Units created: UPW2, UPW3');
    }
}
