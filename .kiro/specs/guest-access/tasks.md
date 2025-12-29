# Implementation Plan - Guest Access

- [x] 1. Setup guest routes dan controller structure





  - Buat route group untuk guest access di `routes/web.php` tanpa auth middleware
  - Buat `GuestController` di `app/Http/Controllers/GuestController.php`
  - Implementasi method `index()` untuk dashboard dengan logic dari InspectorDashboardController
  - _Requirements: 1.1, 1.2, 1.3_
-

- [x] 2. Implementasi guest dashboard




  - [x] 2.1 Buat guest layout di `resources/views/guest/layouts/guest.blade.php`


    - Buat navigation bar dengan badge "Guest Mode"
    - Tambahkan link "Login" di navigation
    - Buat footer dengan informasi guest access
    - _Requirements: 5.1, 5.2, 5.3, 5.4_

  - [x] 2.2 Buat guest dashboard view di `resources/views/guest/dashboard.blade.php`


    - Copy struktur dari inspector dashboard
    - Hapus semua action buttons dan forms
    - Tampilkan statistik untuk semua equipment modules
    - Implementasi charts untuk status peralatan dan trend inspeksi
    - _Requirements: 1.2, 1.3, 4.1, 4.2, 8.1, 8.2_

  - [x] 2.3 Buat navigation component untuk guest


    - Buat menu navigasi untuk semua modul equipment
    - Implementasi breadcrumb navigation
    - Tambahkan tombol "Kembali ke Dashboard"
    - _Requirements: 6.1, 6.2, 6.3, 6.4_

- [x] 3. Implementasi guest APAR module





  - [x] 3.1 Implementasi controller methods untuk APAR


    - Buat method `apar()` untuk menampilkan daftar APAR
    - Buat method `aparRiwayat()` untuk menampilkan riwayat APAR
    - _Requirements: 2.1, 2.8, 3.1, 3.2_

  - [x] 3.2 Buat guest APAR views


    - Buat `resources/views/guest/apar/index.blade.php` untuk daftar APAR
    - Buat `resources/views/guest/apar/riwayat.blade.php` untuk riwayat
    - Hapus action buttons (Create, Edit, Delete)
    - Tampilkan informasi dasar (kode, lokasi, kategori, status)
    - _Requirements: 2.8, 3.3, 3.4, 4.1, 4.3, 4.4_

- [x] 4. Implementasi guest APAT module




  - [x] 4.1 Implementasi controller methods untuk APAT


    - Buat method `apat()` untuk menampilkan daftar APAT
    - Buat method `apatRiwayat()` untuk menampilkan riwayat APAT
    - _Requirements: 2.2, 2.8, 3.1, 3.2_

  - [x] 4.2 Buat guest APAT views


    - Buat `resources/views/guest/apat/index.blade.php`
    - Buat `resources/views/guest/apat/riwayat.blade.php`
    - Reuse component structure dari APAR views
    - _Requirements: 2.8, 3.3, 3.4, 4.1_







- [ ] 5. Implementasi guest P3K module

  - [x] 5.1 Implementasi controller methods untuk P3K


    - Buat method `p3k()` untuk menampilkan daftar P3K
    - Buat method `p3kRiwayat()` untuk menampilkan riwayat P3K
    - _Requirements: 2.3, 2.8, 3.1, 3.2_

  - [x] 5.2 Buat guest P3K views





    - Buat `resources/views/guest/p3k/index.blade.php`
    - Buat `resources/views/guest/p3k/riwayat.blade.php`
    - _Requirements: 2.8, 3.3, 3.4, 4.1_


- [x] 6. Implementasi guest APAB module




  - [x] 6.1 Implementasi controller methods untuk APAB


    - Buat method `apab()` untuk menampilkan daftar APAB
    - Buat method `apabRiwayat()` untuk menampilkan riwayat APAB
    - _Requirements: 2.4, 2.8, 3.1, 3.2_

  - [x] 6.2 Buat guest APAB views


    - Buat `resources/views/guest/apab/index.blade.php`
    - Buat `resources/views/guest/apab/riwayat.blade.php`
    - _Requirements: 2.8, 3.3, 3.4, 4.1_

- [x] 7. Implementasi guest Fire Alarm module




  - [x] 7.1 Implementasi controller methods untuk Fire Alarm


    - Buat method `fireAlarm()` untuk menampilkan daftar Fire Alarm
    - Buat method `fireAlarmRiwayat()` untuk menampilkan riwayat Fire Alarm
    - _Requirements: 2.5, 2.8, 3.1, 3.2_

  - [x] 7.2 Buat guest Fire Alarm views


    - Buat `resources/views/guest/fire-alarm/index.blade.php`
    - Buat `resources/views/guest/fire-alarm/riwayat.blade.php`
    - _Requirements: 2.8, 3.3, 3.4, 4.1_


- [x] 8. Implementasi guest Box Hydrant module




  - [x] 8.1 Implementasi controller methods untuk Box Hydrant

    - Buat method `boxHydrant()` untuk menampilkan daftar Box Hydrant
    - Buat method `boxHydrantRiwayat()` untuk menampilkan riwayat Box Hydrant
    - _Requirements: 2.6, 2.8, 3.1, 3.2_

  - [x] 8.2 Buat guest Box Hydrant views


    - Buat `resources/views/guest/box-hydrant/index.blade.php`
    - Buat `resources/views/guest/box-hydrant/riwayat.blade.php`
    - _Requirements: 2.8, 3.3, 3.4, 4.1_

- [x] 9. Implementasi guest Rumah Pompa module




  - [x] 9.1 Implementasi controller methods untuk Rumah Pompa


    - Buat method `rumahPompa()` untuk menampilkan daftar Rumah Pompa
    - Buat method `rumahPompaRiwayat()` untuk menampilkan riwayat Rumah Pompa
    - _Requirements: 2.7, 2.8, 3.1, 3.2_

  - [x] 9.2 Buat guest Rumah Pompa views


    - Buat `resources/views/guest/rumah-pompa/index.blade.php`
    - Buat `resources/views/guest/rumah-pompa/riwayat.blade.php`
    - _Requirements: 2.8, 3.3, 3.4, 4.1_

- [x] 10. Implementasi security dan error handling






  - [x] 10.1 Implementasi route protection

    - Pastikan guest users tidak dapat mengakses protected routes
    - Implementasi redirect ke login untuk protected routes
    - _Requirements: 1.5, 7.1, 7.2, 7.3, 7.4_

  - [x] 10.2 Implementasi data filtering


    - Filter data sensitif dari guest views (signatures, user details)
    - Implementasi error handling untuk data not found
    - Implementasi empty state handling
    - _Requirements: 7.5_

  - [x] 10.3 Implementasi rate limiting


    - Tambahkan throttle middleware untuk guest routes (60 requests per minute)
    - _Requirements: 7.1, 7.2, 7.3_

- [ ] 11. Optimasi performance
  - [ ] 11.1 Implementasi eager loading
    - Gunakan eager loading untuk relationships di semua queries
    - Optimasi database queries untuk menghindari N+1 problem
    - _Requirements: 8.1, 8.2, 8.5_

  - [ ] 11.2 Implementasi pagination
    - Tambahkan pagination untuk daftar equipment (20 items per page)
    - Implementasi pagination untuk riwayat kartu kendali
    - _Requirements: 8.1, 8.2_

- [ ] 12. Testing dan validasi
  - [ ] 12.1 Manual testing
    - Test akses semua guest routes tanpa login
    - Verifikasi tidak ada action buttons yang muncul
    - Test responsive design di mobile dan desktop
    - Test redirect ke login untuk protected routes
    - _Requirements: 1.1, 1.2, 4.1, 4.2, 4.3, 4.4, 4.5_

  - [ ] 12.2 Browser compatibility testing
    - Test di Chrome, Firefox, Safari, dan Edge
    - Verifikasi layout dan functionality di semua browser
    - _Requirements: 1.1, 1.2_

  - [ ] 12.3 Security testing
    - Verifikasi guest users tidak dapat submit forms
    - Verifikasi guest users tidak dapat mengakses API endpoints untuk modifikasi data
    - Verifikasi tidak ada data sensitif yang terexpose
    - _Requirements: 7.1, 7.2, 7.3, 7.4, 7.5_

- [ ] 13. Update dokumentasi
  - [ ] 13.1 Update CREDENTIALS.md
    - Tambahkan informasi tentang guest access
    - Dokumentasikan route `/guest` dan fitur-fiturnya
    - _Requirements: 1.1, 1.2_

  - [ ] 13.2 Update README.md
    - Tambahkan section tentang guest access feature
    - Dokumentasikan cara mengakses guest mode
    - _Requirements: 1.1, 1.2_
