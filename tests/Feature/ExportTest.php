<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Apar;
use App\Models\KartuApar;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_equipment_export_includes_basic_fields()
    {
        // Create a user
        $user = User::factory()->create();

        // Create an APAR
        $apar = Apar::create([
            'name' => 'APAR TEST-001',
            'serial_no' => 'TEST-001',
            'barcode' => 'BAR-001',
            'location_code' => 'LOC-001',
            'status' => 'baik',
            'capacity' => '5kg',
            'type' => 'Powder',
            'user_id' => $user->id,
        ]);

        // Test equipment export
        $response = $this->actingAs($user)->get(route('quick.export.excel', [
            'module' => 'apar',
            'type' => 'equipment'
        ]));

        $response->assertStatus(200);
        $response->assertDownload();
    }

    public function test_kartu_export_includes_approval_history()
    {
        // Create users
        $creator = User::factory()->create(['name' => 'Creator User']);
        $approver = User::factory()->create(['name' => 'Approver User']);

        // Create an APAR
        $apar = Apar::create([
            'name' => 'APAR TEST-002',
            'serial_no' => 'TEST-002',
            'barcode' => 'BAR-002',
            'location_code' => 'LOC-002',
            'status' => 'baik',
            'capacity' => '5kg',
            'type' => 'Powder',
            'user_id' => $creator->id,
        ]);

        // Create a kartu with approval
        $kartu = KartuApar::create([
            'apar_id' => $apar->id,
            'user_id' => $creator->id,
            'tgl_periksa' => now(),
            'kesimpulan' => 'Baik',
            'petugas' => 'Test Petugas',
            'pressure_gauge' => 'baik',
            'pin_segel' => 'baik',
            'selang' => 'baik',
            'tabung' => 'baik',
            'label' => 'baik',
            'kondisi_fisik' => 'baik',
            'approved_by' => $approver->id,
            'approved_at' => now(),
        ]);

        // Test kartu export
        $response = $this->actingAs($creator)->get(route('quick.export.excel', [
            'module' => 'apar',
            'type' => 'kartu'
        ]));

        $response->assertStatus(200);
        $response->assertDownload();
    }

    public function test_pdf_export_works_for_kartu()
    {
        // Create users
        $creator = User::factory()->create(['name' => 'PDF Creator']);
        $approver = User::factory()->create(['name' => 'PDF Approver']);

        // Create an APAR
        $apar = Apar::create([
            'name' => 'APAR TEST-003',
            'serial_no' => 'TEST-003',
            'barcode' => 'BAR-003',
            'location_code' => 'LOC-003',
            'status' => 'baik',
            'capacity' => '5kg',
            'type' => 'Powder',
            'user_id' => $creator->id,
        ]);

        // Create a kartu with approval
        $kartu = KartuApar::create([
            'apar_id' => $apar->id,
            'user_id' => $creator->id,
            'tgl_periksa' => now(),
            'kesimpulan' => 'Baik',
            'petugas' => 'Test Petugas',
            'pressure_gauge' => 'baik',
            'pin_segel' => 'baik',
            'selang' => 'baik',
            'tabung' => 'baik',
            'label' => 'baik',
            'kondisi_fisik' => 'baik',
            'approved_by' => $approver->id,
            'approved_at' => now(),
        ]);

        // Test PDF export
        $response = $this->actingAs($creator)->get(route('quick.export.pdf', [
            'module' => 'apar',
            'type' => 'kartu'
        ]));

        $response->assertStatus(200);
        $response->assertDownload();
    }
}
