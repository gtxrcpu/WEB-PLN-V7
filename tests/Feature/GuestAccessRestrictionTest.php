<?php

namespace Tests\Feature;

use App\Models\Apar;
use App\Models\Apat;
use App\Models\Apab;
use App\Models\FireAlarm;
use App\Models\BoxHydrant;
use App\Models\RumahPompa;
use App\Models\P3k;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestAccessRestrictionTest extends TestCase
{
    use RefreshDatabase;

    protected Unit $unit;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create unit
        $this->unit = Unit::create([
            'name' => 'Test Unit',
            'code' => 'TEST',
        ]);
        
        // Create user
        $this->user = User::factory()->create([
            'unit_id' => $this->unit->id,
            'position' => 'petugas',
        ]);
    }

    /** @test */
    public function guest_cannot_access_admin_dashboard()
    {
        $response = $this->get(route('admin.dashboard'));
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guest_cannot_access_apar_create_form()
    {
        $response = $this->get(route('apar.create'));
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guest_cannot_create_apar()
    {
        $response = $this->post(route('apar.store'), [
            'location_code' => 'A-101',
            'type' => 'Powder',
            'capacity' => '3 Kg',
            'status' => 'baik',
        ]);
        
        $response->assertRedirect(route('login'));
        
        // Verify no equipment was created
        $this->assertDatabaseCount('apars', 0);
    }

    /** @test */
    public function guest_cannot_access_apar_edit_form()
    {
        $apar = Apar::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'APAR Test',
            'serial_no' => 'A1.001',
            'barcode' => 'APAR A1.001',
            'type' => 'Powder',
            'capacity' => '3 Kg',
            'location_code' => 'A-101',
            'status' => 'baik',
        ]);
        
        $response = $this->get(route('apar.edit', $apar));
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guest_cannot_update_apar()
    {
        $apar = Apar::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'APAR Test',
            'serial_no' => 'A1.001',
            'barcode' => 'APAR A1.001',
            'type' => 'Powder',
            'capacity' => '3 Kg',
            'location_code' => 'A-101',
            'status' => 'baik',
        ]);
        
        $response = $this->put(route('apar.update', $apar), [
            'location_code' => 'B-202',
            'type' => 'CO2',
            'capacity' => '5 Kg',
            'status' => 'rusak',
        ]);
        
        $response->assertRedirect(route('login'));
        
        // Verify equipment was not updated
        $this->assertDatabaseHas('apars', [
            'id' => $apar->id,
            'location_code' => 'A-101',
            'type' => 'Powder',
        ]);
    }

    /** @test */
    public function guest_cannot_access_apat_create_form()
    {
        $response = $this->get(route('apat.create'));
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guest_cannot_access_apab_create_form()
    {
        $response = $this->get(route('apab.create'));
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guest_cannot_access_fire_alarm_create_form()
    {
        $response = $this->get(route('fire-alarm.create'));
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guest_cannot_access_box_hydrant_create_form()
    {
        $response = $this->get(route('box-hydrant.create'));
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guest_cannot_access_rumah_pompa_create_form()
    {
        $response = $this->get(route('rumah-pompa.create'));
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guest_cannot_access_p3k_create_form()
    {
        $response = $this->get(route('p3k.create'));
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guest_cannot_access_kartu_create_form()
    {
        $response = $this->get(route('kartu.create'));
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guest_cannot_create_kartu()
    {
        $apar = Apar::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'APAR Test',
            'serial_no' => 'A1.001',
            'barcode' => 'APAR A1.001',
            'type' => 'Powder',
            'capacity' => '3 Kg',
            'location_code' => 'A-101',
            'status' => 'baik',
        ]);
        
        $response = $this->post(route('kartu.store'), [
            'apar_id' => $apar->id,
            'tgl_periksa' => now()->format('Y-m-d'),
            'pressure_gauge' => 'baik',
            'pin_segel' => 'baik',
            'selang' => 'baik',
            'tabung' => 'baik',
            'label' => 'baik',
            'kondisi_fisik' => 'baik',
            'kesimpulan' => 'Layak',
            'petugas' => 'Test Petugas',
        ]);
        
        $response->assertRedirect(route('login'));
        
        // Verify no kartu was created
        $this->assertDatabaseCount('kartu_apars', 0);
    }

    /** @test */
    public function guest_cannot_access_admin_users_page()
    {
        $response = $this->get(route('admin.users.index'));
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guest_cannot_access_admin_approvals_page()
    {
        $response = $this->get(route('admin.approvals.index'));
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guest_cannot_access_floor_plan_management()
    {
        $response = $this->get(route('admin.floor-plans.index'));
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guest_cannot_access_quick_scan()
    {
        $response = $this->get(route('quick.scan'));
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guest_cannot_access_quick_inspeksi()
    {
        $response = $this->get(route('quick.inspeksi'));
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guest_cannot_access_quick_rekap()
    {
        $response = $this->get(route('quick.rekap'));
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guest_cannot_export_excel()
    {
        $response = $this->get(route('quick.export.excel'));
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guest_cannot_export_pdf()
    {
        $response = $this->get(route('quick.export.pdf'));
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guest_cannot_access_profile_page()
    {
        $response = $this->get(route('profile.edit'));
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guest_cannot_update_profile()
    {
        $response = $this->put(route('profile.update'), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function prevent_guest_access_middleware_redirects_to_login()
    {
        // Test that PreventGuestAccess middleware works
        $response = $this->get(route('apar.index'));
        
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
    }

    /** @test */
    public function authenticated_user_can_access_protected_routes()
    {
        $this->actingAs($this->user);
        
        $response = $this->get(route('apar.index'));
        
        $response->assertStatus(200);
    }

    /** @test */
    public function guest_cannot_access_authenticated_equipment_list()
    {
        // Test authenticated equipment list (not guest list)
        $response = $this->get(route('apar.index'));
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guest_can_access_guest_equipment_list_but_not_authenticated_list()
    {
        // Guest can access guest routes
        $response = $this->get(route('guest.apar'));
        $response->assertStatus(200);
        
        // But cannot access authenticated routes
        $response = $this->get(route('apar.index'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guest_cannot_access_leader_dashboard()
    {
        $response = $this->get(route('leader.dashboard'));
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guest_cannot_access_inspector_dashboard()
    {
        $response = $this->get(route('inspector.dashboard'));
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guest_cannot_access_referensi_page()
    {
        $response = $this->get(route('referensi.index'));
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guest_cannot_access_floor_plan_view()
    {
        $response = $this->get(route('floor-plan.index'));
        
        $response->assertRedirect(route('login'));
    }
}
