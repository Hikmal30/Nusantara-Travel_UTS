# NusantaraTrip

Sistem informasi perusahaan Travel & Tour untuk menjelajahi destinasi dan paket wisata domestik. Repository ini berisi website PHP Native dan REST API CRUD yang menggunakan database MySQL yang sama.

## Teknologi

- PHP Native 8.1+, PDO, dan session
- MySQL 8+ / MariaDB
- HTML5, CSS3, Bootstrap 5, JavaScript
- Bruno untuk pengujian API

## Fitur

**Project 1 — Website:** halaman beranda dengan pencarian, destinasi dan paket wisata dari database; detail paket berdasarkan ID; registrasi dan login; dashboard terproteksi; profil dan logout.

**Project 2 — REST API:** endpoint JSON untuk melihat daftar/detail paket dan membuat, memperbarui, serta menghapus paket.

## Struktur repository

```text
Nusantara Travel_UTS/
|-- config/database.php                 # PDO bersama untuk website dan API
|-- database/nusantaratrip_db.sql       # Skema dan data dummy
|-- project-1-website/                  # Website NusantaraTrip
|   |-- assets/{css,js,images}/
|   |-- config/
|   |-- includes/
|   |-- index.php, detail.php
|   |-- login.php, register.php
|   `-- dashboard.php, profile.php, logout.php
|-- project-2-api/                      # REST API
|   |-- api/packages.php
|   |-- controllers/PackageController.php
|   `-- models/Package.php
`-- screenshots/{website,postman}/      # Tempat screenshot hasil sendiri
```

Database terdiri dari `users`, `destinations`, dan `packages`. Setiap paket terhubung ke destinasi melalui `packages.destination_id` → `destinations.id`.

## Instalasi lokal dengan Laragon

1. Salin repository ke folder web server, misalnya `C:\xampp\htdocs\NusantaraTrip` atau `C:\laragon\www\Nusantara Travel_UTS`.
2. Jalankan Apache dan MySQL.
3. Buka phpMyAdmin, pilih **Import**, lalu impor `database/nusantaratrip_db.sql`. File SQL membuat database `nusantaratrip_db` dan memasukkan enam destinasi beserta enam paket dummy.
4. Konfigurasi default memakai host `127.0.0.1`, port `3306`, user `root`, dan password kosong. Atur environment variables `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, dan `DB_PASSWORD` jika pengaturan MySQL Anda berbeda.
5. Buka `http://localhost/Nusantara%20Travel_UTS/project-1-website/` (sesuaikan nama folder; spasi ditulis `%20`). Buat akun melalui **Daftar**, kemudian masuk menggunakan email/username dan password akun tersebut.

Halaman detail paket menggunakan alamat seperti `detail.php?id=1`. Foto pada contoh data memakai URL Unsplash, sehingga koneksi internet diperlukan untuk memuat gambar dan CDN Bootstrap.

## REST API

Endpoint: `http://localhost/Nusantara%20Travel_UTS/project-2-api/api/packages.php` (sesuaikan nama folder; spasi ditulis `%20`). Panduan terperinci untuk setiap method dan status respons ada di [project-2-api/README.md](./project-2-api/README.md).

| Method | URL | Keterangan |
|---|---|---|
| GET | `/api/packages.php` | Semua paket |
| GET | `/api/packages.php?id=1` | Detail paket |
| POST | `/api/packages.php` | Tambah paket |
| PUT | `/api/packages.php?id=1` | Ubah paket |
| DELETE | `/api/packages.php?id=1` | Hapus paket |

POST dan PUT menggunakan `Content-Type: application/json`. Body wajib berisi `destination_id`, `package_name`, `category`, `description`, `duration`, `price`, `quota`, `image`, `itinerary`, `included_facilities`, dan `excluded_facilities`. Contoh body:

```json
{
  "destination_id": 1,
  "package_name": "Paket Bali Contoh",
  "category": "Family Holiday",
  "description": "Perjalanan wisata di Bali.",
  "duration": "3 Hari 2 Malam",
  "price": 2500000,
  "quota": 10,
  "image": "https://example.com/bali.jpg",
  "itinerary": "Hari 1: Tiba. Hari 2: Wisata. Hari 3: Kembali.",
  "included_facilities": "Hotel dan transportasi lokal.",
  "excluded_facilities": "Tiket pesawat dan pengeluaran pribadi."
}
```

Respons menggunakan bentuk konsisten `success`, `message`, dan `data`. Uji setiap method menggunakan Postman/Bruno dan simpan screenshot nyata di `screenshots/postman/`; simpan screenshot halaman website di `screenshots/website/`. Screenshot belum disediakan atau diklaim sudah diuji.

## Identitas pengembang

UJIAN TENGAH SEMESTER
NAMA: HIKMAL FIRMANSYAH
KELAS: TIF RP 24 E CNS
NIM: 24552011129
MATA KULIAH: PEMROGRAMAN WEB 1
