<?php

namespace Tests\Feature;

use App\Models\Apab;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApabCrudTest extends TestCase
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
    public function user_can_view_apab_list()
    {
        $this->actingAs($this->user);
        
        Apab::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'APAB Test',
            'serial_no' => 'A3.001',
            'barcode' => 'APAB A3.001',
            'location_code' => 'B-101',
            'isi_apab' => 'CO2',
            'capacity' => '50 Kg',
            'status' => 'BAIK',
        ]);
        
        $response = $this->get(route('apab.index'));
        
        $response->assertStatus(200);
        $response->assertSee('APAB Test');
        $response->assertSee('A3.001');
    }

    /** @test */
    public function user_can_create_apab_with_valid_data()
    {
        $this->actingAs($this->user);
        
        $response = $this->post(route('apab.store'), [
            'name' => 'APAB New',
            'serial_no' => 'A3.002',
            'barcode' => 'APAB A3.002',
            'location_code' => 'B-101',
            'isi_apab' => 'CO2',
            'capacity' => '50 Kg',
            'masa_berlaku' => '2025-12-31',
            'status' => 'BAIK',
            'notes' => 'Test notes',
        ]);
        
        $response->assertRedirect(route('apab.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('apabs', [
            'name' => 'APAB New',
            'serial_no' => 'A3.002',
            'location_code' => 'B-101',
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function user_cannot_create_apab_with_invalid_data()
    {
        $this->actingAs($this->user);
        
        $response = $this->post(route('apab.store'), [
            'notes' => 'Test notes',
        ]);
        
        $response->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function user_can_update_apab_details()
    {
        $this->actingAs($this->user);
        
        $apab = Apab::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'APAB Test',
            'serial_no' => 'A3.001',
            'barcode' => 'APAB A3.001',
            'location_code' => 'B-101',
            'status' => 'BAIK',
        ]);
        
        $response = $this->put(route('apab.update', $apab), [
            'name' => 'APAB Updated',
            'location_code' => 'C-303',
            'isi_apab' => 'Foam',
            'capacity' => '75 Kg',
            'status' => 'ISI ULANG',
            'notes' => 'Updated notes',
        ]);
        
        $response->assertRedirect(route('apab.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('apabs', [
            'id' => $apab->id,
            'name' => 'APAB Updated',
            'location_code' => 'C-303',
            'status' => 'ISI ULANG',
        ]);
    }

    /** @test */
    public function qr_code_is_generated_for_apab()
    {
        $this->actingAs($this->user);
        
        $apab = Apab::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'APAB Test',
            'serial_no' => 'A3.001',
            'barcode' => 'APAB A3.001',
            'location_code' => 'B-101',
            'status' => 'BAIK',
        ]);
        
        $this->assertNotNull($apab->qr_url);
        $this->assertStringContainsString('data:image/svg+xml;base64,', $apab->qr_url);
    }

    /** @test */
    public function guest_cannot_access_apab_crud_routes()
    {
        $response = $this->get(route('apab.index'));
        $response->assertRedirect(route('login'));
        
        $response = $this->get(route('apab.create'));
        $response->assertRedirect(route('login'));
        
        $response = $this->post(route('apab.store'), []);
        $response->assertRedirect(route('login'));
    }
}
