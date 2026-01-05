<?php

namespace Tests\Feature\Security;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SessionSecurityTest extends TestCase
{
    use RefreshDatabase;

    // ========== SUBTASK 9.3: Session Security Tests ==========

    /**
     * @test
     * Test session cookies have httpOnly flag
     */
    public function session_cookies_have_http_only_flag(): void
    {
        $config = config('session');

        // Verify httponly is set to true
        $this->assertTrue(
            $config['http_only'],
            'Session cookies must have httpOnly flag for security'
        );
    }

    /**
     * @test
     * Test session cookies have secure flag configuration
     */
    public function session_cookies_have_secure_flag_config(): void
    {
        $config = config('session');

        // In production, secure should be true (HTTPS only)
        // In local/testing, it might be false
        $this->assertArrayHasKey(
            'secure',
            $config,
            'Session configuration must have secure flag setting'
        );

        // Verify it's a boolean
        $this->assertIsBool($config['secure']);
    }

    /**
     * @test
     * Test session cookies have samesite attribute
     */
    public function session_cookies_have_samesite_attribute(): void
    {
        $config = config('session');

        // Verify samesite is configured
        $this->assertArrayHasKey(
            'same_site',
            $config,
            'Session must have samesite attribute configured'
        );

        // Should be lax, strict, or none
        $validValues = ['lax', 'strict', 'none', null];
        $this->assertContains(
            $config['same_site'],
            $validValues,
            'SameSite attribute should be lax, strict, or none'
        );
    }

    /**
     * @test
     * Test session lifetime is configured properly
     */
    public function session_lifetime_is_configured_properly(): void
    {
        $config = config('session');

        // Verify lifetime is set
        $this->assertArrayHasKey('lifetime', $config);
        $this->assertIsInt($config['lifetime']);

        // Lifetime should be reasonable (e.g., 120 minutes = 2 hours)
        $this->assertGreaterThan(0, $config['lifetime']);
        $this->assertLessThanOrEqual(
            1440,
            $config['lifetime'], // Max 24 hours
            'Session lifetime should not exceed 24 hours for security'
        );
    }

    /**
     * @test
     * Test session driver is configured
     */
    public function session_driver_is_configured(): void
    {
        $config = config('session');

        $this->assertArrayHasKey('driver', $config);

        // Valid drivers: file, cookie, database, memcached, redis, etc.
        $validDrivers = ['file', 'cookie', 'database', 'apc', 'memcached', 'redis', 'dynamodb', 'array'];
        $this->assertContains(
            $config['driver'],
            $validDrivers,
            'Session driver must be one of the supported drivers'
        );
    }

    /**
     * @test
     * Test session regenerates after login
     */
    public function session_regenerates_after_login(): void
    {
        $user = User::factory()->create([
            'email' => 'session@test.com',
            'password' => bcrypt('password123'),
        ]);

        // Start a session
        $response = $this->get('/login');
        $initialSessionId = $response->getSession()->getId();

        // Login
        $loginResponse = $this->post('/login', [
            'email' => 'session@test.com',
            'password' => 'password123',
        ]);

        // Session should be regenerated
        $newSessionId = $loginResponse->getSession()->getId();

        // Session ID should change after login (prevents session fixation)
        $this->assertNotEquals(
            $initialSessionId,
            $newSessionId,
            'Session ID should regenerate after login to prevent session fixation attacks'
        );
    }

    /**
     * @test
     * Test session is invalidated after logout
     */
    public function session_is_invalidated_after_logout(): void
    {
        $user = User::factory()->create();

        // Login
        $this->actingAs($user);
        $this->assertAuthenticated();

        // Logout
        $response = $this->post('/logout');

        // Should be logged out
        $this->assertGuest();

        // Session should be cleared
        $response->assertRedirect('/login');
    }

    /**
     * @test
     * Test session encryption is configured
     */
    public function session_encryption_is_configured(): void
    {
        $config = config('session');

        // Verify encrypt setting exists
        $this->assertArrayHasKey('encrypt', $config);
        $this->assertIsBool($config['encrypt']);

        // Note: Session encryption is optional but recommended for sensitive data
    }

    /**
     * @test
     * Test session cookie name is configured
     */
    public function session_cookie_name_is_configured(): void
    {
        $config = config('session');

        $this->assertArrayHasKey('cookie', $config);
        $this->assertIsString($config['cookie']);
        $this->assertNotEmpty($config['cookie']);
    }

    /**
     * @test
     * Test session path and domain are configured
     */
    public function session_path_and_domain_are_configured(): void
    {
        $config = config('session');

        // Path should be configured
        $this->assertArrayHasKey('path', $config);
        $this->assertEquals('/', $config['path']);

        // Domain can be null or a string
        $this->assertArrayHasKey('domain', $config);
    }
}
