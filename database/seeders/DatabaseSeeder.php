<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Urutan seeder yang benar
        $this->call([
            UnitSeeder::class,
            RolePermissionSeeder::class,
            AdminSeeder::class,
            InspectorSeeder::class,
            // ItemSeeder::class, // Disable dulu, tidak diperlukan untuk sistem baru
        ]);
    }
}
