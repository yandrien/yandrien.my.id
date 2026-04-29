@extends('layouts.app')

@section('title', 'Edit Artikel')
@section('content')
<!-- Include Quill Stylesheet -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    #editor-container {
        height: 400px;
        font-family: 'Inter', sans-serif;
        font-size: 16px;
    }
    .ql-toolbar.ql-snow { border-top-left-radius: 8px; border-top-right-radius: 8px; border-color: #d2d2d2; }
    .ql-container.ql-snow { border-bottom-left-radius: 8px; border-bottom-right-radius: 8px; border-color: #d2d2d2; }
    
    .toggle-checkbox:checked { right: 0; border-color: #059669; }
    .toggle-checkbox:checked + .toggle-label { background-color: #059669; }
</style>

<div class="container mx-auto px-4 py-8 md:py-12 max-w-4xl">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between border-b pb-6">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Edit Artikel</h1>
            <p class="text-gray-500 mt-1">Sesuaikan konten, gambar, dan pengaturan publikasi Anda.</p>
        </div>
        <div class="mt-4 md:mt-0 flex gap-3">
            <a href="{{ route('articles.show', $article->id) }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" form="main-form" class="px-6 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 shadow-sm transition">
                Simpan Perubahan
            </button>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-r-lg">
            <div class="flex">
                <div class="ml-3">
                    <p class="text-sm text-red-700 font-bold">Terjadi kesalahan input:</p>
                    <ul class="mt-1 text-sm text-red-600 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('articles.update', $article->id) }}" id="main-form" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Kolom Kiri -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <label for="judul" class="block text-sm font-bold text-gray-700 uppercase tracking-wider mb-2">Judul Artikel</label>
                    <input type="text" name="judul" id="judul" 
                        class="w-full px-4 py-3 text-lg font-semibold border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition" 
                        value="{{ old('judul', $article->judul) }}" required>
                </div>

                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <label class="block text-sm font-bold text-gray-700 uppercase tracking-wider mb-2">Isi Konten</label>
                    <div id="editor-container" class="notranslate">{!! old('isi', $article->isi) !!}</div>
                    <input type="hidden" name="isi" id="isi-input">
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="space-y-6">
                <!-- Status Toggle -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <label class="block text-sm font-bold text-gray-700 uppercase tracking-wider mb-4">Status Publikasi</label>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                        <span id="status-text" class="text-sm font-semibold">
                            {{ old('status', $article->status) === 'publish' ? 'Published' : 'Draft' }}
                        </span>
                        
                        <div class="relative inline-block w-12 mr-2 align-middle select-none transition duration-200 ease-in">
                            <input type="checkbox" id="status_toggle" 
                                class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer transition-all duration-300 {{ old('status', $article->status) == 'publish' ? 'right-0' : 'right-6' }}"
                                {{ old('status', $article->status) == 'publish' ? 'checked' : '' }}
                            />
                            <label for="status_toggle" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer transition-colors duration-300"></label>
                        </div>
                    </div>
                    <!-- Hidden input UTAMA yang dikirim ke Laravel -->
                    <input type="hidden" name="status" id="status-hidden" value="{{ old('status', $article->status) }}">
                </div>

                <!-- Thumbnail -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <label class="block text-sm font-bold text-gray-700 uppercase tracking-wider mb-3">Gambar Sampul</label>
                    <div class="relative group">
                        <div id="image-preview-container" class="w-full h-48 rounded-lg overflow-hidden bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center">
                            @if($article->img_preview)
                                <img src="{{ asset('storage/' . $article->img_preview) }}" id="preview-img" class="object-cover w-full h-full">
                            @else
                                <div id="placeholder-svg" class="text-center p-4">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <img src="" id="preview-img" class="hidden object-cover w-full h-full">
                            @endif
                        </div>
                        <input type="file" name="image" id="image-input" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                    </div>
                </div>

                <!-- Tanggal -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <label for="tanggal_terbit" class="block text-sm font-bold text-gray-700 uppercase tracking-wider mb-2">Tanggal Terbit</label>
                    <input type="date" name="tanggal_terbit" id="tanggal_terbit" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 transition" 
                        value="{{ old('tanggal_terbit', $article->tanggal_terbit ? $article->tanggal_terbit->format('Y-m-d') : date('Y-m-d')) }}" required>
                </div>
            </div>
        </div>
		
		<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mt-6">
    <label class="block text-xs font-bold text-gray-400 uppercase mb-4 tracking-wider">Lampiran Dokumen (.doc, .docx, .pdf)</label>
    
    <div class="flex flex-col gap-4">
        <!-- Area Dropzone -->
        <div id="drop-area" class="flex items-center justify-center w-full">
            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <svg class="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <p class="mb-2 text-sm text-gray-500 text-center px-4">
                        <span class="font-semibold">Klik untuk unggah</span> atau seret dokumen
                    </p>
                    <p class="text-xs text-gray-400">DOC, DOCX, atau PDF (Max. 5MB)</p>
                </div>
                <!-- Pastikan ID sama dengan yang di script -->
                <input type="file" id="file-input" name="lampiran_doc" class="hidden" accept=".doc,.docx,.pdf" />
            </label>
        </div>

        <!-- Area Preview (Awalnya Tersembunyi) -->
        <div id="file-preview" class="hidden animate-in fade-in duration-300">
            <div class="flex items-center justify-between p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-center gap-3">
                    <!-- Icon Container -->
                    <div id="icon-container" class="p-2 bg-white rounded-md shadow-sm">
                        <!-- Icon akan dimasukkan lewat JS -->
                    </div>
                    <div>
                        <p id="file-name" class="text-sm font-semibold text-blue-900 truncate max-w-[200px] md:max-w-xs">Nama file.docx</p>
                        <p id="file-size" class="text-xs text-blue-500">0 KB</p>
                    </div>
                </div>
                <!-- Tombol Hapus -->
                <button type="button" id="remove-file" class="p-1.5 text-blue-400 hover:text-red-600 hover:bg-red-50 rounded-full transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
    </form>
</div>

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    // 1. Quill Initialization
    const quill = new Quill('#editor-container', {
        modules: { toolbar: [ [{ 'header': [1, 2, false] }], ['bold', 'italic', 'underline'], [{ 'list': 'ordered'}, { 'list': 'bullet' }], ['link', 'image'], ['clean'] ] },
        theme: 'snow'
    });

    // 2. Toggle Status Logic
    const toggle = document.getElementById('status_toggle');
    const statusHidden = document.getElementById('status-hidden');
    const statusText = document.getElementById('status-text');

    function updateStatusUI(isPublished) {
        if (isPublished) {
            statusHidden.value = 'publish';
            statusText.innerText = 'Published';
            statusText.className = 'text-sm font-semibold text-green-700';
            toggle.classList.remove('right-6');
            toggle.classList.add('right-0');
        } else {
            statusHidden.value = 'draft';
            statusText.innerText = 'Draft';
            statusText.className = 'text-sm font-semibold text-gray-500';
            toggle.classList.remove('right-0');
            toggle.classList.add('right-6');
        }
    }

    // Event listener untuk perubahan toggle
    toggle.addEventListener('change', function() {
        updateStatusUI(this.checked);
    });

    // 3. Form Submit Logic (PENTING: Memastikan data terisi sebelum kirim)
    document.getElementById('main-form').onsubmit = function() {
        // Set isi konten dari Quill ke hidden input
        document.getElementById('isi-input').value = quill.root.innerHTML;
        
        // Final check untuk status (fallback)
        if (!statusHidden.value) {
            statusHidden.value = toggle.checked ? 'publish' : 'draft';
        }

        if(quill.getText().trim().length === 0) {
            alert('Isi artikel tidak boleh kosong!');
            return false;
        }
        
        return true;
    };

    // 4. Image Preview
    document.getElementById('image-input').addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = document.getElementById('preview-img');
                previewImg.src = e.target.result;
                previewImg.classList.remove('hidden');
                const placeholder = document.getElementById('placeholder-svg');
                if(placeholder) placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    });
	
	
	//manajemen unggah file document
	const fileInput = document.getElementById('file-input');
    const filePreview = document.getElementById('file-preview');
    const fileNameDisplay = document.getElementById('file-name');
    const fileSizeDisplay = document.getElementById('file-size');
    const iconContainer = document.getElementById('icon-container');
    const removeBtn = document.getElementById('remove-file');
    const dropArea = document.getElementById('drop-area');

    // Fungsi mendapatkan icon berdasarkan ekstensi
    function getIcon(filename) {
        const ext = filename.split('.').pop().toLowerCase();
        let color = "text-gray-500";
        
        if (ext === 'pdf') color = "text-red-600";
        if (ext === 'doc' || ext === 'docx') color = "text-blue-600";

        return `<svg class="w-6 h-6 ${color}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>`;
    }

    // Listener saat file dipilih
    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            
            // Tampilkan info file
            fileNameDisplay.textContent = file.name;
            fileSizeDisplay.textContent = (file.size / 1024).toFixed(1) + ' KB';
            iconContainer.innerHTML = getIcon(file.name);

            // Munculkan preview, sembunyikan upload box (opsional)
            filePreview.classList.remove('hidden');
            dropArea.classList.add('hidden');
        }
    });

    // Reset file
    removeBtn.addEventListener('click', function() {
        fileInput.value = ''; // Kosongkan input
        filePreview.classList.add('hidden');
        dropArea.classList.remove('hidden');
    });
</script>
@endsection