<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kategori_kode' => 'Elektronik', 'kategori_nama' => 'Televisi'],
            ['kategori_kode' => 'Fashion', 'kategori_nama' => 'Skena'],
            ['kategori_kode' => 'Makanan', 'kategori_nama' => 'Gudeg'],
            ['kategori_kode' => 'Minuman', 'kategori_nama' => 'Lemon Squash'],
            ['kategori_kode' => 'Alat', 'kategori_nama' => 'Sapu'],
        ];

        DB::table('m_kategori')->insert($data);
    }
}