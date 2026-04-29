<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends Controller
{
    /**
     * Membuat instance controller baru dan menerapkan middleware.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Menampilkan formulir untuk mengatur ulang password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function showResetForm(Request $request)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $request->route('token'), 'email' => $request->email]
        );
    }

    /**
     * Mengatur ulang password pengguna.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function reset(Request $request)
    {
        // Validasi data
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Atur ulang password menggunakan broker
        $response = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        // Jika berhasil, redirect ke home dan kirim pesan sukses
        if ($response == Password::PASSWORD_RESET) {
            return redirect(route('home'))->with('status', trans($response));
        }

        // Jika gagal, lemparkan exception dengan pesan error
        throw ValidationException::withMessages([
            'email' => [trans($response)],
        ]);
    }
}
