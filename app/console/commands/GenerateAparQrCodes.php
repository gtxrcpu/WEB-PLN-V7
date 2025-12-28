<?php

namespace App\Console\Commands;

use App\Models\Apar;
use Illuminate\Console\Command;

class GenerateAparQrCodes extends Command
{
    protected $signature = 'apar:generate-qr {--force : Force regenerate all QR codes}';
    protected $description = 'Generate QR codes for all APAR equipment';

    public function handle()
    {
        $force = $this->option('force');
        
        $this->info('Generating QR codes for APAR...');
        
        $apars = Apar::all();
        $total = $apars->count();
        
        if ($total === 0) {
            $this->warn('No APAR found in database.');
            return 0;
        }
        
        $bar = $this->output->createProgressBar($total);
        $bar->start();
        
        $generated = 0;
        
        foreach ($apars as $apar) {
            try {
                $apar->generateQrSvg($force);
                $generated++;
            } catch (\Exception $e) {
                $this->error("\nFailed to generate QR for APAR {$apar->serial_no}: " . $e->getMessage());
            }
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        $this->info("Successfully generated {$generated} QR codes out of {$total} APAR.");
        
        return 0;
    }
}
