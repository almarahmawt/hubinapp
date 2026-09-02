<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Eksekusi Pondasi Utama (Wajib jalan di semua server)
        $this->call([
            MasterDataSeeder::class,
        ]);

        // 2. Eksekusi Data Uji Coba (HANYA jalan di komputer lokal/development)
        if (app()->environment('local')) {
            $this->call([
                DummyDataSeeder::class,
            ]);
        }
    }
}