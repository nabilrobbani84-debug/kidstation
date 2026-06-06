<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class NotaNestleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/nota_nestle.sql');
        $sql = File::get($path);
        
        DB::unprepared($sql);
        
        $this->command->info('Data Nota Nestle berhasil di-insert ke database!');
    }
}
