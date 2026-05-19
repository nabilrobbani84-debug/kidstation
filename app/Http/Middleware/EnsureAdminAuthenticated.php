<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class EnsureAdminAuthenticated
{
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        if (Auth::check() || $request->session()->has('google_admin.email')) {
            return $next($request);
        }

        return redirect()->route('login');
    }
}
