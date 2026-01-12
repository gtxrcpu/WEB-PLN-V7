<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil unit
        $induk = Unit::where('code', 'INDUK')->first();
        $upw2 = Unit::where('code', 'UPW2')->first();
        $upw3 = Unit::where('code', 'UPW3')->first();

        // 1. SUPERADMIN (di Induk) - Full access ke semua unit
        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@pln.co.id'],
            [
                'name' => 'Super Administrator',
                'username' => 'superadmin',
                'password' => Hash::make('super123'),
                'unit_id' => null, // Tidak terikat unit
                'position' => null,
            ]
        );
        if (!$superadmin->hasRole('superadmin')) {
            $superadmin->assignRole('superadmin');
        }

        // 2. LEADER INDUK (Admin Induk) - Bisa approval TTD
        $leaderInduk = User::firstOrCreate(
            ['email' => 'leader.induk@pln.co.id'],
            [
                'name' => 'Leader Induk',
                'username' => 'leader_induk',
                'password' => Hash::make('leader123'),
                'unit_id' => $induk?->id,
                'position' => 'leader',
            ]
        );
        if (!$leaderInduk->hasRole('leader')) {
            $leaderInduk->assignRole('leader');
        }

        // 3. LEADER UPW2 (Admin Unit 2) - Bisa approval TTD
        $leaderUpw2 = User::firstOrCreate(
            ['email' => 'leader.upw2@pln.co.id'],
            [
                'name' => 'Leader UPW2',
                'username' => 'leader_upw2',
                'password' => Hash::make('leader123'),
                'unit_id' => $upw2?->id,
                'position' => 'leader',
            ]
        );
        if (!$leaderUpw2->hasRole('leader')) {
            $leaderUpw2->assignRole('leader');
        }

        // 4. LEADER UPW3 (Admin Unit 3) - Bisa approval TTD
        $leaderUpw3 = User::firstOrCreate(
            ['email' => 'leader.upw3@pln.co.id'],
            [
                'name' => 'Leader UPW3',
                'username' => 'leader_upw3',
                'password' => Hash::make('leader123'),
                'unit_id' => $upw3?->id,
                'position' => 'leader',
            ]
        );
        if (!$leaderUpw3->hasRole('leader')) {
            $leaderUpw3->assignRole('leader');
        }

        // 5. PETUGAS INDUK - Input data
        $petugasInduk = User::firstOrCreate(
            ['email' => 'petugas.induk@pln.co.id'],
            [
                'name' => 'Petugas Induk',
                'username' => 'petugas_induk',
                'password' => Hash::make('petugas123'),
                'unit_id' => $induk?->id,
                'position' => 'petugas',
            ]
        );
        if (!$petugasInduk->hasRole('petugas')) {
            $petugasInduk->assignRole('petugas');
        }

        // 6. PETUGAS UPW2 (User biasa) - Input data
        $petugasUpw2 = User::firstOrCreate(
            ['email' => 'petugas.upw2@pln.co.id'],
            [
                'name' => 'Petugas UPW2',
                'username' => 'petugas_upw2',
                'password' => Hash::make('petugas123'),
                'unit_id' => $upw2?->id,
                'position' => 'petugas',
            ]
        );
        if (!$petugasUpw2->hasRole('petugas')) {
            $petugasUpw2->assignRole('petugas');
        }

        // 7. PETUGAS UPW3 (User biasa) - Input data
        $petugasUpw3 = User::firstOrCreate(
            ['email' => 'petugas.upw3@pln.co.id'],
            [
                'name' => 'Petugas UPW3',
                'username' => 'petugas_upw3',
                'password' => Hash::make('petugas123'),
                'unit_id' => $upw3?->id,
                'position' => 'petugas',
            ]
        );
        if (!$petugasUpw3->hasRole('petugas')) {
            $petugasUpw3->assignRole('petugas');
        }

        $this->command->info('');
        $this->command->info('✅ SUPERADMIN (Induk):');
        $this->command->info('   Username: superadmin');
        $this->command->info('   Password: super123');
        $this->command->info('   Email: superadmin@pln.co.id');
        $this->command->info('');
        $this->command->info('✅ LEADER INDUK:');
        $this->command->info('   Username: leader_induk');
        $this->command->info('   Password: leader123');
        $this->command->info('   Email: leader.induk@pln.co.id');
        $this->command->info('');
        $this->command->info('✅ LEADER UPW2 (UP2WIII):');
        $this->command->info('   Username: leader_upw2');
        $this->command->info('   Password: leader123');
        $this->command->info('   Email: leader.upw2@pln.co.id');
        $this->command->info('');
        $this->command->info('✅ LEADER UPW3 (UP2WIV):');
        $this->command->info('   Username: leader_upw3');
        $this->command->info('   Password: leader123');
        $this->command->info('   Email: leader.upw3@pln.co.id');
        $this->command->info('');
        $this->command->info('✅ PETUGAS INDUK:');
        $this->command->info('   Username: petugas_induk');
        $this->command->info('   Password: petugas123');
        $this->command->info('   Email: petugas.induk@pln.co.id');
        $this->command->info('');
        $this->command->info('✅ PETUGAS UPW2:');
        $this->command->info('   Username: petugas_upw2');
        $this->command->info('   Password: petugas123');
        $this->command->info('   Email: petugas.upw2@pln.co.id');
        $this->command->info('');
        $this->command->info('✅ PETUGAS UPW3:');
        $this->command->info('   Username: petugas_upw3');
        $this->command->info('   Password: petugas123');
        $this->command->info('   Email: petugas.upw3@pln.co.id');
    }
}
