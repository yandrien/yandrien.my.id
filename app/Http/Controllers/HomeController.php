<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Article; // Impor model Article


class HomeController extends Controller
{
    public function index()
    {
         // 1. Ambil ID user yang sedang login (akan null jika belum login)
        $userId = Auth::id();

        // 2. Gunakan query builder dengan pengelompokan logika yang lebih kuat
        $articles = Article::where(function ($query) use ($userId) {
            
            // Kondisi Utama: Semua orang (tamu/login) bisa melihat yang 'publish'
            $query->where('status', 'publish');

            // Kondisi Tambahan: Jika user login, tampilkan juga draft miliknya sendiri
            if ($userId) {
                $query->orWhere(function ($q) use ($userId) {
                    $q->where('status', 'draft')
                      ->where('user_id', $userId);
                });
            }
        })
        ->orderBy('tanggal_terbit', 'desc') // Mengurutkan berdasarkan tanggal terbit terbaru
        ->orderBy('created_at', 'desc')     // Backup urutan berdasarkan waktu buat
		->take(9) 							//tampilkan 9 terbaru saja
        ->get();
        
        // Kirim data artikel ke view 'home'
        return view('home', compact('articles'));
    }
}
