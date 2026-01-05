<?php

namespace Tests\Feature;

use App\Models\Apar;
use App\Models\KartuApar;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KartuAparTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Unit $unit;
    protected Apar $apar;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create unit
        $this->unit = Unit::create([
            'name' => 'Test Unit',
            'code' => 'TEST',
        ]);
        
        // Create authenticated user
        $this->user = User::factory()->create([
            'unit_id' => $this->unit->id,
            'position' => 'petugas',
        ]);
        
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
    public function user_can_create_kartu_with_valid_data()
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
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('kartu_apars', [
            'apar_id' => $this->apar->id,
            'user_id' => $this->user->id,
            'pressure_gauge' => 'BAIK',
            'kesimpulan' => 'LAYAK',
            'petugas' => 'Test Petugas',
        ]);
    }

    /** @test */
    public function user_cannot_create_kartu_with_invalid_data()
    {
        $this->actingAs($this->user);
        
        // Missing required fields
        $response = $this->post(route('kartu.store'), [
            'apar_id' => $this->apar->id,
        ]);
        
        $response->assertSessionHasErrors([
            'pressure_gauge',
            'pin_segel',
            'selang',
            'tabung',
            'label',
            'kondisi_fisik',
            'kesimpulan',
            'tgl_periksa',
            'petugas',
        ]);
    }

    /** @test */
    public function user_cannot_create_kartu_with_invalid_apar_id()
    {
        $this->actingAs($this->user);
        
        $response = $this->post(route('kartu.store'), [
            'apar_id' => 99999, // Non-existent APAR
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
        
        $response->assertSessionHasErrors(['apar_id']);
    }

    /** @test */
    public function user_can_view_kartu_history_for_equipment()
    {
        $this->actingAs($this->user);
        
        // Create multiple kartu records
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
            'tgl_periksa' => now()->subDays(30),
            'petugas' => 'Petugas 1',
        ]);
        
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
            'petugas' => 'Petugas 2',
        ]);
        
        $response = $this->get(route('apar.riwayat', $this->apar));
        
        $response->assertStatus(200);
        $response->assertSee('Petugas 1');
        $response->assertSee('Petugas 2');
    }

    /** @test */
    public function date_validation_works_correctly()
    {
        $this->actingAs($this->user);
        
        // Invalid date format
        $response = $this->post(route('kartu.store'), [
            'apar_id' => $this->apar->id,
            'pressure_gauge' => 'BAIK',
            'pin_segel' => 'BAIK',
            'selang' => 'BAIK',
            'tabung' => 'BAIK',
            'label' => 'BAIK',
            'kondisi_fisik' => 'BAIK',
            'kesimpulan' => 'LAYAK',
            'tgl_periksa' => 'invalid-date',
            'petugas' => 'Test Petugas',
        ]);
        
        $response->assertSessionHasErrors(['tgl_periksa']);
    }

    /** @test */
    public function equipment_association_is_correct()
    {
        $this->actingAs($this->user);
        
        $this->post(route('kartu.store'), [
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
        
        $kartu = KartuApar::latest()->first();
        
        $this->assertNotNull($kartu);
        $this->assertEquals($this->apar->id, $kartu->apar_id);
        $this->assertEquals($this->apar->id, $kartu->apar->id);
        $this->assertEquals('APAR Test', $kartu->apar->name);
    }

    /** @test */
    public function guest_cannot_create_kartu()
    {
        $response = $this->get(route('kartu.create', ['apar_id' => $this->apar->id]));
        $response->assertRedirect(route('login'));
        
        $response = $this->post(route('kartu.store'), []);
        $response->assertRedirect(route('login'));
    }
}
