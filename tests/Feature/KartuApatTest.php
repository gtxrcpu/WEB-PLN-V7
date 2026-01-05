<?php

namespace Tests\Feature;

use App\Models\Apat;
use App\Models\KartuApat;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KartuApatTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Unit $unit;
    protected Apat $apat;

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
        
        $this->apat = Apat::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'APAT Test',
            'serial_no' => 'AT1.001',
            'barcode' => 'APAT AT1.001',
            'location_code' => 'C-101',
            'status' => 'BAIK',
        ]);
    }

    /** @test */
    public function user_can_create_kartu_apat_with_valid_data()
    {
        $this->actingAs($this->user);
        
        $response = $this->post(route('apat.kartu.store'), [
            'apat_id' => $this->apat->id,
            'kondisi_fisik' => 'BAIK',
            'drum' => 'BAIK',
            'aduk_pasir' => 'BAIK',
            'sekop' => 'BAIK',
            'fire_blanket' => 'BAIK',
            'ember' => 'BAIK',
            'kesimpulan' => 'LAYAK',
            'tgl_periksa' => now()->format('Y-m-d'),
            'petugas' => 'Test Petugas',
        ]);
        
        $response->assertRedirect(route('apat.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('kartu_apats', [
            'apat_id' => $this->apat->id,
            'user_id' => $this->user->id,
            'kesimpulan' => 'LAYAK',
        ]);
    }

    /** @test */
    public function user_can_view_kartu_history_for_apat()
    {
        $this->actingAs($this->user);
        
        KartuApat::create([
            'apat_id' => $this->apat->id,
            'user_id' => $this->user->id,
            'kondisi_fisik' => 'BAIK',
            'kesimpulan' => 'LAYAK',
            'tgl_periksa' => now()->subDays(20),
            'petugas' => 'Petugas APAT 1',
        ]);
        
        KartuApat::create([
            'apat_id' => $this->apat->id,
            'user_id' => $this->user->id,
            'kondisi_fisik' => 'BAIK',
            'kesimpulan' => 'LAYAK',
            'tgl_periksa' => now(),
            'petugas' => 'Petugas APAT 2',
        ]);
        
        $response = $this->get(route('apat.riwayat', $this->apat));
        
        $response->assertStatus(200);
        $response->assertSee('Petugas APAT 1');
        $response->assertSee('Petugas APAT 2');
    }
}
