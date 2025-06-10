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
       Schema::create('spareparts', function (Blueprint $table) {
    $table->id();
    $table->string('nama_barang');
    $table->string('kategori')->nullable();
    $table->integer('jumlah');
    $table->date('expired_date')->nullable();
    $table->boolean('di_pakai')->default(false);
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spareparts');
    }
};
