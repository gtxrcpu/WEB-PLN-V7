<?php

namespace Tests\Feature;

use App\Models\FireAlarm;
use App\Models\KartuFireAlarm;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KartuFireAlarmTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Unit $unit;
    protected FireAlarm $fireAlarm;

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
        
        $this->fireAlarm = FireAlarm::create([
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id,
            'name' => 'Fire Alarm Test',
            'serial_no' => 'FA1.001',
            'barcode' => 'FA FA1.001',
            'location_code' => 'F-101',
            'status' => 'BAIK',
        ]);
    }

    /** @test */
    public function user_can_create_kartu_fire_alarm_with_valid_data()
    {
        $this->actingAs($this->user);
        
        $response = $this->post(route('fire-alarm.kartu.store'), [
            'fire_alarm_id' => $this->fireAlarm->id,
            'kondisi_fisik' => 'BAIK',
            'kesimpulan' => 'LAYAK',
            'tgl_periksa' => now()->format('Y-m-d'),
            'petugas' => 'Test Petugas',
        ]);
        
        $response->assertRedirect(route('fire-alarm.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('kartu_fire_alarms', [
            'fire_alarm_id' => $this->fireAlarm->id,
            'user_id' => $this->user->id,
            'kesimpulan' => 'LAYAK',
        ]);
    }

    /** @test */
    public function user_can_view_kartu_history_for_fire_alarm()
    {
        $this->actingAs($this->user);
        
        KartuFireAlarm::create([
            'fire_alarm_id' => $this->fireAlarm->id,
            'user_id' => $this->user->id,
            'kondisi_fisik' => 'BAIK',
            'kesimpulan' => 'LAYAK',
            'tgl_periksa' => now()->subDays(18),
            'petugas' => 'Petugas FA 1',
        ]);
        
        KartuFireAlarm::create([
            'fire_alarm_id' => $this->fireAlarm->id,
            'user_id' => $this->user->id,
            'kondisi_fisik' => 'BAIK',
            'kesimpulan' => 'LAYAK',
            'tgl_periksa' => now(),
            'petugas' => 'Petugas FA 2',
        ]);
        
        $response = $this->get(route('fire-alarm.riwayat', $this->fireAlarm));
        
        $response->assertStatus(200);
        $response->assertSee('Petugas FA 1');
        $response->assertSee('Petugas FA 2');
    }
}
