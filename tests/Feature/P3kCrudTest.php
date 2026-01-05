<?php

namespace Tests\Feature;

use App\Models\P3k;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class P3kCrudTest extends TestCase
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
    public function user_can_view_p3k_list()
    {
        $this->actingAs($this->user);
        
        P3k::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'P3K Test',
            'serial_no' => 'P3K.001',
            'barcode' => 'P3K P3K.001',
            'location_code' => 'Ruang A',
            'type' => 'Kotak P3K',
            'status' => 'lengkap',
        ]);
        
        $response = $this->get(route('p3k.index'));
        
        $response->assertStatus(200);
        $response->assertSee('P3K Test');
        $response->assertSee('P3K.001');
    }

    /** @test */
    public function user_can_create_p3k_with_valid_data()
    {
        $this->actingAs($this->user);
        
        $response = $this->post(route('p3k.store'), [
            'name' => 'P3K New',
            'serial_no' => 'P3K.002',
            'barcode' => 'P3K P3K.002',
            'location_code' => 'Ruang B',
            'type' => 'Kotak P3K',
            'status' => 'lengkap',
            'notes' => 'Test notes',
        ]);
        
        $response->assertRedirect(route('p3k.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('p3ks', [
            'name' => 'P3K New',
            'serial_no' => 'P3K.002',
            'location_code' => 'Ruang B',
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function user_cannot_create_p3k_with_invalid_data()
    {
        $this->actingAs($this->user);
        
        $response = $this->post(route('p3k.store'), [
            'notes' => 'Test notes',
        ]);
        
        $response->assertSessionHasErrors(['name', 'barcode', 'serial_no']);
    }

    /** @test */
    public function user_can_update_p3k_details()
    {
        $this->actingAs($this->user);
        
        $p3k = P3k::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'P3K Test',
            'serial_no' => 'P3K.001',
            'barcode' => 'P3K P3K.001',
            'location_code' => 'Ruang A',
            'status' => 'lengkap',
        ]);
        
        $response = $this->put(route('p3k.update', $p3k), [
            'name' => 'P3K Updated',
            'location_code' => 'Ruang C',
            'type' => 'Tas P3K',
            'status' => 'tidak lengkap',
            'notes' => 'Updated notes',
        ]);
        
        $response->assertRedirect(route('p3k.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('p3ks', [
            'id' => $p3k->id,
            'name' => 'P3K Updated',
            'location_code' => 'Ruang C',
            'status' => 'tidak lengkap',
        ]);
    }

    /** @test */
    public function qr_code_is_generated_for_p3k()
    {
        $this->actingAs($this->user);
        
        $p3k = P3k::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'P3K Test',
            'serial_no' => 'P3K.001',
            'barcode' => 'P3K P3K.001',
            'location_code' => 'Ruang A',
            'status' => 'lengkap',
        ]);
        
        $this->assertNotNull($p3k->qr_url);
        $this->assertStringContainsString('data:image/svg+xml;base64,', $p3k->qr_url);
    }

    /** @test */
    public function guest_cannot_access_p3k_crud_routes()
    {
        $response = $this->get(route('p3k.index'));
        $response->assertRedirect(route('login'));
        
        $response = $this->get(route('p3k.create'));
        $response->assertRedirect(route('login'));
        
        $response = $this->post(route('p3k.store'), []);
        $response->assertRedirect(route('login'));
    }
}
