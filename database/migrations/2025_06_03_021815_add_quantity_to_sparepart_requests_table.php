<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sparepart_requests', function (Blueprint $table) {
            // Tambahkan kolom 'quantity' sebagai integer
            // Anda bisa menempatkannya setelah 'nama_barang' atau sebelum 'keterangan'
            $table->integer('quantity')->default(0)->after('nama_barang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sparepart_requests', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
    }
};