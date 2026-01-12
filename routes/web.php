<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AparController;
use App\Http\Controllers\KartuKendaliController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\ApatController;
use App\Http\Controllers\ApatKartuController;

Route::get('/', fn() => redirect()->route('guest.dashboard'));

// QR Code Generator (Public - no auth needed for scanning)
Route::get('/qr', [\App\Http\Controllers\QrCodeController::class, 'generate'])->name('qr.generate');

// Guest Routes (No Authentication Required)
Route::prefix('guest')->name('guest.')->middleware('throttle:60,1')->group(function () {
    // Dashboard
    Route::get('/', [\App\Http\Controllers\GuestController::class, 'index'])->name('dashboard');

    // Laporan Keseluruhan
    Route::get('/report', [\App\Http\Controllers\GuestController::class, 'report'])->name('report');

    // APAR
    Route::get('/apar', [\App\Http\Controllers\GuestController::class, 'apar'])->name('apar');
    Route::get('/apar/{apar}/riwayat', [\App\Http\Controllers\GuestController::class, 'aparRiwayat'])->name('apar.riwayat');

    // APAT
    Route::get('/apat', [\App\Http\Controllers\GuestController::class, 'apat'])->name('apat');
    Route::get('/apat/{apat}/riwayat', [\App\Http\Controllers\GuestController::class, 'apatRiwayat'])->name('apat.riwayat');

    // P3K
    Route::get('/p3k', [\App\Http\Controllers\GuestController::class, 'p3k'])->name('p3k');
    Route::get('/p3k/{p3k}/riwayat', [\App\Http\Controllers\GuestController::class, 'p3kRiwayat'])->name('p3k.riwayat');

    // APAB
    Route::get('/apab', [\App\Http\Controllers\GuestController::class, 'apab'])->name('apab');
    Route::get('/apab/{apab}/riwayat', [\App\Http\Controllers\GuestController::class, 'apabRiwayat'])->name('apab.riwayat');

    // Fire Alarm
    Route::get('/fire-alarm', [\App\Http\Controllers\GuestController::class, 'fireAlarm'])->name('fire-alarm');
    Route::get('/fire-alarm/{fireAlarm}/riwayat', [\App\Http\Controllers\GuestController::class, 'fireAlarmRiwayat'])->name('fire-alarm.riwayat');

    // Box Hydrant
    Route::get('/box-hydrant', [\App\Http\Controllers\GuestController::class, 'boxHydrant'])->name('box-hydrant');
    Route::get('/box-hydrant/{boxHydrant}/riwayat', [\App\Http\Controllers\GuestController::class, 'boxHydrantRiwayat'])->name('box-hydrant.riwayat');

    // Rumah Pompa
    Route::get('/rumah-pompa', [\App\Http\Controllers\GuestController::class, 'rumahPompa'])->name('rumah-pompa');
    Route::get('/rumah-pompa/{rumahPompa}/riwayat', [\App\Http\Controllers\GuestController::class, 'rumahPompaRiwayat'])->name('rumah-pompa.riwayat');
});

// Superadmin Routes (Full Access)
Route::middleware(['auth', 'role:superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('signatures', \App\Http\Controllers\Admin\SignatureController::class);

    // Floor Plans
    Route::resource('floor-plans', \App\Http\Controllers\Admin\FloorPlanController::class);
    Route::get('floor-plans/{floor_plan}/placement', [\App\Http\Controllers\Admin\FloorPlanController::class, 'placement'])->name('floor-plans.placement');
    Route::post('floor-plans/{floor_plan}/save-placement', [\App\Http\Controllers\Admin\FloorPlanController::class, 'savePlacement'])->name('floor-plans.save-placement');
    Route::post('floor-plans/{floor_plan}/remove-placement', [\App\Http\Controllers\Admin\FloorPlanController::class, 'removePlacement'])->name('floor-plans.remove-placement');

    // Equipment Modules
    Route::resource('apar', \App\Http\Controllers\Admin\AparController::class);
    Route::resource('apat', \App\Http\Controllers\Admin\ApatController::class);
    Route::resource('apab', \App\Http\Controllers\Admin\ApabController::class);
    Route::resource('fire-alarm', \App\Http\Controllers\Admin\FireAlarmController::class);
    Route::resource('box-hydrant', \App\Http\Controllers\Admin\BoxHydrantController::class);
    Route::resource('rumah-pompa', \App\Http\Controllers\Admin\RumahPompaController::class);
    Route::resource('p3k', \App\Http\Controllers\Admin\P3kController::class);

    // Kartu Settings (Legacy - will be replaced by templates)
    Route::get('/kartu-settings', [\App\Http\Controllers\Admin\KartuSettingController::class, 'index'])->name('kartu-settings.index');
    Route::put('/kartu-settings', [\App\Http\Controllers\Admin\KartuSettingController::class, 'update'])->name('kartu-settings.update');

    // Kartu Templates (New - per module)
    Route::get('/kartu-templates', [\App\Http\Controllers\Admin\KartuTemplateController::class, 'index'])->name('kartu-templates.index');
    Route::get('/kartu-templates/create', [\App\Http\Controllers\Admin\KartuTemplateController::class, 'create'])->name('kartu-templates.create');
    Route::post('/kartu-templates', [\App\Http\Controllers\Admin\KartuTemplateController::class, 'store'])->name('kartu-templates.store');
    Route::get('/kartu-templates/{module}/edit', [\App\Http\Controllers\Admin\KartuTemplateController::class, 'edit'])->name('kartu-templates.edit');
    Route::put('/kartu-templates/{module}', [\App\Http\Controllers\Admin\KartuTemplateController::class, 'update'])->name('kartu-templates.update');

    // Approvals - Superadmin bisa approve semua kartu
    Route::get('/approvals', [\App\Http\Controllers\Admin\ApprovalController::class, 'index'])->name('approvals.index');
    Route::get('/approvals/{id}', [\App\Http\Controllers\Admin\ApprovalController::class, 'show'])->name('approvals.show');
    Route::post('/approvals/{id}/approve', [\App\Http\Controllers\Admin\ApprovalController::class, 'approve'])->name('approvals.approve');
    Route::post('/approvals/{id}/reject', [\App\Http\Controllers\Admin\ApprovalController::class, 'reject'])->name('approvals.reject');

    // Edit Kode (Settings untuk semua modul)
    Route::get('/edit-kode', [\App\Http\Controllers\Admin\KodeSettingController::class, 'index'])->name('edit-kode.index');
    Route::get('/edit-kode/{module}', [\App\Http\Controllers\Admin\KodeSettingController::class, 'edit'])->name('edit-kode.edit');
    Route::put('/edit-kode/{module}', [\App\Http\Controllers\Admin\KodeSettingController::class, 'update'])->name('edit-kode.update');
    Route::post('/edit-kode/{module}/reset-counter', [\App\Http\Controllers\Admin\KodeSettingController::class, 'resetCounter'])->name('edit-kode.reset-counter');
});

// Petugas Routes (Simple UI - Input & View Only)
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Petugas\DashboardController::class, 'index'])->name('dashboard');
});

// Leader Routes (Full Features - Charts, Approval, etc)
Route::middleware(['auth', 'role:leader|superadmin'])->prefix('leader')->name('leader.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Leader\DashboardController::class, 'index'])->name('dashboard');

    // Approvals - Leader bisa approve kartu di unit-nya
    Route::get('/approvals', [\App\Http\Controllers\Leader\ApprovalController::class, 'index'])->name('approvals.index');
    Route::get('/approvals/{id}', [\App\Http\Controllers\Leader\ApprovalController::class, 'show'])->name('approvals.show');
    Route::post('/approvals/{id}/approve', [\App\Http\Controllers\Leader\ApprovalController::class, 'approve'])->name('approvals.approve');
    Route::post('/approvals/{id}/reject', [\App\Http\Controllers\Leader\ApprovalController::class, 'reject'])->name('approvals.reject');

    // Floor Plans - Leader bisa kelola denah unit-nya
    Route::get('/floor-plans', [\App\Http\Controllers\Leader\FloorPlanController::class, 'index'])->name('floor-plans.index');
    Route::get('/floor-plans/{floor_plan}/placement', [\App\Http\Controllers\Leader\FloorPlanController::class, 'placement'])->name('floor-plans.placement');
    Route::post('/floor-plans/{floor_plan}/save-placement', [\App\Http\Controllers\Leader\FloorPlanController::class, 'savePlacement'])->name('floor-plans.save-placement');
    Route::post('/floor-plans/{floor_plan}/remove-placement', [\App\Http\Controllers\Leader\FloorPlanController::class, 'removePlacement'])->name('floor-plans.remove-placement');

    // Manage users di unit sendiri
    Route::resource('users', \App\Http\Controllers\Leader\UserController::class);
});

// Inspector Routes (Read-Only Access)
Route::middleware(['auth', 'role:inspector'])->prefix('inspector')->name('inspector.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Inspector\InspectorDashboardController::class, 'index'])->name('dashboard');

    // APAR
    Route::get('/apar', [\App\Http\Controllers\Inspector\InspectorDashboardController::class, 'apar'])->name('apar');
    Route::get('/apar/{apar}/riwayat', [\App\Http\Controllers\Inspector\InspectorDashboardController::class, 'aparRiwayat'])->name('apar.riwayat');

    // APAT
    Route::get('/apat', [\App\Http\Controllers\Inspector\InspectorDashboardController::class, 'apat'])->name('apat');
    Route::get('/apat/{apat}/riwayat', [\App\Http\Controllers\Inspector\InspectorDashboardController::class, 'apatRiwayat'])->name('apat.riwayat');

    // P3K
    Route::get('/p3k', [\App\Http\Controllers\Inspector\InspectorDashboardController::class, 'p3k'])->name('p3k');
    Route::get('/p3k/{p3k}/riwayat', [\App\Http\Controllers\Inspector\InspectorDashboardController::class, 'p3kRiwayat'])->name('p3k.riwayat');

    // APAB
    Route::get('/apab', [\App\Http\Controllers\Inspector\InspectorDashboardController::class, 'apab'])->name('apab');
    Route::get('/apab/{apab}/riwayat', [\App\Http\Controllers\Inspector\InspectorDashboardController::class, 'apabRiwayat'])->name('apab.riwayat');

    // Fire Alarm
    Route::get('/fire-alarm', [\App\Http\Controllers\Inspector\InspectorDashboardController::class, 'fireAlarm'])->name('fire-alarm');
    Route::get('/fire-alarm/{fireAlarm}/riwayat', [\App\Http\Controllers\Inspector\InspectorDashboardController::class, 'fireAlarmRiwayat'])->name('fire-alarm.riwayat');

    // Box Hydrant
    Route::get('/box-hydrant', [\App\Http\Controllers\Inspector\InspectorDashboardController::class, 'boxHydrant'])->name('box-hydrant');
    Route::get('/box-hydrant/{boxHydrant}/riwayat', [\App\Http\Controllers\Inspector\InspectorDashboardController::class, 'boxHydrantRiwayat'])->name('box-hydrant.riwayat');

    // Rumah Pompa
    Route::get('/rumah-pompa', [\App\Http\Controllers\Inspector\InspectorDashboardController::class, 'rumahPompa'])->name('rumah-pompa');
    Route::get('/rumah-pompa/{rumahPompa}/riwayat', [\App\Http\Controllers\Inspector\InspectorDashboardController::class, 'rumahPompaRiwayat'])->name('rumah-pompa.riwayat');
});

// Route /user redirects based on role
Route::get('/user', function () {
    $u = auth()->user();
    if (!$u)
        return redirect()->route('login');

    // Petugas uses simplified dashboard
    if ($u->hasRole('petugas')) {
        return redirect()->route('petugas.dashboard');
    }

    // Leader/Admin can view full user dashboard with charts
    return app(\App\Http\Controllers\DashboardController::class)->user();
})->middleware('auth')->name('user.dashboard');

Route::get('/dashboard', function () {
    $u = auth()->user();
    if (!$u)
        return redirect()->route('login');

    // Redirect berdasarkan role
    if (method_exists($u, 'hasRole')) {
        if ($u->hasRole('superadmin'))
            return redirect()->route('admin.dashboard');
        if ($u->hasRole('leader'))
            return redirect()->route('leader.dashboard');
        if ($u->hasRole('inspector'))
            return redirect()->route('inspector.dashboard');
        if ($u->hasRole('petugas'))
            return redirect()->route('petugas.dashboard');
    }

    return redirect()->route('petugas.dashboard');
})->middleware('auth')->name('dashboard');



// API search realtime (web guard, JSON)
Route::get('/search/items', [SearchController::class, 'userItems'])
    ->middleware('auth')
    ->name('search.items');

// Ini Modul APAR
Route::middleware(['auth'])->group(function () {
    Route::get('/apar', [AparController::class, 'index'])->name('apar.index');
    Route::get('/apar/create', [AparController::class, 'create'])->name('apar.create');
    Route::post('/apar', [AparController::class, 'store'])->name('apar.store');
    Route::get('/apar/{apar}/riwayat', [AparController::class, 'riwayat'])->name('apar.riwayat');
    Route::get('/apar/{apar}/kartu/{kartu}', [AparController::class, 'viewKartu'])->name('apar.view-kartu');
    Route::get('/kartu/create', [KartuKendaliController::class, 'create'])->name('kartu.create');
    Route::post('/kartu', [KartuKendaliController::class, 'store'])->name('kartu.store');
    Route::get('/scan', [ScanController::class, 'index'])->name('user.scan');
    Route::get('/apar/list', [AparController::class, 'list'])->name('apar.list');
    Route::get('/apar/{apar}/qr.svg', [AparController::class, 'qrSvg'])->name('apar.qr');
    Route::get('/apar/{apar}/edit', [AparController::class, 'edit'])->name('apar.edit');
    Route::put('/apar/{apar}', [AparController::class, 'update'])->name('apar.update');
});

Route::resource('apar', \App\Http\Controllers\AparController::class)
    ->only(['index', 'create', 'store'])
    ->middleware(['auth']);

// Ini Modul APAT
Route::middleware(['auth'])->group(function () {
    Route::get('/apat', [ApatController::class, 'index'])->name('apat.index');
    Route::get('/apat/create', [ApatController::class, 'create'])->name('apat.create');
    Route::post('/apat', [ApatController::class, 'store'])->name('apat.store');
    Route::get('/apat/{apat}/edit', [ApatController::class, 'edit'])->name('apat.edit');
    Route::get('/apat/{apat}/riwayat', [ApatController::class, 'riwayat'])->name('apat.riwayat');
    Route::get('/apat/{apat}/kartu/{kartu}', [ApatController::class, 'viewKartu'])->name('apat.view-kartu');
    Route::put('/apat/{apat}', [ApatController::class, 'update'])->name('apat.update');
    Route::get('/apat/kartu/create', [ApatKartuController::class, 'create'])->name('apat.kartu.create');
    Route::post('/apat/kartu', [ApatKartuController::class, 'store'])->name('apat.kartu.store');
});

// Ini Modul Fire Alarm
Route::middleware(['auth'])->group(function () {
    Route::get('/fire-alarm', [\App\Http\Controllers\FireAlarmController::class, 'index'])->name('fire-alarm.index');
    Route::get('/fire-alarm/create', [\App\Http\Controllers\FireAlarmController::class, 'create'])->name('fire-alarm.create');
    Route::post('/fire-alarm', [\App\Http\Controllers\FireAlarmController::class, 'store'])->name('fire-alarm.store');
    Route::get('/fire-alarm/{fireAlarm}/edit', [\App\Http\Controllers\FireAlarmController::class, 'edit'])->name('fire-alarm.edit');
    Route::put('/fire-alarm/{fireAlarm}', [\App\Http\Controllers\FireAlarmController::class, 'update'])->name('fire-alarm.update');
    Route::get('/fire-alarm/{fireAlarm}/riwayat', [\App\Http\Controllers\FireAlarmController::class, 'riwayat'])->name('fire-alarm.riwayat');
    Route::get('/fire-alarm/{fireAlarm}/kartu/{kartu}', [\App\Http\Controllers\FireAlarmController::class, 'viewKartu'])->name('fire-alarm.view-kartu');
    Route::get('/fire-alarm/kartu/create', [\App\Http\Controllers\FireAlarmKartuController::class, 'create'])->name('fire-alarm.kartu.create');
    Route::post('/fire-alarm/kartu', [\App\Http\Controllers\FireAlarmKartuController::class, 'store'])->name('fire-alarm.kartu.store');
});

// Ini Modul Box Hydrant
Route::middleware(['auth'])->group(function () {
    Route::get('/box-hydrant', [\App\Http\Controllers\BoxHydrantController::class, 'index'])->name('box-hydrant.index');
    Route::get('/box-hydrant/create', [\App\Http\Controllers\BoxHydrantController::class, 'create'])->name('box-hydrant.create');
    Route::post('/box-hydrant', [\App\Http\Controllers\BoxHydrantController::class, 'store'])->name('box-hydrant.store');
    Route::get('/box-hydrant/{boxHydrant}/edit', [\App\Http\Controllers\BoxHydrantController::class, 'edit'])->name('box-hydrant.edit');
    Route::put('/box-hydrant/{boxHydrant}', [\App\Http\Controllers\BoxHydrantController::class, 'update'])->name('box-hydrant.update');
    Route::get('/box-hydrant/{boxHydrant}/riwayat', [\App\Http\Controllers\BoxHydrantController::class, 'riwayat'])->name('box-hydrant.riwayat');
    Route::get('/box-hydrant/{boxHydrant}/kartu/{kartu}', [\App\Http\Controllers\BoxHydrantController::class, 'viewKartu'])->name('box-hydrant.view-kartu');
    Route::get('/box-hydrant/kartu/create', [\App\Http\Controllers\BoxHydrantKartuController::class, 'create'])->name('box-hydrant.kartu.create');
    Route::post('/box-hydrant/kartu', [\App\Http\Controllers\BoxHydrantKartuController::class, 'store'])->name('box-hydrant.kartu.store');
});

// Ini Modul Rumah Pompa
Route::middleware(['auth'])->group(function () {
    Route::get('/rumah-pompa', [\App\Http\Controllers\RumahPompaController::class, 'index'])->name('rumah-pompa.index');
    Route::get('/rumah-pompa/create', [\App\Http\Controllers\RumahPompaController::class, 'create'])->name('rumah-pompa.create');
    Route::post('/rumah-pompa', [\App\Http\Controllers\RumahPompaController::class, 'store'])->name('rumah-pompa.store');
    Route::get('/rumah-pompa/{rumahPompa}/edit', [\App\Http\Controllers\RumahPompaController::class, 'edit'])->name('rumah-pompa.edit');
    Route::put('/rumah-pompa/{rumahPompa}', [\App\Http\Controllers\RumahPompaController::class, 'update'])->name('rumah-pompa.update');
    Route::get('/rumah-pompa/{rumahPompa}/riwayat', [\App\Http\Controllers\RumahPompaController::class, 'riwayat'])->name('rumah-pompa.riwayat');
    Route::get('/rumah-pompa/{rumahPompa}/kartu/{kartu}', [\App\Http\Controllers\RumahPompaController::class, 'viewKartu'])->name('rumah-pompa.view-kartu');
    Route::get('/rumah-pompa/kartu/create', [\App\Http\Controllers\RumahPompaKartuController::class, 'create'])->name('rumah-pompa.kartu.create');
    Route::post('/rumah-pompa/kartu', [\App\Http\Controllers\RumahPompaKartuController::class, 'store'])->name('rumah-pompa.kartu.store');
});

// Ini Modul APAB
Route::middleware(['auth'])->group(function () {
    Route::get('/apab', [\App\Http\Controllers\ApabController::class, 'index'])->name('apab.index');
    Route::get('/apab/create', [\App\Http\Controllers\ApabController::class, 'create'])->name('apab.create');
    Route::post('/apab', [\App\Http\Controllers\ApabController::class, 'store'])->name('apab.store');
    Route::get('/apab/{apab}/edit', [\App\Http\Controllers\ApabController::class, 'edit'])->name('apab.edit');
    Route::put('/apab/{apab}', [\App\Http\Controllers\ApabController::class, 'update'])->name('apab.update');
    Route::get('/apab/{apab}/riwayat', [\App\Http\Controllers\ApabController::class, 'riwayat'])->name('apab.riwayat');
    Route::get('/apab/{apab}/kartu/{kartu}', [\App\Http\Controllers\ApabController::class, 'viewKartu'])->name('apab.view-kartu');
    Route::get('/apab/kartu/create', [\App\Http\Controllers\ApabKartuController::class, 'create'])->name('apab.kartu.create');
    Route::post('/apab/kartu', [\App\Http\Controllers\ApabKartuController::class, 'store'])->name('apab.kartu.store');
});

// Ini Modul Referensi
Route::middleware(['auth'])->group(function () {
    Route::get('/referensi', [\App\Http\Controllers\ReferensiController::class, 'index'])->name('referensi.index');

    // Category Routes
    Route::post('/referensi/category', [\App\Http\Controllers\ReferensiController::class, 'storeCategory'])->name('referensi.category.store');
    Route::put('/referensi/category/{category}', [\App\Http\Controllers\ReferensiController::class, 'updateCategory'])->name('referensi.category.update');
    Route::delete('/referensi/category/{category}', [\App\Http\Controllers\ReferensiController::class, 'deleteCategory'])->name('referensi.category.delete');

    // Location Routes
    Route::post('/referensi/location', [\App\Http\Controllers\ReferensiController::class, 'storeLocation'])->name('referensi.location.store');
    Route::put('/referensi/location/{location}', [\App\Http\Controllers\ReferensiController::class, 'updateLocation'])->name('referensi.location.update');
    Route::delete('/referensi/location/{location}', [\App\Http\Controllers\ReferensiController::class, 'deleteLocation'])->name('referensi.location.delete');

    // Petugas Routes
    Route::post('/referensi/petugas', [\App\Http\Controllers\ReferensiController::class, 'storePetugas'])->name('referensi.petugas.store');
    Route::put('/referensi/petugas/{petugas}', [\App\Http\Controllers\ReferensiController::class, 'updatePetugas'])->name('referensi.petugas.update');
    Route::delete('/referensi/petugas/{petugas}', [\App\Http\Controllers\ReferensiController::class, 'deletePetugas'])->name('referensi.petugas.delete');
});

// Ini Modul P3K
Route::middleware(['auth'])->group(function () {
    Route::get('/p3k', [\App\Http\Controllers\P3kController::class, 'index'])->name('p3k.index');
    Route::get('/p3k/create', [\App\Http\Controllers\P3kController::class, 'create'])->name('p3k.create');
    Route::post('/p3k', [\App\Http\Controllers\P3kController::class, 'store'])->name('p3k.store');
    Route::get('/p3k/{p3k}/edit', [\App\Http\Controllers\P3kController::class, 'edit'])->name('p3k.edit');
    Route::get('/p3k/{p3k}/riwayat', [\App\Http\Controllers\P3kController::class, 'riwayat'])->name('p3k.riwayat');
    Route::put('/p3k/{p3k}', [\App\Http\Controllers\P3kController::class, 'update'])->name('p3k.update');

    // Alur baru: Pilih Jenis → Pilih Lokasi → Isi Kartu
    Route::get('/p3k/pilih-jenis', [\App\Http\Controllers\P3kController::class, 'pilihJenis'])->name('p3k.pilih-jenis');
    Route::get('/p3k/pilih-lokasi', [\App\Http\Controllers\P3kController::class, 'pilihLokasi'])->name('p3k.pilih-lokasi');
    Route::get('/p3k/kartu/create', [\App\Http\Controllers\KartuP3kController::class, 'create'])->name('p3k.kartu.create');
    Route::post('/p3k/kartu', [\App\Http\Controllers\KartuP3kController::class, 'store'])->name('p3k.kartu.store');
});

// Quick Actions
Route::middleware(['auth'])->group(function () {
    Route::get('/quick/scan', [\App\Http\Controllers\QuickActionController::class, 'scan'])->name('quick.scan');
    Route::match(['get', 'post'], '/quick/scan/search', [\App\Http\Controllers\QuickActionController::class, 'searchQR'])->name('quick.scan.search');
    Route::get('/quick/inspeksi', [\App\Http\Controllers\QuickActionController::class, 'inspeksi'])->name('quick.inspeksi');
    Route::get('/quick/rekap', [\App\Http\Controllers\QuickActionController::class, 'rekap'])->name('quick.rekap');
    Route::get('/quick/export-excel', [\App\Http\Controllers\QuickActionController::class, 'exportExcel'])->name('quick.export.excel');
    Route::get('/quick/export-pdf', [\App\Http\Controllers\QuickActionController::class, 'exportPdf'])->name('quick.export.pdf');
});

// Floor Plan View (User Access)
Route::middleware(['auth'])->group(function () {
    Route::get('/floor-plan', [\App\Http\Controllers\FloorPlanController::class, 'index'])->name('floor-plan.index');
    Route::get('/floor-plan/{floorPlan}/equipment-data', [\App\Http\Controllers\FloorPlanController::class, 'getEquipmentData'])->name('floor-plan.equipment-data');
    Route::post('/floor-plan/equipment/update-coordinates', [\App\Http\Controllers\FloorPlanController::class, 'updateEquipmentCoordinates'])->name('floor-plan.update-coordinates');
});

// API Search
Route::get('/api/search', [\App\Http\Controllers\Api\SearchController::class, 'search'])->middleware('auth');

// API Template Version Check (untuk auto-refresh)
Route::get('/api/template-version/{module}', function ($module) {
    $template = \App\Models\KartuTemplate::where('module', $module)->first();
    return response()->json([
        'module' => $module,
        'updated_at' => $template ? $template->updated_at->toIso8601String() : null,
        'version' => $template ? $template->updated_at->timestamp : 0
    ]);
})->middleware('auth');

// Profile
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Unit Switch (untuk admin)
    Route::post('/unit/switch', [\App\Http\Controllers\UnitSwitchController::class, 'switch'])->name('unit.switch');
    Route::post('/unit/clear', [\App\Http\Controllers\UnitSwitchController::class, 'clear'])->name('unit.clear');
});

require __DIR__ . '/auth.php';
