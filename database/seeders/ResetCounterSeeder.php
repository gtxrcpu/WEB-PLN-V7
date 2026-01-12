<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AparSetting;

class ResetCounterSeeder extends Seeder
{
    /**
     * Reset all equipment counters to 1 for each unit
     */
    public function run(): void
    {
        $units = \App\Models\Unit::all();

        // Equipment types yang perlu di-reset counternya
        $equipmentTypes = [
            'apar',
            'apab',
            'apat',
            'box-hydrant',
            'fire-alarm',
            'rumah-pompa',
            'p3k',
        ];

        $this->command->info('🔄 Resetting all equipment counters...');
        $this->command->info('');

        // Reset counter untuk Induk (null unit_id)
        foreach ($equipmentTypes as $type) {
            $counterKey = "{$type}_kode_counter_induk";
            AparSetting::set($counterKey, 1);
            $this->command->info("✅ Reset {$counterKey} = 1");
        }

        // Reset counter untuk setiap unit
        foreach ($units as $unit) {
            $this->command->info('');
            $this->command->info("📦 Unit: {$unit->name} (ID: {$unit->id})");

            foreach ($equipmentTypes as $type) {
                $counterKey = "{$type}_kode_counter_{$unit->id}";
                AparSetting::set($counterKey, 1);
                $this->command->info("  ✅ Reset {$counterKey} = 1");
            }
        }

        $this->command->info('');
        $this->command->info('🎉 All counters reset to 1!');
        $this->command->info('');
        $this->command->info('Next equipment created will start from 001');
    }
}
