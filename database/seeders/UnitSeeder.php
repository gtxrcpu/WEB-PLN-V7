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
        // Update unit names to include Induk, UP2WIII and UP2WIV
        $units = [
            ['code' => 'INDUK', 'name' => 'Induk', 'description' => 'Unit Induk (Pusat)'],
            ['code' => 'UPW2', 'name' => 'UP2WIII', 'description' => 'Unit Pelayanan dan Pengelolaan Wilayah III'],
            ['code' => 'UPW3', 'name' => 'UP2WIV', 'description' => 'Unit Pelayanan dan Pengelolaan Wilayah IV'],
        ];

        foreach ($units as $unit) {
            \App\Models\Unit::updateOrCreate(
                ['code' => $unit['code']],
                $unit
            );
        }

        $this->command->info('✅ Units updated: Induk, UP2WIII, UP2WIV');
    }
}
