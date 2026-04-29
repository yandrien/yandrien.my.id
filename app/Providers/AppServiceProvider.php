<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Visitor; // Ganti dengan Model Statistik Suhu
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Bagikan data ke file footer di semua halaman
        View::composer('*', function ($view) {
            // Ambil data statistik dari database Suhu
            $unique_visitors = Visitor::count(); // Sesuaikan query-nya
            $total_hits = Visitor::sum('hits');  // Sesuaikan query-nya
            $top_countries = Visitor::select('country')
                                ->orderBy('hits', 'desc')
                                ->limit(1)
                                ->get();

            $view->with([
                'unique_visitors' => $unique_visitors,
                'total_hits'      => $total_hits,
                'top_countries'   => $top_countries
            ]);
        });
    }
}