<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparepartRequest extends Model
{
    use HasFactory;

   protected $table = 'sparepart_requests'; // Pastikan ini benar

    protected $fillable = [
        'nama_barang',
        'quantity', // Tambahkan ini
        'keterangan', // Tetap ada jika Anda masih menggunakannya untuk deskripsi
        'type',       // Tambahkan jika Anda punya kolom 'type'
        'requested_at',
        'status',     // Pastikan ini juga ada
        // tambahkan kolom lain yang relevan di sini jika ada
    ];
}