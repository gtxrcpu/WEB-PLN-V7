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
        $units = [
            ['code' => 'UP2W1', 'name' => 'Unit Pelayanan Pelanggan Wilayah 1', 'description' => 'Unit wilayah 1'],
            ['code' => 'UP2W2', 'name' => 'Unit Pelayanan Pelanggan Wilayah 2', 'description' => 'Unit wilayah 2'],
            ['code' => 'UP2W3', 'name' => 'Unit Pelayanan Pelanggan Wilayah 3', 'description' => 'Unit wilayah 3'],
            ['code' => 'UP2W4', 'name' => 'Unit Pelayanan Pelanggan Wilayah 4', 'description' => 'Unit wilayah 4'],
            ['code' => 'UP2W5', 'name' => 'Unit Pelayanan Pelanggan Wilayah 5', 'description' => 'Unit wilayah 5'],
            ['code' => 'UP2W6', 'name' => 'Unit Pelayanan Pelanggan Wilayah 6', 'description' => 'Unit wilayah 6'],
        ];

        foreach ($units as $unit) {
            \App\Models\Unit::create($unit);
        }
    }
}
