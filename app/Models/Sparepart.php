<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    use HasFactory;

    // Tidak perlu protected $table di sini, karena akan diatur dinamis di controller
    // protected $table = 'spareparts'; // Hapus atau biarkan default jika ada tabel 'spareparts' utama yang tidak terkait

    protected $fillable = [
        'nama_barang',
        'no_seri',
        'jumlah',
        'mrh', // Ini penting, pastikan nama kolom di DB adalah 'mrh'
        'harga_satuan',
        'tanggal_masuk',
        'expired_date', // Tambahkan jika Anda menggunakan expired_date
        'di_pakai', // Tambahkan jika Anda menggunakan di_pakai
        // Pastikan semua kolom yang Anda butuhkan ada di fillable
    ];

    // Jika ada hubungan dengan RunningHour, biarkan seperti ini:
    // public function runningHours()
    // {
    //     return $this->hasMany(RunningHour::class);
    // }
}