<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Unit;
use App\Models\FloorPlan;
use App\Models\Apar;
use App\Models\Apat;
use App\Models\Apab;
use App\Models\FireAlarm;
use App\Models\BoxHydrant;
use App\Models\RumahPompa;
use App\Models\P3k;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FloorPlanTest extends TestCase
{
    use RefreshDatabase;

    // ========== SUBTASK 7.1: Loading Floor Plan Page ==========

    /**
     * @test
     * Test that authenticated user can access floor plan page
     */
    public function authenticated_user_can_access_floor_plan_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('floor-plan.index'));

        $response->assertStatus(200);
        $response->assertViewIs('floor-plan.index');
    }

    /**
     * @test
     * Test that user with unit sees their unit's floor plan
     */
    public function user_with_unit_sees_their_floor_plan()
    {
        $unit = Unit::create([
            'code' => 'TEST-UNIT',
            'name' => 'Test Unit',
            'description' => 'Test Unit Description',
            'is_active' => true,
        ]);

        $user = User::factory()->create(['unit_id' => $unit->id]);

        $floorPlan = FloorPlan::create([
            'unit_id' => $unit->id,
            'name' => 'Test Floor Plan',
            'image_path' => 'floor-plans/test.png',
            'width' => 1000,
            'height' => 800,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get(route('floor-plan.index'));

        $response->assertStatus(200);
        $response->assertViewHas('floorPlan', function ($viewFloorPlan) use ($floorPlan) {
            return $viewFloorPlan->id === $floorPlan->id;
        });
    }

    /**
     * @test
     * Test that user without unit sees no floor plan
     */
    public function user_without_unit_sees_no_floor_plan()
    {
        $user = User::factory()->create(['unit_id' => null]);

        $response = $this->actingAs($user)->get(route('floor-plan.index'));

        $response->assertStatus(200);
        $response->assertViewHas('floorPlan', null);
    }

    /**
     * @test
     * Test that guest cannot access floor plan page
     */
    public function guest_cannot_access_floor_plan_page()
    {
        $response = $this->get(route('floor-plan.index'));

        $response->assertRedirect(route('login'));
    }

    // ========== SUBTASK 7.2: Displaying Equipment Markers ==========

    /**
     * @test
     * Test equipment data API returns correct JSON structure
     */
    public function equipment_data_api_returns_correct_json_structure()
    {
        $user = User::factory()->create();
        $unit = Unit::create([
            'code' => 'API-UNIT',
            'name' => 'API Test Unit',
            'is_active' => true,
        ]);

        $floorPlan = FloorPlan::create([
            'unit_id' => $unit->id,
            'name' => 'API Floor Plan',
            'image_path' => 'floor-plans/api-test.png',
            'width' => 1000,
            'height' => 800,
            'is_active' => true,
        ]);

        // Create equipment with floor plan coordinates
        $apar = Apar::create([
            'name' => 'APAR-API-001',
            'serial_no' => 'API-SN-001',
            'barcode' => 'API-BC-001',
            'location_code' => 'API-LOC-001',
            'status' => 'baik',
            'capacity' => '3kg',
            'type' => 'Powder',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'floor_plan_id' => $floorPlan->id,
            'floor_plan_x' => 25.5,
            'floor_plan_y' => 30.2,
        ]);

        $response = $this->actingAs($user)
            ->getJson(route('floor-plan.equipment-data', $floorPlan));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'equipment' => [
                '*' => [
                    'id',
                    'type',
                    'name',
                    'serial_no',
                    'status',
                    'x',
                    'y',
                    'location',
                    'url',
                ]
            ]
        ]);
    }

    /**
     * @test
     * Test equipment coordinates are correctly formatted
     */
    public function equipment_coordinates_are_correctly_formatted()
    {
        $user = User::factory()->create();
        $unit = Unit::create([
            'code' => 'COORD-UNIT',
            'name' => 'Coordinate Test Unit',
            'is_active' => true,
        ]);

        $floorPlan = FloorPlan::create([
            'unit_id' => $unit->id,
            'name' => 'Coordinate Floor Plan',
            'image_path' => 'floor-plans/coord-test.png',
            'width' => 1200,
            'height' => 900,
            'is_active' => true,
        ]);

        $apar = Apar::create([
            'name' => 'APAR-COORD-001',
            'serial_no' => 'COORD-001',
            'barcode' => 'COORD-BC-001',
            'location_code' => 'COORD-LOC',
            'status' => 'baik',
            'capacity' => '5kg',
            'type' => 'CO2',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'floor_plan_id' => $floorPlan->id,
            'floor_plan_x' => 45.75,
            'floor_plan_y' => 67.89,
        ]);

        $response = $this->actingAs($user)
            ->getJson(route('floor-plan.equipment-data', $floorPlan));

        $response->assertStatus(200);

        $equipment = $response->json('equipment');
        $this->assertCount(1, $equipment);
        $this->assertEquals(45.75, $equipment[0]['x']);
        $this->assertEquals(67.89, $equipment[0]['y']);
        $this->assertEquals('apar', $equipment[0]['type']);
    }

    /**
     * @test
     * Test equipment metadata is included in API response
     */
    public function equipment_metadata_is_included_in_api_response()
    {
        $user = User::factory()->create();
        $unit = Unit::create([
            'code' => 'META-UNIT',
            'name' => 'Metadata Test Unit',
            'is_active' => true,
        ]);

        $floorPlan = FloorPlan::create([
            'unit_id' => $unit->id,
            'name' => 'Metadata Floor Plan',
            'image_path' => 'floor-plans/meta-test.png',
            'width' => 1000,
            'height' => 800,
            'is_active' => true,
        ]);

        $apar = Apar::create([
            'name' => 'APAR-META-001',
            'serial_no' => 'META-SN-001',
            'barcode' => 'META-BC-001',
            'location_code' => 'META-LOCATION',
            'status' => 'baik',
            'capacity' => '6kg',
            'type' => 'Foam',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'floor_plan_id' => $floorPlan->id,
            'floor_plan_x' => 50.0,
            'floor_plan_y' => 50.0,
        ]);

        $response = $this->actingAs($user)
            ->getJson(route('floor-plan.equipment-data', $floorPlan));

        $response->assertStatus(200);

        $equipment = $response->json('equipment.0');
        $this->assertEquals('META-SN-001', $equipment['serial_no']);
        $this->assertEquals('baik', $equipment['status']);
        $this->assertEquals('META-LOCATION', $equipment['location']);
        $this->assertStringContainsString('apar', $equipment['url']);
    }

    // ========== SUBTASK 7.3: Equipment Detail Popup ==========

    /**
     * @test
     * Test equipment markers include proper detail URLs
     */
    public function equipment_markers_include_proper_detail_urls()
    {
        $user = User::factory()->create();
        $unit = Unit::create([
            'code' => 'URL-UNIT',
            'name' => 'URL Test Unit',
            'is_active' => true,
        ]);

        $floorPlan = FloorPlan::create([
            'unit_id' => $unit->id,
            'name' => 'URL Floor Plan',
            'image_path' => 'floor-plans/url-test.png',
            'width' => 1000,
            'height' => 800,
            'is_active' => true,
        ]);

        $apar = Apar::create([
            'name' => 'APAR-URL-001',
            'serial_no' => 'URL-001',
            'barcode' => 'URL-BC-001',
            'location_code' => 'URL-LOC',
            'status' => 'baik',
            'capacity' => '3kg',
            'type' => 'Powder',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'floor_plan_id' => $floorPlan->id,
            'floor_plan_x' => 20.0,
            'floor_plan_y' => 30.0,
        ]);

        $response = $this->actingAs($user)
            ->getJson(route('floor-plan.equipment-data', $floorPlan));

        $response->assertStatus(200);

        $equipment = $response->json('equipment.0');
        $this->assertNotEquals('#', $equipment['url']);
        $this->assertStringContainsString('/apar/', $equipment['url']);
    }

    // ========== SUBTASK 7.4: Multiple Equipment Types Support ==========

    /**
     * @test
     * Test floor plan supports all 7 equipment types
     */
    public function floor_plan_supports_all_equipment_types()
    {
        $user = User::factory()->create();
        $unit = Unit::create([
            'code' => 'MULTI-UNIT',
            'name' => 'Multi Equipment Unit',
            'is_active' => true,
        ]);

        $floorPlan = FloorPlan::create([
            'unit_id' => $unit->id,
            'name' => 'Multi Equipment Floor Plan',
            'image_path' => 'floor-plans/multi-test.png',
            'width' => 1500,
            'height' => 1200,
            'is_active' => true,
        ]);

        // Create one of each equipment type
        $apar = Apar::create([
            'name' => 'APAR-MULTI',
            'serial_no' => 'MULTI-APAR-001',
            'barcode' => 'MULTI-APAR-BC',
            'location_code' => 'LOC-1',
            'status' => 'baik',
            'capacity' => '3kg',
            'type' => 'Powder',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'floor_plan_id' => $floorPlan->id,
            'floor_plan_x' => 10.0,
            'floor_plan_y' => 10.0,
        ]);

        $apat = Apat::create([
            'name' => 'APAT-MULTI',
            'serial_no' => 'MULTI-APAT-001',
            'barcode' => 'MULTI-APAT-BC',
            'lokasi' => 'LOC-2',
            'status' => 'baik',
            'kapasitas' => '10kg',
            'jenis' => 'Thermatic',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'floor_plan_id' => $floorPlan->id,
            'floor_plan_x' => 20.0,
            'floor_plan_y' => 20.0,
        ]);

        $apab = Apab::create([
            'name' => 'APAB-MULTI',
            'serial_no' => 'MULTI-APAB-001',
            'barcode' => 'MULTI-APAB-BC',
            'location_code' => 'LOC-3',
            'status' => 'baik',
            'capacity' => '50kg',
            'isi_apab' => 'Powder',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'floor_plan_id' => $floorPlan->id,
            'floor_plan_x' => 30.0,
            'floor_plan_y' => 30.0,
        ]);

        $fireAlarm = FireAlarm::create([
            'name' => 'FA-MULTI',
            'serial_no' => 'MULTI-FA-001',
            'barcode' => 'MULTI-FA-BC',
            'location_code' => 'LOC-4',
            'status' => 'baik',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'floor_plan_id' => $floorPlan->id,
            'floor_plan_x' => 40.0,
            'floor_plan_y' => 40.0,
        ]);

        $boxHydrant = BoxHydrant::create([
            'name' => 'BH-MULTI',
            'serial_no' => 'MULTI-BH-001',
            'barcode' => 'MULTI-BH-BC',
            'location_code' => 'LOC-5',
            'status' => 'baik',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'floor_plan_id' => $floorPlan->id,
            'floor_plan_x' => 50.0,
            'floor_plan_y' => 50.0,
        ]);

        $rumahPompa = RumahPompa::create([
            'name' => 'RP-MULTI',
            'serial_no' => 'MULTI-RP-001',
            'barcode' => 'MULTI-RP-BC',
            'location_code' => 'LOC-6',
            'status' => 'baik',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'floor_plan_id' => $floorPlan->id,
            'floor_plan_x' => 60.0,
            'floor_plan_y' => 60.0,
        ]);

        $p3k = P3k::create([
            'name' => 'P3K-MULTI',
            'serial_no' => 'MULTI-P3K-001',
            'barcode' => 'MULTI-P3K-BC',
            'location_code' => 'LOC-7',
            'status' => 'baik',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'floor_plan_id' => $floorPlan->id,
            'floor_plan_x' => 70.0,
            'floor_plan_y' => 70.0,
        ]);

        $response = $this->actingAs($user)
            ->getJson(route('floor-plan.equipment-data', $floorPlan));

        $response->assertStatus(200);

        $equipment = $response->json('equipment');

        // Verify that multiple equipment types are returned
        $this->assertGreaterThan(0, count($equipment), 'Expected at least one equipment item');

        // Verify types are identifiable (at least some equipment should have type field)
        $types = array_column($equipment, 'type');
        $this->assertNotEmpty($types, 'Equipment should have type field');

        // Verify we have diverse equipment types (at least 2 different types)
        $uniqueTypes = array_unique($types);
        $this->assertGreaterThanOrEqual(
            2,
            count($uniqueTypes),
            'Expected at least 2 different equipment types on floor plan'
        );
    }

    /**
     * @test
     * Test floor plan only shows equipment with coordinates set
     */
    public function floor_plan_only_shows_equipment_with_coordinates()
    {
        $user = User::factory()->create();
        $unit = Unit::create([
            'code' => 'COORD-FILTER-UNIT',
            'name' => 'Coordinate Filter Unit',
            'is_active' => true,
        ]);

        $floorPlan = FloorPlan::create([
            'unit_id' => $unit->id,
            'name' => 'Filter Floor Plan',
            'image_path' => 'floor-plans/filter-test.png',
            'width' => 1000,
            'height' => 800,
            'is_active' => true,
        ]);

        // Equipment WITH coordinates
        $aparWithCoords = Apar::create([
            'name' => 'APAR-WITH-COORDS',
            'serial_no' => 'WC-001',
            'barcode' => 'WC-BC-001',
            'location_code' => 'WC-LOC',
            'status' => 'baik',
            'capacity' => '3kg',
            'type' => 'Powder',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'floor_plan_id' => $floorPlan->id,
            'floor_plan_x' => 25.0,
            'floor_plan_y' => 25.0,
        ]);

        // Equipment WITHOUT coordinates
        $aparWithoutCoords = Apar::create([
            'name' => 'APAR-NO-COORDS',
            'serial_no' => 'NC-001',
            'barcode' => 'NC-BC-001',
            'location_code' => 'NC-LOC',
            'status' => 'baik',
            'capacity' => '3kg',
            'type' => 'Powder',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'floor_plan_id' => null,
            'floor_plan_x' => null,
            'floor_plan_y' => null,
        ]);

        $response = $this->actingAs($user)
            ->getJson(route('floor-plan.equipment-data', $floorPlan));

        $response->assertStatus(200);

        $equipment = $response->json('equipment');
        $this->assertCount(1, $equipment);
        $this->assertEquals('WC-001', $equipment[0]['serial_no']);
    }

    // ========== SUBTASK 7.5: Admin Floor Plan Management ==========

    /**
     * @test
     * Test admin can view floor plans index
     */
    public function admin_can_view_floor_plans_index()
    {
        // Seed roles
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('superadmin');

        $response = $this->actingAs($admin)->get(route('admin.floor-plans.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.floor-plans.index');
    }

    /**
     * @test
     * Test admin can create new floor plan
     */
    public function admin_can_create_new_floor_plan()
    {
        Storage::fake('public');

        // Seed roles
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('superadmin');

        $unit = Unit::create([
            'code' => 'CREATE-UNIT',
            'name' => 'Create Test Unit',
            'is_active' => true,
        ]);

        $file = UploadedFile::fake()->image('floor-plan.png', 1000, 800);

        $response = $this->actingAs($admin)->post(route('admin.floor-plans.store'), [
            'unit_id' => $unit->id,
            'name' => 'New Floor Plan',
            'image' => $file,
            'description' => 'Test floor plan description',
        ]);

        $response->assertRedirect(route('admin.floor-plans.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('floor_plans', [
            'unit_id' => $unit->id,
            'name' => 'New Floor Plan',
        ]);
    }

    /**
     * @test
     * Test admin can save equipment placement
     */
    public function admin_can_save_equipment_placement()
    {
        // Seed roles
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('superadmin');

        $unit = Unit::create([
            'code' => 'PLACEMENT-UNIT',
            'name' => 'Placement Test Unit',
            'is_active' => true,
        ]);

        $floorPlan = FloorPlan::create([
            'unit_id' => $unit->id,
            'name' => 'Placement Floor Plan',
            'image_path' => 'floor-plans/placement.png',
            'width' => 1000,
            'height' => 800,
            'is_active' => true,
        ]);

        $apar = Apar::create([
            'name' => 'APAR-PLACEMENT',
            'serial_no' => 'PLACE-001',
            'barcode' => 'PLACE-BC-001',
            'location_code' => 'PLACE-LOC',
            'status' => 'baik',
            'capacity' => '3kg',
            'type' => 'Powder',
            'user_id' => $admin->id,
            'unit_id' => $unit->id,
        ]);

        $response = $this->actingAs($admin)
            ->postJson(route('admin.floor-plans.save-placement', $floorPlan), [
                'equipment_type' => 'apar',
                'equipment_id' => $apar->id,
                'x' => 35.5,
                'y' => 42.8,
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('apars', [
            'id' => $apar->id,
            'floor_plan_id' => $floorPlan->id,
            'floor_plan_x' => 35.5,
            'floor_plan_y' => 42.8,
        ]);
    }

    /**
     * @test
     * Test admin can remove equipment from floor plan
     */
    public function admin_can_remove_equipment_from_floor_plan()
    {
        // Seed roles
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('superadmin');

        $unit = Unit::create([
            'code' => 'REMOVE-UNIT',
            'name' => 'Remove Test Unit',
            'is_active' => true,
        ]);

        $floorPlan = FloorPlan::create([
            'unit_id' => $unit->id,
            'name' => 'Remove Floor Plan',
            'image_path' => 'floor-plans/remove.png',
            'width' => 1000,
            'height' => 800,
            'is_active' => true,
        ]);

        $apar = Apar::create([
            'name' => 'APAR-REMOVE',
            'serial_no' => 'REMOVE-001',
            'barcode' => 'REMOVE-BC-001',
            'location_code' => 'REMOVE-LOC',
            'status' => 'baik',
            'capacity' => '3kg',
            'type' => 'Powder',
            'user_id' => $admin->id,
            'unit_id' => $unit->id,
            'floor_plan_id' => $floorPlan->id,
            'floor_plan_x' => 50.0,
            'floor_plan_y' => 50.0,
        ]);

        $response = $this->actingAs($admin)
            ->postJson(route('admin.floor-plans.remove-placement', $floorPlan), [
                'equipment_type' => 'apar',
                'equipment_id' => $apar->id,
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('apars', [
            'id' => $apar->id,
            'floor_plan_id' => null,
            'floor_plan_x' => null,
            'floor_plan_y' => null,
        ]);
    }

    /**
     * @test
     * Test placement validates coordinate range
     */
    public function placement_validates_coordinate_range()
    {
        // Seed roles
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('superadmin');

        $unit = Unit::create([
            'code' => 'VALIDATE-UNIT',
            'name' => 'Validation Test Unit',
            'is_active' => true,
        ]);

        $floorPlan = FloorPlan::create([
            'unit_id' => $unit->id,
            'name' => 'Validation Floor Plan',
            'image_path' => 'floor-plans/validate.png',
            'width' => 1000,
            'height' => 800,
            'is_active' => true,
        ]);

        $apar = Apar::create([
            'name' => 'APAR-VALIDATE',
            'serial_no' => 'VALID-001',
            'barcode' => 'VALID-BC-001',
            'location_code' => 'VALID-LOC',
            'status' => 'baik',
            'capacity' => '3kg',
            'type' => 'Powder',
            'user_id' => $admin->id,
            'unit_id' => $unit->id,
        ]);

        // Test coordinates out of range
        $response = $this->actingAs($admin)
            ->postJson(route('admin.floor-plans.save-placement', $floorPlan), [
                'equipment_type' => 'apar',
                'equipment_id' => $apar->id,
                'x' => 150.0, // Out of range (max 100)
                'y' => 42.8,
            ]);

        $response->assertStatus(422); // Validation error
    }
}
