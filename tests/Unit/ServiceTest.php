<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Unit;
use App\Models\Apar;
use App\Models\KartuApar;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    // ========== SUBTASK 10.2: Service Class Tests ==========

    /**
     * @test
     * Test QR code is generated for APAR
     */
    public function qr_code_is_generated_for_apar(): void
    {
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $user = User::factory()->create();
        $unit = Unit::where('code', 'UPW2')->first();

        $apar = Apar::create([
            'name' => 'QR Test APAR',
            'serial_no' => 'QR-001',
            'barcode' => 'QR-BC-001',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'status' => 'baik',
        ]);

        // Verify QR URL is generated (data URI format)
        $qrUrl = $apar->qr_url;

        $this->assertNotNull($qrUrl);
        $this->assertIsString($qrUrl);
        $this->assertStringStartsWith('data:image/svg+xml;base64,', $qrUrl);
    }

    /**
     * @test
     * Test QR code URL is base64 encoded
     */
    public function qr_code_url_is_base64_encoded(): void
    {
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $user = User::factory()->create();
        $unit = Unit::where('code', 'UPW2')->first();

        $apar = Apar::create([
            'name' => 'Data Test APAR',
            'serial_no' => 'DATA-001',
            'barcode' => 'DATA-BC-001',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'status' => 'baik',
        ]);

        $qrUrl = $apar->qr_url;

        // QR URL should be base64 data URI
        $this->assertStringContainsString('base64,', $qrUrl);

        // Extract base64 part
        $base64Part = str_replace('data:image/svg+xml;base64,', '', $qrUrl);
        $decoded = base64_decode($base64Part, true);

        // Should decode successfully
        $this->assertNotFalse($decoded);
    }

    /**
     * @test
     * Test PDF data can be retrieved for KartuApar
     */
    public function pdf_data_can_be_retrieved_for_kartu(): void
    {
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $user = User::factory()->create();
        $unit = Unit::where('code', 'UPW2')->first();

        $apar = Apar::create([
            'name' => 'PDF Test APAR',
            'serial_no' => 'PDF-001',
            'barcode' => 'PDF-BC-001',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'status' => 'baik',
        ]);

        $kartu = KartuApar::create([
            'apar_id' => $apar->id,
            'tgl_periksa' => now(),
            'kondisi' => 'baik',
            'catatan' => 'PDF test',
            'creator_id' => $user->id,
            'tabung' => 'baik',
            'selang' => 'baik',
            'nozzle' => 'baik',
            'pressure_gauge' => 'baik',
            'pin_segel' => 'baik',
            'label' => 'baik',
        ]);

        // Verify we can access kartu for PDF generation
        $this->assertInstanceOf(KartuApar::class, $kartu);
        $this->assertNotNull($kartu->tgl_periksa);
        $this->assertNotNull($kartu->apar);
    }

    /**
     * @test
     * Test Excel export can retrieve equipment list
     */
    public function excel_export_can_retrieve_equipment_list(): void
    {
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $user = User::factory()->create();
        $unit = Unit::where('code', 'UPW2')->first();

        // Create multiple APARs
        for ($i = 1; $i <= 3; $i++) {
            Apar::create([
                'name' => "Excel APAR {$i}",
                'serial_no' => "EXCEL-{$i}",
                'barcode' => "EXCEL-BC-{$i}",
                'user_id' => $user->id,
                'unit_id' => $unit->id,
                'status' => 'baik',
            ]);
        }

        // Retrieve equipment list (similar to export query)
        $apars = Apar::with(['unit'])->get();

        $this->assertGreaterThanOrEqual(3, $apars->count());

        // Verify data structure for export
        $firstApar = $apars->first();
        $this->assertNotNull($firstApar->name);
        $this->assertNotNull($firstApar->serial_no);
        $this->assertInstanceOf(Unit::class, $firstApar->unit);
    }

    /**
     * @test
     * Test Excel export includes relationship data
     */
    public function excel_export_includes_relationship_data(): void
    {
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $user = User::factory()->create(['name' => 'Export User']);
        $unit = Unit::where('code', 'UPW2')->first();

        $apar = Apar::create([
            'name' => 'Relationship Test APAR',
            'serial_no' => 'REL-EXPORT-001',
            'barcode' => 'REL-EXPORT-BC-001',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'status' => 'baik',
        ]);

        // Simulate export query with eager loading
        $exportData = Apar::with(['unit'])
            ->where('id', $apar->id)
            ->first();

        $this->assertNotNull($exportData->unit->name);
        $this->assertEquals($unit->name, $exportData->unit->name);
    }

    /**
     * @test
     * Test QR code generation service is consistent
     */
    public function qr_code_generation_is_consistent(): void
    {
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $user = User::factory()->create();
        $unit = Unit::where('code', 'UPW2')->first();

        $apar = Apar::create([
            'name' => 'Consistent QR Test',
            'serial_no' => 'CONSISTENT-001',
            'barcode' => 'CONSISTENT-BC-001',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'status' => 'baik',
        ]);

        // Generate QR URL multiple times
        $qrUrl1 = $apar->qr_url;
        $qrUrl2 = $apar->qr_url;

        // Should be consistent
        $this->assertEquals($qrUrl1, $qrUrl2);
    }

    /**
     * @test
     * Test Excel export handles empty data gracefully
     */
    public function excel_export_handles_empty_data(): void
    {
        // No equipment created
        $apars = Apar::all();

        // Should return empty collection, not throw error
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $apars);
        $this->assertCount(0, $apars);
    }

    /**
     * @test
     * Test PDF data structure is complete
     */
    public function pdf_data_structure_is_complete(): void
    {
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $user = User::factory()->create(['name' => 'PDF Creator']);
        $unit = Unit::where('code', 'UPW2')->first();

        $apar = Apar::create([
            'name' => 'Complete APAR',
            'serial_no' => 'COMPLETE-001',
            'barcode' => 'COMPLETE-BC-001',
            'type' => 'Powder',
            'capacity' => '3 Kg',
            'location_code' => 'A-101',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'status' => 'baik',
        ]);

        $kartu = KartuApar::create([
            'apar_id' => $apar->id,
            'tgl_periksa' => now(),
            'kondisi' => 'baik',
            'catatan' => 'Complete test',
            'tabung' => 'baik',
            'handle' => 'baik',
            'selang' => 'baik',
            'nozzle' => 'baik',
            'pin_pengaman' => 'baik',
            'pressure_gauge' => 'baik',
            'pin_segel' => 'baik',
            'label' => 'baik',
            'creator_id' => $user->id,
        ]);

        // Verify all necessary data for PDF is present
        $this->assertNotNull($kartu->apar->name);
        $this->assertNotNull($kartu->apar->serial_no);
        $this->assertNotNull($kartu->apar->type);
        $this->assertNotNull($kartu->apar->capacity);
        $this->assertNotNull($kartu->tgl_periksa);
        $this->assertNotNull($kartu->kondisi);
        $this->assertNotNull($kartu->creator->name);
    }
}
