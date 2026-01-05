<?php

namespace Tests\Feature;

use App\Models\RumahPompa;
use App\Models\KartuRumahPompa;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KartuRumahPompaTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Unit $unit;
    protected RumahPompa $rumahPompa;

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
        
        $this->rumahPompa = RumahPompa::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'Rumah Pompa Test',
            'serial_no' => 'RP1.001',
            'barcode' => 'RP RP1.001',
            'location_code' => 'G-101',
            'status' => 'BAIK',
        ]);
    }

    /** @test */
    public function user_can_create_kartu_rumah_pompa_with_valid_data()
    {
        $this->actingAs($this->user);
        
        $response = $this->post(route('rumah-pompa.kartu.store'), [
            'rumah_pompa_id' => $this->rumahPompa->id,
            'kondisi_fisik' => 'BAIK',
            'kesimpulan' => 'LAYAK',
            'tgl_periksa' => now()->format('Y-m-d'),
            'petugas' => 'Test Petugas',
        ]);
        
        $response->assertRedirect(route('rumah-pompa.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('kartu_rumah_pompas', [
            'rumah_pompa_id' => $this->rumahPompa->id,
            'user_id' => $this->user->id,
            'kesimpulan' => 'LAYAK',
        ]);
    }

    /** @test */
    public function user_can_view_kartu_history_for_rumah_pompa()
    {
        $this->actingAs($this->user);
        
        KartuRumahPompa::create([
            'rumah_pompa_id' => $this->rumahPompa->id,
            'user_id' => $this->user->id,
            'kondisi_fisik' => 'BAIK',
            'kesimpulan' => 'LAYAK',
            'tgl_periksa' => now()->subDays(12),
            'petugas' => 'Petugas RP 1',
        ]);
        
        KartuRumahPompa::create([
            'rumah_pompa_id' => $this->rumahPompa->id,
            'user_id' => $this->user->id,
            'kondisi_fisik' => 'BAIK',
            'kesimpulan' => 'LAYAK',
            'tgl_periksa' => now(),
            'petugas' => 'Petugas RP 2',
        ]);
        
        $response = $this->get(route('rumah-pompa.riwayat', $this->rumahPompa));
        
        $response->assertStatus(200);
        $response->assertSee('Petugas RP 1');
        $response->assertSee('Petugas RP 2');
    }
}
