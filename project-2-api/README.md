# Panduan Menguji API NusantaraTrip dengan Bruno

## 1. Jalankan aplikasi

1. Buka Laragon dan klik **Start All** agar Apache dan MySQL aktif. Live Server tidak menjalankan PHP.
2. Pastikan database `nusantaratrip_db` sudah diimpor dari `database/nusantaratrip_db.sql`.
3. Buka Bruno. Buat collection baru, misalnya `NusantaraTrip API`.

Alamat endpoint untuk folder proyek ini:

```text
http://localhost/Nusantara%20Travel_UTS/project-2-api/api/packages.php
```

Jika nama folder di `C:\laragon\www` berbeda, ganti bagian `Nusantara%20Travel_UTS` dengan nama folder tersebut (spasi ditulis `%20`).

## 2. Membuat request di Bruno

Untuk setiap request:

1. Klik **New Request** di collection.
2. Beri nama request, lalu pilih method HTTP (GET, POST, PUT, atau DELETE).
3. Tempel alamat URL pada kolom URL.
4. Untuk POST dan PUT, buka tab **Body**, pilih **JSON**, lalu tempel JSON yang diberikan di bawah.
5. Klik **Send**. Status HTTP terlihat di area response; isi JSON tampil di bawahnya.

### A. Melihat semua paket — GET

- Method: `GET`
- URL:
  `http://localhost/Nusantara%20Travel_UTS/project-2-api/api/packages.php`
- Body: kosong
- Hasil yang diharapkan: status **200**; `data` berisi enam paket contoh.

### B. Melihat satu paket — GET detail

- Method: `GET`
- URL:
  `http://localhost/Nusantara%20Travel_UTS/project-2-api/api/packages.php?id=1`
- Body: kosong
- Hasil yang diharapkan: status **200**; `data` berisi paket dengan ID 1.

### C. Menambahkan paket — POST

- Method: `POST`
- URL: sama dengan GET semua
- Tab **Body** → **JSON**, tempel:

```json
{
  "destination_id": 1,
  "package_name": "Paket Uji Bruno",
  "category": "Family Holiday",
  "description": "Paket sementara untuk menguji endpoint POST.",
  "duration": "3 Hari 2 Malam",
  "price": 2500000,
  "quota": 10,
  "image": "https://example.com/bali.jpg",
  "itinerary": "Hari 1: Tiba. Hari 2: Wisata. Hari 3: Kembali.",
  "included_facilities": "Hotel dan transportasi lokal.",
  "excluded_facilities": "Tiket pesawat dan pengeluaran pribadi."
}
```

- Hasil yang diharapkan: status **201**. Pada response, catat `data.id`; ID itu dipakai untuk PUT dan DELETE.
- `destination_id: 1` menunjuk ke destinasi Bali pada data contoh.

### D. Mengubah paket — PUT

- Method: `PUT`
- URL: `http://localhost/Nusantara%20Travel_UTS/project-2-api/api/packages.php?id=ID_BARU`
- Ganti `ID_BARU` dengan angka `data.id` dari response POST.
- Tab **Body** → **JSON**: gunakan body POST di atas, lalu ubah misalnya `package_name` menjadi `"Paket Uji Bruno Diperbarui"`.
- Hasil yang diharapkan: status **200** dan nama paket yang baru terlihat pada `data`.

### E. Menghapus paket uji — DELETE

- Method: `DELETE`
- URL: `http://localhost/Nusantara%20Travel_UTS/project-2-api/api/packages.php?id=ID_BARU`
- Ganti `ID_BARU` dengan ID yang sama dari POST.
- Body: kosong
- Hasil yang diharapkan: status **200**. GET detail untuk ID tersebut setelahnya akan menghasilkan **404**.

Hapus hanya paket uji yang baru dibuat; jangan gunakan DELETE pada ID paket contoh (1–6) kecuali memang ingin menghapus data tersebut.

## 3. Bentuk response

Semua response menggunakan bentuk berikut:

```json
{
  "success": true,
  "message": "Paket wisata berhasil ditambahkan",
  "data": {}
}
```

Status yang mungkin muncul: **400** (request/ID salah), **404** (paket tidak ditemukan), **405** (method tidak didukung), **422** (data wajib tidak valid), atau **500** (masalah database). Jika muncul 500, pastikan MySQL aktif dan database sudah diimpor.

Simpan screenshot hasil request yang benar-benar dijalankan di `screenshots/postman/`. Jangan gunakan screenshot contoh sebagai bukti hasil pengujian.
