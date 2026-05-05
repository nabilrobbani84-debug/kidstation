<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAsRead(Request $request): RedirectResponse
    {
        $request->session()->put('notifications_read_at', now()->toIso8601String());

        $previousUrl = $request->headers->get('referer', route('dashboard'));

        if (app()->environment('production') || env('VERCEL')) {
            $previousUrl = preg_replace('/^http:\/\//i', 'https://', $previousUrl);
        }

        return redirect()->away($previousUrl);
    }
}
