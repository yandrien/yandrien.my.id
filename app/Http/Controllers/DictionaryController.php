<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KamberaDictionary;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException; // Tambahkan ini untuk findOrFail

/**
 * Controller yang menangani semua operasi CRUD (Create, Read, Update, Delete)
 * dan list/pagination untuk data kamus.
 */
class DictionaryController extends Controller
{
    // --- FUNGSI TAMBAH KATA BARU ---

    public function addWord(Request $request)

    {

        try {

            // 1. Validasi Input

            $validated = $request->validate([

                'id_word' => 'required|string|max:255|unique:kambera_dictionary,id_word', // Harus unik

                'sbk_word' => 'required|string|max:255',

            ], [

                'id_word.unique' => 'Kata Bahasa Indonesia ini sudah ada di dalam kamus.',

                'id_word.required' => 'Kata Bahasa Indonesia harus diisi.',

                'sbk_word.required' => 'Kata Sumba Kambera harus diisi.',

            ]);



            // 2. Normalisasi (lowercase)

            $validated['id_word'] = strtolower($validated['id_word']);

            $validated['sbk_word'] = strtolower($validated['sbk_word']);



            // 3. Simpan ke Database

            $newWord = KamberaDictionary::create($validated);



            // 4. Berhasil: Kembalikan respons JSON

            return response()->json([

                'status' => 'success',

                'message' => 'Kata baru berhasil ditambahkan ke kamus!',

                'word' => $validated['id_word'].' = '.$validated['sbk_word']// Kembalikan objek kata yang baru dibuat

            ]);



        } catch (\Illuminate\Validation\ValidationException $e) {

            // Tangani error validasi

            return response()->json([

                'status' => 'validation_error',

                'errors' => $e->errors(),

                'message' => 'Gagal menyimpan karena ada data yang tidak valid.'

            ], 422);

            

        } catch (\Exception $e) {

            // Tangani error database/server lainnya

            return response()->json([

                'status' => 'error',

                'message' => 'Terjadi kesalahan server saat menyimpan data: ' . $e->getMessage(),

            ], 500);

        }

    }

    // --- FUNGSI DAFTAR & PENCARIAN UMUM TERPAGINASI (Wajib Paginasi) ---
    /**
     * Mengambil daftar kata kamus dengan dukungan pencarian dan pagination.
     * Paginasi HANYA DIPERLUKAN di fungsi ini karena mengembalikan LIST data.
     */
    public function getDictionaryList(Request $request)
    {
        try {
            $query = strtolower(trim($request->input('query', '')));
            $perPage = $request->input('per_page', 15);

            $words = KamberaDictionary::query()
                ->when($query, function ($q) use ($query) {
                    // Cari di kedua kolom dengan LIKE
                    return $q->where('indonesian_word', 'like', "%{$query}%")
                             ->orWhere('kambera_word', 'like', "%{$query}%");
                })
                ->orderBy('indonesian_word', 'asc')
                ->paginate($perPage); // <-- Paginasi diterapkan di sini

            return response()->json([
                'status' => 'success',
                'data' => $words,
                'message' => 'Data kamus berhasil dimuat.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mencari data kamus: ' . $e->getMessage(),
            ], 500);
        }
    }

    // --- FUNGSI PENCARIAN KATA TUNGGAL (Untuk Modal Edit/Hapus) ---
    /**
     * Mencari kata spesifik untuk mengisi form edit/hapus.
     * Mengembalikan ID unik untuk operasi Update/Delete berikutnya.
     */
    public function searchWord(Request $request)
    {
        $word = $request->query('word');
        $mode = $request->query('mode'); // 'id' atau 'sbk'

        if (!$word || !in_array($mode, ['id', 'sbk'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Parameter pencarian (word dan mode) tidak valid.'
            ], 400);
        }

        $column = $mode === 'id' ? 'id_word' : 'sbk_word';

        // Menggunakan ->first() karena kita hanya mencari SATU hasil. TIDAK ADA PAGINASI.
        $result = KamberaDictionary::where($column, strtolower($word))->first();

        if ($result) {
            return response()->json([
                'status' => 'success',
                'word_pair' => [
                    'id' => $result->id, // <-- PENTING: Mengembalikan ID unik
                    'id_word' => $result->id_word,
                    'sbk_word' => $result->sbk_word,
                ]
            ]);
        }

        return response()->json([
            'status' => 'not_found',
            'message' => 'Kata tidak ditemukan dalam kamus.'
        ]);
    }

    // --- FUNGSI UPDATE KATA (MENGGUNAKAN ID UNIK) ---
    /**
     * Memperbarui entri kata yang ada di kamus berdasarkan ID unik.
     * @param int $id ID unik dari database.
     */
    public function updateWord($id, Request $request)
    {
        try {
            // 1. Temukan Data Berdasarkan ID
            $wordPair = KamberaDictionary::findOrFail($id); // Lebih aman daripada mencari berdasarkan string kata

            $newIdWord = strtolower($request->id_word);
            $newSbkWord = strtolower($request->sbk_word);

            // 2. Validasi Input
            $validator = Validator::make($request->all(), [
                'id_word' => [
                    'required',
                    'string',
                    'max:255',
                    'lowercase',
                    // Pastikan ID baru unik, kecuali untuk record yang sedang diupdate (berdasarkan ID unik)
                    Rule::unique('kambera_dictionary', 'id_word')->ignore($id) 
                ],
                'sbk_word' => 'required|string|max:255|lowercase',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'validation_error',
                    'errors' => $validator->errors(),
                    'message' => 'Validasi gagal.'
                ], 422);
            }

            // 3. Update Data
            DB::beginTransaction();

            $wordPair->update([
                'id_word' => $newIdWord,
                'sbk_word' => $newSbkWord,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'id' => $wordPair->id,
                'message' => 'Kata berhasil diperbarui.'
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'Entri kata dengan ID ' . $id . ' tidak ditemukan untuk diperbarui.'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan perubahan karena masalah database: ' . $e->getMessage(),
            ], 500);
        }
    }

    // --- FUNGSI HAPUS KATA (MENGGUNAKAN ID UNIK) ---
    /**
     * Menghapus entri kata dari kamus berdasarkan ID unik.
     * @param int $id ID unik dari database.
     */
    public function deleteWord($id)
    {
        try {
            // 1. Cari dan Hapus Data Berdasarkan ID unik
            $wordPair = KamberaDictionary::findOrFail($id);
            $wordPair->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Kata berhasil dihapus.',
                'id' => $id
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'Entri kata dengan ID ' . $id . ' tidak ditemukan untuk dihapus.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus kata karena masalah database: ' . $e->getMessage(),
            ], 500);
        }
    }
}
