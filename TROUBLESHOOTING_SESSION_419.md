# Troubleshooting: 419 Page Expired Error

## Masalah
Error 419 Page Expired muncul saat:
- Login sebagai user A (misal: petugas)
- Logout
- Login lagi sebagai user B (misal: superadmin)

## Penyebab
Session dan CSRF token tidak ter-flush dengan sempurna saat logout, menyebabkan token mismatch saat login berikutnya.

## Solusi yang Diterapkan

### 1. Enhanced Logout Handler
**File**: `app/Http/Controllers/Auth/AuthenticatedSessionController.php`

```php
public function destroy(Request $request): RedirectResponse
{
    Auth::guard('web')->logout();

    // Flush all session data before invalidating
    $request->session()->flush();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // Force redirect to login with fresh session
    return redirect()->route('login')->with('status', 'You have been logged out successfully.');
}
```

**Perubahan**:
- Tambah `flush()` sebelum `invalidate()` untuk membersihkan semua data session
- Redirect ke route `login` dengan pesan status
- Memastikan token benar-benar di-regenerate

### 2. Enhanced Login Handler
**File**: `app/Http/Controllers/Auth/AuthenticatedSessionController.php`

```php
// Regenerate session to prevent fixation attacks
$request->session()->regenerate();

// Clear any old session data that might interfere
$request->session()->forget('url.intended');
$request->session()->forget('_previous');
```

**Perubahan**:
- Tambah `forget('_previous')` untuk membersihkan data navigasi lama
- Memastikan tidak ada session data lama yang mengganggu

### 3. Exception Handler untuk TokenMismatchException
**File**: `app/Exceptions/Handler.php` (BARU)

```php
public function render($request, Throwable $e)
{
    // Handle CSRF token mismatch (419 Page Expired)
    if ($e instanceof TokenMismatchException) {
        // Clear the session and redirect to login
        if ($request->session()) {
            $request->session()->flush();
            $request->session()->regenerateToken();
        }
        
        return redirect()
            ->route('login')
            ->with('auth_error', 'Your session has expired. Please login again.');
    }

    return parent::render($request, $e);
}
```

**Fungsi**:
- Menangkap TokenMismatchException secara global
- Membersihkan session dan redirect ke login dengan pesan error yang user-friendly
- Mencegah user melihat halaman error 419 yang membingungkan

### 4. CSRF Token Middleware
**File**: `app/Http/Middleware/VerifyCsrfToken.php` (BARU)

File standar Laravel untuk verifikasi CSRF token.

### 5. Session Configuration
**File**: `.env.example`

```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=false
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
```

**Catatan untuk Production**:
- Set `SESSION_SECURE_COOKIE=true` jika menggunakan HTTPS
- Pastikan `SESSION_DOMAIN` sesuai dengan domain production

## Testing
1. Login sebagai user pertama (misal: petugas)
2. Logout
3. Login sebagai user kedua (misal: superadmin)
4. Tidak ada error 419, login berhasil

## Catatan Production
Pastikan di file `.env` production:
```env
SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true  # Jika menggunakan HTTPS
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
```

Jika masih ada masalah, coba:
```bash
php artisan config:clear
php artisan cache:clear
php artisan session:table  # Pastikan tabel sessions ada
php artisan migrate
```
