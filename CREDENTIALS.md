# 🔐 Default User Credentials - PLN K3 Inventaris

## Quick Login Reference

### 🔑 SUPERADMIN (Full Access)
- **Email:** `superadmin@pln.co.id`
- **Username:** `superadmin`
- **Password:** `super123`
- **Access:** All features, all units

---

### 👨‍💼 LEADER UPW2 (Admin Unit 2)
- **Email:** `leader.upw2@pln.co.id`
- **Username:** `leader_upw2`
- **Password:** `leader123`
- **Access:** Manage UPW2, approve inspections

### 👨‍💼 LEADER UPW3 (Admin Unit 3)
- **Email:** `leader.upw3@pln.co.id`
- **Username:** `leader_upw3`
- **Password:** `leader123`
- **Access:** Manage UPW3, approve inspections

---

### 👷 PETUGAS UPW2 (Staff Unit 2)
- **Email:** `petugas.upw2@pln.co.id`
- **Username:** `petugas_upw2`
- **Password:** `petugas123`
- **Access:** Create/edit equipment, create inspections

### 👷 PETUGAS UPW3 (Staff Unit 3)
- **Email:** `petugas.upw3@pln.co.id`
- **Username:** `petugas_upw3`
- **Password:** `petugas123`
- **Access:** Create/edit equipment, create inspections

---

### 🔍 INSPECTOR (Auditor)
- **Email:** `inspector@pln.co.id`
- **Username:** `inspector`
- **Password:** `inspector123`
- **Access:** View all data, export reports

---

## 🚀 Quick Setup (Docker)

```bash
# PowerShell (Windows)
.\docker-reset-db.ps1

# Bash (Linux/Mac)
./docker-reset-db.sh

# Manual
docker compose exec frankenphp php artisan migrate:fresh --seed
```

---

## ⚠️ Security Warning

**IMPORTANT:** These are default development credentials.

For production:
1. ✅ Change ALL passwords immediately
2. ✅ Delete unused accounts
3. ✅ Set `APP_ENV=production`
4. ✅ Set `APP_DEBUG=false`
5. ✅ Use strong passwords (min 12 characters)

---

## 📚 Full Documentation

- **Docker Setup:** See `DOCKER_SETUP.md`
- **Production Deployment:** See `scripts/deployment/README.md`
- **Testing Guide:** See `tests/manual/MANUAL_TESTING_CHECKLIST.md`
