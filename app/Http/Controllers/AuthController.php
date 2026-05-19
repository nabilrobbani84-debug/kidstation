<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check() || session()->has('google_admin.email')) {
            return redirect()->route('dashboard');
        }

        return view('auth.login', [
            'googleLoginConfigured' => $this->googleLoginIsConfigured(),
        ]);
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Email atau password tidak sesuai.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function redirectToGoogle(): RedirectResponse
    {
        if (! $this->googleLoginIsConfigured()) {
            return redirect()
                ->route('login')
                ->with('google_unavailable', 'Login Google belum aktif. Silakan login menggunakan email dan password admin.');
        }

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        // Already logged in — go straight to dashboard
        if (Auth::check() || $request->session()->has('google_admin.email')) {
            return redirect()->route('dashboard');
        }

        if (! $this->googleLoginIsConfigured()) {
            return redirect()
                ->route('login')
                ->with('google_unavailable', 'Login Google belum aktif. Silakan login menggunakan email dan password admin.');
        }

        // Use stateless() because Vercel serverless can't guarantee session
        // continuity between the redirect and callback requests.
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Throwable $e) {
            $msg = $e->getMessage();

            // State mismatch / invalid_grant usually means the user hit
            // the callback URL directly or the code already expired.
            if (str_contains($msg, 'state') || str_contains($msg, 'invalid_grant')) {
                return redirect()
                    ->route('login')
                    ->withErrors(['google' => 'Sesi login Google kadaluarsa atau tidak valid. Silakan coba lagi.']);
            }

            return redirect()
                ->route('login')
                ->withErrors(['google' => 'Login Google gagal: ' . $msg]);
        }

        $email = $googleUser->getEmail();

        if (! $email) {
            return redirect()
                ->route('login')
                ->withErrors(['google' => 'Akun Google tidak mengirimkan alamat email. Gunakan akun Google lain.']);
        }

        try {
            $user = User::where('email', $email)->first();

            if (! $user) {
                $user = new User([
                    'name'     => $googleUser->getName() ?: $email,
                    'email'    => $email,
                    'password' => Str::random(40),
                ]);

                $user->email_verified_at = now();
                $user->save();
            }

            Auth::login($user, true);
        } catch (\Throwable $e) {
            // Database unavailable (e.g. Vercel without DB configured).
            // Store identity in session so the app still works read-only.
            $request->session()->put('google_admin', [
                'name'  => $googleUser->getName() ?: $email,
                'email' => $email,
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function showRegister(): View
    {
        return view('auth.register', [
            'googleLoginConfigured' => $this->googleLoginIsConfigured(),
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        User::create($validated);

        return redirect()->route('login')->with('status', 'Akun admin berhasil dibuat. Silakan login untuk masuk ke dashboard.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->forget('google_admin');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Anda sudah logout.');
    }

    private function googleLoginIsConfigured(): bool
    {
        try {
            return filled(config('services.google.client_id'))
                && filled(config('services.google.client_secret'))
                && filled(config('services.google.redirect'));
        } catch (Throwable) {
            return false;
        }
    }
}
