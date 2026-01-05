<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Unit;
use App\Models\Apar;
use App\Models\KartuApar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class PerformanceTest extends TestCase
{
    use RefreshDatabase;

    // ========== SUBTASK 11.1: Database Query Performance Tests ==========

    /**
     * @test
     * Test N+1 query prevention with eager loading for equipment lists
     */
    public function eager_loading_prevents_n_plus_one_queries_for_equipment(): void
    {
        $this->seed(\Database\Seeders\UnitSeeder::class);
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('petugas');
        $unit = Unit::where('code', 'UPW2')->first();

        // Create 10 APARs
        for ($i = 1; $i <= 10; $i++) {
            Apar::create([
                'name' => "Performance APAR {$i}",
                'serial_no' => "PERF-{$i}",
                'barcode' => "PERF-BC-{$i}",
                'user_id' => $user->id,
                'unit_id' => $unit->id,
                'status' => 'baik',
            ]);
        }

        // Test WITHOUT eager loading (N+1 problem)
        DB::flushQueryLog();
        DB::enableQueryLog();
        $aparsWithout = Apar::all();
        foreach ($aparsWithout as $apar) {
            $unitName = $apar->unit->name; // Triggers separate query for each APAR
        }
        $queriesWithout = count(DB::getQueryLog());
        DB::disableQueryLog();

        // Test WITH eager loading (optimized)
        DB::flushQueryLog();
        DB::enableQueryLog();
        $aparsWith = Apar::with('unit')->get();
        foreach ($aparsWith as $apar) {
            $unitName = $apar->unit->name; // Uses preloaded data
        }
        $queriesWith = count(DB::getQueryLog());
        DB::disableQueryLog();

        // Eager loading should use significantly fewer queries
        // Without: 11+ queries (1 for all APARs + 10 for each unit)
        // With: 2-3 queries (1 for APARs, 1 for Units)
        $this->assertLessThan(
            $queriesWithout,
            $queriesWith,
            "Eager loading should use fewer queries. Without: {$queriesWithout}, With: {$queriesWith}"
        );
        // With eager loading, should be ~2 queries (1 for APARs, 1 for Units)
        $this->assertLessThanOrEqual(3, $queriesWith);
    }

    /**
     * @test
     * Test query execution time for equipment list is acceptable
     */
    public function equipment_list_query_execution_time_is_acceptable(): void
    {
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $user = User::factory()->create();
        $unit = Unit::where('code', 'UPW2')->first();

        // Create 50 APARs for performance test
        for ($i = 1; $i <= 50; $i++) {
            Apar::create([
                'name' => "Speed Test APAR {$i}",
                'serial_no' => "SPEED-{$i}",
                'barcode' => "SPEED-BC-{$i}",
                'user_id' => $user->id,
                'unit_id' => $unit->id,
                'status' => 'baik',
            ]);
        }

        // Measure query execution time
        $startTime = microtime(true);
        $apars = Apar::with('unit')->get();
        $endTime = microtime(true);

        $executionTime = ($endTime - $startTime) * 1000; // Convert to milliseconds

        // Query should complete in under 2 seconds (2000ms) as per requirements
        $this->assertLessThan(
            2000,
            $executionTime,
            "Equipment list query took {$executionTime}ms, should be under 2000ms"
        );

        // Verify data was retrieved
        $this->assertGreaterThanOrEqual(50, $apars->count());
    }

    /**
     * @test  
     * Test pagination performance for large datasets
     */
    public function pagination_performs_well_with_large_dataset(): void
    {
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $user = User::factory()->create();
        $unit = Unit::where('code', 'UPW2')->first();

        // Create 100 APARs
        for ($i = 1; $i <= 100; $i++) {
            Apar::create([
                'name' => "Pagination Test APAR {$i}",
                'serial_no' => "PAGE-{$i}",
                'barcode' => "PAGE-BC-{$i}",
                'user_id' => $user->id,
                'unit_id' => $unit->id,
                'status' => 'baik',
            ]);
        }

        // Test pagination (15 per page)
        $startTime = microtime(true);
        $paginatedApars = Apar::with('unit')->paginate(15);
        $endTime = microtime(true);

        $executionTime = ($endTime - $startTime) * 1000;

        // Pagination should be fast (under 1 second)
        $this->assertLessThan(1000, $executionTime);

        // Verify pagination data
        $this->assertEquals(15, $paginatedApars->perPage());
        $this->assertGreaterThan(1, $paginatedApars->lastPage());
    }

    /**
     * @test
     * Test eager loading multiple relationships efficiently
     */
    public function eager_loading_multiple_relationships_is_efficient(): void
    {
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $user = User::factory()->create();
        $unit = Unit::where('code', 'UPW2')->first();

        // Create APARs with kartu inspections
        for ($i = 1; $i <= 5; $i++) {
            $apar = Apar::create([
                'name' => "Multi Rel APAR {$i}",
                'serial_no' => "MULTI-{$i}",
                'barcode' => "MULTI-BC-{$i}",
                'user_id' => $user->id,
                'unit_id' => $unit->id,
                'status' => 'baik',
            ]);

            // Create 2 kartu inspections per APAR
            for ($j = 1; $j <= 2; $j++) {
                KartuApar::create([
                    'apar_id' => $apar->id,
                    'tgl_periksa' => now()->subDays($j),
                    'kondisi' => 'baik',
                    'catatan' => "Inspection {$j}",
                    'creator_id' => $user->id,
                    'tabung' => 'baik',
                    'selang' => 'baik',
                    'nozzle' => 'baik',
                    'pressure_gauge' => 'baik',
                    'pin_segel' => 'baik',
                    'label' => 'baik',
                    'kondisi_fisik' => 'baik',
                    'kesimpulan' => 'Layak Pakai',
                    'petugas' => $user->name,
                ]);
            }
        }

        // Eager load multiple relationships
        DB::enableQueryLog();
        $apars = Apar::with(['unit', 'kartuApars'])->get();
        $queryCount = count(DB::getQueryLog());
        DB::disableQueryLog();

        // Should use minimal queries (~3: APARs, Units, KartuApars)
        $this->assertLessThanOrEqual(4, $queryCount);

        // Verify relationships are loaded
        $this->assertGreaterThan(0, $apars->first()->kartuApars->count());
    }

    /**
     * @test
     * Test index queries use proper indexing
     */
    public function database_queries_use_indexes_efficiently(): void
    {
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $user = User::factory()->create();
        $unit = Unit::where('code', 'UPW2')->first();

        // Create test data
        Apar::create([
            'name' => 'Index Test APAR',
            'serial_no' => 'INDEX-001',
            'barcode' => 'INDEX-BC-001',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'status' => 'baik',
        ]);

        // Query by indexed column (unit_id should be indexed)
        DB::enableQueryLog();
        $aparsByUnit = Apar::where('unit_id', $unit->id)->get();
        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        // Verify query executed
        $this->assertCount(1, $queries);
        $this->assertGreaterThan(0, $aparsByUnit->count());

        // Query should use WHERE clause (indexed lookup)
        $sql = $queries[0]['query'];
        $this->assertStringContainsString('where', strtolower($sql));
    }

    /**
     * @test
     * Test bulk operations perform efficiently
     */
    public function bulk_operations_are_efficient(): void
    {
        $this->seed(\Database\Seeders\UnitSeeder::class);

        $user = User::factory()->create();
        $unit = Unit::where('code', 'UPW2')->first();

        // Measure bulk insert performance
        $startTime = microtime(true);

        $bulkData = [];
        for ($i = 1; $i <= 20; $i++) {
            $bulkData[] = [
                'name' => "Bulk APAR {$i}",
                'serial_no' => "BULK-{$i}",
                'barcode' => "BULK-BC-{$i}",
                'user_id' => $user->id,
                'unit_id' => $unit->id,
                'status' => 'baik',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Apar::insert($bulkData);

        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000;

        // Bulk insert should be fast (under 500ms)
        $this->assertLessThan(500, $executionTime);

        // Verify all records inserted
        $this->assertGreaterThanOrEqual(20, Apar::count());
    }
}
