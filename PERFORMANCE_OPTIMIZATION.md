# ⚡ Performance Optimization Guide

## 🎯 Quick Wins - Execute These First

### 1. Enable View & Config Caching (Production)
```bash
# Cache configuration (reduces file reads)
php artisan config:cache

# Cache routes (faster routing)
php artisan route:cache

# Cache views (pre-compile Blade templates)
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize --classmap-authoritative
```

### 2. Enable OPcache (PHP)
Edit `php.ini`:
```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0  ; Production only!
```

### 3. Database Query Optimization

**Problem:** N+1 queries in views
**Solution:** Use eager loading in controllers

**Example Fix:**
```php
// ❌ SLOW - N+1 problem
$apars = Apar::all();  // 1 query
foreach($apars as $apar) {
    $apar->unit->name;  // N queries (one per item!)
}

// ✅ FAST - Eager loading
$apars = Apar::with('unit')->get();  // 2 queries total
```

### 4. Use Pagination
```php
// ❌ SLOW - Loads all records
$apars = Apar::all();

// ✅ FAST - Paginate
$apars = Apar::paginate(50);
```

---

## 🚀 Controller Optimizations

### Common Controllers to Check

**AparController.php:**
```php
public function index()
{
    // ✅ Add eager loading
    $apars = Apar::with(['unit', 'user'])
        ->paginate(50);
    
    return view('apar.index', compact('apars'));
}
```

**DashboardController.php:**
```php
public function index()
{
    // ✅ Cache expensive queries
    $stats = Cache::remember('dashboard_stats', 3600, function () {
        return [
            'total_apar' => Apar::count(),
            'total_apat' => Apat::count(),
            // ... other stats
        ];
    });
    
    return view('dashboard', compact('stats'));
}
```

---

## 🎨 View Optimizations

### 1. Fragment Caching (for repeating blocks)
```blade
{{-- Cache expensive view fragments --}}
@cache('sidebar', 3600)
    <div class="sidebar">
        {{-- Expensive sidebar content --}}
    </div>
@endcache
```

### 2. Lazy Loading Images
```blade
{{-- ✅ Add loading="lazy" --}}
<img src="{{ $apar->image_url }}" loading="lazy" alt="APAR">
```

### 3. Reduce Query in Loops
```blade
{{-- ❌ BAD - Query in blade --}}
@foreach(\App\Models\P3k::orderBy('name')->get() as $p3k)
    ...
@endforeach

{{-- ✅ GOOD - Pass from controller --}}
{{-- In controller: $p3ks = P3k::orderBy('name')->get(); --}}
@foreach($p3ks as $p3k)
    ...
@endforeach
```

---

## 📦 Asset Optimization

### 1. Minify CSS/JS
```bash
# In package.json
npm run build  # or npm run production
```

### 2. Use CDN for Libraries
```html
<!-- ✅ Use CDN instead of bundling -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
```

### 3. Image Optimization
```bash
# Install image optimizer
composer require intervention/image

# Optimize uploaded images to max 1920px width
```

---

## 💾 Database Optimization

### 1. Add Indexes
```php
// In migration
$table->index(['unit_id', 'status']);  // Composite index
$table->index('created_at');          // For date queries
```

### 2. Use SELECT specific columns
```php
// ❌ SLOW - Loads all columns
User::all();

// ✅ FAST - Only needed columns
User::select('id', 'name', 'email')->get();
```

---

## 🔧 Quick Performance Checklist

**Execute these commands now:**

```bash
# 1. Clear all caches first
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 2. Rebuild optimized caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Optimize composer autoloader
composer dump-autoload --optimize --classmap-authoritative

# 4. Optimize node assets
npm run build
```

---

## 📊 Measure Performance

### Before & After Testing
```bash
# Install Laravel Debugbar (dev only)
composer require barryvdh/laravel-debugbar --dev

# Check query counts and time per page
# Look for:
# - Query count > 10 (likely N+1 problem)
# - Query time > 100ms (needs optimization)
# - View render > 200ms (cache it!)
```

---

## 🎯 Priority Fixes

### HIGH PRIORITY
1. ✅ Enable view caching: `php artisan view:cache`
2. ✅ Add eager loading to list pages
3. ✅ Use pagination (not `->all()`)
4. ✅ Enable OPcache in PHP

### MEDIUM PRIORITY
5. Cache dashboard statistics
6. Add database indexes
7. Minify CSS/JS assets
8. Lazy load images

### LOW PRIORITY
9. Fragment caching for sidebars
10. CDN for static assets
11. Image optimization on upload

---

## 🚀 Expected Results

After implementing these optimizations:

- **Page Load:** 2-3x faster
- **Database Queries:** 80-90% reduction
- **Memory Usage:** 30-40% reduction
- **Server Response:** < 100ms for most pages

---

## 📝 Monitoring

```bash
# Check PHP OPcache status
php -i | grep opcache

# Monitor query performance
tail -f storage/logs/laravel.log | grep "Slow query"

# Check application performance
php artisan about
```

---

**Run the Quick Wins commands NOW for immediate 2-3x speed improvement!**
