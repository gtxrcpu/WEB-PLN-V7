# Troubleshooting: Floor Plan Images Hilang

## Masalah
Image denah yang di-upload admin hilang setelah ganti laptop atau environment baru.

## Penyebab
1. **Symbolic Link Belum Dibuat**
   - Laravel menyimpan file upload di `storage/app/public/`
   - Untuk akses via web, perlu symbolic link dari `public/storage` → `storage/app/public`
   - Saat ganti laptop/environment, symbolic link ini tidak otomatis terbuat

2. **File Tidak Ter-commit ke Git**
   - Folder `storage/app/public/` biasanya di-ignore di `.gitignore`
   - File upload tidak ikut ter-commit ke repository
   - Saat clone/pull di laptop baru, file tidak ada

## Solusi

### 1. Buat Symbolic Link (WAJIB)
```bash
php artisan storage:link
```

Output yang benar:
```
INFO  The [public/storage] link has been connected to [storage/app/public].
```

### 2. Verifikasi Symbolic Link
**Windows:**
```bash
Test-Path public/storage
# Harus return: True
```

**Linux/Mac:**
```bash
ls -la public/storage
# Harus menunjukkan symbolic link
```

### 3. Cek Folder Storage
```bash
# Cek apakah folder floor-plans ada
ls storage/app/public/floor-plans

# Atau di Windows
dir storage\app\public\floor-plans
```

### 4. Test Upload Baru
1. Login sebagai admin
2. Buka menu Floor Plans
3. Upload denah baru
4. Cek apakah file tersimpan:
   ```bash
   ls storage/app/public/floor-plans
   ```
5. Cek apakah bisa diakses via browser:
   ```
   http://localhost:8000/storage/floor-plans/[filename]
   ```

## Cara Upload Ulang Denah

Jika denah hilang karena ganti laptop:

1. **Backup dari Laptop Lama** (jika masih ada akses):
   ```bash
   # Di laptop lama, copy folder:
   storage/app/public/floor-plans/
   ```

2. **Restore di Laptop Baru**:
   ```bash
   # Paste ke lokasi yang sama
   storage/app/public/floor-plans/
   ```

3. **Atau Upload Ulang via Admin Panel**:
   - Login sebagai admin
   - Menu: Floor Plans → Create
   - Upload file denah baru

## Pencegahan di Masa Depan

### Option 1: Backup Manual (Recommended untuk Development)
```bash
# Backup folder uploads secara berkala
cp -r storage/app/public/ backup/storage-$(date +%Y%m%d)/
```

### Option 2: Gunakan Cloud Storage (Production)
Edit `config/filesystems.php`:
```php
'default' => env('FILESYSTEM_DISK', 's3'), // atau 'cloudinary', 'wasabi', dll
```

### Option 3: Shared Storage (Team Development)
- Gunakan network drive atau cloud sync (Dropbox, Google Drive)
- Symlink `storage/app/public` ke shared folder

## Checklist Setelah Clone/Pull

Setiap kali setup environment baru:

- [ ] Run `composer install`
- [ ] Copy `.env.example` ke `.env`
- [ ] Run `php artisan key:generate`
- [ ] Run `php artisan migrate`
- [ ] **Run `php artisan storage:link`** ← PENTING!
- [ ] Run `php artisan db:seed` (jika perlu)
- [ ] Restore file uploads dari backup (jika ada)

## Verifikasi Upload Berfungsi

Test script untuk verifikasi:
```bash
# 1. Cek symbolic link
php artisan storage:link

# 2. Cek permissions (Linux/Mac)
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# 3. Test upload via tinker
php artisan tinker
>>> Storage::disk('public')->put('test.txt', 'Hello World');
>>> Storage::disk('public')->exists('test.txt');
# Harus return: true

# 4. Cek via browser
# Buka: http://localhost:8000/storage/test.txt
# Harus menampilkan: Hello World
```

## Path yang Digunakan

```
Upload Path (Controller):
$request->file('image')->store('floor-plans', 'public')

Actual Storage Location:
storage/app/public/floor-plans/[filename]

Public URL:
http://localhost:8000/storage/floor-plans/[filename]

Blade View:
{{ asset('storage/' . $floorPlan->image_path) }}
```

## Troubleshooting Lanjutan

### Image Tidak Muncul di Browser

1. **Cek Console Browser (F12)**
   - Lihat error 404 atau 403
   - Cek URL yang di-request

2. **Cek File Permissions** (Linux/Mac):
   ```bash
   ls -la storage/app/public/floor-plans/
   # Harus readable (644 atau 755)
   ```

3. **Cek .htaccess** (jika pakai Apache):
   ```apache
   # Pastikan ada di public/.htaccess
   RewriteEngine On
   RewriteCond %{REQUEST_FILENAME} !-d
   RewriteCond %{REQUEST_FILENAME} !-f
   RewriteRule ^ index.php [L]
   ```

4. **Clear Cache**:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan view:clear
   ```

## Contact

Jika masih ada masalah, cek:
- Laravel Log: `storage/logs/laravel.log`
- Web Server Error Log
- Browser Console (F12)
