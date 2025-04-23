<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanDetailModel extends Model
{
    use HasFactory;

    protected $table = 't_penjualan_detail'; // Nama tabel
    protected $primaryKey = 'detail_id'; // Primary key tabel
    public $timestamps = false; // Nonaktifkan timestamps jika tidak ada created_at & updated_at

    protected $fillable = [
        'penjualan_id',
        'barang_id',
        'harga',
        'jumlah',
        'metode_pembayaran'
    ];

    // Relasi ke tabel penjualan
    public function penjualan()
    {
        return $this->belongsTo(PenjualanModel::class, 'penjualan_id', 'penjualan_id');
    }

    // Relasi ke tabel barang
    public function barang()
    {
        return $this->belongsTo(BarangModel::class, 'barang_id', 'barang_id');
    }
}
