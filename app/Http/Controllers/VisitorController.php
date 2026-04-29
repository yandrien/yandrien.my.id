<?php

namespace App\Http\Controllers;

use App\Models\Visitor; // Pastikan model Visitor sudah ada
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    public function index()
    {
        // Jika bukan admin, jangan kasih lewat!
		if (!auth()->user()->is_admin) {
			return redirect('/')->with('error', 'Akses ditolak!');
			// Atau pakai ini supaya lebih sangar:
			// abort(403, 'Mau ngapain, Suhu?'); 
		}
        
        // Ambil 50 data pengunjung terbaru
        $visitors = Visitor::orderBy('created_at', 'desc')->paginate(50);
        
        // Ringkasan sederhana untuk box informasi
        $totalHits = Visitor::sum('hits');
        $uniqueUsers = Visitor::count();

        return view('admin.visitors', compact('visitors', 'totalHits', 'uniqueUsers'));
    }
}