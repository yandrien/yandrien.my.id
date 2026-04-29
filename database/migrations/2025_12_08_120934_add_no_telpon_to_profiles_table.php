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
        Schema::table('profiles', function (Blueprint $table) {
            // Tambahkan kolom 'nomor_telepon' dengan tipe string (atau tipe data lain yang Anda inginkan)
            // Kolom ini dibuat nullable (boleh kosong) karena mungkin tidak semua pengguna mengisinya.
            $table->string('nomor_telepon')->nullable()->after('lokasi'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            // Perintah untuk menghapus kolom jika migrasi dibatalkan (rollback)
            $table->dropColumn('nomor_telepon');
        });
    }
};
