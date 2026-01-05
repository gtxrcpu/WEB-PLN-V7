<?php

namespace Tests\Feature;

use App\Models\FireAlarm;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FireAlarmCrudTest extends TestCase
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
    public function user_can_view_fire_alarm_list()
    {
        $this->actingAs($this->user);
        
        FireAlarm::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'Fire Alarm Test',
            'serial_no' => 'FA.001',
            'barcode' => 'FA FA.001',
            'location_code' => 'Zona A',
            'type' => 'Smoke Detector',
            'zone' => 'Zone 1',
            'status' => 'BAIK',
        ]);
        
        $response = $this->get(route('fire-alarm.index'));
        
        $response->assertStatus(200);
        $response->assertSee('Fire Alarm Test');
        $response->assertSee('FA.001');
    }

    /** @test */
    public function user_can_create_fire_alarm_with_valid_data()
    {
        $this->actingAs($this->user);
        
        $response = $this->post(route('fire-alarm.store'), [
            'name' => 'Fire Alarm New',
            'serial_no' => 'FA.002',
            'barcode' => 'FA FA.002',
            'location_code' => 'Zona B',
            'type' => 'Heat Detector',
            'zone' => 'Zone 2',
            'status' => 'BAIK',
            'notes' => 'Test notes',
        ]);
        
        $response->assertRedirect(route('fire-alarm.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('fire_alarms', [
            'name' => 'Fire Alarm New',
            'serial_no' => 'FA.002',
            'location_code' => 'Zona B',
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function user_can_update_fire_alarm_details()
    {
        $this->actingAs($this->user);
        
        $fireAlarm = FireAlarm::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'Fire Alarm Test',
            'serial_no' => 'FA.001',
            'barcode' => 'FA FA.001',
            'location_code' => 'Zona A',
            'status' => 'BAIK',
        ]);
        
        $response = $this->put(route('fire-alarm.update', $fireAlarm), [
            'name' => 'Fire Alarm Updated',
            'location_code' => 'Zona C',
            'type' => 'Manual Call Point',
            'zone' => 'Zone 3',
            'status' => 'RUSAK',
            'notes' => 'Updated notes',
        ]);
        
        $response->assertRedirect(route('fire-alarm.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('fire_alarms', [
            'id' => $fireAlarm->id,
            'name' => 'Fire Alarm Updated',
            'location_code' => 'Zona C',
            'status' => 'RUSAK',
        ]);
    }

    /** @test */
    public function qr_code_is_generated_for_fire_alarm()
    {
        $this->actingAs($this->user);
        
        $fireAlarm = FireAlarm::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'Fire Alarm Test',
            'serial_no' => 'FA.001',
            'barcode' => 'FA FA.001',
            'location_code' => 'Zona A',
            'status' => 'BAIK',
        ]);
        
        $this->assertNotNull($fireAlarm->qr_url);
        $this->assertStringContainsString('data:image/svg+xml;base64,', $fireAlarm->qr_url);
    }

    /** @test */
    public function guest_cannot_access_fire_alarm_crud_routes()
    {
        $response = $this->get(route('fire-alarm.index'));
        $response->assertRedirect(route('login'));
        
        $response = $this->get(route('fire-alarm.create'));
        $response->assertRedirect(route('login'));
        
        $response = $this->post(route('fire-alarm.store'), []);
        $response->assertRedirect(route('login'));
    }
}
