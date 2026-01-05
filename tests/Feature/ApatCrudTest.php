<?php

namespace Tests\Feature;

use App\Models\Apat;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApatCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Unit $unit;

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
    }

    /** @test */
    public function user_can_view_apat_list()
    {
        $this->actingAs($this->user);
        
        Apat::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'APAT Test',
            'serial_no' => 'A2.001',
            'barcode' => 'APAT A2.001',
            'lokasi' => 'Lantai 2',
            'jenis' => 'Thermatic',
            'kapasitas' => '10 Kg',
            'status' => 'baik',
        ]);
        
        $response = $this->get(route('apat.index'));
        
        $response->assertStatus(200);
        $response->assertSee('APAT Test');
        $response->assertSee('A2.001');
    }

    /** @test */
    public function user_can_create_apat_with_valid_data()
    {
        $this->actingAs($this->user);
        
        $response = $this->post(route('apat.store'), [
            'name' => 'APAT New',
            'serial_no' => 'A2.002',
            'barcode' => 'APAT A2.002',
            'lokasi' => 'Lantai 3',
            'jenis' => 'Thermatic',
            'kapasitas' => '15 Kg',
            'status' => 'baik',
            'notes' => 'Test notes',
        ]);
        
        $response->assertRedirect(route('apat.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('apats', [
            'name' => 'APAT New',
            'serial_no' => 'A2.002',
            'lokasi' => 'Lantai 3',
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function user_cannot_create_apat_with_invalid_data()
    {
        $this->actingAs($this->user);
        
        $response = $this->post(route('apat.store'), [
            'notes' => 'Test notes',
        ]);
        
        $response->assertSessionHasErrors(['name', 'barcode', 'serial_no']);
    }

    /** @test */
    public function user_can_update_apat_details()
    {
        $this->actingAs($this->user);
        
        $apat = Apat::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'APAT Test',
            'serial_no' => 'A2.001',
            'barcode' => 'APAT A2.001',
            'lokasi' => 'Lantai 2',
            'status' => 'baik',
        ]);
        
        $response = $this->put(route('apat.update', $apat), [
            'name' => 'APAT Updated',
            'lokasi' => 'Lantai 4',
            'jenis' => 'Thermatic Plus',
            'kapasitas' => '20 Kg',
            'status' => 'rusak',
            'notes' => 'Updated notes',
        ]);
        
        $response->assertRedirect(route('apat.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('apats', [
            'id' => $apat->id,
            'name' => 'APAT Updated',
            'lokasi' => 'Lantai 4',
            'status' => 'rusak',
        ]);
    }

    /** @test */
    public function qr_code_is_generated_for_apat()
    {
        $this->actingAs($this->user);
        
        $apat = Apat::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'APAT Test',
            'serial_no' => 'A2.001',
            'barcode' => 'APAT A2.001',
            'lokasi' => 'Lantai 2',
            'status' => 'baik',
        ]);
        
        $this->assertNotNull($apat->qr_url);
        $this->assertStringContainsString('data:image/svg+xml;base64,', $apat->qr_url);
    }

    /** @test */
    public function guest_cannot_access_apat_crud_routes()
    {
        $response = $this->get(route('apat.index'));
        $response->assertRedirect(route('login'));
        
        $response = $this->get(route('apat.create'));
        $response->assertRedirect(route('login'));
        
        $response = $this->post(route('apat.store'), []);
        $response->assertRedirect(route('login'));
    }
}
