<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports'; // Pastikan nama tabelnya 'reports'

    protected $fillable = [
        'action',
        'description',
        // created_at dan updated_at diisi otomatis oleh timestamps()
    ];

    // Tambahkan ini untuk memastikan created_at dan updated_at di-cast ke objek Carbon
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}