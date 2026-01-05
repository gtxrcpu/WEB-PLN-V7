<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/user');
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
    }

    // ========== SUBTASK 8.1: Enhanced Authentication Tests ==========

    /**
     * @test
     * Test superadmin login redirects to admin dashboard
     */
    public function superadmin_login_redirects_to_admin_dashboard(): void
    {
        // Seed roles and units
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $admin = User::factory()->create([
            'email' => 'test.admin@example.com',
            'password' => bcrypt('password123'),
        ]);
        $admin->assignRole('superadmin');

        $response = $this->post('/login', [
            'email' => 'test.admin@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('admin.dashboard'));
    }

    /**
     * @test
     * Test leader login redirects to leader dashboard
     */
    public function leader_login_redirects_to_leader_dashboard(): void
    {
        // Seed roles and units
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $unit = \App\Models\Unit::where('code', 'UPW2')->first();

        $leader = User::factory()->create([
            'email' => 'test.leader@example.com',
            'password' => bcrypt('password123'),
            'unit_id' => $unit->id,
        ]);
        $leader->assignRole('leader');

        $response = $this->post('/login', [
            'email' => 'test.leader@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('leader.dashboard'));
    }

    /**
     * @test
     * Test petugas (regular user) login redirects to user dashboard
     */
    public function petugas_login_redirects_to_user_dashboard(): void
    {
        // Seed roles and units
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $unit = \App\Models\Unit::where('code', 'UPW2')->first();

        $petugas = User::factory()->create([
            'email' => 'test.petugas@example.com',
            'password' => bcrypt('password123'),
            'unit_id' => $unit->id,
        ]);
        $petugas->assignRole('petugas');

        $response = $this->post('/login', [
            'email' => 'test.petugas@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('user.dashboard'));
    }

    /**
     * @test
     * Test inspector login redirects to inspector dashboard
     */
    public function inspector_login_redirects_to_inspector_dashboard(): void
    {
        // Seed roles and units
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $inspector = User::factory()->create([
            'email' => 'test.inspector@example.com',
            'password' => bcrypt('password123'),
        ]);
        $inspector->assignRole('inspector');

        $response = $this->post('/login', [
            'email' => 'test.inspector@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('inspector.dashboard'));
    }

    /**
     * @test
     * Test remember me functionality creates remember token
     */
    public function remember_me_creates_remember_token(): void
    {
        $user = User::factory()->create([
            'email' => 'remember@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'remember@example.com',
            'password' => 'password123',
            'remember' => true,
        ]);

        $this->assertAuthenticated();

        // Verify remember token is set in database
        $user->refresh();
        $this->assertNotNull($user->remember_token);
    }

    /**
     * @test
     * Test session is established correctly after login
     */
    public function session_is_established_after_login(): void
    {
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        $user = User::factory()->create([
            'email' => 'session@example.com',
            'password' => bcrypt('password123'),
        ]);
        $user->assignRole('petugas');

        $this->post('/login', [
            'email' => 'session@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();

        // Verify session has auth data
        $this->assertTrue(auth()->check());
        $this->assertEquals($user->id, auth()->id());
    }
}
