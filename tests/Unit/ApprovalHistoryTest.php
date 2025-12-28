<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\KartuApar;
use App\Models\Apar;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class ApprovalHistoryTest extends TestCase
{
    use RefreshDatabase;

    protected $testUnit;
    protected $testApar;

    protected function setUp(): void
    {
        parent::setUp();
        
        Role::create(['name' => 'superadmin']);
        Role::create(['name' => 'leader']);
        Role::create(['name' => 'user']);
        
        $this->testUnit = Unit::create([
            'code' => 'TEST',
            'name' => 'Test Unit',
            'description' => 'Unit for testing',
            'is_active' => true,
        ]);
        
        // Create a test user for APAR ownership
        $testUser = User::factory()->create(['name' => 'Test Owner']);
        $testUser->assignRole('user');
        
        // Create a test APAR record for foreign key constraint
        $this->testApar = Apar::create([
            'user_id' => $testUser->id,
            'name' => 'Test APAR',
            'barcode' => 'TEST-999',
            'serial_no' => 'TEST-999',
            'type' => 'Powder',
            'capacity' => '3 Kg',
            'location_code' => 'Test Location',
            'status' => 'baik',
            'unit_id' => $this->testUnit->id,
        ]);
    }

    public function test_user_relationship_returns_correct_creator_data()
    {
        $creator = User::factory()->create([
            'name' => 'John Doe',
            'username' => 'johndoe',
            'position' => 'petugas'
        ]);
        $creator->assignRole('user');

        $kartu = KartuApar::create([
            'apar_id' => $this->testApar->id,
            'user_id' => $creator->id,
            'pressure_gauge' => 'Baik',
            'pin_segel' => 'Baik',
            'selang' => 'Baik',
            'tabung' => 'Baik',
            'label' => 'Baik',
            'kondisi_fisik' => 'Baik',
            'kesimpulan' => 'Layak',
            'petugas' => 'Test Petugas',
            'tgl_periksa' => now(),
        ]);

        $this->assertNotNull($kartu->user);
        $this->assertEquals('John Doe', $kartu->user->name);
        $this->assertEquals('johndoe', $kartu->user->username);
        $this->assertEquals($creator->id, $kartu->user->id);
    }

    public function test_approver_relationship_returns_correct_data()
    {
        $creator = User::factory()->create(['name' => 'Creator User']);
        $creator->assignRole('user');
        
        $approver = User::factory()->create([
            'name' => 'Leader User',
            'position' => 'leader'
        ]);
        $approver->assignRole('leader');

        $kartu = KartuApar::create([
            'apar_id' => $this->testApar->id,
            'user_id' => $creator->id,
            'approved_by' => $approver->id,
            'approved_at' => now(),
            'pressure_gauge' => 'Baik',
            'pin_segel' => 'Baik',
            'selang' => 'Baik',
            'tabung' => 'Baik',
            'label' => 'Baik',
            'kondisi_fisik' => 'Baik',
            'kesimpulan' => 'Layak',
            'petugas' => 'Test Petugas',
            'tgl_periksa' => now(),
        ]);

        $this->assertNotNull($kartu->approver);
        $this->assertEquals('Leader User', $kartu->approver->name);
        $this->assertEquals($approver->id, $kartu->approver->id);
    }

    public function test_deleted_creator_returns_null()
    {
        $creator = User::factory()->create(['name' => 'Deleted Creator']);
        $creator->assignRole('user');

        $kartu = KartuApar::create([
            'apar_id' => $this->testApar->id,
            'user_id' => $creator->id,
            'pressure_gauge' => 'Baik',
            'pin_segel' => 'Baik',
            'selang' => 'Baik',
            'tabung' => 'Baik',
            'label' => 'Baik',
            'kondisi_fisik' => 'Baik',
            'kesimpulan' => 'Layak',
            'petugas' => 'Test Petugas',
            'tgl_periksa' => now(),
        ]);

        $creator->delete();
        $kartu = $kartu->fresh();

        $this->assertNull($kartu->user);
    }

    public function test_deleted_approver_returns_null()
    {
        $creator = User::factory()->create(['name' => 'Creator']);
        $creator->assignRole('user');
        
        $approver = User::factory()->create(['name' => 'Deleted Approver']);
        $approver->assignRole('leader');

        $kartu = KartuApar::create([
            'apar_id' => $this->testApar->id,
            'user_id' => $creator->id,
            'approved_by' => $approver->id,
            'approved_at' => now(),
            'pressure_gauge' => 'Baik',
            'pin_segel' => 'Baik',
            'selang' => 'Baik',
            'tabung' => 'Baik',
            'label' => 'Baik',
            'kondisi_fisik' => 'Baik',
            'kesimpulan' => 'Layak',
            'petugas' => 'Test Petugas',
            'tgl_periksa' => now(),
        ]);

        $approver->delete();
        $kartu = $kartu->fresh();

        $this->assertNull($kartu->approver);
    }

    public function test_is_approved_returns_false_for_pending_kartu()
    {
        $creator = User::factory()->create(['name' => 'Creator']);
        $creator->assignRole('user');

        $kartu = KartuApar::create([
            'apar_id' => $this->testApar->id,
            'user_id' => $creator->id,
            'pressure_gauge' => 'Baik',
            'pin_segel' => 'Baik',
            'selang' => 'Baik',
            'tabung' => 'Baik',
            'label' => 'Baik',
            'kondisi_fisik' => 'Baik',
            'kesimpulan' => 'Layak',
            'petugas' => 'Test Petugas',
            'tgl_periksa' => now(),
        ]);

        $this->assertFalse($kartu->isApproved());
        $this->assertNull($kartu->approved_at);
        $this->assertNull($kartu->approved_by);
    }

    public function test_is_approved_returns_true_for_approved_kartu()
    {
        $creator = User::factory()->create(['name' => 'Creator']);
        $creator->assignRole('user');
        
        $approver = User::factory()->create(['name' => 'Approver']);
        $approver->assignRole('leader');

        $kartu = KartuApar::create([
            'apar_id' => $this->testApar->id,
            'user_id' => $creator->id,
            'approved_by' => $approver->id,
            'approved_at' => now(),
            'pressure_gauge' => 'Baik',
            'pin_segel' => 'Baik',
            'selang' => 'Baik',
            'tabung' => 'Baik',
            'label' => 'Baik',
            'kondisi_fisik' => 'Baik',
            'kesimpulan' => 'Layak',
            'petugas' => 'Test Petugas',
            'tgl_periksa' => now(),
        ]);

        $this->assertTrue($kartu->isApproved());
        $this->assertNotNull($kartu->approved_at);
        $this->assertNotNull($kartu->approved_by);
    }

    public function test_filter_by_creator_name_returns_correct_results()
    {
        $creator1 = User::factory()->create(['name' => 'Alice Smith']);
        $creator1->assignRole('user');
        
        $creator2 = User::factory()->create(['name' => 'Bob Johnson']);
        $creator2->assignRole('user');

        KartuApar::create([
            'apar_id' => $this->testApar->id,
            'user_id' => $creator1->id,
            'pressure_gauge' => 'Baik',
            'pin_segel' => 'Baik',
            'selang' => 'Baik',
            'tabung' => 'Baik',
            'label' => 'Baik',
            'kondisi_fisik' => 'Baik',
            'kesimpulan' => 'Layak',
            'petugas' => 'Test Petugas',
            'tgl_periksa' => now(),
        ]);

        KartuApar::create([
            'apar_id' => $this->testApar->id,
            'user_id' => $creator2->id,
            'pressure_gauge' => 'Baik',
            'pin_segel' => 'Baik',
            'selang' => 'Baik',
            'tabung' => 'Baik',
            'label' => 'Baik',
            'kondisi_fisik' => 'Baik',
            'kesimpulan' => 'Layak',
            'petugas' => 'Test Petugas',
            'tgl_periksa' => now()->addDay(),
        ]);

        $results = KartuApar::with('user')
            ->whereHas('user', function($q) {
                $q->where('name', 'like', '%Alice%');
            })
            ->get();

        $this->assertCount(1, $results);
        $this->assertEquals('Alice Smith', $results->first()->user->name);
    }

    public function test_filter_by_approver_name_returns_correct_results()
    {
        $creator = User::factory()->create(['name' => 'Creator']);
        $creator->assignRole('user');
        
        $approver1 = User::factory()->create(['name' => 'Leader One']);
        $approver1->assignRole('leader');
        
        $approver2 = User::factory()->create(['name' => 'Leader Two']);
        $approver2->assignRole('leader');

        KartuApar::create([
            'apar_id' => $this->testApar->id,
            'user_id' => $creator->id,
            'approved_by' => $approver1->id,
            'approved_at' => now(),
            'pressure_gauge' => 'Baik',
            'pin_segel' => 'Baik',
            'selang' => 'Baik',
            'tabung' => 'Baik',
            'label' => 'Baik',
            'kondisi_fisik' => 'Baik',
            'kesimpulan' => 'Layak',
            'petugas' => 'Test Petugas',
            'tgl_periksa' => now(),
        ]);

        KartuApar::create([
            'apar_id' => $this->testApar->id,
            'user_id' => $creator->id,
            'approved_by' => $approver2->id,
            'approved_at' => now(),
            'pressure_gauge' => 'Baik',
            'pin_segel' => 'Baik',
            'selang' => 'Baik',
            'tabung' => 'Baik',
            'label' => 'Baik',
            'kondisi_fisik' => 'Baik',
            'kesimpulan' => 'Layak',
            'petugas' => 'Test Petugas',
            'tgl_periksa' => now()->addDay(),
        ]);

        $results = KartuApar::with('approver')
            ->whereHas('approver', function($q) {
                $q->where('name', 'like', '%Leader One%');
            })
            ->get();

        $this->assertCount(1, $results);
        $this->assertEquals('Leader One', $results->first()->approver->name);
    }

    public function test_filter_by_approved_status_returns_only_approved()
    {
        $creator = User::factory()->create(['name' => 'Creator']);
        $creator->assignRole('user');
        
        $approver = User::factory()->create(['name' => 'Approver']);
        $approver->assignRole('leader');

        KartuApar::create([
            'apar_id' => $this->testApar->id,
            'user_id' => $creator->id,
            'approved_by' => $approver->id,
            'approved_at' => now(),
            'pressure_gauge' => 'Baik',
            'pin_segel' => 'Baik',
            'selang' => 'Baik',
            'tabung' => 'Baik',
            'label' => 'Baik',
            'kondisi_fisik' => 'Baik',
            'kesimpulan' => 'Layak',
            'petugas' => 'Test Petugas',
            'tgl_periksa' => now(),
        ]);

        KartuApar::create([
            'apar_id' => $this->testApar->id,
            'user_id' => $creator->id,
            'pressure_gauge' => 'Baik',
            'pin_segel' => 'Baik',
            'selang' => 'Baik',
            'tabung' => 'Baik',
            'label' => 'Baik',
            'kondisi_fisik' => 'Baik',
            'kesimpulan' => 'Layak',
            'petugas' => 'Test Petugas',
            'tgl_periksa' => now()->addDay(),
        ]);

        $results = KartuApar::whereNotNull('approved_at')->get();

        $this->assertCount(1, $results);
        $this->assertTrue($results->first()->isApproved());
    }

    public function test_filter_by_pending_status_returns_only_pending()
    {
        $creator = User::factory()->create(['name' => 'Creator']);
        $creator->assignRole('user');
        
        $approver = User::factory()->create(['name' => 'Approver']);
        $approver->assignRole('leader');

        KartuApar::create([
            'apar_id' => $this->testApar->id,
            'user_id' => $creator->id,
            'approved_by' => $approver->id,
            'approved_at' => now(),
            'pressure_gauge' => 'Baik',
            'pin_segel' => 'Baik',
            'selang' => 'Baik',
            'tabung' => 'Baik',
            'label' => 'Baik',
            'kondisi_fisik' => 'Baik',
            'kesimpulan' => 'Layak',
            'petugas' => 'Test Petugas',
            'tgl_periksa' => now(),
        ]);

        KartuApar::create([
            'apar_id' => $this->testApar->id,
            'user_id' => $creator->id,
            'pressure_gauge' => 'Baik',
            'pin_segel' => 'Baik',
            'selang' => 'Baik',
            'tabung' => 'Baik',
            'label' => 'Baik',
            'kondisi_fisik' => 'Baik',
            'kesimpulan' => 'Layak',
            'petugas' => 'Test Petugas',
            'tgl_periksa' => now()->addDay(),
        ]);

        $results = KartuApar::whereNull('approved_at')->get();

        $this->assertCount(1, $results);
        $this->assertFalse($results->first()->isApproved());
    }
}

