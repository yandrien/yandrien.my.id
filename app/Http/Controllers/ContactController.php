<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    
    /**
     * Store a newly created contact message in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi data yang masuk. Jika gagal, Laravel akan mengarahkan kembali
        // pengguna dengan pesan kesalahan.
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'emailcontact' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        // Gunakan Model Contact untuk membuat entri baru di tabel 'contacts'
        // dengan data yang sudah divalidasi.
        Contact::create($validatedData);

        // Arahkan pengguna ke halaman beranda ('/') dengan pesan sukses.
        return back()->with('success', 'Pesan terkirim!');
    }
}


