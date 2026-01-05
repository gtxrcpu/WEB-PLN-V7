<?php

namespace Tests\Feature;

use App\Models\Apar;
use App\Models\KartuApar;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ApprovalSubmissionTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $leader;
    protected Unit $unit;
    protected Apar $apar;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::create(['name' => 'user']);
        Role::create(['name' => 'leader']);
        Role::create(['name' => 'superadmin']);
        
        // Create unit
        $this->unit = Unit::create([
            'name' => 'Test Unit',
            'code' => 'TEST',
        ]);
        
        // Create regular user (petugas)
        $this->user = User::factory()->create([
            'unit_id' => $this->unit->id,
            'position' => 'petugas',
        ]);
        
        // Create leader user
        $this->leader = User::factory()->create([
            'unit_id' => $this->unit->id,
            'position' => 'leader',
        ]);
        
        // Assign roles
        $this->user->assignRole('user');
        $this->leader->assignRole('superadmin');
        
        // Create test APAR
        $this->apar = Apar::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'APAR Test',
            'serial_no' => 'A1.001',
            'barcode' => 'APAR A1.001',
            'type' => 'Powder',
            'capacity' => '3 Kg',
            'location_code' => 'A-101',
            'status' => 'BAIK',
        ]);
    }

    /** @test */
    public function user_can_submit_kartu_for_approval()
    {
        $this->actingAs($this->user);
        
        $response = $this->post(route('kartu.store'), [
            'apar_id' => $this->apar->id,
            'pressure_gauge' => 'BAIK',
            'pin_segel' => 'BAIK',
            'selang' => 'BAIK',
            'tabung' => 'BAIK',
            'label' => 'BAIK',
            'kondisi_fisik' => 'BAIK',
            'kesimpulan' => 'LAYAK',
            'tgl_periksa' => now()->format('Y-m-d'),
            'petugas' => 'Test Petugas',
        ]);
        
        $response->assertRedirect(route('apar.index'));
        
        // Verify kartu is created and pending approval
        $kartu = KartuApar::latest()->first();
        $this->assertNotNull($kartu);
        $this->assertNull($kartu->approved_at);
        $this->assertNull($kartu->approved_by);
    }

    /** @test */
    public function leader_can_view_pending_approvals()
    {
        $this->actingAs($this->leader);
        
        // Create pending kartu
        KartuApar::create([
            'apar_id' => $this->apar->id,
            'user_id' => $this->user->id,
            'pressure_gauge' => 'BAIK',
            'pin_segel' => 'BAIK',
            'selang' => 'BAIK',
            'tabung' => 'BAIK',
            'label' => 'BAIK',
            'kondisi_fisik' => 'BAIK',
            'kesimpulan' => 'LAYAK',
            'tgl_periksa' => now(),
            'petugas' => 'Test Petugas',
        ]);
        
        $response = $this->get(route('admin.approvals.index'));
        
        $response->assertStatus(200);
        $response->assertSee('Test Petugas');
        $response->assertSee('APAR');
    }

    /** @test */
    public function regular_user_cannot_access_approval_page()
    {
        $this->actingAs($this->user);
        
        $response = $this->get(route('admin.approvals.index'));
        
        $response->assertStatus(403);
    }

    /** @test */
    public function guest_cannot_access_approval_page()
    {
        $response = $this->get(route('admin.approvals.index'));
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function pending_approvals_show_correct_equipment_details()
    {
        $this->actingAs($this->leader);
        
        // Create pending kartu
        $kartu = KartuApar::create([
            'apar_id' => $this->apar->id,
            'user_id' => $this->user->id,
            'pressure_gauge' => 'BAIK',
            'pin_segel' => 'BAIK',
            'selang' => 'BAIK',
            'tabung' => 'BAIK',
            'label' => 'BAIK',
            'kondisi_fisik' => 'BAIK',
            'kesimpulan' => 'LAYAK',
            'tgl_periksa' => now(),
            'petugas' => 'Test Petugas',
        ]);
        
        $response = $this->get(route('admin.approvals.index'));
        
        $response->assertStatus(200);
        $response->assertSee($this->apar->serial_no);
        $response->assertSee('APAR');
    }

    /** @test */
    public function approved_kartu_not_shown_in_pending_list()
    {
        $this->actingAs($this->leader);
        
        // Create approved kartu
        KartuApar::create([
            'apar_id' => $this->apar->id,
            'user_id' => $this->user->id,
            'pressure_gauge' => 'BAIK',
            'pin_segel' => 'BAIK',
            'selang' => 'BAIK',
            'tabung' => 'BAIK',
            'label' => 'BAIK',
            'kondisi_fisik' => 'BAIK',
            'kesimpulan' => 'LAYAK',
            'tgl_periksa' => now(),
            'petugas' => 'Approved Petugas',
            'approved_by' => $this->leader->id,
            'approved_at' => now(),
        ]);
        
        $response = $this->get(route('admin.approvals.index'));
        
        $response->assertStatus(200);
        $response->assertDontSee('Approved Petugas');
    }

    /** @test */
    public function leader_can_view_approval_detail()
    {
        $this->actingAs($this->leader);
        
        $kartu = KartuApar::create([
            'apar_id' => $this->apar->id,
            'user_id' => $this->user->id,
            'pressure_gauge' => 'BAIK',
            'pin_segel' => 'BAIK',
            'selang' => 'BAIK',
            'tabung' => 'BAIK',
            'label' => 'BAIK',
            'kondisi_fisik' => 'BAIK',
            'kesimpulan' => 'LAYAK',
            'tgl_periksa' => now(),
            'petugas' => 'Test Petugas',
        ]);
        
        $response = $this->get(route('admin.approvals.show', ['id' => $kartu->id, 'type' => 'apar']));
        
        $response->assertStatus(200);
        $response->assertSee('Test Petugas');
        $response->assertSee($this->apar->serial_no);
    }
}
