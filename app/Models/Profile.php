<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Menambahkan ini
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
		'peran',
        'biografi',
        'foto_profil',
        'lokasi',
        'linkedin_url',
        'github_url',
		'nomor_telepon',
		'tlahir',
		'tgllahir',
		'alamat_lengkap',
    ];
	
	/**
     * Properti yang harus dicasting ke tipe bawaan.
     * Ini memastikan bahwa field 'tgllahir' secara otomatis
     * dikonversi menjadi objek Carbon saat diakses.
     */
    protected $casts = [
        // Pastikan field 'tgllahir' di-cast sebagai 'date'.
        // Ini akan membuatnya menjadi instance Carbon.
        'tgllahir' => 'date', 
    ];


    /**
     * Mendefinisikan hubungan (relationship) dengan model User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
	 /**
     * Relasi: Sebuah Profil dimiliki oleh satu User.
     * Ini memungkinkan kita mengakses $profile->user->name di Blade.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
