<?php

namespace Tests\Feature;

use App\Models\RumahPompa;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RumahPompaCrudTest extends TestCase
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
    public function user_can_view_rumah_pompa_list()
    {
        $this->actingAs($this->user);
        
        RumahPompa::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'Rumah Pompa Test',
            'serial_no' => 'RP.001',
            'barcode' => 'RUMAH POMPA RP.001',
            'location_code' => 'Area A',
            'type' => 'Pompa Utama',
            'zone' => 'Zone 1',
            'status' => 'BAIK',
        ]);
        
        $response = $this->get(route('rumah-pompa.index'));
        
        $response->assertStatus(200);
        $response->assertSee('Rumah Pompa Test');
        $response->assertSee('RP.001');
    }

    /** @test */
    public function user_can_create_rumah_pompa_with_valid_data()
    {
        $this->actingAs($this->user);
        
        $response = $this->post(route('rumah-pompa.store'), [
            'name' => 'Rumah Pompa New',
            'serial_no' => 'RP.002',
            'barcode' => 'RUMAH POMPA RP.002',
            'location_code' => 'Area B',
            'type' => 'Pompa Cadangan',
            'zone' => 'Zone 2',
            'status' => 'BAIK',
            'notes' => 'Test notes',
        ]);
        
        $response->assertRedirect(route('rumah-pompa.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('rumah_pompas', [
            'name' => 'Rumah Pompa New',
            'serial_no' => 'RP.002',
            'location_code' => 'Area B',
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function user_can_update_rumah_pompa_details()
    {
        $this->actingAs($this->user);
        
        $rumahPompa = RumahPompa::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'Rumah Pompa Test',
            'serial_no' => 'RP.001',
            'barcode' => 'RUMAH POMPA RP.001',
            'location_code' => 'Area A',
            'status' => 'BAIK',
        ]);
        
        $response = $this->put(route('rumah-pompa.update', $rumahPompa), [
            'name' => 'Rumah Pompa Updated',
            'location_code' => 'Area C',
            'type' => 'Pompa Jockey',
            'zone' => 'Zone 3',
            'status' => 'RUSAK',
            'notes' => 'Updated notes',
        ]);
        
        $response->assertRedirect(route('rumah-pompa.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('rumah_pompas', [
            'id' => $rumahPompa->id,
            'name' => 'Rumah Pompa Updated',
            'location_code' => 'Area C',
            'status' => 'RUSAK',
        ]);
    }

    /** @test */
    public function qr_code_is_generated_for_rumah_pompa()
    {
        $this->actingAs($this->user);
        
        $rumahPompa = RumahPompa::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'Rumah Pompa Test',
            'serial_no' => 'RP.001',
            'barcode' => 'RUMAH POMPA RP.001',
            'location_code' => 'Area A',
            'status' => 'BAIK',
        ]);
        
        $this->assertNotNull($rumahPompa->qr_url);
        $this->assertStringContainsString('data:image/svg+xml;base64,', $rumahPompa->qr_url);
    }

    /** @test */
    public function guest_cannot_access_rumah_pompa_crud_routes()
    {
        $response = $this->get(route('rumah-pompa.index'));
        $response->assertRedirect(route('login'));
        
        $response = $this->get(route('rumah-pompa.create'));
        $response->assertRedirect(route('login'));
        
        $response = $this->post(route('rumah-pompa.store'), []);
        $response->assertRedirect(route('login'));
    }
}
