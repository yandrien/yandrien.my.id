<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //Agar middleware TrackVisitor berjalan otomatis di setiap halaman, kita harus mendaftarkannya.23/04/2026
		$middleware->append(\App\Http\Middleware\TrackVisitor::class);
		
		//pengecualian proteksi CSRF translator jadi halaman tidak perlu direfres untuk token baru --29/10/2026
		// rute POST translator dan CRUD kamus agar bebas dari error 419
        $middleware->validateCsrfTokens(except: [
            'api/translate',
            'api/dictionary/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();