<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment('production') || env('VERCEL')) {
            $url = \Illuminate\Support\Facades\URL::current();
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        $database = config('database.connections.sqlite.database');
        if($database && str_starts_with($database, '/tmp/') && !file_exists($database)) {
            touch($database);
            // Auto run migration for demo purpose (Be careful in real prod)
            \Illuminate\Support\Facades\Artisan::call('migrate --force');
        }
    }
}
