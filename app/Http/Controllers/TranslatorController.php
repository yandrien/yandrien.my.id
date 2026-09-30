<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KamberaDictionary;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;

/**
 * Controller yang menangani logika penerjemahan dari Indonesia ke Sumba Kambera
 * dan manajemen kamus (CRUD).
 * Menggunakan kolom id_word (Indonesia) dan sbk_word (Sumba Kambera).
 */
class TranslatorController extends Controller
{
    /**
     * Menampilkan halaman translator (penerjemahan utama).
     */
    public function index()
    {
        // Mengarah ke file translator.blade.php
        return view('translator'); 
    }


   /**
     * Logika untuk memproses terjemahan dari Database (Indonesia <-> Sumba Kambera).
     */
	 
	  /**
     * Set karakter tanda baca yang akan dihilangkan dari kata saat pencarian kamus.
     * Hyphen (-) sengaja dihilangkan dari daftar ini agar kata majemuk (e.g., "e-mail") tetap utuh
     * saat dibersihkan menjadi "email" untuk pencarian, alih-alih dipecah.
     * * HATI-HATI: Jika Anda ingin mengecualikan hyphen dari semua operasi, daftar ini harus diperiksa.
     * Namun, untuk menjaga kestabilan kode, kita akan gunakan daftar ini hanya untuk pembersihan kata sebelum lookup.
     */
    private const PUNCTUATION_TO_CLEAN = '.,?!:;\"\'\/()\[\]\{\}<>'; // Hyphen (-) tidak termasuk di sini
	 
    public function translate(Request $request)
	{
		try {
			// 1. Validasi permintaan
			$request->validate([
				'text' => 'required|string|max:1000',
				'mode' => 'required|in:id_to_sbk,sbk_to_id',
			]);

			$sourceText = strtolower($request->input('text'));
			$mode = $request->input('mode');

			// Rapikan spasi setelah tanda baca
			$sourceText = preg_replace('/([' . self::PUNCTUATION_TO_CLEAN . '])(?!\p{P})(\S)/u', '$1 $2', $sourceText);

			$sourceColumn = ($mode === 'id_to_sbk') ? 'id_word' : 'sbk_word';
			$targetColumn = ($mode === 'id_to_sbk') ? 'sbk_word' : 'id_word';

			// 2. Ekstrak kata tanpa tanda baca untuk pembentukan N-Gram
			$words = preg_split('/\s+/u', trim($sourceText), -1, PREG_SPLIT_NO_EMPTY);
			$cleanWords = array_map(function($w) {
				return preg_replace('/[' . self::PUNCTUATION_TO_CLEAN . ']/u', '', $w);
			}, $words);

			$totalWords = count($cleanWords);
			$ngrams = [];

			// Generate kombinasi frasa (dari terpanjang ke 1 kata)
			for ($length = $totalWords; $length >= 1; $length--) {
				for ($i = 0; $i <= $totalWords - $length; $i++) {
					$phrase = implode(' ', array_slice($cleanWords, $i, $length));
					if (!empty(trim($phrase))) {
						$ngrams[] = trim($phrase);
					}
				}
			}

			$ngrams = array_unique($ngrams);

			if (empty($ngrams)) {
				return response()->json([
					'status' => 'success',
					'translation' => ucfirst($sourceText),
				]);
			}

			// 3. Query Bulk Lookup ke Database (Diurutkan berdasarkan yang TERPANJANG)
			$dictionary = KamberaDictionary::whereIn($sourceColumn, $ngrams)
				->select($sourceColumn, $targetColumn)
				// Diurutkan dari string terpanjang ke terpendek agar frasa didahulukan
				->orderByRaw("LENGTH({$sourceColumn}) DESC") 
				->pluck($targetColumn, $sourceColumn)
				->toArray();

			// 4. Lakukan Penggantian Frasa/Kata pada Teks Asli
			$resultText = $sourceText;

			foreach ($dictionary as $sourceWord => $targetWord) {
				// Regex \b (word boundary) memastikan kata tidak tertukar di pertengahan kata lain
				$pattern = '/\b' . preg_quote($sourceWord, '/') . '\b/iu';
				$resultText = preg_replace($pattern, $targetWord, $resultText);
			}

			// 5. Rapikan Kapitalisasi Huruf Pertama
			$finalTranslation = ucfirst($resultText);

			return response()->json([
				'status' => 'success',
				'translation' => $finalTranslation,
			]);

		} catch (\Exception $e) {
			\Log::error('Translation Error: ' . $e->getMessage(), [
				'text' => $request->input('text'),
				'mode' => $request->input('mode')
			]);

			return response()->json([
				'status' => 'error',
				'message' => 'Gagal memproses terjemahan: ' . $e->getMessage(),
			], 500);
		}
	}
}
	
   