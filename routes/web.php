<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;



//lokasi file Controller yang dibuat
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProfileController; 
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TranslatorController;
use App\Http\Controllers\DictionaryController;

use App\Http\Controllers\TranslationBridgeController; //translator stichoza google, dipakai untuk kamus dari asing ke kambera, 26 jan 2026

use App\Http\Controllers\VisitorController;

//rute untuk menampilkan halaman data pengjung web--23/04/2026
Route::get('/monitoring-pengunjung', [VisitorController::class, 'index'])->middleware('auth');

// 1. Rute Tampilan: Menampilkan halaman utama translator

Route::get('/translator-sumba', [TranslatorController::class, 'index'])->name('translator.index');

// 2. Rute Tampilan BARU: Menampilkan halaman manajemen kamus (CRUD)
// Akses: /admin/dictionary
Route::get('/admin/dictionary', [TranslatorController::class, 'dictionaryIndex'])->name('dictionary.manage');

/*
|--------------------------------------------------------------------------
| API ROUTES (Untuk permintaan AJAX/JSON)
|--------------------------------------------------------------------------
| Rute-rute ini biasanya berada di file routes/api.php dan otomatis memiliki prefix '/api'
|
*/
//RUTE API KRITIS: Menghandle permintaan POST dari JavaScript (terjemahan)
Route::post('/api/translate', [TranslatorController::class, 'translate'])->name('translator.translate');

// Route untuk CRUD Dictionary
Route::post('/api/dictionary/add', [DictionaryController::class, 'addWord']);
Route::get('/api/dictionary/search', [DictionaryController::class, 'searchWord']);
Route::put('/api/dictionary/update/{uniqueId}', [DictionaryController::class, 'updateWord']);
Route::delete('/api/dictionary/delete/{id}', [DictionaryController::class, 'deleteWord']);
//------------------------------------------------------------------------------

// Rute untuk menjembatani terjemahan ke Bahasa Indonesia, 26 jan 2026
Route::post('/bridge-translate', [TranslationBridgeController::class, 'translateToIndo'])->name('bridge.translate');


// Rute untuk memproses data login dari form
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/login', [LoginController::class, 'loginForm'])->name('gate');

// Rute untuk menampilkan form lupa password
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');

// Rute untuk memproses permintaan lupa password (mengirim email)
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Rute untuk menampilkan form reset password setelah user mengklik link di email
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');

// Rute untuk memproses formulir reset password (mengupdate password)
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

//************************************************************



// Rute untuk menampilkan halaman formulir pendaftaran

// Rute untuk menampilkan formulir pendaftaran
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
// Rute untuk memproses data pendaftaran
Route::post('/register', [RegisterController::class, 'store'])->name('save');

// admin
Route::get('/registerme', [RegisterController::class, 'showRegistrationme'])->name('registerme');
Route::post('/registerme', [RegisterController::class, 'storeme'])->name('saveme');


// Rute untuk memproses pengiriman formulir kontak
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');


//kebijakan privasi yang disyaratkan fb
Route::get('/privacy-policy', function () {
    return view('privacy-policy');
});
//kebijakan penghapusan data user yang disyaratkan fb
Route::get('/data-deletion', function () {
    return view('data-deletion');
});

// Rute untuk mengarahkan pengguna ke halaman otorisasi Google/Facebook
Route::get('/auth/{provider}', [LoginController::class, 'redirectToProvider'])->name('socialite.redirect');

// Rute untuk menangani respons dari Google/Facebook setelah login berhasil
Route::get('/auth/{provider}/callback', [LoginController::class, 'handleProviderCallback'])->name('socialite.callback');

// Rute untuk proses logout
Route::get('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Rute untuk menampilkan semua artikel
Route::get('/articles', [ArticleController::class, 'index'])->name('articles');
// Rute untuk menampilkan satu artikel berdasarkan ID-nya
Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('articles.show') ->where('id', '[0-9]+');


///// Grup rute yang hanya bisa diakses oleh pengguna yang sudah login
Route::middleware(['auth'])->group(function () {
// Rute untuk menampilkan form pembuatan artikel baru
Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
// Rute untuk memproses data dari form dan menyimpannya ke database
Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');

// Rute untuk menampilkan form edit artikel
Route::get('/articles/{id}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
// Rute untuk memproses data dari form edit
Route::put('/articles/{id}', [ArticleController::class, 'update'])->name('articles.update');

// Rute untuk menghapus artikel
Route::delete('/articles/{id}', [ArticleController::class, 'destroy'])->name('articles.destroy');



// Rute untuk mengelola profil pengguna
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
	
});
///////
//menampilkan profile
Route::get('/profile', [ProfileController::class, 'show'])->name('profile');


// Rute untuk halaman Beranda
Route::get('/', [HomeController::class, 'index'])->name('home');

use Illuminate\Support\Facades\Artisan;

/*ini berguna untuk menghapus paksa cache laravel jika tidak memiliki terminal di hosting, aktifkan untuk membersihkan --20/4/2026
pernah saya kasus ubah2 -authorized riderict URIs google console jadinya terblok oleh google karena laravel masih mengirim APP-URL yang lama, jadi saya gunakan kode ini untuk membersihkan cachenya.
Route::get('/force-clear', function() {
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    //Artisan::call('cache:clear');
    return "Memori Laravel sudah bersih total, Suhu! Silakan coba login lagi.";
}); */