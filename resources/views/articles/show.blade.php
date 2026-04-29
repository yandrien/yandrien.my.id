@extends('layouts.app')

@section('title', 'Artikel')
@section('content')
<div class="container mx-auto px-4 py-16">
    {{-- Judul Artikel --}}
    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4 leading-tight">
        {{ $article->judul }}
    </h1>
    
    <div class="flex items-center gap-4 text-gray-600 text-sm md:text-base mb-8">
        <p>
            <span data-key="article_terbit" class="font-semibold text-gray-900">Diterbitkan:</span> 
            <span data-isodateartikel="{{ \Carbon\Carbon::parse($article->tanggal_terbit)->toIso8601String() ?? '' }}" class="tglartikel"> {{ $article->tanggal_terbit->format('d F Y') }}</span>
        </p>
        @if($article->status == 'draft')
            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Draft</span>
        @endif
    </div>

    {{-- Gambar Utama --}}
    @if($article->img_preview)
        <div class="mb-10 rounded-2xl overflow-hidden shadow-lg">
            <img src="{{ asset('storage/' . $article->img_preview) }}" 
                 alt="{{ $article->judul }}" 
                 class="w-full h-auto max-h-[500px] object-cover"
                 onerror="this.src='https://placehold.co/1200x600?text=Gambar+Tidak+Tersedia'">
        </div>
    @endif

    {{-- Konten Artikel --}}
    <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed mb-12">
        {!! $article->isi !!}
    </div>

    {{-- SECTION LAMPIRAN DOKUMEN --}}
    @if($article->lampiran_doc)
    <div class="mt-8 p-6 bg-blue-50 rounded-2xl border border-blue-100">
        <h3 class="text-lg font-bold text-blue-900 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
            <span data-key="dok_lampiran">Dokumen Lampiran</span>
        </h3>
        <div class="flex flex-wrap gap-4">
            {{-- Tombol Lihat (View) - Cocok untuk PDF --}}
            <a href="{{ asset('storage/' . $article->lampiran_doc) }}" 
               target="_blank" 
               class="inline-flex items-center px-6 py-3 bg-white border border-blue-600 text-blue-600 font-semibold rounded-xl hover:bg-blue-50 transition duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                <span data-key="lihat_dok">Lihat Dokumen</span>
            </a>

            {{-- Tombol Download Langsung --}}
            <a href="{{ asset('storage/' . $article->lampiran_doc) }}" 
               download 
               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 shadow-md transition duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                <span data-key="down_dok">Download Dokumen</span>
            </a>
        </div>
        <p class="mt-3 text-sm text-blue-600 italic" data-key="des_lihat">
            * Klik "Lihat" untuk membuka di tab baru, atau "Download" untuk menyimpan ke perangkat.
        </p>
    </div>
    @endif

    {{-- Navigasi & Aksi --}}
    <div class="mt-12 pt-8 border-t border-gray-200 flex flex-wrap justify-center gap-4 items-center" x-data="{ openModal: false }">
        <a href="{{ route('articles') }}" class="inline-flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 px-8 rounded-full transition duration-300">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span data-key="kembali_list">Kembali ke List</span>
        </a>
                    
        @auth
            @if(auth()->id() === (int)$article->user_id || auth()->user()->is_admin)
                <a href="{{ route('articles.edit', $article->id) }}" class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-full transition duration-300 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span data-key="edit_artikel">Edit Artikel</span>
                </a>
        
                <button @click.prevent="openModal = true" class="inline-flex items-center justify-center bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-full transition duration-300 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    <span data-key="hapus">Hapus</span>
                </button>
            @endif
        @endauth

        {{-- Modal Konfirmasi (Tetap sama seperti kode Anda sebelumnya) --}}
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm" 
             x-show="openModal" 
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100">
            
            <div class="bg-white p-8 rounded-2xl shadow-2xl max-w-sm w-full mx-4" @click.away="openModal = false">
                <div class="text-red-600 mb-4">
                    <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 data-key="hapusartikel" class="text-xl font-bold text-center text-gray-900 mb-2">Hapus Artikel?</h3>
                <p class="text-center text-gray-600 mb-6"><span data-key="delwarning">Tindakan ini permanen. Artikel</span> <strong>{{ $article->judul }}</strong> <span data-key="delwarning2">akan dihapus selamanya.</span></p>
                
                <div class="flex flex-col gap-3">
                    <form action="{{ route('articles.destroy', $article->id) }}" method="POST" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl transition duration-200">
                            <span data-key="yahapus">Ya, Hapus Sekarang</span>
                        </button>
                    </form>
                    <button @click="openModal = false" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 rounded-xl transition duration-200">
                        <span data-key="batal">Batalkan</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection