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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
			
			// Kolom untuk menghubungkan profil dengan pengguna (user)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Kolom untuk data profil
            $table->string('peran')->nullable(); // Contoh: "Pengembang Web"
            $table->text('biografi')->nullable();
            $table->string('foto_profil')->nullable();
            $table->string('lokasi')->nullable();
			$table->string('nomor_telepon')->nullable(); 
			$table->string('tlahir')->nullable();
			$table->string('tgllahir')->nullable();
			$table->string('alamat_lengkap')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('github_url')->nullable();
			
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
