<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Expense;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Products
        $p1 = Product::create(['name' => 'Dancow 1+ 800g', 'category' => 'Susu Pertumbuhan', 'price' => 125000]);
        $p2 = Product::create(['name' => 'SGM Eksplor 3+ 900g', 'category' => 'Susu Pertumbuhan', 'price' => 98000]);
        $p3 = Product::create(['name' => 'Bebelac 3 800g', 'category' => 'Susu Pertumbuhan', 'price' => 145000]);
        $p4 = Product::create(['name' => 'MamyPoko Pants L30', 'category' => 'Popok Bayi', 'price' => 65000]);
        $p5 = Product::create(['name' => 'Zwitsal Baby Bath', 'category' => 'Perlengkapan Bayi', 'price' => 35000]);

        // Sales (Last 7 days)
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            // 2-5 sales per day
            $dailySalesCount = rand(2, 5);
            for ($j = 0; $j < $dailySalesCount; $j++) {
                $product = $i % 2 == 0 ? $p1 : $p2; // Simple logic
                if ($j % 2 != 0) $product = $p3;
                
                $qty = rand(1, 3);
                Sale::create([
                    'date' => $date,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'total_price' => $product->price * $qty
                ]);
            }
        }

        // Expenses
        Expense::create([
            'date' => Carbon::today(),
            'category' => 'Stok Barang',
            'description' => 'Restock Susu Dancow',
            'amount' => 5000000
        ]);
        
        Expense::create([
            'date' => Carbon::yesterday(),
            'category' => 'Operasional',
            'description' => 'Listrik & Air',
            'amount' => 750000
        ]);
    }
}
