# Inspector Login Credentials

## 🔐 Login Information

**Role:** Inspector (Read-Only Access)

**Email:** `inspector@pln.com`  
**Password:** `inspector123`

---

## ✅ What Inspector Can Do

1. ✅ **View All Equipment** - Dapat melihat semua equipment dari semua unit (tidak terikat unit)
2. ✅ **View History** - Dapat melihat riwayat kartu kendali/inspeksi
3. ✅ **View QR Codes** - Dapat melihat QR code untuk setiap equipment
4. ✅ **Search & Filter** - Dapat mencari dan filter equipment

## ❌ What Inspector Cannot Do

1. ❌ **No Create** - Tidak bisa menambah equipment baru
2. ❌ **No Edit** - Tidak bisa mengubah data equipment
3. ❌ **No Delete** - Tidak bisa menghapus equipment
4. ❌ **No Create Kartu** - Tidak bisa membuat kartu kendali baru

---

## 📋 Available Equipment Modules

Inspector dapat mengakses semua modul berikut dalam mode read-only:

1. **APAR** - Alat Pemadam Api Ringan
2. **APAT** - Alat Pemadam Api Tradisional
3. **APAB** - Alat Pemadam Api Berat
4. **P3K** - Pertolongan Pertama Pada Kecelakaan
5. **Fire Alarm** - Sistem Alarm Kebakaran
6. **Box Hydrant** - Box Hydrant
7. **Rumah Pompa** - Rumah Pompa

---

## 🚀 How to Login

1. Buka browser dan akses aplikasi: `http://localhost/login`
2. Masukkan email: `inspector@pln.com`
3. Masukkan password: `inspector123`
4. Klik "Login"
5. Anda akan diarahkan ke **Inspector Dashboard**

---

## 📱 Inspector Dashboard Features

- **Total Equipment Stats** - Melihat jumlah total semua equipment
- **Equipment Cards** - Card untuk setiap jenis equipment dengan jumlah
- **Quick Access** - Klik card untuk langsung ke halaman equipment
- **Read-Only Badge** - Badge yang menunjukkan mode read-only

---

## 🔄 Other User Credentials

### Admin
- **Email:** `admin@pln.com`
- **Password:** `admin123`
- **Access:** Full CRUD access + User management

### User (Regular)
- **Email:** `user@pln.com`
- **Password:** `user123`
- **Access:** CRUD access (terikat dengan unit)

---

## 📝 Notes

- Inspector role menggunakan middleware `role:Inspector`
- Semua routes Inspector ada di prefix `/inspector`
- View Inspector terpisah dari User dan Admin
- Inspector tidak terikat dengan unit, bisa lihat semua data

---

## 🛠️ Technical Details

**Controller:** `App\Http\Controllers\Inspector\InspectorDashboardController`  
**Views:** `resources/views/inspector/`  
**Routes:** `routes/web.php` (prefix: `/inspector`)  
**Middleware:** `auth`, `role:Inspector`

---

**Created:** November 25, 2025  
**Status:** ✅ Ready to Use
