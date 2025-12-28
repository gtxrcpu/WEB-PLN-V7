<?php

namespace App\Console\Commands;

use App\Models\FireAlarm;
use Illuminate\Console\Command;

class GenerateFireAlarmQrCodes extends Command
{
    protected $signature = 'qr:generate-fire-alarm';
    protected $description = 'Generate QR codes for all Fire Alarm records';

    public function handle()
    {
        $this->info('Generating QR codes for Fire Alarm...');
        
        $fireAlarms = FireAlarm::all();
        $count = 0;
        
        foreach ($fireAlarms as $fireAlarm) {
            try {
                $fireAlarm->generateQrSvg(true);
                $count++;
                $this->info("Generated QR for Fire Alarm: {$fireAlarm->serial_no}");
            } catch (\Exception $e) {
                $this->error("Failed to generate QR for Fire Alarm {$fireAlarm->serial_no}: " . $e->getMessage());
            }
        }
        
        $this->info("Successfully generated {$count} QR codes!");
        
        return 0;
    }
}
