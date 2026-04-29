<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
	
	public function __construct()
    {
		//tamu hanya dapat mengakses index dan show aj, tidak crud
        $this->middleware('auth')->except(['index', 'show']);
    }
	
  	/**
     * Menampilkan daftar artikel untuk publik dan pemilik draft.
     */
    public function index()
    {
        // 1. Ambil ID user yang sedang login (akan null jika belum login)
        $userId = Auth::id();

        // 2. Gunakan query builder dengan pengelompokan logika yang lebih kuat
        $articles = Article::where(function ($query) use ($userId) {
            
            // Kondisi Utama: Semua orang (tamu/login) bisa melihat yang 'publish'
            $query->where('status', 'publish');

            // Kondisi Tambahan: Jika user login, tampilkan juga draft miliknya sendiri
            if ($userId) {
                $query->orWhere(function ($q) use ($userId) {
                    $q->where('status', 'draft')
                      ->where('user_id', $userId);
                });
            }
        })
        ->orderBy('tanggal_terbit', 'desc') // Mengurutkan berdasarkan tanggal terbit terbaru
        ->orderBy('created_at', 'desc')     // Backup urutan berdasarkan waktu buat
        ->get();

        // 3. Kirim data ke view
        // Pastikan nama file view sesuai (contoh: resources/views/articles/index.blade.php)
        return view('articles', compact('articles'));
    }
	
	 public function show($id)
    {
        // Ambil satu artikel berdasarkan ID-nya
        //$article = Article::find($id);
		
		// Ambil satu artikel berdasarkan ID-nya, atau tampilkan halaman 404 jika tidak ditemukan
		$article = Article::findOrFail($id);

        // Kirim artikel ke view 'articles.show'
        return view('articles.show', compact('article'));
    }
	
	/**
     * Tampilkan formulir untuk membuat artikel baru.
     */
    public function create()
    {
        return view('articles.create');
    }

    /**
     * Simpan artikel baru ke database.
     */
   public function store(Request $request)
	{
    // 1. Validasi input
    // Ubah 'string' menjadi 'file' dan tentukan mimes (ekstensi) agar lebih aman
    $validatedData = $request->validate([
        'judul' => 'required|max:255',
        'isi' => 'required',
        'status' => 'required|in:publish,draft',
        'tanggal_terbit' => 'required|date',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'lampiran_doc' => 'nullable|file|mimes:doc,docx,pdf|max:5120', // Max 5MB
    ]);

    // 2. Tambahkan user_id
    $validatedData['user_id'] = auth()->id(); 

    // 3. Kelola upload GAMBAR (Thumbnail)
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('articles/thumbnails', 'public');
        // Masukkan path ke array validatedData agar ikut tersimpan saat create()
        $validatedData['image'] = $imagePath; 
    }

    // 4. Kelola upload DOKUMEN (Lampiran)
    if ($request->hasFile('lampiran_doc')) {
        $docPath = $request->file('lampiran_doc')->store('articles/documents', 'public');
        // Pastikan nama kolom di database sesuai (misal: lampiran_doc)
        $validatedData['lampiran_doc'] = $docPath;
    }

    // 5. Simpan data ke Database
    // Pastikan kolom 'image' dan 'lampiran_doc' sudah ada di $fillable model Article
    Article::create($validatedData);

    return redirect()->route('articles')->with('success', 'Artikel dan lampiran berhasil dibuat!');
	}
	
	/**
     * Tampilkan formulir untuk mengedit artikel.
     */
    public function edit($id)
    {
        $article = Article::findOrFail($id);
        
        //Cek kepemilikan
		if ((int)$article->user_id !== auth()->id() && !auth()->user()->is_admin) {
			// Kembalikan langsung ke halaman list artikel
			return redirect()->route('articles');
		}
        
        return view('articles.edit', compact('article'));
    }

    /**
     * Perbarui artikel yang ada di database.
     */
 public function update(Request $request, $id)
	{
    // 1. Cari artikel dan pastikan hanya pemilik yang bisa mengedit
    $article = Article::findOrFail($id);
    
    //Cek kepemilikan
	if ((int)$article->user_id !== auth()->id() && !auth()->user()->is_admin) {
		// Kembalikan langsung ke halaman list artikel
		return redirect()->route('articles');
	}
		
    //if ($article->user_id != Auth::id()) {
        //return redirect()->route('articles')->with('error', 'Anda tidak memiliki akses untuk mengedit artikel ini.');
    //}

    // 2. Validasi input (DIUBAH: lampiran_doc sekarang divalidasi sebagai file)
    $request->validate([
        'judul' => 'required|max:255',
        'isi' => 'required',
        'status' => 'required|in:publish,draft',
        'tanggal_terbit' => 'required|date',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'lampiran_doc' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,zip|max:5120' // Maks 5MB
    ]);

    // 3. Update data dasar
    $article->judul = $request->judul;
    $article->isi = $request->isi; 
    $article->status = $request->status;
    $article->tanggal_terbit = $request->tanggal_terbit;

    // 4. Kelola upload gambar (img_preview) jika ada file baru
    if ($request->hasFile('image')) {
        // Hapus gambar lama jika ada di storage
        if ($article->img_preview) {
            Storage::disk('public')->delete($article->img_preview);
        }

        // Simpan gambar baru
        $pathImage = $request->file('image')->store('articles/thumbnails', 'public');
        $article->img_preview = $pathImage;
    }

    // 5. Kelola upload lampiran_doc jika ada file baru
    if ($request->hasFile('lampiran_doc')) {
        // Hapus dokumen lama jika ada di storage
        if ($article->lampiran_doc) {
            Storage::disk('public')->delete($article->lampiran_doc);
        }

        // Simpan dokumen baru
        $pathDoc = $request->file('lampiran_doc')->store('articles/documents', 'public');
        $article->lampiran_doc = $pathDoc;
    }

    // 6. Simpan perubahan
    $article->save();

    // 7. Redirect dengan pesan sukses
    return redirect()->route('articles.show', $article->id)
                     ->with('success', 'Artikel berhasil diperbarui!');
	}
	
	public function destroy($id)
	{
     $article = Article::findOrFail($id);

    //Cek kepemilikan
	if ((int)$article->user_id !== auth()->id() && !auth()->user()->is_admin) {
		// Kembalikan langsung ke halaman list artikel
		return redirect()->route('articles');
	}
		
    $article->delete();

    return redirect()->route('articles')->with('success', 'Artikel berhasil dihapus.');
	}
}
