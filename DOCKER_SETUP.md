# 🐳 Docker FrankenPHP - Database Setup Guide

## 📋 Langkah-langkah Setup Database

### 1️⃣ Pastikan Docker Container Running

```bash
# Check container status
docker ps

# Jika belum running, start dengan:
docker compose up -d
```

### 2️⃣ Masuk ke Container FrankenPHP

```bash
# Masuk ke container
docker compose exec frankenphp sh

# Atau jika nama service berbeda:
docker compose exec app sh
```

### 3️⃣ Jalankan Migration & Seeder

```bash
# Di dalam container, jalankan:
php artisan migrate:fresh --seed
```

**ATAU** jika ingin langsung dari luar container:

```bash
docker compose exec frankenphp php artisan migrate:fresh --seed
```

---

## 👤 Default User Credentials

Setelah seeder berhasil, gunakan kredensial berikut untuk login:

### 🔑 SUPERADMIN (Full Access)
```
Email    : superadmin@pln.co.id
Username : superadmin
Password : super123
```

### 👨‍💼 LEADER UPW2 (Admin Unit 2)
```
Email    : leader.upw2@pln.co.id
Username : leader_upw2
Password : leader123
```

### 👨‍💼 LEADER UPW3 (Admin Unit 3)
```
Email    : leader.upw3@pln.co.id
Username : leader_upw3
Password : leader123
```

### 👷 PETUGAS UPW2 (Staff Unit 2)
```
Email    : petugas.upw2@pln.co.id
Username : petugas_upw2
Password : petugas123
```

### 👷 PETUGAS UPW3 (Staff Unit 3)
```
Email    : petugas.upw3@pln.co.id
Username : petugas_upw3
Password : petugas123
```

### 🔍 INSPECTOR (Auditor)
```
Email    : inspector@pln.co.id
Username : inspector
Password : inspector123
```

---

## 🔧 Troubleshooting

### ❌ Error: "SQLSTATE[HY000] [2002] Connection refused"

**Penyebab:** Database container belum ready

**Solusi:**
```bash
# Check database container
docker compose ps

# Restart database
docker compose restart mysql

# Wait 10 seconds, then try again
docker compose exec frankenphp php artisan migrate:fresh --seed
```

### ❌ Error: "Access denied for user"

**Penyebab:** Database credentials salah

**Solusi:** Check `.env` di container
```bash
docker compose exec frankenphp cat .env | grep DB_
```

Expected output:
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=pln_inventaris
DB_USERNAME=sail
DB_PASSWORD=password
```

### ❌ Error: "Database does not exist"

**Solusi:** Create database manually
```bash
# Masuk ke MySQL container
docker compose exec mysql mysql -u sail -ppassword

# Di MySQL prompt:
CREATE DATABASE IF NOT EXISTS pln_inventaris;
exit;

# Lalu jalankan migration lagi
docker compose exec frankenphp php artisan migrate:fresh --seed
```

---

## 📦 Quick Commands

```bash
# Fresh install (HAPUS SEMUA DATA!)
docker compose exec frankenphp php artisan migrate:fresh --seed

# Hanya run seeder (tanpa hapus data)
docker compose exec frankenphp php artisan db:seed

# Check migration status
docker compose exec frankenphp php artisan migrate:status

# Rollback last migration
docker compose exec frankenphp php artisan migrate:rollback

# Clear all cache
docker compose exec frankenphp php artisan optimize:clear

# Generate app key (jika belum ada)
docker compose exec frankenphp php artisan key:generate
```

---

## 🎯 Verification

Setelah seeder berhasil, verify dengan:

```bash
# Check users
docker compose exec frankenphp php artisan tinker --execute="echo 'Total users: ' . App\Models\User::count() . PHP_EOL;"

# Check roles
docker compose exec frankenphp php artisan tinker --execute="Spatie\Permission\Models\Role::all(['name'])"

# Check units
docker compose exec frankenphp php artisan tinker --execute="App\Models\Unit::all(['code', 'name'])"
```

Expected output:
- Total users: 6
- Roles: superadmin, leader, petugas, inspector
- Units: INDUK, UPW2, UPW3

---

## 🔐 Security Notes

⚠️ **PENTING untuk Production:**

1. **Ganti semua password default** setelah deployment
2. Disable atau hapus user yang tidak diperlukan
3. Set `APP_ENV=production` dan `APP_DEBUG=false`
4. Gunakan password yang kuat (min 12 karakter)

```bash
# Ganti password via tinker
docker compose exec frankenphp php artisan tinker

# Di tinker:
$user = App\Models\User::where('email', 'superadmin@pln.co.id')->first();
$user->password = Hash::make('NewSecurePassword123!');
$user->save();
exit;
```

---

## 📞 Support

Jika masih ada masalah, check logs:

```bash
# Laravel logs
docker compose exec frankenphp tail -f storage/logs/laravel.log

# Container logs
docker compose logs -f frankenphp
docker compose logs -f mysql
```
