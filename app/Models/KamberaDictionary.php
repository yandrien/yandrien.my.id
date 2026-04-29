<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel kambera_dictionary
 * Menghubungkan kata Indonesia (id_word) dengan terjemahan Sumba Kambera (sbk_word).
 */
class KamberaDictionary extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'kambera_dictionary';

    // Kolom yang dapat diisi (sesuai dengan seeder dan migration Anda)
    protected $fillable = [
        'id_word',
        'sbk_word',
    ];

    // Karena tabel ini hanya berisi pasangan kata dan tidak memerlukan kolom timestamp (created_at/updated_at)
    public $timestamps = false;
}
