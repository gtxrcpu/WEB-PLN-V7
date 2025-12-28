# 🔐 Kredensial Login Sistem

## Struktur Role

Sistem ini memiliki 4 role dengan hierarki sebagai berikut:

1. **Superadmin** - Full access ke semua unit (di Induk)
2. **Leader** - Admin di unit masing-masing, bisa approval TTD kartu kendali
3. **Petugas** - User biasa yang input data kartu kendali
4. **Inspector** - Read-only access untuk monitoring

---

## Unit yang Tersedia

- **UPW2** - Unit Pelayanan Wilayah 2
- **UPW3** - Unit Pelayanan Wilayah 3

---

## Akun Login

### 1. SUPERADMIN (Induk)
**Full access ke semua unit dan fitur**

- **Username:** `superadmin`
- **Password:** `super123`
- **Email:** superadmin@pln.co.id
- **Unit:** - (Tidak terikat unit)
- **Akses:**
  - Kelola semua unit
  - Kelola semua user
  - Kelola template kartu
  - Kelola referensi
  - Export semua data
  - Approval kartu kendali semua unit

---

### 2. LEADER UPW2 (Admin Unit 2)
**Admin di Unit Pelayanan Wilayah 2**

- **Username:** `leader_upw2`
- **Password:** `leader123`
- **Email:** leader.upw2@pln.co.id
- **Unit:** UPW2
- **Akses:**
  - Kelola user di unit UPW2
  - Approval TTD kartu kendali unit UPW2
  - Kelola equipment unit UPW2
  - View & export data unit UPW2
  - Input kartu kendali

---

### 3. LEADER UPW3 (Admin Unit 3)
**Admin di Unit Pelayanan Wilayah 3**

- **Username:** `leader_upw3`
- **Password:** `leader123`
- **Email:** leader.upw3@pln.co.id
- **Unit:** UPW3
- **Akses:**
  - Kelola user di unit UPW3
  - Approval TTD kartu kendali unit UPW3
  - Kelola equipment unit UPW3
  - View & export data unit UPW3
  - Input kartu kendali

---

### 4. PETUGAS UPW2
**User biasa di Unit 2 - Input data**

- **Username:** `petugas_upw2`
- **Password:** `petugas123`
- **Email:** petugas.upw2@pln.co.id
- **Unit:** UPW2
- **Akses:**
  - Input kartu kendali
  - Edit kartu kendali sendiri
  - View kartu kendali sendiri

---

### 5. PETUGAS UPW3
**User biasa di Unit 3 - Input data**

- **Username:** `petugas_upw3`
- **Password:** `petugas123`
- **Email:** petugas.upw3@pln.co.id
- **Unit:** UPW3
- **Akses:**
  - Input kartu kendali
  - Edit kartu kendali sendiri
  - View kartu kendali sendiri

---

### 6. INSPECTOR
**Read-only untuk monitoring**

- **Username:** `inspector`
- **Password:** `inspector123`
- **Email:** inspector@pln.co.id
- **Unit:** - (Bisa lihat semua unit)
- **Akses:**
  - View semua data (read-only)
  - Tidak bisa edit/hapus/approve

---

## Cara Setup

1. Jalankan migration:
```bash
php artisan migrate:fresh
```

2. Jalankan seeder:
```bash
php artisan db:seed --class=UnitSeeder
php artisan db:seed --class=RolePermissionSeeder
php artisan db:seed --class=AdminSeeder
php artisan db:seed --class=InspectorSeeder
```

Atau jalankan semua seeder sekaligus:
```bash
php artisan migrate:fresh --seed
```

---

## Workflow Approval

1. **Petugas** input kartu kendali di unit masing-masing
2. Kartu masuk ke **Pending Approval**
3. **Leader** unit review dan approve dengan TTD
4. **Superadmin** bisa approve kartu dari semua unit
5. **Inspector** hanya bisa melihat (monitoring)

---

## Dashboard Routes

- **Superadmin:** `/admin`
- **Leader:** `/leader`
- **Petugas:** `/dashboard` (user dashboard)
- **Inspector:** `/inspector`

---

## Notes

- Leader hanya bisa kelola user dan approve kartu di unit-nya sendiri
- Petugas hanya bisa input dan edit kartu kendali sendiri
- Superadmin bisa akses semua fitur di semua unit
- Inspector tidak bisa melakukan perubahan apapun (read-only)
