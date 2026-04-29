<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        
        'provider',    // Tambahkan ini 20/04/2026
        'provider_id', // Tambahkan ini 20/04/2026
        
        'is_admin', // Menambahkan kolom is_admin
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean', // Menambahkan tipe data boolean untuk is_admin
        ];
    }
	
	/**
     * Metode boot() akan dipanggil saat model dimuat.
     * Kita akan mendaftarkan event 'creating' di sini.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // Mendaftarkan callback untuk event 'creating'.
        // Ini akan dijalankan sebelum user baru benar-benar dibuat.
        static::creating(function ($user) {
            // Periksa apakah user yang akan dibuat adalah admin.
            if ($user->is_admin) {
                // Jika ya, periksa apakah sudah ada admin lain di database.
                $adminExists = self::where('is_admin', 1)->exists();

                if ($adminExists) {
                    // Jika admin sudah ada, cegah user baru ini dibuat.
                    // Anda bisa melempar exception atau mengembalikan false.
                    throw new \Exception("Permintaan tidak dapat diproses!");
                    
                    // Mengembalikan false juga akan membatalkan operasi.
                    // return false;
                }
            }
        });
    }

    /**
     * Get the profile associated with the user.
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }
}
