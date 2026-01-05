<?php

namespace Tests\Feature;

use App\Models\BoxHydrant;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BoxHydrantCrudTest extends TestCase
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
    public function user_can_view_box_hydrant_list()
    {
        $this->actingAs($this->user);
        
        BoxHydrant::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'Box Hydrant Test',
            'serial_no' => 'BH.001',
            'barcode' => 'BH BH.001',
            'location_code' => 'Lantai 1',
            'type' => 'Indoor',
            'status' => 'BAIK',
        ]);
        
        $response = $this->get(route('box-hydrant.index'));
        
        $response->assertStatus(200);
        $response->assertSee('Box Hydrant Test');
        $response->assertSee('BH.001');
    }

    /** @test */
    public function user_can_create_box_hydrant_with_valid_data()
    {
        $this->actingAs($this->user);
        
        $response = $this->post(route('box-hydrant.store'), [
            'name' => 'Box Hydrant New',
            'serial_no' => 'BH.002',
            'barcode' => 'BH BH.002',
            'location_code' => 'Lantai 2',
            'type' => 'Outdoor',
            'status' => 'BAIK',
            'notes' => 'Test notes',
        ]);
        
        $response->assertRedirect(route('box-hydrant.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('box_hydrants', [
            'name' => 'Box Hydrant New',
            'serial_no' => 'BH.002',
            'location_code' => 'Lantai 2',
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function user_can_update_box_hydrant_details()
    {
        $this->actingAs($this->user);
        
        $boxHydrant = BoxHydrant::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'Box Hydrant Test',
            'serial_no' => 'BH.001',
            'barcode' => 'BH BH.001',
            'location_code' => 'Lantai 1',
            'status' => 'BAIK',
        ]);
        
        $response = $this->put(route('box-hydrant.update', $boxHydrant), [
            'name' => 'Box Hydrant Updated',
            'location_code' => 'Lantai 3',
            'type' => 'Indoor',
            'status' => 'RUSAK',
            'notes' => 'Updated notes',
        ]);
        
        $response->assertRedirect(route('box-hydrant.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('box_hydrants', [
            'id' => $boxHydrant->id,
            'name' => 'Box Hydrant Updated',
            'location_code' => 'Lantai 3',
            'status' => 'RUSAK',
        ]);
    }

    /** @test */
    public function qr_code_is_generated_for_box_hydrant()
    {
        $this->actingAs($this->user);
        
        $boxHydrant = BoxHydrant::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'Box Hydrant Test',
            'serial_no' => 'BH.001',
            'barcode' => 'BH BH.001',
            'location_code' => 'Lantai 1',
            'status' => 'BAIK',
        ]);
        
        $this->assertNotNull($boxHydrant->qr_url);
        $this->assertStringContainsString('data:image/svg+xml;base64,', $boxHydrant->qr_url);
    }

    /** @test */
    public function guest_cannot_access_box_hydrant_crud_routes()
    {
        $response = $this->get(route('box-hydrant.index'));
        $response->assertRedirect(route('login'));
        
        $response = $this->get(route('box-hydrant.create'));
        $response->assertRedirect(route('login'));
        
        $response = $this->post(route('box-hydrant.store'), []);
        $response->assertRedirect(route('login'));
    }
}
