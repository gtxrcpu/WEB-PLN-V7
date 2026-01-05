<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Apar;
use App\Models\Apat;
use App\Models\Apab;
use App\Models\BoxHydrant;
use App\Models\FireAlarm;
use App\Models\RumahPompa;
use App\Models\KartuApar;
use App\Models\KartuApat;
use App\Models\KartuApab;
use App\Models\KartuBoxHydrant;
use App\Models\KartuFireAlarm;
use App\Models\KartuRumahPompa;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExportTest extends TestCase
{
    use RefreshDatabase;

    // ========== SUBTASK 6.1: Excel Export Tests ==========

    public function test_excel_export_apar_equipment()
    {
        $user = User::factory()->create();

        Apar::create([
            'name' => 'APAR-001',
            'serial_no' => 'SN-001',
            'barcode' => 'BC-001',
            'location_code' => 'LOC-001',
            'status' => 'baik',
            'capacity' => '3kg',
            'type' => 'Powder',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('quick.export.excel', [
            'module' => 'apar',
            'type' => 'equipment'
        ]));

        $response->assertStatus(200);
        $response->assertDownload();
    }

    public function test_excel_export_all_equipment_types()
    {
        $user = User::factory()->create();

        Apar::create([
            'name' => 'APAR-ALL',
            'serial_no' => 'ALL-APAR-001',
            'barcode' => 'ALL-APAR-BC',
            'location_code' => 'ALL-LOC',
            'status' => 'baik',
            'capacity' => '3kg',
            'type' => 'Powder',
            'user_id' => $user->id,
        ]);

        Apat::create([
            'name' => 'APAT-ALL',
            'serial_no' => 'ALL-APAT-001',
            'barcode' => 'ALL-APAT-BC',
            'lokasi' => 'ALL-LOC',
            'status' => 'baik',
            'kapasitas' => '10kg',
            'jenis' => 'Thermatic',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('quick.export.excel', [
            'module' => 'all',
            'type' => 'equipment'
        ]));

        $response->assertStatus(200);
        $response->assertDownload();
    }

    public function test_excel_export_kartu_with_approval_history()
    {
        $creator = User::factory()->create(['name' => 'Creator User']);
        $approver = User::factory()->create(['name' => 'Approver User']);

        $apar = Apar::create([
            'name' => 'APAR-KARTU',
            'serial_no' => 'KARTU-001',
            'barcode' => 'KARTU-BC-001',
            'location_code' => 'KARTU-LOC',
            'status' => 'baik',
            'capacity' => '5kg',
            'type' => 'Powder',
            'user_id' => $creator->id,
        ]);

        KartuApar::create([
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

        $response = $this->actingAs($creator)->get(route('quick.export.excel', [
            'module' => 'apar',
            'type' => 'kartu'
        ]));

        $response->assertStatus(200);
        $response->assertDownload();
    }

    public function test_excel_export_with_large_dataset()
    {
        $user = User::factory()->create();

        for ($i = 1; $i <= 50; $i++) {
            Apar::create([
                'name' => "APAR-LARGE-{$i}",
                'serial_no' => "LARGE-SN-{$i}",
                'barcode' => "LARGE-BC-{$i}",
                'location_code' => "LARGE-LOC-{$i}",
                'status' => $i % 2 == 0 ? 'baik' : 'rusak',
                'capacity' => '3kg',
                'type' => 'Powder',
                'user_id' => $user->id,
            ]);
        }

        $response = $this->actingAs($user)->get(route('quick.export.excel', [
            'module' => 'apar',
            'type' => 'equipment'
        ]));

        $response->assertStatus(200);
        $response->assertDownload();
    }

    // ========== SUBTASK 6.2: PDF Export Tests ==========

    public function test_pdf_export_kartu_apar()
    {
        $creator = User::factory()->create(['name' => 'PDF Creator']);
        $approver = User::factory()->create(['name' => 'PDF Approver']);

        $apar = Apar::create([
            'name' => 'APAR-PDF',
            'serial_no' => 'PDF-001',
            'barcode' => 'PDF-BC-001',
            'location_code' => 'PDF-LOC',
            'status' => 'baik',
            'capacity' => '5kg',
            'type' => 'Powder',
            'user_id' => $creator->id,
        ]);

        KartuApar::create([
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

        $response = $this->actingAs($creator)->get(route('quick.export.pdf', [
            'module' => 'apar',
            'type' => 'kartu'
        ]));

        $response->assertStatus(200);
        $response->assertDownload();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_pdf_export_with_approval_history()
    {
        $creator = User::factory()->create(['name' => 'PDF Creator']);
        $approver = User::factory()->create(['name' => 'PDF Approver']);

        $apar = Apar::create([
            'name' => 'APAR-APPROVAL',
            'serial_no' => 'APPROVAL-001',
            'barcode' => 'APPROVAL-BC',
            'location_code' => 'APPROVAL-LOC',
            'status' => 'baik',
            'capacity' => '5kg',
            'type' => 'Powder',
            'user_id' => $creator->id,
        ]);

        $approvalTime = now()->subDays(2);
        KartuApar::create([
            'apar_id' => $apar->id,
            'user_id' => $creator->id,
            'tgl_periksa' => now()->subDays(3),
            'kesimpulan' => 'Baik',
            'petugas' => 'Test Petugas',
            'pressure_gauge' => 'baik',
            'pin_segel' => 'baik',
            'selang' => 'baik',
            'tabung' => 'baik',
            'label' => 'baik',
            'kondisi_fisik' => 'baik',
            'approved_by' => $approver->id,
            'approved_at' => $approvalTime,
        ]);

        $response = $this->actingAs($creator)->get(route('quick.export.pdf', [
            'module' => 'apar',
            'type' => 'kartu'
        ]));

        $response->assertStatus(200);
        $response->assertDownload();
    }

    public function test_pdf_export_performance()
    {
        $creator = User::factory()->create();
        $approver = User::factory()->create();

        for ($i = 1; $i <= 10; $i++) {
            $apar = Apar::create([
                'name' => "APAR-PERF-{$i}",
                'serial_no' => "PERF-SN-{$i}",
                'barcode' => "PERF-BC-{$i}",
                'location_code' => "PERF-LOC-{$i}",
                'status' => 'baik',
                'capacity' => '3kg',
                'type' => 'Powder',
                'user_id' => $creator->id,
            ]);

            KartuApar::create([
                'apar_id' => $apar->id,
                'user_id' => $creator->id,
                'tgl_periksa' => now(),
                'kesimpulan' => 'Baik',
                'petugas' => "Petugas {$i}",
                'pressure_gauge' => 'baik',
                'pin_segel' => 'baik',
                'selang' => 'baik',
                'tabung' => 'baik',
                'label' => 'baik',
                'kondisi_fisik' => 'baik',
                'approved_by' => $approver->id,
                'approved_at' => now(),
            ]);
        }

        $startTime = microtime(true);
        
        $response = $this->actingAs($creator)->get(route('quick.export.pdf', [
            'module' => 'apar',
            'type' => 'kartu'
        ]));

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        $response->assertStatus(200);
        $response->assertDownload();
        
        $this->assertLessThan(10, $executionTime, 'PDF generation took too long');
    }
}
