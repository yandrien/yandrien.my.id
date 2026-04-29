<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslationBridgeController extends Controller
{
    public function translateToIndo(Request $request)
    {
        // Validasi input
        $request->validate([
            'text' => 'required|string'
        ]);

        try {
            $tr = new GoogleTranslate('id'); // Target: Indonesia
            $hasil = $tr->translate($request->text);

            return response()->json([
                'status' => 'success',
                'translated' => $hasil
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menerjemahkan'
            ], 500);
        }
    }
}