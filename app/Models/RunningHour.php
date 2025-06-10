<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RunningHour extends Model
{
    // app/Models/RunningHour.php
protected $fillable = [
    'kategori',
    'nama_barang',
    'no_seri', 
    'max_running_hour',
    'tanggal_mulai'
];

// Tambahkan relasi jika diperlukan
public function sparepart()
{
    return $this->belongsTo(Sparepart::class);
}

}