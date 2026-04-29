<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller; //menghubungkan controller buatan Anda dengan kerangka kerja Laravel. Ini memastikan controller Anda memiliki semua alat dan fungsionalitas yang diperlukan untuk bekerja dengan baik di dalam ekosistem Laravel.


class RegisterController extends Controller
{
    /**
     * Menampilkan formulir pendaftaran.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('register');
    }
	
	/**
     * Memproses permintaan pendaftaran pengguna baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
		try {
			// Memvalidasi data yang dikirimkan dari formulir
			$validatedData = $request->validate([
				'name' => 'required|string',
				'email' => 'required|string|email|unique:users',
				'password' => 'required|string|min:8|confirmed',
			]);

			// Membuat pengguna baru di database
			// Kolom 'is_admin' diatur secara default menjadi 0 (false)
			User::create([
				'name' => $validatedData['name'],
				'email' => $validatedData['email'],
				'password' => Hash::make($validatedData['password']),
				'is_admin' => 0, // Mengatur is_admin ke 0 secara default
			]);

			// Mengalihkan pengguna ke beranda dengan session status
			return redirect('/')->with('status', 'Pendaftaran berhasil!');
			
		} catch (Exception $e) {
			// Jika ada Exception yang dilempar dari model, tangkap di sini
			// $e->getMessage() akan berisi "Maaf, hanya satu user admin yang diizinkan."

			// Redirect kembali dengan pesan error
			return redirect()->back()->with('error', $e->getMessage());
		}
    }
	
	//admin
	public function showRegistrationme()
    {
        return view('registerme');
    }
	
	public function storeme(Request $request)
    {
		try {
			// Memvalidasi data yang dikirimkan dari formulir
			$validatedData = $request->validate([
				'name' => 'required|string',
				'email' => 'required|string|email|unique:users',
				'password' => 'required|string|min:8|confirmed',
			]);

			// Membuat pengguna baru di database
			// Kolom 'is_admin' diatur secara default menjadi 0 (false)
			User::create([
				'name' => $validatedData['name'],
				'email' => $validatedData['email'],
				'password' => Hash::make($validatedData['password']),
				'is_admin' => 1,
			]);

			// Mengalihkan pengguna ke beranda dengan session status
			return redirect('/')->with('status', 'Admin Registered!');
			
		} catch (Exception $e) {
			// Jika ada Exception yang dilempar dari model, tangkap di sini
			// $e->getMessage() akan berisi "Maaf, hanya satu user admin yang diizinkan."

			// Redirect kembali dengan pesan error
			return redirect()->back()->with('error', $e->getMessage());
		}
    }
}
