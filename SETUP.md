# 🚀 Tutorial Setup Gadgetra — Dari Clone Sampai Tampil di Localhost

Panduan ini untuk anggota tim yang ingin menjalankan project **Gadgetra Laravel** di komputer masing-masing.

---

## ✅ Prasyarat — Install Dulu Sebelum Mulai

Pastikan semua software berikut sudah terinstall:

| Software | Versi | Download |
|---|---|---|
| **PHP** | 8.1+ | [laragon.org](https://laragon.org) |
| **MySQL** | 8.0+ | via Laragon |
| **Composer** | terbaru | [getcomposer.org](https://getcomposer.org) |
| **Git** | terbaru | [git-scm.com](https://git-scm.com) |
| **Node.js** | 18+ | [nodejs.org](https://nodejs.org) |

> 💡 **Rekomendasi**: Gunakan **Laragon** (Windows) — sudah include PHP, MySQL, Apache/Nginx, dan Composer sekaligus.

---

## 📥 Langkah 1 — Clone Repository

Buka terminal (CMD / PowerShell / Git Bash) lalu jalankan:

```bash
# Masuk ke folder web server kamu
# Jika pakai Laragon:
cd C:\laragon\www

# Jika pakai XAMPP:
cd C:\xampp\htdocs

# Clone repository
git clone https://github.com/zuhdiarif/gadgetra-laravel.git

# Masuk ke folder project
cd gadgetra-laravel
```

---

## 📦 Langkah 2 — Install Dependencies PHP

```bash
composer install
```

> ⏳ Proses ini membutuhkan waktu 1–3 menit pertama kali, tergantung kecepatan internet.

---

## ⚙️ Langkah 3 — Buat File `.env`

```bash
# Copy file contoh konfigurasi
copy .env.example .env

# Generate application key (wajib!)
php artisan key:generate
```

---

## 🛠️ Langkah 4 — Konfigurasi Database di `.env`

Buka file `.env` menggunakan text editor (VS Code, Notepad++, dll), cari dan ubah bagian ini:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gadgetra
DB_USERNAME=root
DB_PASSWORD=
```

> ⚠️ Jika MySQL kamu punya password, isi di `DB_PASSWORD`. Default Laragon biasanya kosong.

---

## 🗄️ Langkah 5 — Buat Database

Buka **phpMyAdmin** (biasanya di `http://localhost/phpmyadmin`) atau gunakan terminal:

```bash
# Via MySQL CLI
mysql -u root -p -e "CREATE DATABASE gadgetra CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

Atau lewat phpMyAdmin:
1. Klik **New** di sidebar kiri
2. Nama database: `gadgetra`
3. Collation: `utf8mb4_unicode_ci`
4. Klik **Create**

---

## 🌱 Langkah 6 — Jalankan Migrasi & Seeder

```bash
# Buat tabel + isi data awal (produk, admin, transaksi sample)
php artisan migrate --seed
```

> Ini akan membuat semua tabel dan mengisi ~400+ produk gadget beserta akun admin default.

---

## 🖼️ Langkah 7 — Buat Storage Link

```bash
php artisan storage:link
```

> Perintah ini membuat symlink dari `public/storage` ke `storage/app/public` agar file upload bisa diakses browser.

---

## 🌐 Langkah 8 — Jalankan Server

```bash
php artisan serve
```

Jika berhasil, terminal akan menampilkan:

```
INFO  Server running on [http://127.0.0.1:8000].
```

Buka browser dan akses: **http://127.0.0.1:8000**

---

## 🎉 Website Sudah Tampil!

Kamu akan melihat halaman utama Gadgetra dengan produk-produk gadget.

---

## 👤 Akun Default

| Role | Email | Password |
|---|---|---|
| **Admin** | `admin@gadgetra.com` | `admin123` |
| **User** | Buat akun baru via `/register` | - |

**Cara masuk Admin Panel:**
1. Login dengan akun admin
2. Klik ikon **Profile** di navbar kanan atas
3. Klik **Admin Panel**

---

## ❗ Troubleshooting — Masalah Umum

### Error: `php artisan` tidak dikenali
```bash
# Pastikan PHP sudah di PATH environment variable
# Coba cek dengan:
php --version
```
Jika belum, tambahkan folder PHP ke PATH, atau gunakan full path: `C:\laragon\bin\php\php8.x\php.exe artisan serve`

---

### Error: `SQLSTATE[HY000] [1049] Unknown database 'gadgetra'`
Database belum dibuat. Kembali ke **Langkah 5**.

---

### Error: `SQLSTATE[HY000] [1045] Access denied`
Username/password MySQL salah di `.env`. Periksa `DB_USERNAME` dan `DB_PASSWORD`.

---

### Error: `No application encryption key has been specified`
Belum generate APP_KEY. Jalankan:
```bash
php artisan key:generate
```

---

### Error: `Class "App\Http\Middleware\SecurityHeaders" not found`
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

---

### Halaman tampil tapi CSS/gambar tidak muncul
Pastikan kamu mengakses via `http://127.0.0.1:8000` (bukan file:///...).
Atau cek APP_URL di `.env`:
```env
APP_URL=http://localhost:8000
```

---

### Upload gambar tidak bekerja
```bash
php artisan storage:link
```
Pastikan folder `public/uploads` dan `public/assets/products` ada dan bisa ditulis.

---

## 🔄 Update — Cara Sync dengan Repository Terbaru

Jika ada update dari tim:

```bash
git pull origin main
composer install
php artisan migrate
php artisan config:clear
php artisan view:clear
php artisan cache:clear
```

---

## 📁 Struktur Folder Penting

```
gadgetra-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/     ← Logic control (thin)
│   │   └── Middleware/      ← EnsureIsAdmin, SecurityHeaders
│   └── Models/              ← Business logic (User, Product, Transaction)
├── config/                  ← Konfigurasi (session, auth, dll)
├── database/
│   ├── migrations/          ← Skema tabel DB
│   └── seeders/             ← Data awal
├── public/
│   ├── js/                  ← Frontend JS (booking flow, profil, auth)
│   ├── css/                 ← Stylesheet
│   └── assets/products/     ← Gambar produk
├── resources/views/         ← Template Blade (HTML)
├── routes/web.php           ← Semua URL mapping
├── .env                     ← Konfigurasi environment (JANGAN di-commit!)
└── gadgetra.txt             ← Dokumentasi arsitektur lengkap
```

---

## 💬 Butuh Bantuan?

Hubungi maintainer project atau baca dokumentasi lengkap di file [`gadgetra.txt`](gadgetra.txt) di root folder project.

---

*Tutorial ini dibuat untuk Gadgetra Laravel v2.0 — Mei 2026*
