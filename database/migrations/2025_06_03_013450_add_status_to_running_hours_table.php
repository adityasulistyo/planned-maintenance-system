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
        Schema::table('running_hours', function (Blueprint $table) {
            // Menambahkan kolom 'status'
            // Defaultnya bisa diatur 'Safe' atau kosong/nullable sesuai kebutuhan awal Anda
            $table->string('status')->default('Safe')->after('tanggal_mulai'); // Misalnya, setelah tanggal_mulai
            // Atau jika bisa nullable: $table->string('status')->nullable()->after('tanggal_mulai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('running_hours', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};