<?php

namespace App\Http\Controllers;

//impor kelas controller
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    /**
     * Membuat instance controller baru.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
	

    /**
     * Menampilkan formulir permintaan reset password.
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Mengirim link reset password ke alamat email pengguna.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function sendResetLinkEmail(Request $request)
    {
        // Validasi email
        $request->validate(['email' => 'required|email']);

        // Kirim link reset password
        $response = Password::broker()->sendResetLink(
            $request->only('email')
        );

        // Jika berhasil, redirect dengan pesan status
        if ($response == Password::RESET_LINK_SENT) {
            return back()->with('status', trans($response));
        }

        // Jika gagal, lemparkan exception dengan pesan error
        throw ValidationException::withMessages([
            'email' => [trans($response)],
        ]);
    }
}
