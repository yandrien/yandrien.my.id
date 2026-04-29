<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    /**
     * Field yang boleh diisi melalui form (Mass Assignment).
     * Pastikan nama 'image' atau 'img_preview' sesuai dengan nama kolom di migration Anda.
     */
    protected $fillable = [
        'judul',
        'isi',
        'tanggal_terbit',
        'img_preview',
		'status',
		'user_id',
		'lampiran_doc'
    ];

    /**
     * Mengonversi atribut ke tipe data tertentu secara otomatis.
     */
    protected $casts = [
        'tanggal_terbit' => 'date',
    ];

    /**
     * Opsi: Jika Anda ingin membuat slug otomatis dari judul atau 
     * fungsi pembantu lainnya, bisa ditambahkan di bawah sini.
     */
}
