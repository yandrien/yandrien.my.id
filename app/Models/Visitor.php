<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
	//izibkan kolom2nya agar bisa diisi--23/04/2026
    protected $fillable = ['ip_address', 'country', 'hits', 'page_visited', 'browser'];
}
