<?php

namespace Tests\Feature;

use App\Models\Apar;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AparCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Unit $unit;

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
    }

    /** @test */
    public function user_can_view_apar_list()
    {
        $this->actingAs($this->user);
        
        // Create test APAR
        Apar::create([
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
        
        $response = $this->get(route('apar.index'));
        
        $response->assertStatus(200);
        $response->assertSee('APAR Test');
        $response->assertSee('A1.001');
    }

    /** @test */
    public function user_can_create_apar_with_valid_data()
    {
        $this->actingAs($this->user);
        
        $response = $this->post(route('apar.store'), [
            'location_code' => 'A-101',
            'type' => 'Powder',
            'capacity' => '3 Kg',
            'agent' => '500',
            'status' => 'BAIK',
            'notes' => 'Test notes',
        ]);
        
        $response->assertRedirect(route('apar.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('apars', [
            'location_code' => 'A-101',
            'type' => 'Powder',
            'capacity' => '3 Kg',
            'status' => 'BAIK',
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function user_cannot_create_apar_with_invalid_data()
    {
        $this->actingAs($this->user);
        
        // Missing required fields
        $response = $this->post(route('apar.store'), [
            'notes' => 'Test notes',
        ]);
        
        $response->assertSessionHasErrors(['location_code', 'type', 'capacity', 'status']);
    }

    /** @test */
    public function user_can_update_apar_details()
    {
        $this->actingAs($this->user);
        
        $apar = Apar::create([
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
        
        $response = $this->put(route('apar.update', $apar), [
            'location_code' => 'B-202',
            'type' => 'CO2',
            'capacity' => '5 Kg',
            'agent' => '600',
            'status' => 'ISI ULANG',
            'notes' => 'Updated notes',
        ]);
        
        $response->assertRedirect(route('apar.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('apars', [
            'id' => $apar->id,
            'location_code' => 'B-202',
            'type' => 'CO2',
            'capacity' => '5 Kg',
            'status' => 'ISI ULANG',
        ]);
    }

    /** @test */
    public function user_can_view_apar_history()
    {
        $this->actingAs($this->user);
        
        $apar = Apar::create([
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
        
        $response = $this->get(route('apar.riwayat', $apar));
        
        $response->assertStatus(200);
        $response->assertSee('APAR Test');
        $response->assertSee('A1.001');
    }

    /** @test */
    public function qr_code_is_generated_on_apar_creation()
    {
        $this->actingAs($this->user);
        
        $this->post(route('apar.store'), [
            'location_code' => 'A-101',
            'type' => 'Powder',
            'capacity' => '3 Kg',
            'agent' => '500',
            'status' => 'BAIK',
        ]);
        
        $apar = Apar::latest()->first();
        
        $this->assertNotNull($apar);
        $this->assertNotNull($apar->qr_url);
        $this->assertStringContainsString('data:image/svg+xml;base64,', $apar->qr_url);
    }

    /** @test */
    public function guest_cannot_access_apar_crud_routes()
    {
        $response = $this->get(route('apar.index'));
        $response->assertRedirect(route('login'));
        
        $response = $this->get(route('apar.create'));
        $response->assertRedirect(route('login'));
        
        $response = $this->post(route('apar.store'), []);
        $response->assertRedirect(route('login'));
    }
}
