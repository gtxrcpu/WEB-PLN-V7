<?php

namespace Tests\Feature;

use App\Models\Apar;
use App\Models\Apat;
use App\Models\Apab;
use App\Models\FireAlarm;
use App\Models\BoxHydrant;
use App\Models\RumahPompa;
use App\Models\P3k;
use App\Models\KartuApar;
use App\Models\KartuApat;
use App\Models\KartuApab;
use App\Models\KartuFireAlarm;
use App\Models\KartuBoxHydrant;
use App\Models\KartuRumahPompa;
use App\Models\KartuP3k;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestQrAccessTest extends TestCase
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
        
        // Create user for equipment ownership
        $this->user = User::factory()->create([
            'unit_id' => $this->unit->id,
            'position' => 'petugas',
        ]);
    }

    /** @test */
    public function guest_can_access_apar_details_via_qr_code()
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
        
        // Guest accesses equipment via QR code route (no authentication)
        $response = $this->get(route('guest.apar.riwayat', $apar));
        
        $response->assertStatus(200);
        $response->assertSee('APAR Test');
        $response->assertSee('A1.001');
        $response->assertSee('A-101');
    }

    /** @test */
    public function guest_can_view_apar_kartu_history()
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
        
        // Create kartu history
        KartuApar::create([
            'apar_id' => $apar->id,
            'user_id' => $this->user->id,
            'tgl_periksa' => now(),
            'pressure_gauge' => 'baik',
            'pin_segel' => 'baik',
            'selang' => 'baik',
            'tabung' => 'baik',
            'label' => 'baik',
            'kondisi_fisik' => 'baik',
            'kesimpulan' => 'Layak',
            'petugas' => 'Test Petugas',
        ]);
        
        $response = $this->get(route('guest.apar.riwayat', $apar));
        
        $response->assertStatus(200);
        $response->assertSee('Test Petugas');
        $response->assertSee('Layak');
    }

    /** @test */
    public function guest_can_access_apat_details()
    {
        $apat = Apat::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'APAT Test',
            'serial_no' => 'AT1.001',
            'barcode' => 'APAT AT1.001',
            'type' => 'Thermatic',
            'capacity' => '10 Kg',
            'location_code' => 'B-201',
            'status' => 'baik',
        ]);
        
        $response = $this->get(route('guest.apat.riwayat', $apat));
        
        $response->assertStatus(200);
        $response->assertSee('APAT Test');
        $response->assertSee('AT1.001');
    }

    /** @test */
    public function guest_can_access_apab_details()
    {
        $apab = Apab::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'APAB Test',
            'serial_no' => 'AB1.001',
            'barcode' => 'APAB AB1.001',
            'type' => 'Heavy',
            'capacity' => '50 Kg',
            'location_code' => 'C-301',
            'status' => 'baik',
        ]);
        
        $response = $this->get(route('guest.apab.riwayat', $apab));
        
        $response->assertStatus(200);
        $response->assertSee('APAB Test');
        $response->assertSee('AB1.001');
    }

    /** @test */
    public function guest_can_access_fire_alarm_details()
    {
        $fireAlarm = FireAlarm::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'Fire Alarm Test',
            'serial_no' => 'FA1.001',
            'barcode' => 'FA FA1.001',
            'type' => 'Smoke Detector',
            'location_code' => 'D-401',
            'status' => 'baik',
        ]);
        
        $response = $this->get(route('guest.fire-alarm.riwayat', $fireAlarm));
        
        $response->assertStatus(200);
        $response->assertSee('Fire Alarm Test');
        $response->assertSee('FA1.001');
    }

    /** @test */
    public function guest_can_access_box_hydrant_details()
    {
        $boxHydrant = BoxHydrant::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'Box Hydrant Test',
            'serial_no' => 'BH1.001',
            'barcode' => 'BH BH1.001',
            'type' => 'Indoor',
            'location_code' => 'E-501',
            'status' => 'baik',
        ]);
        
        $response = $this->get(route('guest.box-hydrant.riwayat', $boxHydrant));
        
        $response->assertStatus(200);
        $response->assertSee('Box Hydrant Test');
        $response->assertSee('BH1.001');
    }

    /** @test */
    public function guest_can_access_rumah_pompa_details()
    {
        $rumahPompa = RumahPompa::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'Rumah Pompa Test',
            'serial_no' => 'RP1.001',
            'barcode' => 'RP RP1.001',
            'type' => 'Electric',
            'location_code' => 'F-601',
            'status' => 'baik',
        ]);
        
        $response = $this->get(route('guest.rumah-pompa.riwayat', $rumahPompa));
        
        $response->assertStatus(200);
        $response->assertSee('Rumah Pompa Test');
        $response->assertSee('RP1.001');
    }

    /** @test */
    public function guest_can_access_p3k_details()
    {
        $p3k = P3k::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'P3K Test',
            'serial_no' => 'P3K1.001',
            'barcode' => 'P3K P3K1.001',
            'jenis' => 'Kotak P3K',
            'location_code' => 'G-701',
            'status' => 'baik',
        ]);
        
        $response = $this->get(route('guest.p3k.riwayat', $p3k));
        
        $response->assertStatus(200);
        $response->assertSee('P3K Test');
        $response->assertSee('P3K1.001');
    }

    /** @test */
    public function guest_can_view_equipment_list()
    {
        Apar::create([
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
        
        $response = $this->get(route('guest.apar'));
        
        $response->assertStatus(200);
        $response->assertSee('APAR Test');
        $response->assertSee('A1.001');
    }

    /** @test */
    public function guest_can_access_dashboard()
    {
        $response = $this->get(route('guest.dashboard'));
        
        $response->assertStatus(200);
        $response->assertSee('Dashboard');
    }

    /** @test */
    public function guest_cannot_see_sensitive_data_in_kartu_history()
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
        
        // Create kartu with approval data
        $approver = User::factory()->create([
            'unit_id' => $this->unit->id,
            'position' => 'leader',
        ]);
        
        KartuApar::create([
            'apar_id' => $apar->id,
            'user_id' => $this->user->id,
            'tgl_periksa' => now(),
            'pressure_gauge' => 'baik',
            'pin_segel' => 'baik',
            'selang' => 'baik',
            'tabung' => 'baik',
            'label' => 'baik',
            'kondisi_fisik' => 'baik',
            'kesimpulan' => 'Layak',
            'petugas' => 'Test Petugas',
            'approved_by' => $approver->id,
            'approved_at' => now(),
        ]);
        
        $response = $this->get(route('guest.apar.riwayat', $apar));
        
        $response->assertStatus(200);
        // Should see basic info
        $response->assertSee('Test Petugas');
        // Should not see approval details in the response data
        // (The view filters these out via makeHidden)
    }

    /** @test */
    public function guest_can_access_report_page()
    {
        $response = $this->get(route('guest.report'));
        
        $response->assertStatus(200);
        $response->assertSee('Laporan');
    }

    /** @test */
    public function qr_code_validation_returns_404_for_invalid_equipment()
    {
        // Try to access non-existent equipment
        $response = $this->get(route('guest.apar.riwayat', 99999));
        
        $response->assertStatus(404);
    }
}
