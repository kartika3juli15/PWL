<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanDetailSeeder extends Seeder
{
    public function run()
    {
        $detail = [];
        $metode = ['cash', 'bank', 'e-money'];

        // Ambil semua penjualan_id yang ada di tabel t_penjualan
        $penjualanIds = DB::table('t_penjualan')->pluck('penjualan_id');

        foreach ($penjualanIds as $penjualanId) {
            for ($j = 1; $j <= 3; $j++) { // Setiap transaksi memiliki 3 barang
                $barang_id = rand(1, 10);
                $harga = DB::table('m_barang')->where('barang_id', $barang_id)->value('harga_jual');

                if ($harga !== null) {
                    $detail[] = [
                        'penjualan_id' => $penjualanId,
                        'barang_id' => $barang_id,
                        'harga' => $harga,
                        'jumlah' => rand(1, 5),
                        'metode_pembayaran' => $metode[array_rand($metode)],
                    ];
                }
            }
        }

        DB::table('t_penjualan_detail')->insert($detail);
    }
}
