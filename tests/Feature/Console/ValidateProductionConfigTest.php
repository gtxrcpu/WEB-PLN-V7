<?php

namespace Tests\Feature\Console;

use Tests\TestCase;

class ValidateProductionConfigTest extends TestCase
{
    /**
     * Test that the validation command runs successfully.
     */
    public function test_validation_command_runs_successfully(): void
    {
        $this->artisan('app:validate-production-config')
            ->assertExitCode(1); // Expect failure in test environment (not production)
    }

    /**
     * Test that the validation command checks APP_DEBUG.
     */
    public function test_validation_checks_app_debug(): void
    {
        $this->artisan('app:validate-production-config')
            ->expectsOutput('Checking Environment Settings...')
            ->assertExitCode(1);
    }

    /**
     * Test that the validation command checks storage permissions.
     */
    public function test_validation_checks_storage_permissions(): void
    {
        $this->artisan('app:validate-production-config')
            ->expectsOutput('Checking Storage Permissions...')
            ->assertExitCode(1);
    }

    /**
     * Test that the validation command checks PHP version.
     */
    public function test_validation_checks_php_version(): void
    {
        $this->artisan('app:validate-production-config')
            ->expectsOutput('Checking PHP Version...')
            ->assertExitCode(1);
    }

    /**
     * Test that the validation command checks PHP extensions.
     */
    public function test_validation_checks_php_extensions(): void
    {
        $this->artisan('app:validate-production-config')
            ->expectsOutput('Checking Required PHP Extensions...')
            ->assertExitCode(1);
    }

    /**
     * Test that the validation command displays summary.
     */
    public function test_validation_displays_summary(): void
    {
        $this->artisan('app:validate-production-config')
            ->expectsOutputToContain('Validation Summary')
            ->assertExitCode(1);
    }
}
