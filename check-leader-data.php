<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CHECKING LEADER DATA ===\n\n";

// Check leader user
$leader = \App\Models\User::whereHas('roles', function($q) {
    $q->where('name', 'leader');
})->first();

if ($leader) {
    echo "Leader Found:\n";
    echo "- Name: {$leader->name}\n";
    echo "- Unit ID: " . ($leader->unit_id ?? 'NULL') . "\n";
    if ($leader->unit) {
        echo "- Unit Name: {$leader->unit->name}\n";
    }
} else {
    echo "No leader found!\n";
}

echo "\n=== PENDING KARTU KENDALI ===\n\n";

$pending = \App\Models\KartuApar::with('apar')->whereNull('approved_at')->get();

if ($pending->count() > 0) {
    foreach ($pending as $k) {
        echo "Kartu ID: {$k->id}\n";
        echo "- APAR: {$k->apar->barcode}\n";
        echo "- APAR Unit ID: " . ($k->apar->unit_id ?? 'NULL') . "\n";
        echo "- Tanggal: {$k->tgl_periksa}\n";
        echo "- Petugas: {$k->petugas}\n";
        echo "\n";
    }
} else {
    echo "No pending kartu found\n";
}

echo "\n=== CHECKING QUERY ===\n\n";

if ($leader && $leader->unit_id) {
    $count = \App\Models\KartuApar::whereHas('apar', function($q) use ($leader) {
        $q->where('unit_id', $leader->unit_id);
    })->whereNull('approved_at')->count();
    
    echo "Pending kartu for leader's unit (ID: {$leader->unit_id}): {$count}\n";
    
    // Test exact query from controller
    $pendingApprovals = \App\Models\KartuApar::with(['apar', 'user'])
        ->whereHas('apar', function($q) use ($leader) {
            $q->where('unit_id', $leader->unit_id);
        })
        ->whereNull('approved_at')
        ->latest()
        ->take(10)
        ->get();
    
    echo "\nController Query Result:\n";
    echo "Count: {$pendingApprovals->count()}\n";
    foreach ($pendingApprovals as $p) {
        echo "- ID: {$p->id}, APAR: {$p->apar->barcode}, User: " . ($p->user ? $p->user->name : 'NULL') . "\n";
    }
}
