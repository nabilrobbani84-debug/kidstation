# Deploy Kidstation ke Vercel

Project ini sudah memiliki konfigurasi `vercel.json` untuk menjalankan Laravel melalui runtime PHP Vercel.

## URL Production

```text
https://kidstation-laravel.vercel.app
```

## Environment Variables

Set environment berikut di Vercel Project Settings:

```env
APP_KEY=base64:GENERATE_DI_LOCAL_DENGAN_PHP_ARTISAN_KEY_GENERATE_SHOW
APP_URL=https://kidstation-laravel.vercel.app

DB_CONNECTION=pgsql
DB_URL=ISI_CONNECTION_STRING_POSTGRES

GOOGLE_CLIENT_ID=ISI_DARI_GOOGLE_CLOUD
GOOGLE_CLIENT_SECRET=ISI_DARI_GOOGLE_CLOUD
GOOGLE_REDIRECT_URI=https://kidstation-laravel.vercel.app/auth/google/callback
```

`GOOGLE_REDIRECT_URI` boleh dikosongkan jika `APP_URL` sudah benar, karena aplikasi akan memakai fallback:

```text
{APP_URL}/auth/google/callback
```

`GOOGLE_CLIENT_ID` juga sudah ditulis di `vercel.json` karena nilainya public. `GOOGLE_CLIENT_SECRET` tetap wajib diisi di Vercel Dashboard dan jangan dicommit ke repo.

## Google Cloud Console

Di OAuth Client Google, tambahkan Authorized redirect URI:

```text
https://kidstation-laravel.vercel.app/auth/google/callback
```

Untuk testing lokal, tambahkan juga:

```text
http://127.0.0.1:8000/auth/google/callback
```

## Deploy Ulang

Setelah environment variable di Vercel diperbarui, lakukan redeploy dari dashboard Vercel atau push commit baru ke GitHub.

Jika memakai Vercel CLI:

```powershell
vercel --prod
```

Jangan commit `.env`; file itu berisi credential lokal dan sudah diabaikan oleh `.gitignore` serta `.vercelignore`.
