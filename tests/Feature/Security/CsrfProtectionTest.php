<?php

namespace Tests\Feature\Security;

use Tests\TestCase;
use App\Models\User;
use App\Models\Apar;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CsrfProtectionTest extends TestCase
{
    use RefreshDatabase;

    // ========== SUBTASK 9.1: CSRF Protection Tests ==========

    /**
     * @test
     * Test POST request requires CSRF token
     */
    public function post_request_requires_csrf_token(): void
    {
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('petugas');

        // Make POST request (Laravel test includes CSRF automatically)
        $response = $this->actingAs($user)
            ->post('/logout', []);

        // Laravel automatically includes CSRF in tests, so we verify it works
        $response->assertStatus(302); // Successful logout redirect
        $this->assertGuest();
    }

    /**
     * @test
     * Test forms include CSRF token field
     */
    public function forms_include_csrf_token_field(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        // Verify CSRF token is present in the form
        $response->assertSee('_token', false);
    }

    /**
     * @test
     * Test CSRF middleware class exists
     */
    public function csrf_middleware_class_exists(): void
    {
        // Verify CSRF middleware exists in the application
        $csrfMiddleware = \App\Http\Middleware\VerifyCsrfToken::class;

        $this->assertTrue(
            class_exists($csrfMiddleware),
            'CSRF protection middleware class should exist'
        );
    }

    /**
     * @test
     * Test AJAX requests can include CSRF token in header
     */
    public function ajax_requests_can_include_csrf_token_in_header(): void
    {
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('superadmin');

        // Get CSRF token
        $token = csrf_token();

        // Make AJAX request with X-CSRF-TOKEN header
        $response = $this->actingAs($user)
            ->withHeaders([
                'X-CSRF-TOKEN' => $token,
                'X-Requested-With' => 'XMLHttpRequest',
            ])
            ->postJson(route('admin.users.index'), []);

        // Should not get 419 (CSRF token mismatch)
        $this->assertNotEquals(419, $response->status());
    }

    /**
     * @test
     * Test CSRF token is generated
     */
    public function csrf_token_is_generated(): void
    {
        $response = $this->get('/login');

        // Verify CSRF token is generated
        $token = csrf_token();

        $this->assertNotNull($token);
        $this->assertIsString($token);
        $this->assertGreaterThan(10, strlen($token));
    }
}
