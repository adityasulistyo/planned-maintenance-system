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
    Schema::table('spareparts', function (Blueprint $table) {
        if (!Schema::hasColumn('spareparts', 'no_seri')) {
            $table->string('no_seri')->nullable();
        }
        if (!Schema::hasColumn('spareparts', 'max_running_hour')) {
            $table->integer('max_running_hour')->nullable();
        }
        if (!Schema::hasColumn('spareparts', 'harga_satuan')) {
            $table->bigInteger('harga_satuan')->nullable();
        }
        if (!Schema::hasColumn('spareparts', 'tanggal_masuk')) {
            $table->date('tanggal_masuk')->nullable();
        }
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spareparts', function (Blueprint $table) {
            //
        });
    }
};
