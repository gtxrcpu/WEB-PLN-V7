<?php

namespace Tests\Feature\Security;

use Tests\TestCase;
use App\Models\User;
use App\Models\Apar;
use App\Models\Unit;
use App\Models\FloorPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class InputValidationTest extends TestCase
{
    use RefreshDatabase;

    // ========== SUBTASK 9.2: Input Validation Tests ==========

    /**
     * @test
     * Test user input with script tags is escaped in output
     */
    public function user_input_with_script_tags_is_escaped(): void
    {
        $user = User::factory()->create([
            'name' => '<script>alert("XSS")</script>',
            'email' => 'xss@test.com',
        ]);

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
        // Blade {{ }} should escape output
        $response->assertDontSee('<script>alert("XSS")</script>', false);
        // Should see escaped version
        $response->assertSee('&lt;script&gt;', false);
    }

    /**
     * @test
     * Test HTML entities are encoded in output
     */
    public function html_entities_are_encoded_in_output(): void
    {
        $user = User::factory()->create([
            'name' => '<b>Bold Name</b>',
        ]);

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
        // Should not render as HTML
        $response->assertDontSee('<b>Bold Name</b>', false);
        // Should see escaped
        $response->assertSee('&lt;b&gt;', false);
    }

    /**
     * @test
     * Test SQL injection prevention with Eloquent ORM
     */
    public function eloquent_prevents_sql_injection(): void
    {
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('petugas');

        // Create legitimate APAR
        $unit = Unit::where('code', 'UPW2')->first();
        $apar = Apar::create([
            'name' => 'Test APAR',
            'serial_no' => 'TEST-001',
            'barcode' => 'TEST-BC-001',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'status' => 'baik',
        ]);

        // Attempt SQL injection
        $maliciousInput = "'; DROP TABLE apars; --";

        // This should not execute SQL injection
        $result = Apar::where('name', $maliciousInput)->get();

        // Verify table still exists and original data intact
        $this->assertDatabaseHas('apars', ['id' => $apar->id]);
        $this->assertEquals(0, $result->count());
    }

    /**
     * @test
     * Test parameterized queries prevent SQL injection
     */
    public function parameterized_queries_prevent_sql_injection(): void
    {
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        $user = User::factory()->create(['name' => 'Test User']);

        $maliciousInput = "admin' OR '1'='1";

        // Use parameterized query
        $result = DB::select('SELECT * FROM users WHERE name = ?', [$maliciousInput]);

        // Should return 0 results (not all users)
        $this->assertCount(0, $result);

        // Verify user table still intact
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    /**
     * @test
     * Test file upload rejects disallowed file types
     */
    public function file_upload_rejects_disallowed_file_types(): void
    {
        Storage::fake('public');

        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('superadmin');

        $unit = Unit::where('code', 'UPW2')->first();

        // Try to upload PHP file (should be rejected)
        $phpFile = UploadedFile::fake()->create('malicious.php', 100, 'text/php');

        $response = $this->actingAs($admin)
            ->post(route('admin.floor-plans.store'), [
                'unit_id' => $unit->id,
                'name' => 'Test Floor Plan',
                'image' => $phpFile,
                'description' => 'Test',
            ]);

        // Should have validation error
        $response->assertStatus(302); // Redirect back with errors
        $response->assertSessionHasErrors('image');
    }

    /**
     * @test
     * Test file upload validates file size
     */
    public function file_upload_validates_file_size(): void
    {
        Storage::fake('public');

        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('superadmin');

        $unit = Unit::where('code', 'UPW2')->first();

        // Try to upload oversized file (11MB, limit is typically 10MB)
        $oversizedFile = UploadedFile::fake()->create('large.png', 11000);

        $response = $this->actingAs($admin)
            ->post(route('admin.floor-plans.store'), [
                'unit_id' => $unit->id,
                'name' => 'Test Floor Plan',
                'image' => $oversizedFile,
                'description' => 'Test',
            ]);

        // Should have validation error for file size
        $response->assertStatus(302);
        $response->assertSessionHasErrors('image');
    }

    /**
     * @test
     * Test file upload validates MIME type
     */
    public function file_upload_validates_mime_type(): void
    {
        Storage::fake('public');

        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('superadmin');

        $unit = Unit::where('code', 'UPW2')->first();

        // Create valid image file
        $validImage = UploadedFile::fake()->image('floor-plan.png', 800, 600);

        $response = $this->actingAs($admin)
            ->post(route('admin.floor-plans.store'), [
                'unit_id' => $unit->id,
                'name' => 'Valid Floor Plan',
                'image' => $validImage,
                'description' => 'Test',
            ]);

        // Should succeed (200 or 302 redirect to success)
        $this->assertContains($response->status(), [200, 302]);

        if ($response->status() === 302) {
            // Check it didn't redirect back with errors
            $response->assertSessionHasNoErrors();
        }
    }

    /**
     * @test
     * Test models have fillable or guarded protection
     */
    public function models_have_mass_assignment_protection(): void
    {
        // Test Apar model
        $aparFillable = (new Apar())->getFillable();
        $aparGuarded = (new Apar())->getGuarded();

        // Should have either fillable or guarded set
        $this->assertTrue(
            !empty($aparFillable) || !empty($aparGuarded),
            'Apar model should have mass assignment protection via $fillable or $guarded'
        );

        // Test User model
        $userFillable = (new User())->getFillable();
        $userGuarded = (new User())->getGuarded();

        $this->assertTrue(
            !empty($userFillable) || !empty($userGuarded),
            'User model should have mass assignment protection'
        );
    }

    /**
     * @test
     * Test unauthorized attributes cannot be mass assigned
     */
    public function unauthorized_attributes_cannot_be_mass_assigned(): void
    {
        // Attempt to mass assign 'id' which should be guarded
        $user = User::factory()->make();

        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'id' => 999, // Should not be assignable
        ];

        $createdUser = User::create($userData);

        // ID should be auto-generated, not 999
        $this->assertNotEquals(999, $createdUser->id);
    }

    /**
     * @test
     * Test hidden attributes are not exposed in JSON
     */
    public function hidden_attributes_not_exposed_in_json(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('secret123'),
        ]);

        $json = $user->toJson();

        // Password should be hidden
        $this->assertStringNotContainsString('secret123', $json);
        $this->assertStringNotContainsString('password', $json);

        // But other attributes should be present
        $this->assertStringContainsString($user->email, $json);
    }

    /**
     * @test
     * Test input with special characters is handled safely
     */
    public function input_with_special_characters_is_handled_safely(): void
    {
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $user = User::factory()->create();
        $unit = Unit::where('code', 'UPW2')->first();

        // Input with special characters
        $specialInput = "Test's \"APAR\" & <Equipment>";

        $apar = Apar::create([
            'name' => $specialInput,
            'serial_no' => 'SPEC-001',
            'barcode' => 'SPEC-BC-001',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'status' => 'baik',
        ]);

        // Verify data is stored correctly
        $this->assertDatabaseHas('apars', [
            'name' => $specialInput,
        ]);

        // Retrieve and verify
        $retrieved = Apar::find($apar->id);
        $this->assertEquals($specialInput, $retrieved->name);
    }

    /**
     * @test
     * Test validation rules prevent empty required fields
     */
    public function validation_prevents_empty_required_fields(): void
    {
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('superadmin');

        // Attempt to create floor plan without required fields
        $response = $this->actingAs($admin)
            ->post(route('admin.floor-plans.store'), [
                // Missing unit_id, name, and image
            ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['unit_id', 'name', 'image']);
    }

    /**
     * @test
     * Test numeric validation prevents non-numeric input
     */
    public function numeric_validation_prevents_non_numeric_input(): void
    {
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('superadmin');

        $floorPlan = FloorPlan::create([
            'unit_id' => Unit::where('code', 'UPW2')->first()->id,
            'name' => 'Test Floor Plan',
            'image_path' => 'test.png',
            'width' => 1000,
            'height' => 800,
            'is_active' => true,
        ]);

        $unit = Unit::where('code', 'UPW2')->first();
        $apar = Apar::create([
            'name' => 'Test APAR',
            'serial_no' => 'VAL-001',
            'barcode' => 'VAL-BC-001',
            'user_id' => $admin->id,
            'unit_id' => $unit->id,
            'status' => 'baik',
        ]);

        // Try to save placement with non-numeric coordinates
        $response = $this->actingAs($admin)
            ->postJson(route('admin.floor-plans.save-placement', $floorPlan), [
                'equipment_type' => 'apar',
                'equipment_id' => $apar->id,
                'x' => 'not-a-number', // Should fail
                'y' => 50,
            ]);

        $response->assertStatus(422); // Validation error
    }
}
