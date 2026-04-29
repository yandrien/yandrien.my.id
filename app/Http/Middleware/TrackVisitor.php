<?php
//Kita akan pakai API gratis dari ip-api.com

namespace App\Http\Middleware;

use Closure;
use App\Models\Visitor;
use Illuminate\Support\Facades\Http;

class TrackVisitor
{
    public function handle($request, Closure $next)
    {
        $ip = $request->ip();
        $today = now()->toDateString();

        // Cari apakah IP ini sudah ada di database untuk HARI INI
        $visitor = Visitor::where('ip_address', $ip)
                          ->whereDate('created_at', $today)
                          ->first();

        if ($visitor) {
            // JIKA SUDAH ADA: Tambah frekuensi kunjungan (hits)
            $visitor->increment('hits');
        } else {
            // JIKA BELUM ADA: Cari asal negara
            $country = 'Unknown';
            
            // Note: Jangan cek IP lokal (127.0.0.1) ke API karena pasti gagal
            if ($ip !== '127.0.0.1' && $ip !== '::1') {
                try {
                    $response = Http::timeout(3)->get("http://ip-api.com/json/{$ip}");
                    if ($response->successful()) {
                        $country = $response->json()['country'] ?? 'Unknown';
                    }
                } catch (\Exception $e) {
                    // Jika API limit atau offline, biarkan Unknown
                }
            }

            Visitor::create([
                'ip_address'   => $ip,
                'country'      => $country,
                'page_visited' => $request->fullUrl(),
                'browser'      => $request->header('User-Agent'),
                'hits'         => 1,
            ]);
        }

        return $next($request);
    }
}
