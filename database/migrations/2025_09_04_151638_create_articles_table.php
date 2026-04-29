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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
			$table->string('judul');
			$table->date('tanggal_terbit');
			$table->longText('isi');
			$table->string('img_preview')->nullable();
			$table->enum('status', ['draft', 'published'])->default('draft');
			$table->foreignId('user_id')->constrained()->onDelete('cascade');
			$table->string('lampiran_doc')->nullable();
			$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
