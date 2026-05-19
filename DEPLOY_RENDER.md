# Deploy gratis yang paling cocok: Render

Untuk proyek ini, opsi gratis yang paling masuk akal adalah Render, dengan catatan ini cocok untuk demo, tugas kuliah, atau hobby app. Ini bukan pilihan terbaik untuk production jangka panjang.

## Kenapa Render

- Mendukung web service gratis
- Mendukung deploy dari `Dockerfile`
- Mendukung database Postgres gratis
- Bisa memakai `render.yaml` agar web service dan database dibuat dari repo

## Batasan penting Render free

- Web service akan sleep setelah 15 menit idle
- Startup setelah sleep bisa butuh sekitar 1 menit
- Database Postgres gratis punya batas 1 GB
- Database Postgres gratis kedaluwarsa setelah 30 hari jika tidak di-upgrade

## File yang sudah disiapkan

- `Dockerfile`
- `.dockerignore`
- `render.yaml`

## Cara deploy

1. Login ke Render.
2. Pilih `New +` lalu `Blueprint`.
3. Hubungkan repo GitHub ini.
4. Render akan membaca `render.yaml` dan membuat:
   - web service `kidstation-web`
   - Postgres `kidstation-db`
5. Saat diminta, isi dua environment variable manual:
   - `APP_KEY`
   - `APP_URL`
   - `GOOGLE_CLIENT_ID`
   - `GOOGLE_CLIENT_SECRET`
   - `GOOGLE_REDIRECT_URI`

Render akan menjalankan migration melalui `preDeployCommand`, lalu startup container akan membuat storage link dan cache Laravel production.

## Nilai environment variable manual

### APP_KEY

Generate lokal dengan:

```powershell
php artisan key:generate --show
```

Lalu paste hasilnya ke `APP_KEY` di Render.

### APP_URL

Isi dengan domain Render Anda, misalnya:

```env
https://kidstation-web.onrender.com
```

### Google OAuth

Isi environment berikut di Render:

```env
GOOGLE_CLIENT_ID=ISI_DARI_GOOGLE_CLOUD
GOOGLE_CLIENT_SECRET=ISI_DARI_GOOGLE_CLOUD
GOOGLE_REDIRECT_URI=https://kidstation-web.onrender.com/auth/google/callback
```

Tambahkan URI callback yang sama di Google Cloud Console pada Authorized redirect URIs.

## Catatan database

Deploy gratis Render memakai PostgreSQL, jadi konfigurasi `render.yaml` sudah mengarahkan Laravel ke `DB_CONNECTION=pgsql` dan mengambil `DB_URL` langsung dari database Render.

Artinya:

- aplikasi utama bisa jalan dengan migration Laravel
- file SQL MySQL seperti `database_susu_lengkap.sql` tidak bisa langsung di-import ke Render Postgres tanpa konversi

Kalau Anda butuh data contoh itu ikut online, langkah paling aman adalah saya buatkan seeder Laravel berbasis data yang sama, bukan import SQL MySQL mentah.

## Kapan jangan pakai Render free

Jangan pilih Render free kalau Anda butuh:

- aplikasi selalu aktif tanpa sleep
- database gratis yang tidak kedaluwarsa 30 hari
- performa stabil untuk user nyata

## Sumber resmi

- Render free services: https://render.com/docs/free
- Render Blueprint spec: https://render.com/docs/blueprint-spec
- Render Docker deploy: https://render.com/docs/docker
