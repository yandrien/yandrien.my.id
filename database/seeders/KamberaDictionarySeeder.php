<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KamberaDictionarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Data kamus contoh: Indonesia (id) ke Sumba Kambera (sbk)
        // PENTING: Mohon koreksi dan tambahkan pasangan kata yang benar berdasarkan dialek Sumba Kambera yang Anda gunakan.
        $dictionary = [
            // Kata dasar
            ['id_word' => 'saya', 'sbk_word' => 'nyungga'],
            ['id_word' => 'kamu', 'sbk_word' => 'nyumu'], 
            ['id_word' => 'makan', 'sbk_word' => 'ngangu'], 
            ['id_word' => 'minum', 'sbk_word' => 'unu'], 
            ['id_word' => 'pergi', 'sbk_word' => 'laku'], 
            ['id_word' => 'datang', 'sbk_word' => 'namu'], 
            
            // Kata benda
            ['id_word' => 'air', 'sbk_word' => 'wai'], 
            ['id_word' => 'rumah', 'sbk_word' => 'uma'], 
            ['id_word' => 'jalan', 'sbk_word' => 'pangga'], 
            ['id_word' => 'padi', 'sbk_word' => 'uhu'], 

            // Kata sifat
            ['id_word' => 'besar', 'sbk_word' => 'bakul'], 
            ['id_word' => 'kecil', 'sbk_word' => 'kudu'], 
            ['id_word' => 'lama', 'sbk_word' => 'mandai'], 
            ['id_word' => 'cepat', 'sbk_word' => 'pariangga'], 
        ];

        // Masukkan data ke tabel kambera_dictionary
        // Setelah selesai mengisi, jalankan: php artisan migrate:fresh --seed
        DB::table('kambera_dictionary')->insert($dictionary);
    }
}
