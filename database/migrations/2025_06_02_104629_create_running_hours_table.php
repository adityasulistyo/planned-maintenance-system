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
        Schema::create('running_hours', function (Blueprint $table) {
        $table->id();
        $table->string('kategori'); // AE, ME, PE
        $table->string('nama_barang');
        $table->string('no_seri');
        $table->integer('max_running_hour');
        $table->integer('running_hour_awal')->default(0); // optional
        $table->integer('jumlah')->default(1);
        $table->timestamp('tanggal_mulai');
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('running_hours');
    }
};
