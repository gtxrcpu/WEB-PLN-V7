# Design Document - Guest Access

## Overview

Fitur Guest Access memungkinkan pengunjung publik untuk mengakses dan melihat data inventaris peralatan keselamatan tanpa memerlukan autentikasi. Fitur ini memberikan transparansi penuh terhadap status peralatan dengan interface read-only yang mirip dengan role Inspector, namun tanpa memerlukan kredensial login.

### Goals
- Memberikan akses publik ke data inventaris peralatan keselamatan
- Meningkatkan transparansi operasional PLN
- Memudahkan monitoring eksternal tanpa mengorbankan keamanan
- Menyediakan interface yang jelas dan user-friendly untuk guest users

### Non-Goals
- Guest users tidak dapat melakukan operasi CRUD (Create, Read, Update, Delete)
- Guest users tidak dapat mengakses fitur administratif
- Guest users tidak dapat melihat data sensitif (signatures, user details, approval details)
- Guest users tidak dapat mengakses API endpoints yang melakukan modifikasi data

## Architecture

### High-Level Architecture

```
┌─────────────────┐
│   Guest User    │
└────────┬────────┘
         │
         ▼
┌─────────────────────────────────────┐
│   Guest Routes (No Auth Required)   │
│   - /guest                          │
│   - /guest/{module}                 │
│   - /guest/{module}/{id}/riwayat    │
└────────┬────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────┐
│   GuestController                   │
│   - Reuses Inspector logic          │
│   - No authentication middleware    │
│   - Read-only operations            │
└────────┬────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────┐
│   Models (Eloquent ORM)             │
│   - Apar, Apat, P3k, etc.          │
│   - KartuApar, KartuApat, etc.     │
└────────┬────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────┐
│   Database (MySQL)                  │
└─────────────────────────────────────┘
```

### Request Flow

1. Guest user mengakses `/guest` atau `/guest/{module}`
2. Request diterima oleh route tanpa middleware `auth`
3. GuestController memproses request dan mengambil data dari database
4. Data dikirim ke view dengan layout khusus guest
5. View menampilkan data tanpa action buttons atau form input

## Components and Interfaces

### 1. Routes (routes/web.php)

```php
// Guest Routes (No Authentication Required)
Route::prefix('guest')->name('guest.')->group(function () {
    // Dashboard
    Route::get('/', [GuestController::class, 'index'])->name('dashboard');
    
    // Equipment Modules
    Route::get('/apar', [GuestController::class, 'apar'])->name('apar');
    Route::get('/apar/{apar}/riwayat', [GuestController::class, 'aparRiwayat'])->name('apar.riwayat');
    
    Route::get('/apat', [GuestController::class, 'apat'])->name('apat');
    Route::get('/apat/{apat}/riwayat', [GuestController::class, 'apatRiwayat'])->name('apat.riwayat');
    
    Route::get('/p3k', [GuestController::class, 'p3k'])->name('p3k');
    Route::get('/p3k/{p3k}/riwayat', [GuestController::class, 'p3kRiwayat'])->name('p3k.riwayat');
    
    Route::get('/apab', [GuestController::class, 'apab'])->name('apab');
    Route::get('/apab/{apab}/riwayat', [GuestController::class, 'apabRiwayat'])->name('apab.riwayat');
    
    Route::get('/fire-alarm', [GuestController::class, 'fireAlarm'])->name('fire-alarm');
    Route::get('/fire-alarm/{fireAlarm}/riwayat', [GuestController::class, 'fireAlarmRiwayat'])->name('fire-alarm.riwayat');
    
    Route::get('/box-hydrant', [GuestController::class, 'boxHydrant'])->name('box-hydrant');
    Route::get('/box-hydrant/{boxHydrant}/riwayat', [GuestController::class, 'boxHydrantRiwayat'])->name('box-hydrant.riwayat');
    
    Route::get('/rumah-pompa', [GuestController::class, 'rumahPompa'])->name('rumah-pompa');
    Route::get('/rumah-pompa/{rumahPompa}/riwayat', [GuestController::class, 'rumahPompaRiwayat'])->name('rumah-pompa.riwayat');
});
```

### 2. Controller (app/Http/Controllers/GuestController.php)

Controller ini akan menggunakan logic yang sama dengan `InspectorDashboardController`, tetapi dengan beberapa perbedaan:
- Tidak memerlukan authentication middleware
- Menggunakan view yang berbeda (guest views)
- Tidak menampilkan data sensitif

```php
<?php

namespace App\Http\Controllers;

use App\Models\Apar;
use App\Models\Apat;
use App\Models\Apab;
use App\Models\FireAlarm;
use App\Models\BoxHydrant;
use App\Models\RumahPompa;
use App\Models\P3k;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index()
    {
        // Reuse Inspector dashboard logic
        // Get statistics for all equipment modules
        // Return guest.dashboard view
    }
    
    public function apar()
    {
        // Get all APAR data
        // Return guest.apar.index view
    }
    
    public function aparRiwayat(Apar $apar)
    {
        // Get APAR history
        // Return guest.apar.riwayat view
    }
    
    // Similar methods for other modules...
}
```

### 3. Views Structure

```
resources/views/guest/
├── dashboard.blade.php          # Main guest dashboard
├── layouts/
│   └── guest.blade.php         # Guest-specific layout
├── apar/
│   ├── index.blade.php         # APAR list
│   └── riwayat.blade.php       # APAR history
├── apat/
│   ├── index.blade.php
│   └── riwayat.blade.php
├── p3k/
│   ├── index.blade.php
│   └── riwayat.blade.php
├── apab/
│   ├── index.blade.php
│   └── riwayat.blade.php
├── fire-alarm/
│   ├── index.blade.php
│   └── riwayat.blade.php
├── box-hydrant/
│   ├── index.blade.php
│   └── riwayat.blade.php
└── rumah-pompa/
    ├── index.blade.php
    └── riwayat.blade.php
```

### 4. Guest Layout (resources/views/guest/layouts/guest.blade.php)

Layout khusus untuk guest users dengan karakteristik:
- Navigation bar dengan badge "Guest Mode"
- Link ke halaman login
- Tidak ada menu untuk fitur yang memerlukan autentikasi
- Footer dengan informasi guest access

```blade
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Guest Access' }} - PLN Inventaris</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Navigation with Guest Badge -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="text-xl font-bold text-blue-600">PLN Inventaris</span>
                    <span class="ml-3 px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">
                        Guest Mode
                    </span>
                </div>
                <div class="flex items-center">
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <p class="text-center text-sm text-gray-600">
                Anda dalam mode Guest (Read-Only). 
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a> 
                untuk akses penuh.
            </p>
        </div>
    </footer>
</body>
</html>
```

### 5. Navigation Component for Guest

Guest navigation akan menampilkan menu untuk semua modul equipment:

```blade
<nav class="bg-white shadow-sm mb-6">
    <div class="flex space-x-4 overflow-x-auto">
        <a href="{{ route('guest.dashboard') }}" class="px-4 py-3 text-sm font-medium">
            Dashboard
        </a>
        <a href="{{ route('guest.apar') }}" class="px-4 py-3 text-sm font-medium">
            APAR
        </a>
        <a href="{{ route('guest.apat') }}" class="px-4 py-3 text-sm font-medium">
            APAT
        </a>
        <a href="{{ route('guest.p3k') }}" class="px-4 py-3 text-sm font-medium">
            P3K
        </a>
        <a href="{{ route('guest.apab') }}" class="px-4 py-3 text-sm font-medium">
            APAB
        </a>
        <a href="{{ route('guest.fire-alarm') }}" class="px-4 py-3 text-sm font-medium">
            Fire Alarm
        </a>
        <a href="{{ route('guest.box-hydrant') }}" class="px-4 py-3 text-sm font-medium">
            Box Hydrant
        </a>
        <a href="{{ route('guest.rumah-pompa') }}" class="px-4 py-3 text-sm font-medium">
            Rumah Pompa
        </a>
    </div>
</nav>
```

## Data Models

Fitur ini menggunakan model yang sudah ada tanpa modifikasi:

- **Apar**: Model untuk APAR equipment
- **Apat**: Model untuk APAT equipment
- **P3k**: Model untuk P3K equipment
- **Apab**: Model untuk APAB equipment
- **FireAlarm**: Model untuk Fire Alarm equipment
- **BoxHydrant**: Model untuk Box Hydrant equipment
- **RumahPompa**: Model untuk Rumah Pompa equipment
- **KartuApar, KartuApat, dll**: Models untuk kartu kendali/riwayat inspeksi

Semua model ini sudah memiliki relationships yang diperlukan untuk menampilkan data riwayat.

## Error Handling

### 1. Route Protection

Meskipun guest routes tidak memerlukan autentikasi, kita tetap perlu memastikan:
- Guest users tidak dapat mengakses protected routes
- Redirect ke login jika mencoba mengakses route yang memerlukan autentikasi

### 2. Data Not Found

Jika equipment atau kartu kendali tidak ditemukan:
```php
public function aparRiwayat(Apar $apar)
{
    if (!$apar) {
        abort(404, 'Equipment tidak ditemukan');
    }
    
    $riwayatInspeksi = $apar->kartuApars()->orderBy('tgl_periksa', 'desc')->get();
    return view('guest.apar.riwayat', compact('apar', 'riwayatInspeksi'));
}
```

### 3. Empty Data Handling

Views harus menangani kasus ketika tidak ada data:
```blade
@if($apars->isEmpty())
    <div class="text-center py-12">
        <p class="text-gray-500">Belum ada data APAR</p>
    </div>
@else
    <!-- Display data -->
@endif
```

## Testing Strategy

### 1. Manual Testing

**Test Cases:**
1. Akses `/guest` tanpa login → Harus berhasil menampilkan dashboard
2. Akses `/guest/apar` → Harus menampilkan daftar APAR
3. Akses `/guest/apar/{id}/riwayat` → Harus menampilkan riwayat APAR
4. Verifikasi tidak ada action buttons (Create, Edit, Delete, Approve, Reject)
5. Verifikasi badge "Guest Mode" muncul di navigation
6. Verifikasi link "Login" tersedia
7. Coba akses `/admin` dari guest → Harus redirect ke login
8. Coba akses `/leader` dari guest → Harus redirect ke login
9. Verifikasi data yang ditampilkan sama dengan Inspector view
10. Test responsive design di mobile dan desktop

### 2. Browser Testing

Test di berbagai browser:
- Chrome
- Firefox
- Safari
- Edge

### 3. Performance Testing

- Measure page load time untuk guest dashboard
- Verify database queries are optimized (use eager loading)
- Check for N+1 query problems

### 4. Security Testing

- Verify guest users cannot access protected routes
- Verify guest users cannot submit forms
- Verify guest users cannot access API endpoints for data modification
- Check for SQL injection vulnerabilities
- Verify no sensitive data is exposed in guest views

## Security Considerations

### 1. Route Protection

```php
// Ensure authenticated routes are protected
Route::middleware(['auth'])->group(function () {
    // Protected routes
});

// Guest routes are explicitly outside auth middleware
Route::prefix('guest')->name('guest.')->group(function () {
    // Public routes
});
```

### 2. Data Filtering

Guest views tidak boleh menampilkan:
- User credentials atau personal information
- Signature images atau approval details
- Internal notes atau comments
- Audit logs

### 3. CSRF Protection

Karena guest users tidak dapat submit forms, tidak perlu CSRF token di guest views.

### 4. Rate Limiting

Implementasi rate limiting untuk guest routes untuk mencegah abuse:

```php
Route::prefix('guest')->name('guest.')->middleware('throttle:60,1')->group(function () {
    // Guest routes with rate limiting (60 requests per minute)
});
```

## UI/UX Design

### 1. Visual Indicators

- **Guest Badge**: Badge kuning dengan teks "Guest Mode" di navigation bar
- **Read-Only Banner**: Banner informatif di top halaman yang menjelaskan mode guest
- **Login CTA**: Call-to-action yang jelas untuk login

### 2. Simplified Interface

- Tidak ada action buttons
- Tidak ada forms
- Fokus pada data visualization dan readability
- Clean dan minimalist design

### 3. Responsive Design

- Mobile-first approach
- Touch-friendly navigation
- Optimized untuk berbagai screen sizes

### 4. Color Scheme

- Gunakan warna yang berbeda dari authenticated views untuk membedakan guest mode
- Yellow/amber untuk guest indicators
- Blue untuk primary actions (Login button)

## Implementation Notes

### 1. Code Reusability

- Reuse logic dari `InspectorDashboardController`
- Reuse view components dari inspector views
- Create shared partials untuk data display

### 2. View Composition

Gunakan Blade components untuk reusable elements:
```blade
<x-guest.equipment-card :equipment="$apar" />
<x-guest.stats-card :title="'Total APAR'" :value="$total" />
<x-guest.navigation />
```

### 3. Performance Optimization

- Use eager loading untuk relationships
- Cache dashboard statistics
- Optimize database queries
- Use pagination untuk large datasets

```php
// Example: Eager loading
$apars = Apar::with(['unit', 'category', 'location'])->paginate(20);

// Example: Caching
$stats = Cache::remember('guest.dashboard.stats', 300, function () {
    return [
        'total_apar' => Apar::count(),
        'total_apat' => Apat::count(),
        // ... other stats
    ];
});
```

## Future Enhancements

### Phase 2 (Optional)

1. **Search Functionality**: Allow guests to search equipment by serial number or location
2. **Export to PDF**: Allow guests to export equipment lists to PDF
3. **QR Code Scanning**: Allow guests to scan QR codes to view equipment details
4. **Multi-language Support**: Add English translation for international visitors
5. **Analytics**: Track guest access patterns for insights
6. **Floor Plan View**: Show equipment placement on floor plans (read-only)

### Phase 3 (Optional)

1. **API for Guest Access**: RESTful API for third-party integrations
2. **Mobile App**: Dedicated mobile app for guest access
3. **Real-time Updates**: WebSocket integration for real-time data updates
4. **Advanced Filtering**: More sophisticated filtering and sorting options
