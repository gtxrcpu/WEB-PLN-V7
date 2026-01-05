<?php

namespace Tests\Feature;

use App\Models\BoxHydrant;
use App\Models\KartuBoxHydrant;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KartuBoxHydrantTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Unit $unit;
    protected BoxHydrant $boxHydrant;

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
        
        $this->boxHydrant = BoxHydrant::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'Box Hydrant Test',
            'serial_no' => 'BH1.001',
            'barcode' => 'BH BH1.001',
            'location_code' => 'E-101',
            'status' => 'BAIK',
        ]);
    }

    /** @test */
    public function user_can_create_kartu_box_hydrant_with_valid_data()
    {
        $this->actingAs($this->user);
        
        $response = $this->post(route('box-hydrant.kartu.store'), [
            'box_hydrant_id' => $this->boxHydrant->id,
            'kondisi_fisik' => 'BAIK',
            'kesimpulan' => 'LAYAK',
            'tgl_periksa' => now()->format('Y-m-d'),
            'petugas' => 'Test Petugas',
        ]);
        
        $response->assertRedirect(route('box-hydrant.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('kartu_box_hydrants', [
            'box_hydrant_id' => $this->boxHydrant->id,
            'user_id' => $this->user->id,
            'kesimpulan' => 'LAYAK',
        ]);
    }

    /** @test */
    public function user_can_view_kartu_history_for_box_hydrant()
    {
        $this->actingAs($this->user);
        
        KartuBoxHydrant::create([
            'box_hydrant_id' => $this->boxHydrant->id,
            'user_id' => $this->user->id,
            'kondisi_fisik' => 'BAIK',
            'kesimpulan' => 'LAYAK',
            'tgl_periksa' => now()->subDays(25),
            'petugas' => 'Petugas BH 1',
        ]);
        
        KartuBoxHydrant::create([
            'box_hydrant_id' => $this->boxHydrant->id,
            'user_id' => $this->user->id,
            'kondisi_fisik' => 'BAIK',
            'kesimpulan' => 'LAYAK',
            'tgl_periksa' => now(),
            'petugas' => 'Petugas BH 2',
        ]);
        
        $response = $this->get(route('box-hydrant.riwayat', $this->boxHydrant));
        
        $response->assertStatus(200);
        $response->assertSee('Petugas BH 1');
        $response->assertSee('Petugas BH 2');
    }
}
