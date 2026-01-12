<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Unit;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class InspectorSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil unit
        $induk = Unit::where('code', 'INDUK')->first();
        $upw2 = Unit::where('code', 'UPW2')->first();
        $upw3 = Unit::where('code', 'UPW3')->first();

        // 1. INSPECTOR INDUK
        $inspectorInduk = User::firstOrCreate(
            ['email' => 'inspector.induk@pln.co.id'],
            [
                'name' => 'Inspector Induk',
                'username' => 'inspector_induk',
                'password' => Hash::make('inspector123'),
                'unit_id' => $induk?->id,
                'position' => 'inspector',
            ]
        );
        if (!$inspectorInduk->hasRole('inspector')) {
            $inspectorInduk->assignRole('inspector');
        }

        // 2. INSPECTOR UPW2 (UP2WIII)
        $inspectorUpw2 = User::firstOrCreate(
            ['email' => 'inspector.upw2@pln.co.id'],
            [
                'name' => 'Inspector UPW2',
                'username' => 'inspector_upw2',
                'password' => Hash::make('inspector123'),
                'unit_id' => $upw2?->id,
                'position' => 'inspector',
            ]
        );
        if (!$inspectorUpw2->hasRole('inspector')) {
            $inspectorUpw2->assignRole('inspector');
        }

        // 3. INSPECTOR UPW3 (UP2WIV)
        $inspectorUpw3 = User::firstOrCreate(
            ['email' => 'inspector.upw3@pln.co.id'],
            [
                'name' => 'Inspector UPW3',
                'username' => 'inspector_upw3',
                'password' => Hash::make('inspector123'),
                'unit_id' => $upw3?->id,
                'position' => 'inspector',
            ]
        );
        if (!$inspectorUpw3->hasRole('inspector')) {
            $inspectorUpw3->assignRole('inspector');
        }

        $this->command->info('');
        $this->command->info('✅ INSPECTOR INDUK:');
        $this->command->info('   Username: inspector_induk');
        $this->command->info('   Password: inspector123');
        $this->command->info('   Email: inspector.induk@pln.co.id');
        $this->command->info('');
        $this->command->info('✅ INSPECTOR UPW2 (UP2WIII):');
        $this->command->info('   Username: inspector_upw2');
        $this->command->info('   Password: inspector123');
        $this->command->info('   Email: inspector.upw2@pln.co.id');
        $this->command->info('');
        $this->command->info('✅ INSPECTOR UPW3 (UP2WIV):');
        $this->command->info('   Username: inspector_upw3');
        $this->command->info('   Password: inspector123');
        $this->command->info('   Email: inspector.upw3@pln.co.id');
    }
}
