<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StokSeeder extends Seeder
{
    public function run(): void
    {
        $data = [];
        for ($i = 1; $i <= 10; $i++) {
            $data[] = [
                'barang_id' => $i,
                'stok_tanggal' => now(),
                'stok_jumlah' => rand(10, 100),
                'user_id' => 1, // Pastikan user_id ada, bisa diubah sesuai kebutuhan
            ];
        }

        DB::table('t_stok')->insert($data);
    }
}
