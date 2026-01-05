<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Unit;
use App\Models\Apar;
use App\Models\KartuApar;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModelRelationshipTest extends TestCase
{
    use RefreshDatabase;

    // ========== SUBTASK 10.1: Model Relationship Tests ==========

    /**
     * @test
     * Test User has unit relationship
     */
    public function user_has_unit_relationship(): void
    {
        $unit = Unit::create([
            'code' => 'TEST-UNIT',
            'name' => 'Test Unit',
            'is_active' => true,
        ]);

        $user = User::factory()->create([
            'unit_id' => $unit->id,
        ]);

        $this->assertInstanceOf(Unit::class, $user->unit);
        $this->assertEquals($unit->id, $user->unit->id);
    }

    /**
     * @test
     * Test User has kartu apars relationship
     */
    public function user_has_kartu_apars_relationship(): void
    {
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $user = User::factory()->create();
        $unit = Unit::where('code', 'UPW2')->first();

        $apar = Apar::create([
            'name' => 'Test APAR',
            'serial_no' => 'REL-001',
            'barcode' => 'REL-BC-001',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'status' => 'baik',
        ]);

        $kartu = KartuApar::create([
            'apar_id' => $apar->id,
            'tgl_periksa' => now(),
            'kondisi' => 'baik',
            'catatan' => 'Test',
            'creator_id' => $user->id,
            'tabung' => 'baik',
            'selang' => 'baik',
            'nozzle' => 'baik',
            'pressure_gauge' => 'baik',
            'pin_segel' => 'baik',
            'label' => 'baik',
        ]);

        $user->refresh();

        $this->assertGreaterThan(0, $user->kartuApars()->count());
    }

    /**
     * @test
     * Test Equipment (Apar) has unit relationship
     */
    public function equipment_has_unit_relationship(): void
    {
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $unit = Unit::where('code', 'UPW2')->first();
        $user = User::factory()->create();

        $apar = Apar::create([
            'name' => 'Test APAR',
            'serial_no' => 'UNIT-001',
            'barcode' => 'UNIT-BC-001',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'status' => 'baik',
        ]);

        $this->assertInstanceOf(Unit::class, $apar->unit);
        $this->assertEquals($unit->id, $apar->unit->id);
    }

    /**
     * @test
     * Test Equipment (Apar) has user relationship
     */
    public function equipment_has_user_relationship(): void
    {
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $user = User::factory()->create();
        $unit = Unit::where('code', 'UPW2')->first();

        $apar = Apar::create([
            'name' => 'Test APAR',
            'serial_no' => 'USER-001',
            'barcode' => 'USER-BC-001',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'status' => 'baik',
        ]);

        $this->assertInstanceOf(User::class, $apar->user);
        $this->assertEquals($user->id, $apar->user->id);
    }

    /**
     * @test
     * Test Equipment (Apar) has kartu inspections relationship
     */
    public function equipment_has_kartu_inspections_relationship(): void
    {
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $user = User::factory()->create();
        $unit = Unit::where('code', 'UPW2')->first();

        $apar = Apar::create([
            'name' => 'Test APAR',
            'serial_no' => 'KARTU-001',
            'barcode' => 'KARTU-BC-001',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'status' => 'baik',
        ]);

        $kartu = KartuApar::create([
            'apar_id' => $apar->id,
            'tgl_periksa' => now(),
            'kondisi' => 'baik',
            'catatan' => 'Test inspection',
            'creator_id' => $user->id,
            'tabung' => 'baik',
            'selang' => 'baik',
            'nozzle' => 'baik',
            'pressure_gauge' => 'baik',
            'pin_segel' => 'baik',
            'label' => 'baik',
        ]);

        $apar->refresh();

        $this->assertGreaterThan(0, $apar->kartuInspeksi()->count());
        $this->assertInstanceOf(KartuApar::class, $apar->kartuInspeksi()->first());
    }

    /**
     * @test
     * Test KartuApar has equipment relationship
     */
    public function kartu_has_equipment_relationship(): void
    {
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $user = User::factory()->create();
        $unit = Unit::where('code', 'UPW2')->first();

        $apar = Apar::create([
            'name' => 'Test APAR',
            'serial_no' => 'KARTU-EQ-001',
            'barcode' => 'KARTU-EQ-BC-001',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'status' => 'baik',
        ]);

        $kartu = KartuApar::create([
            'apar_id' => $apar->id,
            'tgl_periksa' => now(),
            'kondisi' => 'baik',
            'catatan' => 'Test',
            'creator_id' => $user->id,
            'tabung' => '​baik',
            'selang' => 'baik',
            'nozzle' => 'baik',
            'pressure_gauge' => 'baik',
            'pin_segel' => 'baik',
            'label' => 'baik',
        ]);

        $this->assertInstanceOf(Apar::class, $kartu->apar);
        $this->assertEquals($apar->id, $kartu->apar->id);
    }

    /**
     * @test
     * Test KartuApar has creator relationship
     */
    public function kartu_has_creator_relationship(): void
    {
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $user = User::factory()->create();
        $unit = Unit::where('code', 'UPW2')->first();

        $apar = Apar::create([
            'name' => 'Test APAR',
            'serial_no' => 'CREATOR-001',
            'barcode' => 'CREATOR-BC-001',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'status' => 'baik',
        ]);

        $kartu = KartuApar::create([
            'apar_id' => $apar->id,
            'tgl_periksa' => now(),
            'kondisi' => 'baik',
            'catatan' => 'Test',
            'creator_id' => $user->id,
            'tabung' => 'baik',
            'selang' => 'baik',
            'nozzle' => 'baik',
            'pressure_gauge' => 'baik',
            'pin_segel' => 'baik',
            'label' => 'baik',
        ]);

        $this->assertInstanceOf(User::class, $kartu->creator);
        $this->assertEquals($user->id, $kartu->creator->id);
    }

    /**
     * @test
     * Test KartuApar has approver relationship
     */
    public function kartu_has_approver_relationship(): void
    {
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $creator = User::factory()->create();
        $approver = User::factory()->create();
        $approver->assignRole('leader');

        $unit = Unit::where('code', 'UPW2')->first();

        $apar = Apar::create([
            'name' => 'Test APAR',
            'serial_no' => 'APPROVER-001',
            'barcode' => 'APPROVER-BC-001',
            'user_id' => $creator->id,
            'unit_id' => $unit->id,
            'status' => 'baik',
        ]);

        $kartu = KartuApar::create([
            'apar_id' => $apar->id,
            'tgl_periksa' => now(),
            'kondisi' => 'baik',
            'catatan' => 'Test',
            'creator_id' => $creator->id,
            'approved_by' => $approver->id,
            'approved_at' => now(),
            'tabung' => 'baik',
            'selang' => 'baik',
            'nozzle' => 'baik',
            'pressure_gauge' => 'baik',
            'pin_segel' => 'baik',
            'label' => 'baik',
        ]);

        $this->assertInstanceOf(User::class, $kartu->approver);
        $this->assertEquals($approver->id, $kartu->approver->id);
    }

    /**
     * @test
     * Test Unit has users relationship
     */
    public function unit_has_users_relationship(): void
    {
        $unit = Unit::create([
            'code' => 'USERS-UNIT',
            'name' => 'Users Test Unit',
            'is_active' => true,
        ]);

        $user1 = User::factory()->create(['unit_id' => $unit->id]);
        $user2 = User::factory()->create(['unit_id' => $unit->id]);

        $unit->refresh();

        $this->assertGreaterThanOrEqual(2, $unit->users()->count());
        $this->assertInstanceOf(User::class, $unit->users()->first());
    }

    /**
     * @test
     * Test Unit has equipment relationship
     */
    public function unit_has_equipment_relationship(): void
    {
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $unit = Unit::where('code', 'UPW2')->first();
        $user = User::factory()->create();

        $apar = Apar::create([
            'name' => 'Test APAR',
            'serial_no' => 'UNIT-EQ-001',
            'barcode' => 'UNIT-EQ-BC-001',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'status' => 'baik',
        ]);

        $unit->refresh();

        $this->assertGreaterThan(0, $unit->apars()->count());
        $this->assertInstanceOf(Apar::class, $unit->apars()->first());
    }
}
