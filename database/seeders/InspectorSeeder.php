<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class InspectorSeeder extends Seeder
{
    public function run(): void
    {
        // Create Inspector user (Read-only)
        $inspector = User::firstOrCreate(
            ['email' => 'inspector@pln.co.id'],
            [
                'name' => 'Inspector PLN',
                'username' => 'inspector',
                'password' => Hash::make('inspector123'),
                'unit_id' => null, // Bisa lihat semua unit
                'position' => null,
            ]
        );

        if (!$inspector->hasRole('inspector')) {
            $inspector->assignRole('inspector');
        }

        $this->command->info('');
        $this->command->info('✅ INSPECTOR (Read-only):');
        $this->command->info('   Username: inspector');
        $this->command->info('   Password: inspector123');
        $this->command->info('   Email: inspector@pln.co.id');
    }
}
