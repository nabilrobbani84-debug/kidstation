<?php

namespace App\Providers;

use App\Models\Expense;
use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

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
            URL::forceScheme('https');
        }

        $database = config('database.connections.sqlite.database');
        if($database && str_starts_with($database, '/tmp/') && !file_exists($database)) {
            touch($database);
            // Auto run migration for demo purpose (Be careful in real prod)
            Artisan::call('migrate --force');
        }

        View::composer('layouts.app', function ($view): void {
            $notifications = collect();
            $readAt = session('notifications_read_at')
                ? Carbon::parse(session('notifications_read_at'))
                : null;

            try {
                $latestSale = Sale::with('product')->latest()->first();
                if ($latestSale) {
                    $notifications->push([
                        'title' => 'Penjualan terbaru',
                        'body' => sprintf(
                            '%s x%s senilai Rp %s',
                            $latestSale->product?->name ?? 'Produk terhapus',
                            number_format($latestSale->quantity, 0, ',', '.'),
                            number_format($latestSale->total_price, 0, ',', '.')
                        ),
                        'icon' => 'fa-cart-shopping',
                        'tone' => 'emerald',
                        'url' => route('sales.index'),
                        'created_at' => $latestSale->created_at,
                    ]);
                }

                $latestExpense = Expense::latest()->first();
                if ($latestExpense) {
                    $notifications->push([
                        'title' => 'Pengeluaran terbaru',
                        'body' => sprintf(
                            '%s senilai Rp %s',
                            $latestExpense->category,
                            number_format($latestExpense->amount, 0, ',', '.')
                        ),
                        'icon' => 'fa-money-bill-wave',
                        'tone' => 'rose',
                        'url' => route('expenses.index'),
                        'created_at' => $latestExpense->created_at,
                    ]);
                }

                $today = Carbon::today();
                $todaySalesCount = Sale::whereDate('date', $today)->count();
                $todaySalesTotal = Sale::whereDate('date', $today)->sum('total_price');

                if ($todaySalesCount > 0) {
                    $notifications->push([
                        'title' => 'Ringkasan penjualan hari ini',
                        'body' => sprintf(
                            '%s transaksi, total Rp %s',
                            number_format($todaySalesCount, 0, ',', '.'),
                            number_format($todaySalesTotal, 0, ',', '.')
                        ),
                        'icon' => 'fa-chart-line',
                        'tone' => 'indigo',
                        'url' => route('dashboard'),
                        'created_at' => Sale::whereDate('date', $today)->latest()->value('created_at') ?? $today,
                    ]);
                } else {
                    $notifications->push([
                        'title' => 'Belum ada penjualan hari ini',
                        'body' => 'Catat transaksi pertama supaya laporan harian terisi.',
                        'icon' => 'fa-bell',
                        'tone' => 'amber',
                        'url' => route('sales.index'),
                        'created_at' => $today,
                    ]);
                }

                if (Product::count() === 0) {
                    $notifications->push([
                        'title' => 'Data produk masih kosong',
                        'body' => 'Tambahkan produk agar penjualan bisa dicatat.',
                        'icon' => 'fa-box-open',
                        'tone' => 'amber',
                        'url' => route('products.index'),
                        'created_at' => $today,
                    ]);
                }
            } catch (Throwable) {
                $notifications = collect();
            }

            $notifications = $notifications
                ->sortByDesc('created_at')
                ->take(5)
                ->map(function (array $notification) use ($readAt): array {
                    $createdAt = $notification['created_at'] instanceof Carbon
                        ? $notification['created_at']
                        : Carbon::parse($notification['created_at']);

                    $notification['created_at'] = $createdAt;
                    $notification['time'] = $createdAt->diffForHumans();
                    $notification['unread'] = ! $readAt || $createdAt->greaterThan($readAt);

                    return $notification;
                })
                ->values();

            $view->with([
                'notifications' => $notifications,
                'unreadNotificationsCount' => $notifications->where('unread', true)->count(),
            ]);
        });
    }
}
