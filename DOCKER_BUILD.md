# Dokumentasi Build Docker Kidstation

Dokumen ini menjelaskan cara membangun dan menjalankan aplikasi Kidstation menggunakan Docker. Project ini adalah aplikasi Laravel 12 dengan asset frontend yang dibuild memakai Vite.

## Ringkasan Dockerfile

Dockerfile project ini memakai multi-stage build:

1. Stage `assets`
   - memakai image `node:22-bookworm-slim`
   - menjalankan `npm ci`
   - menyalin folder `resources`, `public`, dan `vite.config.js`
   - menjalankan `npm run build`
   - menghasilkan asset production di `public/build`

2. Stage runtime
   - memakai image `php:8.2-cli-bookworm`
   - menginstall extension PHP: `pdo_mysql`, `pdo_pgsql`, `pdo_sqlite`, `mbstring`, `intl`, dan `zip`
   - menginstall dependency Composer tanpa package development
   - menyalin source Laravel
   - menyalin asset hasil build dari stage `assets`
   - menjalankan startup script `docker/start.sh`, lalu aplikasi dengan `php artisan serve`

Port default container adalah `8080`.

## Prasyarat

Pastikan sudah terinstall:

- Docker Desktop
- Git
- akses terminal PowerShell atau terminal lain

Cek Docker:

```powershell
docker --version
docker compose version
```

## File Penting

- `Dockerfile`: instruksi build image aplikasi
- `.dockerignore`: daftar file/folder yang tidak ikut masuk context Docker
- `composer.json` dan `composer.lock`: dependency PHP Laravel
- `package.json` dan `package-lock.json`: dependency frontend Vite
- `.env.example`: contoh environment variable
- `railway.toml`: konfigurasi deploy Railway
- `render.yaml`: konfigurasi deploy Render
- `DEPLOY_VERCEL.md`: catatan deploy Vercel dan Google OAuth

## Build Image Lokal

Jalankan dari root project:

```powershell
docker build -t kidstation:local .
```

Jika ingin memastikan build benar-benar bersih tanpa cache:

```powershell
docker build --no-cache -t kidstation:local .
```

## Menjalankan Container

Contoh menjalankan container pada port lokal `8080`:

```powershell
docker run --rm -p 8080:8080 --env-file .env kidstation:local
```

Buka aplikasi:

```text
http://localhost:8080
```

Jika ingin memakai port lokal lain, misalnya `8081`:

```powershell
docker run --rm -p 8081:8080 --env-file .env kidstation:local
```

Lalu buka:

```text
http://localhost:8081
```

## Environment Variable Minimal

Untuk production atau container runtime, siapkan environment berikut:

```env
APP_NAME=Kidstation
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost:8080
APP_KEY=base64:ISI_DENGAN_APP_KEY

LOG_CHANNEL=stderr
LOG_LEVEL=info

DB_CONNECTION=mysql
DB_HOST=host.docker.internal
DB_PORT=3306
DB_DATABASE=logistik_nestle
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
RUN_MIGRATIONS=false

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=http://localhost:8080/auth/google/callback
```

Untuk membuat `APP_KEY`:

```powershell
php artisan key:generate --show
```

Jika database MySQL berjalan di komputer host melalui XAMPP, dari dalam Docker biasanya host database dapat diakses dengan:

```env
DB_HOST=host.docker.internal
```

Jika database berjalan sebagai service Docker lain, gunakan nama service database sebagai `DB_HOST`.

## Menjalankan Migration

Setelah container berjalan dan database dapat diakses, jalankan migration dari container baru:

```powershell
docker run --rm --env-file .env kidstation:local php artisan migrate --force
```

Jika container aplikasi sedang berjalan dengan nama tertentu:

```powershell
docker ps
docker exec -it NAMA_CONTAINER php artisan migrate --force
```

Alternatifnya, untuk container yang ingin menjalankan migration otomatis saat start, isi:

```env
RUN_MIGRATIONS=true
```

Nilai ini sudah diset di `docker-compose.yml` lokal karena service `app` menunggu MySQL sehat lebih dulu.

## Import Data SQL

Project memiliki file SQL:

- `import_susu.sql`
- `database_susu_lengkap.sql`

File tersebut adalah SQL MySQL. Import ke database MySQL dapat dilakukan lewat phpMyAdmin, MySQL CLI, atau tool database lain.

Contoh memakai MySQL CLI di host:

```powershell
mysql -u root -p logistik_nestle < database_susu_lengkap.sql
```

Catatan: file SQL ini tidak bisa langsung di-import ke PostgreSQL tanpa konversi.

## Build dan Jalankan untuk Railway

Railway sudah disiapkan lewat:

- `Dockerfile`
- `railway.toml`

Alur deploy:

1. Push project ke GitHub.
2. Buat project Railway.
3. Tambahkan MySQL service.
4. Tambahkan service dari GitHub repo.
5. Isi environment variable di Railway.
6. Railway akan menjalankan `php artisan migrate --force` melalui `preDeployCommand`.
7. Startup container akan membuat storage link dan cache Laravel production setelah environment deployment tersedia.

Healthcheck Railway memakai endpoint:

```text
/up
```

## Build dan Jalankan untuk Render

Render sudah disiapkan lewat:

- `Dockerfile`
- `render.yaml`

Render akan membuat:

- web service `kidstation-web`
- database `kidstation-db`

Catatan penting:

- Render free memakai PostgreSQL.
- Migration Laravel bisa berjalan.
- File SQL MySQL perlu dikonversi atau diganti dengan seeder Laravel jika ingin masuk ke PostgreSQL.

## Troubleshooting

### Asset mencoba load dari Vite localhost

Jika halaman mencoba mengambil asset dari `http://127.0.0.1:5173`, hapus file:

```text
public/hot
```

File ini hanya untuk development Vite dan tidak dibutuhkan saat Docker production build.

### Database tidak terkoneksi

Cek nilai berikut:

```env
DB_CONNECTION=mysql
DB_HOST=host.docker.internal
DB_PORT=3306
DB_DATABASE=logistik_nestle
DB_USERNAME=root
DB_PASSWORD=
```

Pastikan MySQL berjalan dan database sudah dibuat.

### APP_KEY belum ada

Jika muncul error terkait encryption key, generate key:

```powershell
php artisan key:generate --show
```

Lalu masukkan hasilnya ke environment variable `APP_KEY`.

### Permission storage atau cache

Dockerfile sudah membuat dan memberi permission ke:

```text
storage/framework/cache
storage/framework/sessions
storage/framework/views
bootstrap/cache
```

Jika masih ada error permission, rebuild image:

```powershell
docker build --no-cache -t kidstation:local .
```

## Checklist Sebelum Build Production

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_KEY` sudah diisi
- database production sudah siap
- migration sudah dijalankan
- `RUN_MIGRATIONS=false` untuk Render/Railway karena migration sudah dijalankan lewat `preDeployCommand`
- `public/hot` tidak ikut masuk image
- `storage/logs` dan cache development tidak ikut masuk image
- kredensial tidak dikomit ke Git

## Command Cepat

Build:

```powershell
docker build -t kidstation:local .
```

Run:

```powershell
docker run --rm -p 8080:8080 --env-file .env kidstation:local
```

Migration:

```powershell
docker run --rm --env-file .env kidstation:local php artisan migrate --force
```

Tes healthcheck:

```powershell
curl http://localhost:8080/up
```
