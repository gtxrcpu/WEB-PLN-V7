<?php

namespace Tests\Feature;

use App\Models\Apab;
use App\Models\KartuApab;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KartuApabTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Unit $unit;
    protected Apab $apab;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->unit = Unit::create([
            'name' => 'Test Unit',
            'code' => 'TEST',
        ]);
        
        $this->user = User::factory()->create([
            'unit_id' => $this->unit->id,
            'position' => 'petugas',
        ]);
        
        $this->apab = Apab::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'APAB Test',
            'serial_no' => 'AB1.001',
            'barcode' => 'APAB AB1.001',
            'type' => 'CO2',
            'capacity' => '50 Kg',
            'location_code' => 'B-101',
            'status' => 'BAIK',
        ]);
    }

    /** @test */
    public function user_can_create_kartu_apab_with_valid_data()
    {
        $this->actingAs($this->user);
        
        $response = $this->post(route('apab.kartu.store'), [
            'apab_id' => $this->apab->id,
            'kondisi_fisik' => 'BAIK',
            'kesimpulan' => 'LAYAK',
            'tgl_periksa' => now()->format('Y-m-d'),
            'petugas' => 'Test Petugas',
        ]);
        
        $response->assertRedirect(route('apab.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('kartu_apabs', [
            'apab_id' => $this->apab->id,
            'user_id' => $this->user->id,
            'kesimpulan' => 'LAYAK',
        ]);
    }

    /** @test */
    public function user_can_view_kartu_history_for_apab()
    {
        $this->actingAs($this->user);
        
        KartuApab::create([
            'apab_id' => $this->apab->id,
            'user_id' => $this->user->id,
            'kondisi_fisik' => 'BAIK',
            'kesimpulan' => 'LAYAK',
            'tgl_periksa' => now()->subDays(15),
            'petugas' => 'Petugas APAB 1',
        ]);
        
        KartuApab::create([
            'apab_id' => $this->apab->id,
            'user_id' => $this->user->id,
            'kondisi_fisik' => 'BAIK',
            'kesimpulan' => 'LAYAK',
            'tgl_periksa' => now(),
            'petugas' => 'Petugas APAB 2',
        ]);
        
        $response = $this->get(route('apab.riwayat', $this->apab));
        
        $response->assertStatus(200);
        $response->assertSee('Petugas APAB 1');
        $response->assertSee('Petugas APAB 2');
    }
}
