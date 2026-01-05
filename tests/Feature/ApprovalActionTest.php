<?php

namespace Tests\Feature;

use App\Models\Apar;
use App\Models\KartuApar;
use App\Models\User;
use App\Models\Unit;
use App\Models\Signature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ApprovalActionTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $leader;
    protected Unit $unit;
    protected Apar $apar;
    protected Signature $signature;

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
        
        // Create test signature
        $this->signature = Signature::create([
            'name' => 'Test Leader',
            'position' => 'Manager',
            'nip' => '123456',
            'is_active' => true,
        ]);
    }

    /** @test */
    public function leader_can_approve_kartu()
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
        
        $response = $this->post(route('admin.approvals.approve', $kartu->id), [
            'signature_id' => $this->signature->id,
            'type' => 'apar',
        ]);
        
        $response->assertRedirect(route('admin.approvals.index'));
        $response->assertSessionHas('success');
        
        // Verify kartu is approved
        $kartu->refresh();
        $this->assertNotNull($kartu->approved_at);
        $this->assertEquals($this->leader->id, $kartu->approved_by);
        $this->assertEquals($this->signature->id, $kartu->signature_id);
    }

    /** @test */
    public function leader_can_reject_kartu()
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
            'signature_id' => $this->signature->id,
            'approved_by' => $this->leader->id,
            'approved_at' => now(),
        ]);
        
        $response = $this->post(route('admin.approvals.reject', $kartu->id), [
            'type' => 'apar',
        ]);
        
        $response->assertRedirect(route('admin.approvals.index'));
        $response->assertSessionHas('success');
        
        // Verify approval is removed
        $kartu->refresh();
        $this->assertNull($kartu->approved_at);
        $this->assertNull($kartu->approved_by);
        $this->assertNull($kartu->signature_id);
    }

    /** @test */
    public function regular_user_cannot_approve_kartu()
    {
        $this->actingAs($this->user);
        
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
        
        $response = $this->post(route('admin.approvals.approve', $kartu->id), [
            'signature_id' => $this->signature->id,
            'type' => 'apar',
        ]);
        
        $response->assertStatus(403);
    }

    /** @test */
    public function guest_cannot_approve_kartu()
    {
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
        
        $response = $this->post(route('admin.approvals.approve', $kartu->id), [
            'signature_id' => $this->signature->id,
            'type' => 'apar',
        ]);
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function approval_requires_valid_signature()
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
        
        $response = $this->post(route('admin.approvals.approve', $kartu->id), [
            'signature_id' => 99999, // Invalid signature
            'type' => 'apar',
        ]);
        
        $response->assertSessionHasErrors(['signature_id']);
    }

    /** @test */
    public function approval_history_is_tracked()
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
        
        $this->post(route('admin.approvals.approve', $kartu->id), [
            'signature_id' => $this->signature->id,
            'type' => 'apar',
        ]);
        
        $kartu->refresh();
        
        // Verify approval history
        $this->assertNotNull($kartu->approved_at);
        $this->assertEquals($this->leader->id, $kartu->approved_by);
        $this->assertInstanceOf(\DateTime::class, $kartu->approved_at);
    }

    /** @test */
    public function approval_timestamps_are_recorded()
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
        
        $beforeApproval = now();
        
        $this->post(route('admin.approvals.approve', $kartu->id), [
            'signature_id' => $this->signature->id,
            'type' => 'apar',
        ]);
        
        $afterApproval = now();
        
        $kartu->refresh();
        
        // Verify timestamp is within expected range
        $this->assertTrue($kartu->approved_at >= $beforeApproval);
        $this->assertTrue($kartu->approved_at <= $afterApproval);
    }

    /** @test */
    public function approval_user_tracking_is_correct()
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
        
        $this->post(route('admin.approvals.approve', $kartu->id), [
            'signature_id' => $this->signature->id,
            'type' => 'apar',
        ]);
        
        $kartu->refresh();
        
        // Verify approver relationship
        $this->assertNotNull($kartu->approver);
        $this->assertEquals($this->leader->id, $kartu->approver->id);
        $this->assertEquals($this->leader->name, $kartu->approver->name);
    }

    /** @test */
    public function kartu_can_be_approved_and_rejected_multiple_times()
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
        
        // First approval
        $this->post(route('admin.approvals.approve', $kartu->id), [
            'signature_id' => $this->signature->id,
            'type' => 'apar',
        ]);
        
        $kartu->refresh();
        $this->assertNotNull($kartu->approved_at);
        
        // Reject
        $this->post(route('admin.approvals.reject', $kartu->id), [
            'type' => 'apar',
        ]);
        
        $kartu->refresh();
        $this->assertNull($kartu->approved_at);
        
        // Second approval
        $this->post(route('admin.approvals.approve', $kartu->id), [
            'signature_id' => $this->signature->id,
            'type' => 'apar',
        ]);
        
        $kartu->refresh();
        $this->assertNotNull($kartu->approved_at);
    }
}
