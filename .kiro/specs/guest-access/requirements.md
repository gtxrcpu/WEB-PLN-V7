# Requirements Document

## Introduction

Sistem ini memerlukan fitur Guest Access yang memungkinkan pengunjung tanpa autentikasi untuk melihat data inventaris peralatan keselamatan (APAR, APAT, P3K, APAB, Fire Alarm, Box Hydrant, Rumah Pompa). Guest Access memberikan akses read-only yang mirip dengan role Inspector, tetapi tanpa memerlukan login. Fitur ini bertujuan untuk meningkatkan transparansi dan memudahkan monitoring publik terhadap status peralatan keselamatan.

## Glossary

- **Guest User**: Pengunjung yang mengakses sistem tanpa melakukan autentikasi login
- **System**: Aplikasi web inventaris PLN
- **Equipment Module**: Modul peralatan keselamatan (APAR, APAT, P3K, APAB, Fire Alarm, Box Hydrant, Rumah Pompa)
- **Kartu Kendali**: Kartu kontrol/riwayat pemeriksaan peralatan
- **Inspector**: Role yang memiliki akses read-only dengan autentikasi
- **Dashboard**: Halaman utama yang menampilkan ringkasan data
- **CRUD Operations**: Create, Read, Update, Delete operations
- **Action Button**: Tombol untuk melakukan operasi seperti edit, delete, approve, reject

## Requirements

### Requirement 1

**User Story:** Sebagai pengunjung publik, saya ingin mengakses halaman guest tanpa login, sehingga saya dapat melihat data inventaris peralatan keselamatan secara transparan

#### Acceptance Criteria

1. THE System SHALL menyediakan route `/guest` yang dapat diakses tanpa autentikasi
2. WHEN Guest User mengakses route `/guest`, THE System SHALL menampilkan dashboard dengan ringkasan data semua equipment modules
3. THE System SHALL menampilkan data dari semua unit (UPW2, UPW3, dan unit lainnya) pada guest dashboard
4. THE System SHALL menggunakan layout yang berbeda dari authenticated users untuk guest dashboard
5. WHEN Guest User mencoba mengakses route yang memerlukan autentikasi, THE System SHALL redirect ke halaman login

### Requirement 2

**User Story:** Sebagai pengunjung publik, saya ingin melihat daftar peralatan per modul, sehingga saya dapat memonitor status peralatan keselamatan

#### Acceptance Criteria

1. THE System SHALL menyediakan route `/guest/apar` untuk menampilkan daftar APAR tanpa autentikasi
2. THE System SHALL menyediakan route `/guest/apat` untuk menampilkan daftar APAT tanpa autentikasi
3. THE System SHALL menyediakan route `/guest/p3k` untuk menampilkan daftar P3K tanpa autentikasi
4. THE System SHALL menyediakan route `/guest/apab` untuk menampilkan daftar APAB tanpa autentikasi
5. THE System SHALL menyediakan route `/guest/fire-alarm` untuk menampilkan daftar Fire Alarm tanpa autentikasi
6. THE System SHALL menyediakan route `/guest/box-hydrant` untuk menampilkan daftar Box Hydrant tanpa autentikasi
7. THE System SHALL menyediakan route `/guest/rumah-pompa` untuk menampilkan daftar Rumah Pompa tanpa autentikasi
8. WHEN Guest User mengakses equipment list, THE System SHALL menampilkan informasi dasar peralatan (kode, lokasi, kategori, status)

### Requirement 3

**User Story:** Sebagai pengunjung publik, saya ingin melihat riwayat kartu kendali peralatan, sehingga saya dapat mengetahui histori pemeriksaan peralatan

#### Acceptance Criteria

1. THE System SHALL menyediakan route `/guest/{module}/{id}/riwayat` untuk menampilkan riwayat kartu kendali tanpa autentikasi
2. WHEN Guest User mengakses halaman riwayat, THE System SHALL menampilkan semua kartu kendali yang terkait dengan peralatan tersebut
3. THE System SHALL menampilkan detail kartu kendali termasuk tanggal pemeriksaan, petugas, dan hasil pemeriksaan
4. THE System SHALL menampilkan status approval kartu kendali (pending, approved, rejected)

### Requirement 4

**User Story:** Sebagai pengunjung publik, saya tidak ingin melihat tombol atau fitur yang memerlukan autentikasi, sehingga interface lebih bersih dan tidak membingungkan

#### Acceptance Criteria

1. WHEN Guest User melihat halaman apapun, THE System SHALL menyembunyikan semua action buttons (Create, Edit, Delete, Approve, Reject)
2. THE System SHALL menyembunyikan tombol "Tambah Data" pada semua halaman guest
3. THE System SHALL menyembunyikan tombol "Edit" dan "Hapus" pada daftar peralatan
4. THE System SHALL menyembunyikan tombol "Approve" dan "Reject" pada halaman kartu kendali
5. THE System SHALL menyembunyikan form input dan form edit dari guest users

### Requirement 5

**User Story:** Sebagai pengunjung publik, saya ingin melihat indikator bahwa saya sedang dalam mode guest, sehingga saya memahami keterbatasan akses saya

#### Acceptance Criteria

1. THE System SHALL menampilkan badge atau label "Guest Mode" pada navigation bar
2. THE System SHALL menampilkan informasi "Anda dalam mode Guest (Read-Only)" pada dashboard
3. WHEN Guest User berada di halaman guest, THE System SHALL menyediakan link atau tombol "Login" untuk autentikasi
4. THE System SHALL menggunakan warna atau styling yang berbeda untuk membedakan guest mode dari authenticated mode

### Requirement 6

**User Story:** Sebagai pengunjung publik, saya ingin navigasi yang mudah antar modul peralatan, sehingga saya dapat dengan cepat melihat data yang saya butuhkan

#### Acceptance Criteria

1. THE System SHALL menampilkan navigation menu dengan link ke semua equipment modules pada guest pages
2. WHEN Guest User mengklik menu item, THE System SHALL mengarahkan ke halaman modul yang sesuai tanpa memerlukan autentikasi
3. THE System SHALL menampilkan breadcrumb navigation pada setiap halaman guest
4. THE System SHALL menyediakan tombol "Kembali ke Dashboard" pada setiap halaman detail

### Requirement 7

**User Story:** Sebagai administrator sistem, saya ingin memastikan guest users tidak dapat mengakses fitur administratif, sehingga keamanan sistem tetap terjaga

#### Acceptance Criteria

1. THE System SHALL memblokir akses guest users ke route `/admin/*`
2. THE System SHALL memblokir akses guest users ke route `/leader/*`
3. THE System SHALL memblokir akses guest users ke semua API endpoints yang melakukan modifikasi data
4. WHEN Guest User mencoba mengakses protected route, THE System SHALL redirect ke halaman login dengan pesan error
5. THE System SHALL tidak menampilkan data sensitif seperti signature, approval details, atau user information pada guest pages

### Requirement 8

**User Story:** Sebagai pengunjung publik, saya ingin melihat data yang sama dengan Inspector, sehingga saya mendapatkan informasi yang lengkap dan akurat

#### Acceptance Criteria

1. THE System SHALL menampilkan data equipment yang sama dengan yang ditampilkan pada Inspector dashboard
2. THE System SHALL menampilkan statistik dan summary yang sama dengan Inspector dashboard
3. THE System SHALL menerapkan filter dan sorting yang sama dengan Inspector pages
4. THE System SHALL menampilkan data dari semua unit tanpa pembatasan
5. THE System SHALL menampilkan data real-time yang sama dengan authenticated users
