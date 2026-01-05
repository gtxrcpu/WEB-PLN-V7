# 📋 Manual Testing Checklist - K3 PLN Inventory System

> **Purpose:** Comprehensive manual testing guide for pre-production validation  
> **Version:** 1.0  
> **Last Updated:** 2026-01-05

---

## ✅ Testing Instructions

- [ ] Mark items with ✅ when test passes
- [ ] Mark items with ❌ when test fails  
- [ ] Document any failures in **Issues Found** section
- [ ] Test on all specified browsers and devices
- [ ] Perform tests in sequential order where noted

---

## 1️⃣ Authentication Flow Tests

### 1.1 Superadmin Login
- [ ] Navigate to `/login`
- [ ] Enter superadmin credentials
- [ ] Click "Login" button
- [ ] **Expected:** Redirect to `/user` dashboard
- [ ] **Expected:** See superadmin menu items (User Management, Units, etc.)
- [ ] **Expected:** Session persists after page refresh

### 1.2 Leader Login
- [ ] Login with leader credentials
- [ ] **Expected:** Redirect to `/user` dashboard
- [ ] **Expected:** See "Antrian Persetujuan" menu
- [ ] **Expected:** Cannot access user management

### 1.3 Petugas Login
- [ ] Login with petugas credentials
- [ ] **Expected:** Redirect to `/user` dashboard
- [ ] **Expected:** Can create equipment records
- [ ] **Expected:** Cannot access admin features

### 1.4 Inspector Login
- [ ] Login with inspector credentials
- [ ] **Expected:** Redirect to `/user` dashboard
- [ ] **Expected:** Can view equipment
- [ ] **Expected:** Read-only access (cannot create/edit)

### 1.5 Remember Me Function
- [ ] Login with "Remember Me" checked
- [ ] Close browser completely
- [ ] Reopen browser and navigate to site
- [ ] **Expected:** Still logged in

### 1.6 Logout
- [ ] Click logout button
- [ ] **Expected:** Redirect to `/login`
- [ ] **Expected:** Session cleared (cannot access protected pages)

### 1.7 Invalid Credentials
- [ ] Attempt login with wrong password
- [ ] **Expected:** Error message displayed
- [ ] **Expected:** Still on login page

---

## 2️⃣ Equipment Management Tests

### 2.1 APAR (Fire Extinguisher) Management

#### Create APAR
- [ ] Login as petugas
- [ ] Navigate to "Tambah APAR"
- [ ] Fill all required fields (Name, Serial No, Type, Capacity, Location, Status)
- [ ] Click "Simpan"
- [ ] **Expected:** Success message
- [ ] **Expected:** APAR appears in list
- [ ] **Expected:** QR code generated automatically

#### View APAR Details
- [ ] Click on APAR from list
- [ ] **Expected:** All data displayed correctly
- [ ] **Expected:** QR code visible
- [ ] **Expected:** Related kartu inspections listed

#### Edit APAR
- [ ] Click "Edit" on APAR
- [ ] Modify status to "ISI ULANG"
- [ ] Click "Update"
- [ ] **Expected:** Changes saved
- [ ] **Expected:** Updated status reflected

#### Delete APAR
- [ ] Click "Hapus" on test APAR
- [ ] Confirm deletion
- [ ] **Expected:** APAR removed from list

### 2.2 APAT (Outdoor Hydrant) Management
- [ ] Repeat Create/View/Edit/Delete tests for APAT
- [ ] Verify APAT-specific fields (pressure, hose length, etc.)

### 2.3 Fire Alarm Management
- [ ] Repeat Create/View/Edit/Delete tests for Fire Alarm
- [ ] Verify alarm-specific fields (type, location, last test)

### 2.4 Box Hydrant Management
- [ ] Repeat Create/View/Edit/Delete tests for Box Hydrant

### 2.5 P3K (First Aid Kit) Management
- [ ] Repeat Create/View/Edit/Delete tests for P3K
- [ ] Verify medical supplies tracking

### 2.6 APAB Management
- [ ] Repeat Create/View/Edit/Delete tests for APAB
- [ ] Verify APAB-specific fields (isi_apab, capacity)

---

## 3️⃣ Kartu Kendali (Control Card) Flow Tests

### 3.1 Create Kartu Kendali for APAR
- [ ] Navigate to APAR details
- [ ] Click "Tambah Kartu Inspeksi"
- [ ] Fill inspection date
- [ ] Select condition for each component (Tabung, Handle, Selang, Nozzle, dll)
- [ ] Add notes
- [ ] Click "Simpan"
- [ ] **Expected:** Kartu created with "Pending" status

### 3.2 View Kartu History
- [ ] Navigate to equipment details
- [ ] **Expected:** All kartu inspections listed chronologically
- [ ] **Expected:** Status (Pending/Approved) visible
- [ ] **Expected:** Can filter by date range

### 3.3 Print Kartu Kendali
- [ ] Click "Cetak" on kartu
- [ ] **Expected:** PDF generated with all inspection data
- [ ] **Expected:** PDF formatted correctly for A4 paper
- [ ] **Expected:** QR code included in PDF

### 3.4 Download Kartu as PDF
- [ ] Click "Download PDF"
- [ ] **Expected:** File downloads successfully
- [ ] **Expected:** PDF opens correctly

---

## 4️⃣ Approval Workflow Tests

### 4.1 Leader Approval Queue
- [ ] Login as leader
- [ ] Navigate to "Antrian Persetujuan"
- [ ] **Expected:** See list of pending kartu inspections
- [ ] **Expected:** Filter by date/status works

### 4.2 Approve Kartu Kendali
- [ ] Select pending kartu
- [ ] Review inspection details
- [ ] Click "Setujui"
- [ ] **Expected:** Status changes to "Approved"
- [ ] **Expected:** Approved timestamp recorded
- [ ] **Expected:** Kartu removed from pending queue

### 4.3 Reject Kartu Kendali (if implemented)
- [ ] Select pending kartu
- [ ] Click "Tolak" (if available)
- [ ] Add rejection reason
- [ ] **Expected:** Status changes to "Rejected"
- [ ] **Expected:** Creator notified

### 4.4 Approval Permissions
- [ ] Login as petugas
- [ ] Attempt to access approval queue
- [ ] **Expected:** Access denied (403 or redirect)

---

## 5️⃣ Guest Access Tests

### 5.1 Public Pages
- [ ] Logout completely
- [ ] Navigate to `/` (home page)
- [ ] **Expected:** Public landing page visible
- [ ] **Expected:** No protected data shown

### 5.2 Protected Pages
- [ ] Attempt to access `/user` without login
- [ ] **Expected:** Redirect to `/login`
- [ ] Attempt to access `/admin` without login
- [ ] **Expected:** Redirect to `/login`

### 5.3 QR Code Scanning (Public)
- [ ] Scan equipment QR code (if public access enabled)
- [ ] **Expected:** Equipment details displayed
- [ ] **Expected:** Inspection history visible
- [ ] **Expected:** No edit/delete options

---

## 6️⃣ Floor Plan Functionality Tests

### 6.1 View Floor Plan
- [ ] Login as authenticated user
- [ ] Navigate to "Floor Plan"
- [ ] **Expected:** Floor plan image loads
- [ ] **Expected:** Equipment markers displayed on correct positions

### 6.2 Interactive Markers
- [ ] Click on equipment marker
- [ ]**Expected:** Popup shows equipment name, status, and location
- [ ] Click "View Details" in popup
- [ ] **Expected:** Navigate to equipment detail page

### 6.3 Admin: Upload Floor Plan
- [ ] Login as superadmin
- [ ] Navigate to "Admin > Floor Plans"
- [ ] Upload new floor plan image (PNG/JPG, < 10MB)
- [ ] **Expected:** Image uploaded successfully
- [ ] **Expected:** Floor plan available for selection

### 6.4 Admin: Place Equipment on Floor Plan
- [ ] Select equipment from list
- [ ] Click position on floor plan
- [ ] Save placement
- [ ] **Expected:** Equipment marker appears at clicked position
- [ ] **Expected:** Coordinates saved to database

---

## 7️⃣ Data Export Tests

### 7.1 Export Equipment List to Excel
- [ ] Navigate to equipment list (APAR, APAT, etc.)
- [ ] Click "Export to Excel" button
- [ ] **Expected:** Excel file downloads
- [ ] **Expected:** All visible data included (Name, Serial, Location, Status, Unit)
- [ ] **Expected:** File opens correctly in Excel/LibreOffice

### 7.2 Export Kartu History to PDF
- [ ] Navigate to equipment details
- [ ] Click "Export History"
- [ ] **Expected:** PDF with all inspection records generated

---

## 8️⃣ Cross-Browser Testing Checklist

Test **all critical flows** on each browser:

### Desktop Browsers
- [ ] **Google Chrome** (latest version)
  - [ ] Login/Logout
  - [ ] Create Equipment
  - [ ] View Floor Plan
  - [ ] Export Excel
  
- [ ] **Mozilla Firefox** (latest version)
  - [ ] Login/Logout
  - [ ] Create Equipment
  - [ ] View Floor Plan
  - [ ] Export Excel
  
- [ ] **Microsoft Edge** (latest version)
  - [ ] Login/Logout
  - [ ] Create Equipment
  - [ ] View Floor Plan
  - [ ] Export Excel

---

## 9️⃣ Mobile Responsive Testing Checklist

### Mobile Devices (iOS)
- [ ] **iPhone Safari** (iOS 14+)
  - [ ] Login page responsive
  - [ ] Dashboard menu accessible (hamburger/drawer)
  - [ ] Equipment list scrollable
  - [ ] Forms usable (no zoom required)
  - [ ] Floor plan viewable and interactive

### Mobile Devices (Android)
- [ ] **Chrome Mobile** (Android 10+)
  - [ ] Login page responsive
  - [ ] Dashboard menu accessible
  - [ ] Equipment list scrollable
  - [ ] Forms usable
  - [ ] Floor plan viewable

### Tablet
- [ ] **iPad/Android Tablet**
  - [ ] Layout adapts to tablet screen
  - [ ] Touch interactions work
  - [ ] No horizontal scrolling

---

## 🔟 Performance Verification Steps

### 10.1 Page Load Times
- [ ] Dashboard loads in < 2 seconds
- [ ] Equipment list (50 items) loads in < 2 seconds
- [ ] Floor plan page loads in < 3 seconds
- [ ] Excel export completes in < 5 seconds

### 10.2 Concurrent Users (if possible)
- [ ] 5 users logged in simultaneously
- [ ] **Expected:** No performance degradation
- [ ] **Expected:** No session conflicts

### 10.3 Large Datasets
- [ ] Test with 200+ equipment records
- [ ] **Expected:** Pagination works smoothly
- [ ] **Expected:** Search/filter responsive

---

## 1️⃣1️⃣ Security Verification

### 11.1 CSRF Protection
- [ ] Submit form without CSRF token (use browser dev tools)
- [ ] **Expected:** Request rejected (419 error)

### 11.2 Role-Based Access
- [ ] Login as petugas
- [ ] Attempt to access `/admin/users`
- [ ] **Expected:** Access denied (403 or redirect)

### 11.3 SQL Injection Prevention
- [ ] Enter `' OR '1'='1` in search field
- [ ] **Expected:** Sanitized search (no SQL error)

### 11.4 XSS Prevention
- [ ] Enter `<script>alert('XSS')</script>` in equipment name
- [ ] **Expected:** Script tags escaped in output

---

## 🐛 Issues Found

| # | Date | Tester | Issue Description | Severity | Status |
|---|------|--------|-------------------|----------|--------|
| 1 |      |        |                   | High/Med/Low | Open/Fixed |
| 2 |      |        |                   |          |        |
| 3 |      |        |                   |          |        |

---

## ✅ Sign-Off

**Tested By:** _____________________  
**Date:** _____________________  
**Environment:** Production / Staging / Local  
**Overall Result:** PASS / FAIL / PARTIAL  

**Notes:**


---

## 📌 Additional Notes

- Perform regression testing after any bug fixes
- Document any new features not covered in this checklist
- Update checklist version after major changes
- Keep screenshots of any failures for debugging
