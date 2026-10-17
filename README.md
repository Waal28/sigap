# SIGAP - Sistem Kenaikan Gaji dan Pangkat

**SIGAP** (Sistem Kenaikan Gaji dan Pangkat) adalah aplikasi berbasis web yang dikembangkan khusus untuk **Dinas Lingkungan Hidup (DLH) Kabupaten Bengkalis**. Sistem ini memfasilitasi pengajuan kenaikan pangkat dan kenaikan gaji berkala bagi para pegawai, proses review dan verifikasi bertingkat oleh Pejabat Penilai, pengelolaan master data pegawai oleh Administrator, serta pelaporan riwayat keputusan secara transparan.

---

## 1. Teknologi & Arsitektur

- **Framework**: CodeIgniter 4 (v4.7+)
- **Bahasa Pemrograman**: PHP 8.2+ (Terverifikasi di PHP 8.5)
- **Database**: PostgreSQL (Driver: `Postgre`, schema: `public`)
- **Styling**: Tailwind CSS & Vanilla CSS (Warna Tema: Hijau Gelap `#2D6A4F`, Hijau Mint `#E8F5E9`)
- **Analisis Statis**: PHPStan Level 5 (`phpstan.neon`)
- **Unit Testing**: PHPUnit 11 (`phpunit.xml`)
- **Pola Arsitektur**: Entity Pattern (Model dengan strongly-typed Entities, Slim Controllers)

---

## 2. Panduan Setup Awal

### A. Prasyarat Sistem
1. PHP 8.2 atau lebih baru dengan ekstensi: `intl`, `mbstring`, `pgsql`, `pdo_pgsql`, `curl`.
2. PostgreSQL 14/15/16/17 aktif.
3. Composer (v2.x).

### B. Konfigurasi Lingkungan (`.env`)
Salin file `env` menjadi `.env` jika belum ada:
```bash
cp env .env
```
Sesuaikan konfigurasi database pada file `.env`:
```ini
CI_ENVIRONMENT = development

database.default.hostname = localhost
database.default.database = sigap_db
database.default.username = postgres
database.default.password = postgres
database.default.DBDriver = Postgre
database.default.DBPrefix =
database.default.port     = 5432
database.default.schema   = public
```

### C. Eksekusi Migrasi & Seeder Database
Jalankan perintah Spark berikut secara berurutan:
```bash
# 1. Jalankan semua migrasi schema tabel (users, pegawais, pengajuans, lampiran_pengajuans)
php spark migrate

# 2. Isi data awal akun & data pegawai default
php spark db:seed UserSeeder
```

### D. Menjalankan Server Lokal
```bash
php spark serve --port 8080
```
Akses aplikasi melalui peramban: `http://localhost:8080`

---

## 3. Akun Login Default

| Role Pengguna | NIP / Username | Kata Sandi | Hak Akses Utama |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin` | `password123` | Dashboard statistik, CRUD master Data Pegawai, monitoring pengajuan masuk & lampiran PDF, laporan pengajuan. |
| **Pejabat Penilai** | `198001012005011001` | `password123` | Dashboard review, modal telaah berkas lengkap (icon mata), persetujuan (checklist hijau), penolakan berikan catatan alasan (silang merah), laporan riwayat putusan. |
| **Pegawai** | `199502152020121002` | `password123` | Dashboard status pribadi, form input pengajuan 2 kolom + 11 slot upload PDF (maks 2MB per berkas), monitoring progres usulan (Menunggu / Disetujui / Ditolak). |

---

## 4. Daftar Route Aplikasi & Hak Akses

Semua rute aplikasi dilindungi oleh filter autentikasi (`auth`) dan filter berbasis peran (`role:ROLE_NAME`).

| Metode | URL Route | Controller / Aksi | Hak Akses (Role) | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| **GET** | `/` | Redirect ke `/dashboard` atau `/login` | Publik | Halaman awal otomatis |
| **GET/POST** | `/login` | `AuthController::login` / `processLogin` | Tamu (Guest) | Halaman & proses autentikasi |
| **GET** | `/logout` | `AuthController::logout` | Auth (Semua Role) | Mengakhiri sesi pengguna |
| **GET** | `/dashboard` | `DashboardController::index` | Auth (Semua Role) | Menampilkan dashboard sesuai role aktif |
| **GET** | `/pengajuan` | Dispatcher dinamis | Auth (Semua Role) | Mengarahkan ke rute pengajuan role aktif |
| **GET** | `/laporan` | Dispatcher dinamis | Auth (Semua Role) | Mengarahkan ke rute laporan role aktif |
| **GET** | `/admin/pegawai` | `Admin\PegawaiController::index` | `ADMIN` | Daftar tabel master pegawai + search & filter |
| **POST** | `/admin/pegawai/store` | `Admin\PegawaiController::store` | `ADMIN` | Tambah pegawai baru & akun login otomatis |
| **POST** | `/admin/pegawai/update/(:num)` | `Admin\PegawaiController::update/$1` | `ADMIN` | Perbarui data identitas pegawai |
| **GET/POST** | `/admin/pegawai/delete/(:num)` | `Admin\PegawaiController::delete/$1` | `ADMIN` | Hapus data pegawai beserta akun terkait |
| **GET** | `/admin/pegawai/detail/(:num)` | `Admin\PegawaiController::detail/$1` | `ADMIN` | Endpoint JSON detail data pegawai |
| **GET** | `/admin/pengajuan` | `Admin\PengajuanController::index` | `ADMIN` | Monitoring daftar pengajuan kenaikan |
| **GET/POST** | `/admin/pengajuan/delete/(:num)`| `Admin\PengajuanController::delete/$1` | `ADMIN` | Hapus pengajuan & berkas fisik lampiran |
| **GET** | `/admin/laporan` | `Admin\LaporanController::index` | `ADMIN` | Rekapitulasi usulan yang telah selesai diproses |
| **GET** | `/pejabat/pengajuan` | `Pejabat\PengajuanController::index` | `PEJABAT_PENILAI` | Tabel telaah pengajuan masuk |
| **POST** | `/pejabat/pengajuan/approve/(:num)` | `Pejabat\PengajuanController::approve/$1` | `PEJABAT_PENILAI` | Menyetujui pengajuan kenaikan |
| **POST** | `/pejabat/pengajuan/reject/(:num)` | `Pejabat\PengajuanController::reject/$1` | `PEJABAT_PENILAI` | Menolak pengajuan kenaikan + catatan alasan |
| **GET** | `/pejabat/pengajuan/detail/(:num)` | `Pejabat\PengajuanController::detail/$1` | `PEJABAT_PENILAI` | Endpoint JSON review data lengkap & berkas |
| **GET** | `/pejabat/laporan` | `Pejabat\LaporanController::index` | `PEJABAT_PENILAI` | Rekapitulasi keputusan pejabat penilai |
| **GET** | `/pegawai/pengajuan` | `Pegawai\PengajuanController::index` | `PEGAWAI` | Form pengajuan berkas kenaikan 2 kolom |
| **POST** | `/pegawai/pengajuan/store` | `Pegawai\PengajuanController::store` | `PEGAWAI` | Submit usulan kenaikan + validasi 11 PDF |
| **GET** | `/pegawai/laporan` | `Pegawai\LaporanController::index` | `PEGAWAI` | Riwayat usulan & status verifikasi pribadi |
| **GET** | `/lampiran/view/(:num)` | `LampiranController::view/$1` | Auth (Semua Role) | Pratinjau berkas PDF langsung di peramban |
| **GET** | `/lampiran/download/(:num)` | `LampiranController::download/$1` | Auth (Semua Role) | Unduh berkas fisik dokumen PDF |
| **GET** | `/lampiran/list/(:num)` | `LampiranController::list/$1` | Auth (Semua Role) | Endpoint JSON daftar lampiran per pengajuan |

---

## 5. Struktur Modul & Entity

```
app/
├── Config/                 # Konfigurasi Routes, Database, Filters
├── Controllers/
│   ├── Admin/              # PegawaiController, PengajuanController, LaporanController
│   ├── Pejabat/            # PengajuanController, LaporanController
│   ├── Pegawai/            # PengajuanController, LaporanController
│   ├── AuthController.php
│   ├── DashboardController.php
│   └── LampiranController.php
├── Entities/
│   ├── User.php            # Strongly typed User entity (id, nip, password, role)
│   ├── Pegawai.php         # Strongly typed Pegawai entity (nama, nip, jabatan, masa_kerja)
│   ├── Pengajuan.php       # Strongly typed Pengajuan entity (jenis_kenaikan, status, catatan)
│   └── LampiranPengajuan.php # Strongly typed Lampiran entity (nama_dokumen, file_path)
├── Models/
│   ├── UserModel.php
│   ├── PegawaiModel.php
│   ├── PengajuanModel.php
│   └── LampiranPengajuanModel.php
├── Database/
│   ├── Migrations/         # Schema tabel version-controlled untuk PostgreSQL
│   └── Seeds/              # UserSeeder data inisialisasi
└── Views/
    ├── admin/              # Dashboard, Pegawai CRUD, Pengajuan list, Laporan
    ├── pejabat/            # Dashboard, Pengajuan review, Laporan
    ├── pegawai/            # Dashboard, Form Pengajuan 2-kolom, Laporan
    └── layouts/            # Template induk (Sidebar #2D6A4F, Card #E8F5E9)
```

---

## 6. Verifikasi Kualitas & Pengujian Mandiri

Setiap perubahan kode PHP dalam SIGAP diverifikasi menggunakan perintah otomasi:
```bash
composer check
```
Perintah ini menjalankan:
1. **PHPStan Level 5**: Memastikan konsistensi tipe data, integritas Entity, dan validitas pemanggilan method.
2. **PHPUnit 11**: Menjalankan seluruh *test suites* model, entity, dan controller assertions.

**Hasil Pemeriksaan**:
- PHPStan: `[OK] No errors (73/73 files)`
- PHPUnit: `16 / 16 tests passed (100%)`
