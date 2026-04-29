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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
			$table->string('name'); // Kolom untuk menyimpan nama pengirim
            $table->string('email'); // Kolom untuk menyimpan alamat email pengirim
            $table->text('message'); // Kolom untuk menyimpan pesan yang panjang
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
