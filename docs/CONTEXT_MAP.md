# SIGAP Architecture & AI Context Map (CONTEXT_MAP.md)

Dokumen ini adalah **Peta Konteks (Context Map)** berbasis prinsip *Domain-Driven Design (DDD)* untuk memandu agen AI (Antigravity, Cursor, Claude, Copilot) dan pengembang dalam menavigasi, memahami batas-batas domain (*bounded contexts*), relasi antar komponen, serta berkas yang harus disentuh saat melakukan penambahan atau perubahan fitur.

---

## 1. Diagram Peta Konteks Domain (Bounded Context Map)

```mermaid
flowchart TD
    subgraph AuthContext["1. Identity & Access Context (Auth)"]
        UsersTable[("Tabel: users")]
        UserEntity["Entity: User"]
        UserModel["Model: UserModel"]
        AuthController["Controller: AuthController"]
        Filters["Filters: AuthFilter & RoleFilter"]
    end

    subgraph EmployeeContext["2. Employee Master Data Context"]
        PegawaisTable[("Tabel: pegawais")]
        PegawaiEntity["Entity: Pegawai"]
        PegawaiModel["Model: PegawaiModel"]
        AdminPegawaiController["Controller: Admin/PegawaiController"]
    end

    subgraph ProgressionContext["3. Promotion & Salary Progression Context"]
        PengajuansTable[("Tabel: pengajuans")]
        PengajuanEntity["Entity: Pengajuan"]
        PengajuanModel["Model: PengajuanModel"]
        PegawaiPengajuanController["Controller: Pegawai/PengajuanController"]
        AdminPengajuanController["Controller: Admin/PengajuanController"]
        PejabatPengajuanController["Controller: Pejabat/PengajuanController"]
    end

    subgraph DocumentContext["4. Document & Attachment Storage Context"]
        LampiranTable[("Tabel: lampiran_pengajuans")]
        LampiranEntity["Entity: LampiranPengajuan"]
        LampiranModel["Model: LampiranPengajuanModel"]
        LampiranController["Controller: LampiranController"]
        FileStorage["Storage: writable/uploads/lampiran/"]
    end

    subgraph ReportingContext["5. Audit & Reporting Context"]
        LaporanControllers["Controllers: Admin/Pejabat/Pegawai LaporanController"]
        DashboardController["Controller: DashboardController"]
    end

    %% Relasi antar context
    AuthContext -- "[Upstream: User ID & Session]" --> EmployeeContext
    AuthContext -- "[Guard: RBAC Session]" --> ProgressionContext
    AuthContext -- "[Guard: RBAC Session]" --> ReportingContext

    EmployeeContext -- "[Customer/Supplier: Pegawai Profil]" --> ProgressionContext
    
    ProgressionContext -- "[Upstream: Pengajuan ID]" --> DocumentContext
    ProgressionContext -- "[Data Source: Rekap Putusan]" --> ReportingContext
    EmployeeContext -- "[Data Source: Master Pegawai]" --> ReportingContext
```

---

## 2. Definisi & Batasan Domain (Bounded Contexts)

### A. Identity & Access Context (`App\Entities\User`)
* **Tanggung Jawab**: Autentikasi kredensial (NIP & bcrypt password), manajemen sesi (`isLoggedIn`, `role`), dan proteksi rute (`AuthFilter`, `RoleFilter`).
* **Hubungan**: Bertindak sebagai *Upstream* bagi seluruh konteks lainnya. Setiap interaksi pengguna harus divalidasi oleh konteks ini.
* **Tabel Terkait**: `users`.

### B. Employee Master Data Context (`App\Entities\Pegawai`)
* **Tanggung Jawab**: Pengelolaan profil pegawai (Nama, NIP, Jabatan/Golongan, Masa Kerja). Hanya dapat dimanipulasi (*write*) oleh Administrator.
* **Hubungan**: Menyediakan data identitas bagi *Progression Context* saat pengajuan dibuat.
* **Tabel Terkait**: `pegawais` (berelasi 1:1 dengan `users`).

### C. Promotion & Salary Progression Context (`App\Entities\Pengajuan`)
* **Tanggung Jawab**: Inti bisnis SIGAP. Menangani alur pembuatan pengajuan kenaikan pangkat / kenaikan gaji berkala oleh Pegawai, telaah dan persetujuan/penolakan oleh Pejabat Penilai, serta monitoring oleh Administrator.
* **Hubungan**: *Downstream* dari Pegawai, dan *Upstream* bagi Dokumen Lampiran.
* **Tabel Terkait**: `pengajuans`.

### D. Document & Attachment Storage Context (`App\Entities\LampiranPengajuan`)
* **Tanggung Jawab**: Upload aman berkas PDF (11 jenis dokumen usulan), validasi tipe MIME & ukuran (maks 2MB), penamaan acak terenkripsi, penyimpanan fisik di disk (`writable/uploads/lampiran/`), serta streaming pratinjau inline di peramban.
* **Hubungan**: Dimiliki secara mutlak oleh *Progression Context* (Cascading Delete: jika pengajuan dihapus, berkas fisik dan record di database ikut terhapus).
* **Tabel Terkait**: `lampiran_pengajuans`.

### E. Audit & Reporting Context
* **Tanggung Jawab**: Agregasi data untuk metrik Dashboard (KPI cards) dan rekapitulasi riwayat pengajuan yang sudah diputuskan (*Disetujui* / *Ditolak*), serta riwayat transparan untuk Pegawai.
* **Hubungan**: *Read-only Consumer* dari konteks Pegawai dan Progression.

---

## 3. Matriks Navigasi Cepat AI (AI Touchpoint Matrix)

Gunakan tabel ini sebagai kompas instan: **"Jika ingin mengubah fitur X, berkas apa saja yang harus disentuh?"**

| Kebutuhan Fitur / Perubahan | Controller | Model & Entity | Views | Database / Migration |
| :--- | :--- | :--- | :--- | :--- |
| **Ubah Logika Login / Role / Sesi** | `app/Controllers/AuthController.php` | `UserModel.php`<br>`User.php` | `app/Views/auth/login.php` | `2026-10-17-041002_CreateUsersTable.php` |
| **Proteksi Hak Akses Rute Baru** | `app/Config/Routes.php` | `app/Filters/RoleFilter.php`<br>`AuthFilter.php` | - | - |
| **CRUD Data Pegawai (Admin)** | `app/Controllers/Admin/PegawaiController.php` | `PegawaiModel.php`<br>`Pegawai.php` | `app/Views/admin/pegawai/index.php` | `2026-10-17-041016_CreatePegawaisTable.php` |
| **Form Usulan Pegawai (+Slot Berkas)**| `app/Controllers/Pegawai/PengajuanController.php` | `PengajuanModel.php`<br>`Pengajuan.php` | `app/Views/pegawai/pengajuan/create.php` | `2026-10-17-041017_CreatePengajuansTable.php` |
| **Review / Setujui / Tolak Usulan** | `app/Controllers/Pejabat/PengajuanController.php` | `PengajuanModel.php`<br>`Pengajuan.php` | `app/Views/pejabat/pengajuan/index.php` | - |
| **Upload, Pratinjau & Unduh PDF** | `app/Controllers/LampiranController.php` | `LampiranPengajuanModel.php`<br>`LampiranPengajuan.php` | Modal PDF di `Views/admin/`, `pejabat/`, `pegawai/` | `2026-10-17-041018_CreateLampiranPengajuansTable.php` |
| **Laporan & Filter Hasil Keputusan** | `app/Controllers/*/LaporanController.php` | `PengajuanModel.php` | `app/Views/*/laporan/index.php` | - |
| **Kartu Statistik Dashboard** | `app/Controllers/DashboardController.php` | `PegawaiModel.php`<br>`PengajuanModel.php` | `app/Views/*/dashboard.php` | - |
| **Perubahan Warna / Sidebar / Layout**| - | - | `app/Views/layouts/main.php` | - |

---

## 4. Diagram Alur Data Lintas Konteks (Cross-Context Data Flow)

### Skenario: Pegawai Mengajukan Kenaikan Gaji / Pangkat
```mermaid
sequenceDiagram
    autonumber
    actor P as Pegawai
    participant C as Pegawai\PengajuanController
    participant AM as PegawaiModel
    participant PM as PengajuanModel
    participant LM as LampiranPengajuanModel
    participant FS as File Storage (writable/)

    P->>C: GET /pegawai/pengajuan
    C->>AM: getPegawaiByUserId(session.user_id)
    AM-->>C: Profil Pegawai (Nama, NIP, Jabatan, Masa Kerja)
    C-->>P: Render Form 2-Kolom (Data Pre-filled)

    P->>C: POST /pegawai/pengajuan/store (Jenis Kenaikan + 11 PDF Files)
    C->>C: Validasi MIME (PDF) & Ukuran (<= 2MB)
    C->>PM: TransBegin() -> Insert Pengajuan (status: 'Menunggu')
    PM-->>C: Return pengajuan_id
    loop Untuk Setiap Berkas PDF Terunggah
        C->>FS: Move file ke writable/uploads/lampiran/{random_name}.pdf
        C->>LM: Insert Lampiran (pengajuan_id, nama_dokumen, file_path)
    end
    C->>PM: TransCommit()
    C-->>P: Redirect ke /pegawai/pengajuan dengan Flashdata Success
```

---

## 5. Protokol Pengambilan Keputusan untuk AI Agent

Saat AI diminta melakukan tugas baru di codebase ini, AI **wajib** mengikuti alur keputusan berikut:

```mermaid
graph TD
    A[Menerima Permintaan Pengguna] --> B[Identifikasi Bounded Context terkait]
    B --> C[Konsultasi docs/CONTEXT_MAP.md & docs/FEATURES.md]
    C --> D[Apakah memerlukan tabel/kolom baru?]
    D -- Ya --> E[Buat Migration baru via php spark make:migration]
    D -- Tidak --> F[Gunakan Entity yang sudah ada]
    E --> G[Update Model & Entity bertipe ketat]
    F --> G
    G --> H[Implementasi Slim Controller & Tailwind View]
    H --> I[Jalankan Self-Healing Loop: composer check]
    I --> J{Lolos PHPStan & PHPUnit?}
    J -- Gagal --> K[Perbaiki error / typo / tipe data]
    K --> I
    J -- Berhasil --> L[Sajikan hasil akhir ke pengguna]
```

> **Aturan Portabilitas Dokumentasi**: Setiap referensi berkas dalam dokumentasi wajib menggunakan path relatif (contoh: `docs/FEATURES.md` atau `app/Controllers/AuthController.php`), dilarang menggunakan path absolut mesin lokal agar proyek tetap kompatibel di semua perangkat.

