<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ValidateProductionConfig extends Command
{
    protected $signature = 'app:validate-production-config';
    protected $description = 'Validate production configuration and environment settings';

    private array $results = [];
    private int $passed = 0;
    private int $failed = 0;
    private int $warnings = 0;

    public function handle()
    {
        $this->info('===========================================');
        $this->info('  Production Configuration Validator');
        $this->info('===========================================');
        $this->newLine();

        // Run all validation checks
        $this->checkEnvironmentSettings();
        $this->checkStoragePermissions();
        $this->checkCachePermissions();
        $this->checkStorageLink();
        $this->checkLaravelCaches();
        $this->checkPhpVersion();
        $this->checkPhpExtensions();

        // Display results
        $this->displayResults();

        // Return appropriate exit code
        return $this->failed > 0 ? 1 : 0;
    }

    private function checkEnvironmentSettings(): void
    {
        $this->info('Checking Environment Settings...');
        
        // Check APP_DEBUG
        $appDebug = config('app.debug');
        if ($appDebug === false) {
            $this->checkPass('APP_DEBUG is set to false');
        } else {
            $this->checkFail('APP_DEBUG should be false in production (currently: ' . ($appDebug ? 'true' : 'false') . ')');
        }

        // Check APP_ENV
        $appEnv = config('app.env');
        if ($appEnv === 'production') {
            $this->checkPass('APP_ENV is set to production');
        } else {
            $this->checkFail('APP_ENV should be "production" (currently: ' . $appEnv . ')');
        }

        // Check APP_KEY
        $appKey = config('app.key');
        if (!empty($appKey) && strlen($appKey) >= 32) {
            $this->checkPass('APP_KEY is set and valid');
        } else {
            $this->checkFail('APP_KEY is missing or invalid');
        }

        // Check database configuration
        $dbConnection = config('database.default');
        $dbHost = config("database.connections.{$dbConnection}.host");
        $dbDatabase = config("database.connections.{$dbConnection}.database");
        
        if (!empty($dbHost) && !empty($dbDatabase)) {
            $this->checkPass('Database configuration is set');
        } else {
            $this->checkFail('Database configuration is incomplete');
        }

        $this->newLine();
    }

    private function checkStoragePermissions(): void
    {
        $this->info('Checking Storage Permissions...');
        
        $storagePath = storage_path();
        $directories = [
            'storage/app',
            'storage/framework',
            'storage/framework/cache',
            'storage/framework/sessions',
            'storage/framework/views',
            'storage/logs',
        ];

        foreach ($directories as $dir) {
            $fullPath = base_path($dir);
            if (File::exists($fullPath)) {
                if (File::isWritable($fullPath)) {
                    $this->checkPass("$dir is writable");
                } else {
                    $this->checkFail("$dir is not writable");
                }
            } else {
                $this->checkFail("$dir does not exist");
            }
        }

        $this->newLine();
    }

    private function checkCachePermissions(): void
    {
        $this->info('Checking Cache Permissions...');
        
        $cachePath = base_path('bootstrap/cache');
        
        if (File::exists($cachePath)) {
            if (File::isWritable($cachePath)) {
                $this->checkPass('bootstrap/cache is writable');
            } else {
                $this->checkFail('bootstrap/cache is not writable');
            }
        } else {
            $this->checkFail('bootstrap/cache does not exist');
        }

        $this->newLine();
    }

    private function checkStorageLink(): void
    {
        $this->info('Checking Storage Link...');
        
        $publicStoragePath = public_path('storage');
        
        if (File::exists($publicStoragePath)) {
            if (is_link($publicStoragePath)) {
                $this->checkPass('Storage link exists at public/storage');
            } else {
                $this->checkWarn('public/storage exists but is not a symbolic link');
            }
        } else {
            $this->checkFail('Storage link does not exist. Run: php artisan storage:link');
        }

        $this->newLine();
    }

    private function checkLaravelCaches(): void
    {
        $this->info('Checking Laravel Caches...');
        
        // Check config cache
        $configCachePath = base_path('bootstrap/cache/config.php');
        if (File::exists($configCachePath)) {
            $this->checkPass('Config cache exists');
        } else {
            $this->checkWarn('Config cache not found. Run: php artisan config:cache');
        }

        // Check route cache
        $routeCachePath = base_path('bootstrap/cache/routes-v7.php');
        if (File::exists($routeCachePath)) {
            $this->checkPass('Route cache exists');
        } else {
            $this->checkWarn('Route cache not found. Run: php artisan route:cache');
        }

        // Check view cache
        $viewCachePath = storage_path('framework/views');
        if (File::exists($viewCachePath) && count(File::files($viewCachePath)) > 0) {
            $this->checkPass('View cache exists');
        } else {
            $this->checkWarn('View cache is empty. Run: php artisan view:cache');
        }

        $this->newLine();
    }

    private function checkPhpVersion(): void
    {
        $this->info('Checking PHP Version...');
        
        $phpVersion = PHP_VERSION;
        $requiredVersion = '8.2.0';
        
        if (version_compare($phpVersion, $requiredVersion, '>=')) {
            $this->checkPass("PHP version is $phpVersion (>= $requiredVersion)");
        } else {
            $this->checkFail("PHP version is $phpVersion (requires >= $requiredVersion)");
        }

        $this->newLine();
    }

    private function checkPhpExtensions(): void
    {
        $this->info('Checking Required PHP Extensions...');
        
        $requiredExtensions = [
            'pdo',
            'mbstring',
            'openssl',
            'tokenizer',
            'xml',
            'ctype',
            'json',
            'bcmath',
            'fileinfo',
            'gd',
        ];

        foreach ($requiredExtensions as $extension) {
            if (extension_loaded($extension)) {
                $this->checkPass("Extension '$extension' is loaded");
            } else {
                $this->checkFail("Extension '$extension' is not loaded");
            }
        }

        $this->newLine();
    }

    private function checkPass(string $message): void
    {
        $this->results[] = ['status' => 'pass', 'message' => $message];
        $this->passed++;
        $this->line("<fg=green>✓</> $message");
    }

    private function checkFail(string $message): void
    {
        $this->results[] = ['status' => 'fail', 'message' => $message];
        $this->failed++;
        $this->line("<fg=red>✗</> $message");
    }

    private function checkWarn(string $message): void
    {
        $this->results[] = ['status' => 'warn', 'message' => $message];
        $this->warnings++;
        $this->line("<fg=yellow>⚠</> $message");
    }

    private function displayResults(): void
    {
        $this->info('===========================================');
        $this->info('  Validation Summary');
        $this->info('===========================================');
        $this->newLine();

        $total = $this->passed + $this->failed + $this->warnings;
        
        $this->line("<fg=green>Passed:</> {$this->passed}");
        $this->line("<fg=red>Failed:</> {$this->failed}");
        $this->line("<fg=yellow>Warnings:</> {$this->warnings}");
        $this->line("Total Checks: {$total}");
        $this->newLine();

        if ($this->failed > 0) {
            $this->error('❌ Production configuration validation FAILED');
            $this->error('Please fix the issues above before deploying to production.');
        } elseif ($this->warnings > 0) {
            $this->warn('⚠️  Production configuration validation passed with warnings');
            $this->warn('Consider addressing the warnings for optimal production setup.');
        } else {
            $this->info('✅ Production configuration validation PASSED');
            $this->info('All checks passed successfully!');
        }
        
        $this->newLine();
    }
}
