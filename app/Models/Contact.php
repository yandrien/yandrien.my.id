<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    /**
     * Properti $fillable menentukan kolom mana yang dapat diisi secara massal.
     * Ini penting untuk keamanan, mencegah pengguna mengisi kolom yang tidak seharusnya.
     */
    protected $fillable = [
        'name',
        'email',
        'message',
    ];
}
