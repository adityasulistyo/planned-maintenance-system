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
            // Tambahkan kolom 'status' setelah 'requested_at' (sesuaikan posisi jika perlu)
            $table->string('status')->default('pending')->after('requested_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sparepart_requests', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};