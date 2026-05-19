# Deploy Kidstation ke Railway

Proyek ini paling cocok dideploy sebagai aplikasi Laravel biasa dengan satu service web dan satu database MySQL managed di Railway.

## Kenapa Railway

- Cocok untuk Laravel + PHP + MySQL
- Mendukung deploy berbasis `Dockerfile`
- Mendukung `preDeployCommand` untuk `php artisan migrate --force`
- Bisa memakai healthcheck bawaan Laravel di `/up`

## File yang sudah disiapkan

- `Dockerfile`
- `.dockerignore`
- `railway.toml`

## Langkah deploy

1. Push repo ini ke GitHub.
2. Buat project baru di Railway.
3. Tambahkan service `MySQL`.
4. Tambahkan service `GitHub Repo` dan pilih repo ini.
5. Railway akan membaca `Dockerfile` dan `railway.toml`.
6. Setelah service web dan MySQL terhubung, isi environment variables di service web.

## Environment variables untuk service web

Set minimal variabel berikut di Railway:

```env
APP_NAME=Kidstation
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-domain.up.railway.app
APP_KEY=base64:GENERATE_DI_LOCAL_DENGAN_PHP_ARTISAN_KEY_GENERATE_SHOW

LOG_CHANNEL=stderr
LOG_LEVEL=info

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
RUN_MIGRATIONS=false

GOOGLE_CLIENT_ID=ISI_DARI_GOOGLE_CLOUD
GOOGLE_CLIENT_SECRET=ISI_DARI_GOOGLE_CLOUD
GOOGLE_REDIRECT_URI=https://your-app-domain.up.railway.app/auth/google/callback
```

Catatan:

- Nama service database diasumsikan `MySQL`. Jika nanti nama service di Railway berbeda, ganti namespace referensinya mengikuti nama service itu.
- `APP_KEY` bisa dibuat lokal dengan:

```powershell
php artisan key:generate --show
```

## Setelah deploy

- Railway akan menjalankan healthcheck ke `/up`
- Railway akan menjalankan migration sebelum service start lewat `preDeployCommand`
- Container akan membuat storage link dan cache Laravel production saat start
- Jika domain publik Railway sudah aktif, ubah `APP_URL` agar sesuai domain final
- Di Google Cloud Console, tambahkan redirect URI yang sama dengan `GOOGLE_REDIRECT_URI`

## Import data awal

Jika Anda ingin data contoh ikut masuk ke production, import `database_susu_lengkap.sql` secara manual ke database Railway setelah database selesai dibuat.

## Catatan penting

- File `vercel.json` yang ada di repo bukan target deploy yang saya rekomendasikan untuk proyek ini.
- Untuk kebutuhan saat ini, service worker terpisah belum saya tambahkan karena kode aplikasi belum menunjukkan job queue yang wajib berjalan terus.
