# Hubin (Hubungan Industri) Management System

Aplikasi manajemen Hubungan Industri (Hubin) berbasis **Laravel 13** dan **Filament PHP** untuk pengelolaan Praktik Kerja Lapangan (PKL), kemitraan industri, serta data siswa dan guru. Sistem ini disiapkan untuk mendukung eksekusi berbasis lingkungan lokal tradisional maupun kontainerisasi **Docker** berkinerja tinggi.

---

## 📋 Daftar Isi

- [Tech Stack](#-tech-stack)
- [Fitur & Struktur Migrasi Database](#-fitur--struktur-migrasi-database)
- [Panduan Instalasi Lokal (Non-Docker)](#-panduan-instalasi-lokal-non-docker--standard)
- [Arsitektur & Konfigurasi Docker](#-arsitektur--konfigurasi-docker-recommended)
- [Migrasi Data & Reset Sequence PostgreSQL](#-panduan-migrasi-data--reset-sequence-postgresql)
- [Deployment ke Production (VPS)](#-panduan-deployment-ke-production-vps)

---

## 🛠 Tech Stack

- **Framework**: Laravel 13 & Filament PHP
- **Runtime**: PHP 8.4 (Alpine-based)
- **Database**: PostgreSQL 16
- **Web Server**: Nginx (Alpine-based)
- **Containerization**: Docker & Docker Compose

---

## 🚀 Fitur & Struktur Migrasi Database

Aplikasi ini mencakup modul utama yang dikelola melalui migrasi database berikut:

| Kategori | Tabel Database | Deskripsi Fungsi |
| :--- | :--- | :--- |
| **Autentikasi & Akses** | `users`, `roles`, `permissions` | Autentikasi user & manajemen RBAC (Spatie Permission) |
| **Master Data** | `kompetensi_keahlians`, `kelas`, `gurus`, `siswas` | Data pendidik, peserta didik, serta keahlian |
| **Kemitraan Industri** | `industris`, `periode_pkls`, `lowongan_pkls` | Data perusahaan mitra dan pembukaan lowongan PKL |
| **Operasional PKL** | `pendaftaran_pkls`, `penempatan_pkls` | Pengajuan dan pemetaan penempatan siswa PKL |
| **Jurnal & Evaluasi** | `jurnal_pkls` | Log harian kegiatan PKL & pembiasaan budaya kerja |

---

## 💻 Panduan Instalasi Lokal (Non-Docker / Standard)

Gunakan metode ini jika Anda ingin menjalankan aplikasi langsung menggunakan PHP & PostgreSQL yang terinstall di OS lokal (Windows/Linux/Mac).

### 1. Prasyarat
- PHP >= 8.4
- Composer 2.x
- PostgreSQL 16
- Node.js & NPM (Opsional untuk kompilasi aset)

### 2. Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/username/hubinapp.git
cd hubinapp

# 2. Install dependensi PHP
composer install

# 3. Salin file environment
cp .env.example .env

# 4. Generate Application Key
php artisan key:generate
```

Sesuaikan konfigurasi database di `.env` lokal:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=hubin
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

```bash
# 5. Jalankan migrasi dan seeder
php artisan migrate --seed

# 6. Buat storage link
php artisan storage:link

# 7. (Opsional) Install & compile asset frontend
npm install
npm run dev

# 8. Jalankan server lokal
php artisan serve
```

Aplikasi dapat diakses di `http://localhost:8000`.

---

## 🐳 Arsitektur & Konfigurasi Docker (Recommended)

Project ini menggunakan arsitektur Docker berbasis Alpine Linux yang ringan dan optimal, memisahkan web server, runtime PHP, dan database ke dalam container yang saling terisolasi.

### 1. Struktur File & Folder Docker

Sistem kontainerisasi dibangun menggunakan struktur direktori berikut:

```text
hubinapp/
├── docker/
│   └── nginx/
│       └── app.conf         # Konfigurasi Virtual Host Nginx khusus untuk Laravel
├── Dockerfile               # Blueprint image PHP 8.4-FPM (Alpine) beserta ekstensi (pgsql, gd, dll)
├── docker-compose.yml       # Orkestrasi 3 service utama: app (PHP), web (Nginx), dan db (PostgreSQL)
├── .env                     # Config environment OS Host lokal (untuk Tinker/Artisan lokal)
└── .env.docker              # Config environment runtime container (otomatis me-replace .env di Docker)
```

**Penjelasan Service (`docker-compose.yml`):**

- **Service `app`**: Menjalankan aplikasi Laravel via PHP-FPM. Direktori lokal di-mount (sinkronisasi dua arah) sehingga perubahan kode (live reload) langsung terbaca tanpa perlu rebuild. File `.env.docker` otomatis terpetakan sebagai `.env` di dalam container.
- **Service `web`**: Berperan sebagai Reverse Proxy dan melayani file statis, meneruskan request PHP ke service `app` melalui port 9000.
- **Service `db`**: Database PostgreSQL dengan penyimpanan persisten (`postgres_data` volume). Port diakses dari host menggunakan `5433` agar tidak konflik dengan PostgreSQL instalasi Windows lokal (`5432`).

### 2. Konfigurasi Dual Environment

Sistem ini menggunakan teknik Volume Mapping agar Anda tidak perlu mengubah file `.env` berulang kali saat berpindah dari eksekusi lokal Windows ke Docker.

Salin template environment untuk Docker:

```bash
cp .env.example .env.docker
```

Sesuaikan nilai database pada `.env.docker`:

```env
APP_ENV=local
DB_CONNECTION=pgsql
DB_HOST=db           # Gunakan nama service 'db' (bukan 127.0.0.1)
DB_PORT=5432         # Gunakan port internal Docker
DB_DATABASE=hubin
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### 3. Menjalankan Container

Jalankan perintah berikut di terminal root project Anda:

```bash
# Build custom image (PHP 8.4) dan jalankan seluruh service di background
docker-compose up -d --build

# Periksa status container (pastikan statusnya 'Up')
docker-compose ps
```

Setelah berjalan, aplikasi dapat diakses di:

- **Web Browser**: `http://localhost`
- **Database DBeaver/Adminer**: `localhost:5433` (dari laptop/host)

---

## 🔄 Panduan Migrasi Data & Reset Sequence PostgreSQL

Jika Anda memindahkan data dari database MySQL lama ke PostgreSQL Docker (misalnya menggunakan fitur Data Transfer di DBeaver), PostgreSQL memerlukan sinkronisasi ulang ID sequence (auto-increment).

### Masalah

Setelah impor data mentah selesai, pembuatan data baru via aplikasi akan gagal dengan error `SQLSTATE[23505]: Unique violation: ERROR: duplicate key value`.

### Solusi: Reset Sequence via Tinker

Masuk ke terminal Tinker di dalam container Docker:

```bash
docker-compose exec app php artisan tinker
```

Jalankan skrip pemulihan sequence berikut yang aman untuk tabel kosong maupun tabel bernilai ID non-numerik (seperti `sessions`):

```php
$tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema='public' AND table_type='BASE TABLE'");

foreach ($tables as $table) {
    $tableName = $table->table_name;

    if (Schema::hasColumn($tableName, 'id')) {
        $seqResult = DB::selectOne("SELECT pg_get_serial_sequence(?, 'id') as seq", [$tableName]);

        if ($seqResult && $seqResult->seq) {
            $seq = $seqResult->seq;
            $maxId = (int) (DB::table($tableName)->max('id') ?? 0);

            if ($maxId > 0) {
                DB::statement("SELECT setval(?, ?, true)", [$seq, $maxId]);
                echo "Reset $seq to $maxId\n";
            } else {
                DB::statement("SELECT setval(?, 1, false)", [$seq]);
                echo "Reset $seq (tabel $tableName kosong, ID berikutnya = 1)\n";
            }
        }
    }
}
```

---

## 🚢 Panduan Deployment ke Production (VPS)

### 1. Persiapan Server VPS

- Pastikan VPS (Ubuntu 22.04 / 24.04 LTS disarankan) sudah terinstall Git, Docker Engine, dan Docker Compose Plugin.
- Konfigurasi Firewall (UFW) untuk membuka port 80 (HTTP) dan 443 (HTTPS).

### 2. Steps Deployment

```bash
# 1. Clone repository di server
git clone https://github.com/username/hubinapp.git /var/www/hubinapp
cd /var/www/hubinapp

# 2. Buat file .env.docker untuk production
cp .env.example .env.docker
nano .env.docker
# Ubah: APP_ENV=production, APP_DEBUG=false, sesuaikan DB_PASSWORD & APP_URL

# 3. Jalankan container production
docker-compose up -d --build

# 4. Jalankan migrasi & optimasi cache di dalam container
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```