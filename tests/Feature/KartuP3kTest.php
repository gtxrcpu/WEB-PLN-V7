<?php

namespace Tests\Feature;

use App\Models\P3k;
use App\Models\KartuP3k;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KartuP3kTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Unit $unit;
    protected P3k $p3k;

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
        
        $this->p3k = P3k::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'P3K Test',
            'serial_no' => 'P3K1.001',
            'barcode' => 'P3K P3K1.001',
            'location_code' => 'D-101',
            'status' => 'BAIK',
        ]);
    }

    /** @test */
    public function user_can_create_kartu_p3k_with_valid_data()
    {
        $this->actingAs($this->user);
        
        $response = $this->post(route('p3k.kartu.store'), [
            'p3k_id' => $this->p3k->id,
            'kotak_p3k' => 'BAIK',
            'plester' => 'BAIK',
            'perban' => 'BAIK',
            'kasa_steril' => 'BAIK',
            'antiseptik' => 'BAIK',
            'gunting' => 'BAIK',
            'sarung_tangan' => 'BAIK',
            'masker' => 'BAIK',
            'obat_luka' => 'BAIK',
            'buku_panduan' => 'BAIK',
            'kesimpulan' => 'LAYAK',
            'tgl_periksa' => now()->format('Y-m-d'),
            'petugas' => 'Test Petugas',
        ]);
        
        $response->assertRedirect(route('p3k.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('kartu_p3ks', [
            'p3k_id' => $this->p3k->id,
            'user_id' => $this->user->id,
            'kesimpulan' => 'LAYAK',
        ]);
    }

    /** @test */
    public function user_can_view_kartu_history_for_p3k()
    {
        $this->actingAs($this->user);
        
        KartuP3k::create([
            'p3k_id' => $this->p3k->id,
            'user_id' => $this->user->id,
            'kotak_p3k' => 'BAIK',
            'kesimpulan' => 'LAYAK',
            'tgl_periksa' => now()->subDays(10),
            'petugas' => 'Petugas P3K 1',
        ]);
        
        KartuP3k::create([
            'p3k_id' => $this->p3k->id,
            'user_id' => $this->user->id,
            'kotak_p3k' => 'BAIK',
            'kesimpulan' => 'LAYAK',
            'tgl_periksa' => now(),
            'petugas' => 'Petugas P3K 2',
        ]);
        
        $response = $this->get(route('p3k.riwayat', $this->p3k));
        
        $response->assertStatus(200);
        $response->assertSee('Petugas P3K 1');
        $response->assertSee('Petugas P3K 2');
    }
}
