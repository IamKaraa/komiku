

# Komiku — Platform Webcomic Interaktif

**Komiku** adalah platform webcomic berbasis Laravel yang dirancang untuk memberikan pengalaman membaca komik digital yang nyaman sekaligus menjadi wadah terstruktur bagi kreator lokal untuk mengunggah dan mempublikasikan karya mereka. Fokus: aksesibilitas pembaca, manajemen konten kreator, dan dashboard admin untuk kurasi serta analitik ringan. 

![WhatsApp Image 2025-12-09 at 14 01 41_1bb7f5e8](https://github.com/user-attachments/assets/6c95545d-bcdb-4a16-8c1d-782fa2bd3372)
![WhatsApp Image 2025-12-09 at 14 01 20_8eedf819](https://github.com/user-attachments/assets/4f1bbf25-8630-4d69-90b8-3334e75c08f4)
![WhatsApp Image 2025-12-09 at 14 10 51_108aff39](https://github.com/user-attachments/assets/9c5d8d1f-2230-47b2-ae3c-655909270190)
![WhatsApp Image 2025-12-09 at 14 20 02_1adeaccc](https://github.com/user-attachments/assets/e634fc38-d33f-4c80-9044-14a53a0bbae1)


---

## Fitur Utama

* Halaman baca komik responsif (reader)
* Studio kreator: unggah komik, buat episode, kelola konten
* Dashboard admin: review/approve komik, kelola data master (users, genres)
* Sistem rekomendasi dasar (berbasis usia & genre favorit)
* Sistem komentar, rating, dan kontrol usia untuk konten dewasa

---

## Teknologi

* Framework: **Laravel 12**. 
* Bahasa: **PHP >= 8.3**. 
* Database: **PostgreSQL** (utama). 
* Templating: **Blade**
* Storage: **Laravel Storage** (gambar cover & halaman)
* Autentikasi (web): Laravel auth (email verification, hashed password)
* Note: API internal pada laporan menyebut penggunaan JWT — namun dokumentasi endpoint tidak disertakan di sini. 

---

## Struktur Repository (disarankan)

```
/app
  /Http
  /Models
/config
/database
  /migrations
  /seeders
/public
/resources
  /views
  /css
  /js
/routes
  web.php
  api.php  <-- (boleh ada, tapi dokumentasinya tidak termasuk di README)
.env.example
README.md
```

---

## Persiapan (Instalasi Lokal)

Berikut merupakan langkah langkah menjalankan project Komiku :

1. Clone repo

   ```bash
   git clone https://github.com/IamKaraa/komiku.git
   cd komiku
   ```

2. Install dependency PHP

   ```bash
   composer install
   ```

3. Salin file environment dan sesuaikan

   ```bash
   cp .env.example .env
   ```

   Edit `.env` (contoh pengaturan penting):

   ```
   APP_NAME=Komiku
   APP_ENV=local
   APP_KEY= (jalankan php artisan key:generate)
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=komiku_db
   DB_USERNAME=komiku_user
   DB_PASSWORD=secret
   FILESYSTEM_DRIVER=public
   ```

4. Buat key aplikasi

   ```bash
   php artisan key:generate
   ```

5. Migrasi dan seeder (jika tersedia)

   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. Jalankan storage link (untuk akses file gambar)

   ```bash
   php artisan storage:link
   ```

7. Jalankan server lokal

   ```bash
   php artisan serve
   ```

   Akses: `http://127.0.0.1:8000`

---

## Database — Tabel Utama (ringkasan)

Project Komiku memakai beberapa tabel inti; migrasi tersedia di folder `database/migrations`:

* `users` (role: reader, creator, admin; email_verified_at; status aktif/nonaktif)
* `genres`
* `comics` (judul, deskripsi, thumbnail, status_approval)
* `comic_genre` (pivot)
* `chapters`
* `chapter_images` (halaman/gambar per chapter)
* `ratings`, `comments`, `favorite_genres` 

---

## Peran Pengguna & Otorisasi

* **Admin**: manage genre, user, approve/decline komik, lihat statistik.
* **Creator**: upload komik, buat/edit/delete chapter, submit untuk review.
* **User/Pembaca**: membaca komik, beri rating & komentar, pilih genre favorit.
  Akses dibatasi melalui middleware role-based (`auth`, `isAdmin`, `isCreator`). 

---
