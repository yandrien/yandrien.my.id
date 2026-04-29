<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Membuat tabel kamus untuk menyimpan pasangan kata
        Schema::create('kambera_dictionary', function (Blueprint $table) {
            $table->id();
            // Kolom untuk kata bahasa Indonesia (id_word)
            $table->string('id_word')->unique(); 
            // Kolom untuk terjemahan bahasa Sumba Kambera (sbk_word)
            $table->string('sbk_word'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kambera_dictionary');
    }
};
