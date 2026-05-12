<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kidstation</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3 { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-950 text-slate-900">
    <main class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-10">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(45,212,191,0.22),_transparent_34%),radial-gradient(circle_at_bottom_right,_rgba(99,102,241,0.28),_transparent_36%)]"></div>
        <div class="absolute left-1/2 top-10 h-72 w-72 -translate-x-1/2 rounded-full bg-white/10 blur-3xl"></div>

        <section class="relative grid w-full max-w-5xl overflow-hidden rounded-[2rem] bg-white shadow-2xl shadow-black/30 lg:grid-cols-[1fr_440px]">
            <div class="hidden bg-gradient-to-br from-indigo-600 via-blue-600 to-teal-500 p-10 text-white lg:flex lg:flex-col lg:justify-between">
                <div>
                    <div class="mb-8 flex items-center gap-4">
                        <div class="rounded-2xl bg-white/20 p-3 backdrop-blur">
                            <i class="fa-solid fa-shapes text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold tracking-tight">Kidstation</h1>
                            <p class="text-sm text-white/75">Premium Baby Shop</p>
                        </div>
                    </div>
                    <h2 class="max-w-md text-4xl font-bold leading-tight">Kelola penjualan, pengeluaran, produk, dan laporan dari satu tempat.</h2>
                </div>
                <div class="rounded-3xl border border-white/20 bg-white/10 p-5 backdrop-blur">
                    <p class="text-sm text-white/80">Masuk sebagai admin untuk menjaga data toko tetap aman dan teratur.</p>
                </div>
            </div>

            <div class="p-6 sm:p-10">
                <div class="mb-8 lg:hidden">
                    <div class="mb-4 inline-flex rounded-2xl bg-indigo-50 p-3 text-indigo-600">
                        <i class="fa-solid fa-shapes text-2xl"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-slate-900">Kidstation</h1>
                    <p class="mt-1 text-sm text-slate-500">Premium Baby Shop</p>
                </div>

                <div class="mb-8">
                    <p class="text-sm font-bold uppercase tracking-[0.25em] text-indigo-500">Admin Area</p>
                    <h2 class="mt-2 text-3xl font-bold text-slate-900">Login</h2>
                    <p class="mt-2 text-sm text-slate-500">Masukkan email dan password admin untuk lanjut.</p>
                </div>

                @if(session('status'))
                    <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('login.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100" placeholder="admin@email.com" autofocus>
                        @error('email')
                            <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Password</label>
                        <input type="password" name="password" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100" placeholder="Password">
                        @error('password')
                            <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="flex items-center gap-3 text-sm font-medium text-slate-600">
                        <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        Ingat saya
                    </label>

                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-2xl bg-indigo-600 px-5 py-3 font-bold text-white shadow-lg shadow-indigo-200 transition hover:bg-indigo-700">
                        Masuk
                        <i class="fa-solid fa-arrow-right text-sm"></i>
                    </button>
                </form>

                <p class="mt-8 text-center text-sm text-slate-500">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-700">Daftar admin</a>
                </p>
            </div>
        </section>
    </main>
</body>
</html>
