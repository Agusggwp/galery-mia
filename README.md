# Dokumentasi Website Gallery Kelas (Laravel & Google Drive API)

Website **Gallery Kelas** modern yang dibangun menggunakan **Laravel 11**, **Blade Templates**, **Tailwind CSS**, **MySQL**, dan terintegrasi langsung dengan **Google Drive API v3**.

---

## 🌟 Fitur Utama

- **Zero Host Storage Overhead**: Seluruh berkas foto & video disimpan di Google Drive. Laravel hanya mengelola metadata dan menampilkan thumbnail & pemutar media.
- **Auto-Sync Google Drive API**:
  - Artisan Command: `php artisan gallery:sync`
  - Laravel Scheduler (terjadwal otomatis setiap jam)
  - Tombol manual sync di Admin Panel (`/admin/google-drive`)
- **Tampilan Public Modern & Responsive**:
  - Primary color: `#14433b` | Accent: `#21c9a4`
  - **Beranda**: Hero section, deskripsi kelas, statistik counter album/foto/video, album terbaru, & media highlight.
  - **Galeri Media**: Pencarian live, filter per album, filter tahun, filter tipe (Foto/Video), pagination.
  - **Halaman Album (`/album/{slug}`)**: Tampilan detail album dengan filter media internal.
  - **Interactive Lightbox Modal**: Preview foto resolusi tinggi & pemutar video (HTML5 / Google Drive embed) tanpa meninggalkan halaman.
- **Admin Panel Terproteksi (`/admin`)**:
  - Authentication khusus Admin (`/login`).
  - **Dashboard**: Statistik ringkasan, riwayat sync terakhir, & status koneksi.
  - **Album Management**: Edit nama, deskripsi, dan ubah status visibilitas (tampil/sembunyi).
  - **Media Management**: Pencarian & filter media, ubah visibilitas per foto/video.
  - **Google Drive Admin Page**: Monitor Folder ID, trigger manual sync, laporan error.
  - **Website Settings**: Mengatur nama kelas, deskripsi, logo, informasi footer, & Google Drive Folder ID.

---

## 🛠️ Persyaratan Sistem

- **PHP**: 8.2 atau lebih baru (dengan ekstensi `pdo_mysql`, `curl`, `json`, `mbstring`, `fileinfo`)
- **Composer**: v2.x
- **Node.js**: v18.x / v20.x / v24.x
- **Database**: MySQL 8.0+ atau MariaDB 10.4+

---

## 🚀 Cara Instalasi & Menjalankan Project

### 1. Clone / Siapkan Directory Project

```bash
cd c:\Users\AGUS\Documents\web
```

### 2. Install Dependency Composer & NPM

```bash
composer install
npm install
```

### 3. Konfigurasi File Environment (`.env`)

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Sesuaikan koneksi database MySQL di file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gallery_kelas
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Key Application & Build Asset Frontend

```bash
php artisan key:generate
npm run build
```

### 5. Jalankan Migration & Database Seeder

```bash
php artisan migrate:fresh --seed
```

> **Catatan Seeder**: Command ini akan otomatis membuat akun Admin default:
> - **URL Admin Login**: `http://localhost:8000/login`
> - **Email**: `admin@gallery.com`
> - **Password**: `password`

### 6. Jalankan Server Dev Laravel

```bash
php artisan serve
```

Akses website publik di browser: `http://localhost:8000`

---

## 🔑 Panduan Integrasi Google Drive API

### A. Cara Mendapatkan Folder ID Google Drive

1. Buka [Google Drive](https://drive.google.com).
2. Buat folder utama dengan nama **Gallery Kelas**.
3. Di dalam folder **Gallery Kelas**, buat subfolder untuk setiap **Album** (contoh: `Kunjungan Industri 2024`, `Pentas Seni`, dll.).
4. Masukkan file foto (`.jpg`, `.jpeg`, `.png`, `.webp`) atau video (`.mp4`, `.mov`) ke dalam subfolder album.
5. Buka folder utama **Gallery Kelas**, lalu perhatikan URL browser Anda:
   ```text
   https://drive.google.com/drive/folders/1ABC123xyz_ExampleFolderID
   ```
6. Kode acak di bagian akhir URL (`1ABC123xyz_ExampleFolderID`) adalah **Folder ID**.
7. Salin Folder ID tersebut dan masukkan ke dalam file `.env`:
   ```env
   GOOGLE_DRIVE_FOLDER_ID=1ABC123xyz_ExampleFolderID
   ```
   *Atau masukkan melalui halaman Admin Panel di `/admin/settings`.*

---

### B. Cara Mendapatkan Google Drive API Credentials

Anda dapat memilih salah satu dari 2 metode autentikasi berikut:

#### Metode 1: Service Account (Sangat Direkomendasikan)
1. Buka [Google Cloud Console](https://console.cloud.google.com/).
2. Buat Project Baru (misal: `Gallery Kelas Project`).
3. Buka menu **APIs & Services** > **Library**, cari **Google Drive API** dan klik **Enable**.
4. Buka menu **APIs & Services** > **Credentials** > **Create Credentials** > **Service Account**.
5. Isi nama Service Account, lalu klik **Create and Continue**.
6. Klik Service Account yang telah dibuat, buka tab **Keys** > **Add Key** > **Create new key** (pilih tipe **JSON**).
7. Simpan file JSON yang terdownload ke direktori project Anda: `storage/app/google-service-account.json`.
8. Buka file JSON tersebut, salin alamat email service account (contoh: `gallery-bot@project-id.iam.gserviceaccount.com`).
9. Buka Google Drive Anda, klik kanan folder utama **Gallery Kelas** > **Share (Bagikan)**, lalu tambahkan email service account tersebut sebagai **Viewer (Penglihat)**.
10. Isikan path file JSON di `.env`:
    ```env
    GOOGLE_SERVICE_ACCOUNT_JSON=storage/app/google-service-account.json
    ```

#### Metode 2: OAuth 2.0 Client ID
1. Di Google Cloud Console, buka **APIs & Services** > **OAuth consent screen**, pilih **External**, lalu lengkapi info dasar.
2. Buka menu **Credentials** > **Create Credentials** > **OAuth client ID**.
3. Pilih Application type: **Web application**.
4. Tambahkan Authorized redirect URIs: `http://localhost:8000/auth/google/callback`.
5. Salin Client ID dan Client Secret ke `.env`:
   ```env
   GOOGLE_CLIENT_ID=your_client_id.apps.googleusercontent.com
   GOOGLE_CLIENT_SECRET=your_client_secret
   ```

---

## 🔄 Cara Menjalankan Sinkronisasi Google Drive

### 1. Manual via Artisan Command (Terminal)

```bash
php artisan gallery:sync
```

### 2. Manual via Web Admin Panel
Buka menu `/admin/google-drive` pada browser, lalu klik tombol **Sinkronkan Sekarang**.

### 3. Otomatis via Laravel Scheduler
Jalankan scheduler Laravel di server / environment Anda:

```bash
php artisan schedule:run
```

Atau di server produksi Linux (Cron Job):
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 📁 Struktur Project Overview

```text
app/
├── Console/
│   └── Commands/
│       └── SyncGalleryCommand.php       # Command artisan gallery:sync
├── Http/
│   ├── Controllers/
│   │   ├── HomeController.php          # Controller Beranda publik
│   │   ├── GalleryController.php       # Controller Galeri publik (search/filter/page)
│   │   ├── AlbumController.php         # Controller Detail album
│   │   └── Admin/
│   │       ├── AuthController.php      # Autentikasi Admin Login/Logout
│   │       ├── DashboardController.php # Ringkasan statistik & sync status
│   │       ├── AlbumController.php     # Manajemen album & visibilitas
│   │       ├── MediaController.php     # Manajemen media & visibilitas
│   │       ├── GoogleDriveController.php # Status & trigger sync Google Drive
│   │       └── SettingController.php   # Pengaturan identitas website & Folder ID
├── Models/
│   ├── Album.php                       # Model Album & relasi
│   ├── Media.php                       # Model Media foto/video & relasi
│   └── Setting.php                     # Model Setting key-value
└── Services/
    └── GoogleDriveService.php          # Service integrasi Google Drive API v3

resources/
├── views/
│   ├── layouts/
│   │   ├── app.blade.php               # Layout publik
│   │   └── admin.blade.php             # Layout admin panel
│   ├── components/
│   │   ├── navbar.blade.php            # Navbar publik
│   │   ├── footer.blade.php            # Footer publik
│   │   └── lightbox.blade.php          # Modal Preview foto & video player
│   ├── home.blade.php                  # Halaman Utama (Beranda)
│   ├── gallery.blade.php               # Halaman Galeri Foto & Video
│   ├── album.blade.php                 # Halaman Detail Album
│   └── admin/
│       ├── login.blade.php             # Form Login Admin
│       ├── dashboard.blade.php         # Dashboard Admin
│       ├── google_drive.blade.php      # Halaman Sync Google Drive
│       ├── settings.blade.php          # Pengaturan Website
│       ├── albums/                     # Manajemen Album
│       └── media/                      # Manajemen Media
```
# galery-mia
