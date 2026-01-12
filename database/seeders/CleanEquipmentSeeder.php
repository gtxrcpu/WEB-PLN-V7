<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AparSetting;
use App\Models\Apar;
use App\Models\Apab;
use App\Models\Apat;
use App\Models\BoxHydrant;
use App\Models\FireAlarm;
use App\Models\RumahPompa;
use App\Models\P3k;

class CleanEquipmentSeeder extends Seeder
{
    /**
     * Delete all equipment and reset counters to 1
     */
    public function run(): void
    {
        $this->command->warn('⚠️  WARNING: This will DELETE all equipment data!');
        $this->command->info('');

        // Delete all equipment
        $this->command->info('🗑️  Deleting all equipment...');

        $aparCount = Apar::count();
        Apar::truncate();
        $this->command->info("  ✅ Deleted {$aparCount} APAR");

        $apabCount = Apab::count();
        Apab::truncate();
        $this->command->info("  ✅ Deleted {$apabCount} APAB");

        $apatCount = Apat::count();
        Apat::truncate();
        $this->command->info("  ✅ Deleted {$apatCount} APAT");

        $boxCount = BoxHydrant::count();
        BoxHydrant::truncate();
        $this->command->info("  ✅ Deleted {$boxCount} Box Hydrant");

        $fireCount = FireAlarm::count();
        FireAlarm::truncate();
        $this->command->info("  ✅ Deleted {$fireCount} Fire Alarm");

        $pompaCount = RumahPompa::count();
        RumahPompa::truncate();
        $this->command->info("  ✅ Deleted {$pompaCount} Rumah Pompa");

        $p3kCount = P3k::count();
        P3k::truncate();
        $this->command->info("  ✅ Deleted {$p3kCount} P3K");

        $this->command->info('');
        $this->command->info('🔄 Resetting all counters to 1...');

        $units = \App\Models\Unit::all();

        $equipmentTypes = [
            'apar',
            'apab',
            'apat',
            'box-hydrant',
            'fire-alarm',
            'rumah-pompa',
            'p3k',
        ];

        // Reset counter untuk Induk
        foreach ($equipmentTypes as $type) {
            $counterKey = "{$type}_kode_counter_induk";
            AparSetting::set($counterKey, 1);
        }
        $this->command->info('  ✅ Reset counters for Induk');

        // Reset counter untuk setiap unit
        foreach ($units as $unit) {
            foreach ($equipmentTypes as $type) {
                $counterKey = "{$type}_kode_counter_{$unit->id}";
                AparSetting::set($counterKey, 1);
            }
            $this->command->info("  ✅ Reset counters for {$unit->name}");
        }

        $this->command->info('');
        $this->command->info('🎉 Done! All equipment deleted and counters reset to 1');
        $this->command->info('');
        $this->command->info('Next equipment you create will start from 001 for each unit');
    }
}
