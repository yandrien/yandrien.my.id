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
        $sourceText = '';
        $mode = 'id_to_sbk';
        
        try {
            // 1. Validasi permintaan JSON
            $request->validate([
                'text' => 'required|string|max:1000',
                'mode' => 'required|in:id_to_sbk,sbk_to_id',
            ]);

            $sourceText = strtolower($request->input('text'));
            $mode = $request->input('mode');
			
			// --- PERBAIKAN SPASI SETELAH TANDA BACA (BARU DITAMBAHKAN) ---
            // Tujuannya: Memastikan ada spasi setelah tanda baca (misal: "saya,kamu" menjadi "saya, kamu")
            // Pola regex: Cari tanda baca ([[:punct:]]) yang diikuti oleh karakter non-spasi (\S).
            // Pengganti: Tanda baca ($1), spasi, karakter non-spasi ($2).
            //$sourceText = preg_replace('/([[:punct:]])(\S)/u', '$1 $2', $sourceText);
			
			
			$sourceText = preg_replace('/([' . self::PUNCTUATION_TO_CLEAN . '])(?!\p{P})(\S)/u', '$1 $2', $sourceText);
			
			//**`(?!\p{P})`**: Ini adalah perbaikan tambahan untuk menjaga elipsis (`...`) atau tanda baca berulang (`!!`) tetap utuh.
            // -------------------------------------------------------------
            
            // --- LOGIKA UTAMA: MENENTUKAN ARAH TERJEMAHAN ---
            $sourceColumn = ($mode === 'id_to_sbk') ? 'id_word' : 'sbk_word'; // Kolom untuk mencari
            $targetColumn = ($mode === 'id_to_sbk') ? 'sbk_word' : 'id_word'; // Kolom untuk diambil hasilnya
            // ----------------------------------------------------
            
            // 2. Tokenisasi: Memecah kalimat, sambil mempertahankan pemisah (spasi)
            $words = preg_split('/(\s+)/', $sourceText, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
            
            $translatedWords = [];

            // 2a. Saring kata-kata non-spasi untuk diolah
            $searchableWordsWithPunctuation = array_filter($words, function($word) {
                return !ctype_space($word) && !empty(trim($word));
            });
            
            // 2b. Hapus tanda baca dari daftar kata untuk QUERY ke database
            $uniqueCleanWords = [];
            foreach ($searchableWordsWithPunctuation as $word) {
                // Hapus semua tanda baca dari kata untuk pencocokan database
                $cleaned = preg_replace('/[' . self::PUNCTUATION_TO_CLEAN . ']/u', '', $word);
                if (!empty($cleaned)) {
                    $uniqueCleanWords[] = $cleaned;
                }
            }
            // Pastikan hanya kata unik yang dicari
            $uniqueCleanWords = array_unique($uniqueCleanWords); 
            
            // 2c. Mengambil terjemahan dari kamus dalam satu query (bulk lookup)
            // Pencarian dilakukan berdasarkan kata yang sudah bersih ($uniqueCleanWords)
            $dictionaryWords = KamberaDictionary::whereIn($sourceColumn, $uniqueCleanWords)
                ->pluck($targetColumn, $sourceColumn)
                ->toArray();
            
            // 3. Susun kembali kalimat terjemahan
            foreach ($words as $token) {
                // Jika token adalah spasi, tambahkan spasi dan lanjut
                if (ctype_space($token)) {
                    $translatedWords[] = $token;
                    continue;
                }

                // 3a. Bersihkan token untuk mencocokkan dengan hasil kamus
                $cleanedToken = preg_replace('/[' . self::PUNCTUATION_TO_CLEAN . ']/u', '', $token);
                
                // 3b. Ekstrak tanda baca yang ada di awal dan akhir token asli ($token)
                $startPunctuation = '';
                $endPunctuation = '';
                
                // Cari tanda baca di awal (misal: "('kata')")
                if (preg_match('/^([[:punct:]]+)/u', $token, $matches)) {
                    $startPunctuation = $matches[1];
                }

                // Cari tanda baca di akhir (misal: "kata,")
                if (preg_match('/([[:punct:]]+)$/u', $token, $matches)) {
                    $endPunctuation = $matches[1];
                }

                // Cek apakah kata bersih ada di kamus
                if (isset($dictionaryWords[$cleanedToken])) {
                    // Ambil terjemahan
                    $translation = $dictionaryWords[$cleanedToken];
                    
                    // Gabungkan terjemahan dengan tanda baca asli (start dan end)
                    // Kita harus menghilangkan tanda baca yang sudah teridentifikasi dari token sebelum menempelkannya
                    // (Catatan: $token sudah di lowercase di awal)
                    $translatedWords[] = $startPunctuation . $translation . $endPunctuation;

                } else {
                    // Jika tidak ditemukan, kembalikan kata asli (termasuk tanda baca)
                    $translatedWords[] = $token;
                }
            }

            $translation = implode('', $translatedWords);

            // Perbaiki kapitalisasi (Opsional: hanya huruf pertama)
            if (!empty($translation)) {
                $translation = ucfirst($translation);
            }

            // 4. Berhasil: Kembalikan respons JSON
            return response()->json([
                'status' => 'success',
                'translation' => $translation,
            ]);

        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Translation Error: ' . $e->getMessage(), [
                'text' => $sourceText,
                'mode' => $mode
            ]);

            // 5. Gagal: Tangani error server dan kembalikan JSON error
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memproses terjemahan: ' . $e->getMessage(),
                'hint' => 'Pastikan koneksi database aktif dan model KamberaDictionary benar.'
            ], 500);
        }
    }
}
	
   