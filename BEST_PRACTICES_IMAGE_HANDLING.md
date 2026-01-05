# Best Practices: Image Handling di Laravel

## ❌ JANGAN Gunakan Pola Ini

```blade
<!-- SALAH: Double wrapping -->
<img src="{{ asset(Storage::url($model->image_path)) }}">

<!-- SALAH: Storage::url() di blade -->
<img src="{{ Storage::url($model->image_path) }}">
```

## ✅ Gunakan Pola Ini

### 1. Gunakan Accessor di Model

**File Model** (contoh: `app/Models/FloorPlan.php`):

```php
use Illuminate\Support\Facades\Storage;

public function getImageUrlAttribute()
{
    // Jika image_path kosong, return placeholder
    if (empty($this->image_path)) {
        return asset('images/placeholder.png');
    }
    
    // Jika path dimulai dengan 'storage/', gunakan asset() langsung
    if (str_starts_with($this->image_path, 'storage/')) {
        return asset($this->image_path);
    }
    
    // Jika path dimulai dengan folder storage (misal: 'floor-plans/')
    if (str_starts_with($this->image_path, 'floor-plans/')) {
        if (Storage::disk('public')->exists($this->image_path)) {
            return asset('storage/' . $this->image_path);
        }
    }
    
    // Cek apakah file exists di public folder
    $fullPath = public_path($this->image_path);
    if (file_exists($fullPath)) {
        return asset($this->image_path);
    }
    
    // Jika file tidak ada, return placeholder
    return asset('images/placeholder.png');
}
```

**File Blade**:

```blade
<!-- BENAR: Gunakan accessor -->
<img src="{{ $floorPlan->image_url }}" alt="Floor Plan">
```

### 2. Untuk Avatar User

**File Model** (`app/Models/User.php`):

```php
public function getAvatarUrlAttribute()
{
    if (empty($this->avatar)) {
        return asset('images/default-avatar.png');
    }
    
    // External URL
    if (str_starts_with($this->avatar, 'http')) {
        return $this->avatar;
    }
    
    // Storage path
    if (str_starts_with($this->avatar, 'storage/')) {
        return asset($this->avatar);
    }
    
    // Avatars folder in storage
    if (str_starts_with($this->avatar, 'avatars/')) {
        return asset('storage/' . $this->avatar);
    }
    
    return asset($this->avatar);
}
```

**File Blade**:

```blade
<img src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
```

## Penjelasan Helper Functions

### `asset()`
- Digunakan untuk file di folder `public/`
- Menghasilkan URL lengkap: `http://domain.com/path/to/file.jpg`
- **Gunakan ini untuk semua image URL di blade**

```php
asset('images/logo.png')
// Output: http://domain.com/images/logo.png

asset('storage/avatars/user.jpg')
// Output: http://domain.com/storage/avatars/user.jpg
```

### `Storage::url()`
- Digunakan di **Controller/Model** untuk generate URL dari storage
- Sudah menghasilkan URL lengkap
- **JANGAN wrap dengan `asset()`**

```php
// Di Controller
$url = Storage::url('avatars/user.jpg');
// Output: /storage/avatars/user.jpg (relative URL)

// Jika perlu full URL
$fullUrl = Storage::disk('public')->url('avatars/user.jpg');
```

### `url()`
- Generate absolute URL
- Bisa digunakan untuk path apapun

```php
url('images/logo.png')
// Output: http://domain.com/images/logo.png
```

## Storage Structure

```
project/
├── public/
│   ├── images/              # Static images (logo, icons, etc)
│   │   ├── logo.png
│   │   ├── default-avatar.png
│   │   └── placeholder.png
│   └── storage/             # Symlink ke storage/app/public
│       ├── avatars/
│       ├── floor-plans/
│       └── documents/
├── storage/
│   └── app/
│       └── public/          # User uploaded files
│           ├── avatars/
│           ├── floor-plans/
│           └── documents/
```

## Setup Storage Link

Untuk membuat symlink dari `storage/app/public` ke `public/storage`:

```bash
php artisan storage:link
```

## Contoh Lengkap: Upload & Display

### Controller (Upload)

```php
public function store(Request $request)
{
    $request->validate([
        'image' => 'required|image|max:2048'
    ]);
    
    // Upload ke storage/app/public/floor-plans
    $path = $request->file('image')->store('floor-plans', 'public');
    
    // Simpan path ke database (tanpa 'storage/')
    FloorPlan::create([
        'name' => $request->name,
        'image_path' => $path, // Simpan: 'floor-plans/xxx.jpg'
    ]);
}
```

### Model (Accessor)

```php
public function getImageUrlAttribute()
{
    if (empty($this->image_path)) {
        return asset('images/placeholder.png');
    }
    
    // image_path = 'floor-plans/xxx.jpg'
    return asset('storage/' . $this->image_path);
    // Output: http://domain.com/storage/floor-plans/xxx.jpg
}
```

### Blade (Display)

```blade
<img src="{{ $floorPlan->image_url }}" alt="{{ $floorPlan->name }}">
```

## Cache Busting

Untuk memaksa browser reload image setelah update:

```php
public function getImageUrlAttribute()
{
    if (empty($this->image_path)) {
        return asset('images/placeholder.png');
    }
    
    $fullPath = public_path('storage/' . $this->image_path);
    
    if (file_exists($fullPath)) {
        // Tambahkan timestamp sebagai query parameter
        return asset('storage/' . $this->image_path) . '?v=' . filemtime($fullPath);
    }
    
    return asset('images/placeholder.png');
}
```

Output: `http://domain.com/storage/floor-plans/xxx.jpg?v=1640995200`

## Checklist

✅ Gunakan accessor di Model untuk generate image URL
✅ Gunakan `asset()` untuk semua path di blade
✅ JANGAN wrap `Storage::url()` dengan `asset()`
✅ Simpan relative path di database (tanpa domain)
✅ Buat placeholder untuk image yang tidak ada
✅ Gunakan cache busting untuk force reload
✅ Jalankan `php artisan storage:link` di production

## Testing

```bash
# Test storage link
php artisan storage:link

# Test upload
# Upload file via form

# Check file exists
ls -la public/storage/floor-plans/

# Check symlink
ls -la public/storage
```
