<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController; // Menggunakan Controller inti Laravel

class Controller extends BaseController // Penting: Mewarisi semua fungsionalitas Laravel
{
    use AuthorizesRequests, ValidatesRequests;
}