<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//mengaktifkan fungsionalitas Socialite
use Laravel\Socialite\Facades\Socialite; // Tambahkan ini
use App\Models\User; // Tambahkan ini
use Illuminate\Support\Str; // Tambahkan ini

class LoginController extends Controller
{
	
	
	public function loginForm()
	{
		return redirect(route('home'))->with('status', 'back');
	}
		
	
       /**
     * Proses login email/password.
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) { //memverifikasi kredensial email dan password
            $request->session()->regenerate(); //buat sesi untuk user. Laravel hanya menyimpan id pengguna di dalam sesi.
            // Kembalikan respons JSON sukses jika ini adalah request AJAX
            if ($request->wantsJson() || $request->ajax()) {
				// Untuk AJAX, kembalikan data pengguna (opsional)
                return response()->json(['success' => true,
				'user' => Auth::user()  //kirim data pengguna sebagai repons
				//Auth::user(): Menggunakan id dari sesi untuk mengambil semua data pengguna dari database saat Anda memerlukannya.
				]);
            }
            // Jika bukan request AJAX, arahkan seperti biasa
            return redirect()->intended(route('home'));
        }

        // Jika login gagal
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Informasi yang diberikan tidak cocok dengan data kami.'], 401);
        }
        
        return back()->withErrors([
            'email' => 'Informasi yang diberikan tidak cocok dengan data kami.',
        ])->onlyInput('email');
    }
    
    // --- Metode Baru untuk Login Sosial ---

    /**
     * Redirect the user to the social provider's authentication page.
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from the social provider.
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
   public function handleProviderCallback($provider)
    {
        try {
            // Menggunakan variabel dinamis sesuai provider (google/facebook)
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['msg' => 'Gagal login melalui ' . ucfirst($provider)]);
        }
    
        // Cari pengguna berdasarkan email
        $existingUser = User::where('email', $socialUser->getEmail())->first();
        
        if ($existingUser) {
            // Jika user sudah ada, update data terbaru
            $existingUser->provider = $provider;
            $existingUser->provider_id = $socialUser->getId();
            $existingUser->avatar = $socialUser->getAvatar(); // Simpan/update foto terbaru
            $existingUser->save();
    
            auth()->login($existingUser, true);
        } else {
            // Jika user baru
            $newUser = new User;
            $newUser->name = $socialUser->getName();
            $newUser->email = $socialUser->getEmail();
            
            // CARA YANG BENAR:
            $newUser->avatar = $socialUser->getAvatar(); // Ambil foto dari socialUser
            
            $newUser->password = bcrypt(\Illuminate\Support\Str::random(16));
            $newUser->provider = $provider;
            $newUser->provider_id = $socialUser->getId();
            $newUser->save();
    
            auth()->login($newUser, true);
        }
    
        return redirect()->route('home');
    }
}