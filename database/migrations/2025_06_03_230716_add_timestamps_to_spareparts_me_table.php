<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_add_timestamps_to_spareparts_ae_table.php (dan untuk _me, _pe)
public function up(): void
{
    Schema::table('spareparts_me', function (Blueprint $table) { // Ganti 'spareparts_ae' sesuai nama tabel
        $table->timestamps(); // Menambahkan created_at dan updated_at
    });
}

public function down(): void
{
    Schema::table('spareparts_me', function (Blueprint $table) { // Ganti 'spareparts_ae' sesuai nama tabel
        $table->dropTimestamps(); // Menghapus created_at dan updated_at
    });
}
};
