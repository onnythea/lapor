# LAPOR Aspirasi — Sistem Layanan Pengaduan Masyarakat & Mahasiswa

Aplikasi web pengaduan/aspirasi berbasis **CodeIgniter 4** + **PHP 8.x** + **MySQL 8** + **Bootstrap 5**, dengan RBAC (Pelapor, Petugas, Super Admin), tracking nomor tiket, upload bukti, tanggapan/disposisi, rating, ekspor PDF/Excel, dan audit trail.

## Struktur Direktori (yang relevan)

```
lapor-ci4/
├── app/
│   ├── Config/
│   │   ├── Routes.php               # Definisi seluruh route + filter RBAC
│   │   └── Filters.php              # Registrasi alias filter 'auth'
│   ├── Controllers/
│   │   ├── BaseController.php
│   │   ├── Home.php
│   │   ├── Auth.php                 # login, register, logout
│   │   ├── Complaint.php            # area pelapor (buat/histori/detail/rating/unduh)
│   │   ├── Tracking.php             # pelacakan publik via nomor tiket
│   │   └── Admin/
│   │       ├── Dashboard.php        # statistik ringkas
│   │       ├── ComplaintManager.php # verifikasi, status, tanggapan, disposisi
│   │       ├── UserManager.php      # CRUD user, reset password, ubah role
│   │       ├── CategoryManager.php  # CRUD kategori
│   │       ├── Report.php           # ekspor PDF & Excel
│   │       └── ActivityLog.php      # audit trail
│   ├── Filters/
│   │   └── AuthFilter.php           # autentikasi + role checking
│   ├── Helpers/
│   │   └── ticket_helper.php        # generate_ticket_number(), status_badge()
│   ├── Models/
│   │   ├── UserModel.php
│   │   ├── CategoryModel.php
│   │   ├── ComplaintModel.php
│   │   ├── ResponseModel.php
│   │   ├── RatingModel.php
│   │   └── ActivityLogModel.php
│   ├── Database/
│   │   ├── Migrations/              # 6 file migration
│   │   └── Seeds/                   # SuperAdminSeeder, CategorySeeder, DatabaseSeeder
│   └── Views/
│       ├── layouts/main.php
│       ├── auth/ (login, register)
│       ├── complaints/ (create, index, show)
│       ├── tracking/ (index, result)
│       └── admin/ (dashboard, complaints, users, categories, report, activity_log)
├── writable/uploads/complaints/     # lampiran privat (di luar public root)
├── composer.json
└── env                              # salin menjadi .env
```

## Langkah Setup

### 1. Composer
```bash
cd lapor-ci4
composer install
```
Paket utama: `codeigniter4/framework`, `phpoffice/phpspreadsheet` (Excel), `dompdf/dompdf` (PDF).

> Jika belum ada kerangka penuh CI4 (folder `public/`, `system/`, `spark`), buat proyek dasar terlebih dahulu lalu salin folder `app/` ini ke dalamnya:
> ```bash
> composer create-project codeigniter4/appstarter lapor-ci4
> ```

### 2. Konfigurasi Environment
```bash
cp env .env
php spark key:generate
```
Sesuaikan `app.baseURL` dan kredensial `database.default.*` di `.env`.

### 3. Buat Database (MySQL 8.0+)
```sql
CREATE DATABASE lapor_aspirasi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Migration & Seeder
```bash
php spark migrate
php spark db:seed DatabaseSeeder
```

### 5. Jalankan
```bash
php spark serve
```
Akses: `http://localhost:8080`

## Akun Default (dari seeder)

| Role       | Email               | Password        |
|------------|---------------------|-----------------|
| Super Admin| admin@lapor.test    | `Admin#12345`   |
| Petugas    | petugas@lapor.test  | `Petugas#12345` |

> Segera ganti password default di lingkungan produksi.

## Alur Status Laporan
`Draft → Terkirim (pending) → Diproses (processing) → Selesai (completed) / Ditolak (rejected)`

## Catatan Keamanan
- **CSRF**: aktif global (`app.CSRFProtection = 'cookie'`); `form_open()` menyisipkan token otomatis. Route tracking publik dikecualikan (hanya baca).
- **XSS**: seluruh output di-`esc()`.
- **SQL Injection**: memakai Query Builder / Model (prepared statement).
- **Password**: `password_hash()` (bcrypt) + `password_verify()`.
- **Upload**: divalidasi `ext_in` + `mime_in` + `max_size` (2MB), disimpan di `writable/uploads/` (di luar web root) dan diunduh via controller dengan kontrol akses.
- **RBAC**: filter `auth:role1,role2` pada level route.
- **Audit trail**: setiap aksi penting dicatat di tabel `activity_logs`.
