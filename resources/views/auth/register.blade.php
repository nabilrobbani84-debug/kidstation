<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Kidstation</title>
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
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(20,184,166,0.22),_transparent_34%),radial-gradient(circle_at_bottom_right,_rgba(79,70,229,0.30),_transparent_36%)]"></div>
        <div class="absolute right-10 top-10 h-72 w-72 rounded-full bg-cyan-300/10 blur-3xl"></div>

        <section class="relative w-full max-w-xl rounded-[2rem] bg-white p-6 shadow-2xl shadow-black/30 sm:p-10">
            <div class="mb-8">
                <div class="mb-4 inline-flex rounded-2xl bg-indigo-50 p-3 text-indigo-600">
                    <i class="fa-solid fa-user-plus text-2xl"></i>
                </div>
                <p class="text-sm font-bold uppercase tracking-[0.25em] text-indigo-500">Admin Area</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Daftar Admin</h1>
                <p class="mt-2 text-sm text-slate-500">Buat akun untuk mengakses dashboard dan mengelola data toko.</p>
            </div>

            <form action="{{ route('register.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100" placeholder="Nama admin" autofocus>
                    @error('name')
                        <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100" placeholder="admin@email.com">
                    @error('email')
                        <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Password</label>
                    <input type="password" name="password" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100" placeholder="Minimal 8 karakter">
                    @error('password')
                        <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100" placeholder="Ulangi password">
                </div>

                <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-2xl bg-indigo-600 px-5 py-3 font-bold text-white shadow-lg shadow-indigo-200 transition hover:bg-indigo-700">
                    Buat Akun
                    <i class="fa-solid fa-arrow-right text-sm"></i>
                </button>
            </form>

            <p class="mt-8 text-center text-sm text-slate-500">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-700">Login</a>
            </p>
        </section>
    </main>
</body>
</html>
