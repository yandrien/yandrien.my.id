<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('articles')->insert([
            [
                'judul' => '5 Kiat Belajar Coding untuk Pemula',
                'isi' => 'Panduan praktis untuk memulai perjalanan Anda di dunia pemrograman dengan langkah yang tepat.',
                'tanggal_terbit' => '2025-08-28',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Mengoptimalkan Desain Aplikasi Modern',
                'isi' => 'Pelajari prinsip-prinsip dasar untuk menciptakan antarmuka pengguna yang intuitif dan menarik.',
                'tanggal_terbit' => '2025-08-20',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Membangun Portofolio yang Kuat',
                'isi' => 'Bagaimana cara menyoroti proyek terbaik Anda untuk menarik perhatian klien atau perekrut.',
                'tanggal_terbit' => '2025-08-15',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
