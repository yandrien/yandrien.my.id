@extends('layouts.app')

@section('title', 'Buat Artikel')
@section('content')
<!-- Quill 2.0 Stylesheet -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />

<style>
    :root {
        --primary-green: #059669;
    }
    #editor-container {
        height: 500px;
        font-size: 16px;
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
        background: white;
    }
    .ql-toolbar.ql-snow {
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        background-color: #f3f4f6;
        border-bottom: 1px solid #e5e7eb;
    }
    
    /* Styling Tabel & Kontrol */
    .ql-editor table {
        border-collapse: collapse;
        margin: 15px 0;
        width: 100%;
        table-layout: fixed;
    }
    .ql-editor td, .ql-editor th {
        border: 1px solid #d1d5db;
        padding: 8px;
        min-width: 50px;
    }
    
    /* Panel Kontrol Tabel */
    .table-management-panel {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 15px;
        background: #ffffff;
        border-left: 1px solid #e5e7eb;
        border-right: 1px solid #e5e7eb;
        border-bottom: 1px solid #f3f4f6;
        flex-wrap: wrap;
    }
    .table-label {
        font-size: 11px;
        font-weight: 800;
        color: #6b7280;
        text-transform: uppercase;
        margin-right: 5px;
    }
    .btn-table-action {
        font-size: 11px;
        padding: 5px 10px;
        border-radius: 6px;
        border: 1px solid #d1d5db;
        background: white;
        cursor: pointer;
        transition: all 0.2s;
        font-weight: 600;
        color: #374151;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .btn-table-action:hover {
        background: #f9fafb;
        border-color: #9ca3af;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .btn-table-danger {
        color: #dc2626;
    }
    .btn-table-danger:hover {
        background: #fef2f2;
        border-color: #fecaca;
        color: #b91c1c;
    }

    /* Toggle Switch */
    .toggle-checkbox:checked { right: 0; border-color: var(--primary-green); }
    .toggle-checkbox:checked + .toggle-label { background-color: var(--primary-green); }
</style>

<div class="container mx-auto px-4 py-8 max-w-5xl">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between border-b pb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Buat Artikel Baru</h1>
            <p class="text-gray-500 text-sm mt-1">Format teks dengan warna dan kelola tabel secara dinamis.</p>
        </div>
        <div class="mt-4 md:mt-0 flex gap-3">
            <button type="submit" form="main-form" class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-bold shadow-lg transition-all transform hover:-translate-y-0.5">
                Simpan Artikel
            </button>
        </div>
    </div>

    <form action="{{ route('articles.store') }}" id="main-form" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf

        <div class="lg:col-span-2 space-y-6">
            <!-- Input Judul -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-wider">Judul Artikel</label>
                <input type="text" name="judul" value="{{ old('judul') }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 outline-none text-xl font-semibold" 
                    placeholder="Masukkan judul artikel...">
            </div>

            <!-- Editor Section -->
            <div class="bg-white overflow-hidden rounded-xl shadow-sm border border-gray-200">
                <!-- Toolbar Tambah Tabel -->
                <div class="flex justify-between items-center p-4 border-b bg-gray-50/50">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Konten Utama</span>
                    <button type="button" id="insert-table" class="text-xs bg-white text-gray-700 border border-gray-300 px-3 py-1.5 rounded-md hover:bg-gray-50 flex items-center gap-1.5 font-bold shadow-sm transition">
                        <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Sisipkan Tabel
                    </button>
                </div>

                <!-- Manajemen Baris & Kolom (Aktif saat tabel dipilih) -->
                <div class="table-management-panel">
                    <span class="table-label">Opsi Tabel:</span>
                    <button type="button" class="btn-table-action" onclick="tableAction('insertRowBelow')">
                        <span class="text-green-600">+</span> Baris
                    </button>
                    <button type="button" class="btn-table-action" onclick="tableAction('insertColumnRight')">
                        <span class="text-green-600">+</span> Kolom
                    </button>
                    <div class="h-4 w-px bg-gray-300 mx-1"></div>
                    <button type="button" class="btn-table-action btn-table-danger" onclick="tableAction('deleteRow')">Hapus Baris</button>
                    <button type="button" class="btn-table-action btn-table-danger" onclick="tableAction('deleteColumn')">Hapus Kolom</button>
                    <button type="button" class="btn-table-action btn-table-danger" onclick="tableAction('deleteTable')">Hapus Tabel</button>
                </div>

                <div id="editor-container">{!! old('isi') !!}</div>
                <input type="hidden" name="isi" id="isi-input">
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <label class="block text-xs font-bold text-gray-400 uppercase mb-4 tracking-wider">Status Publikasi</label>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                    <span id="status-text" class="text-sm font-bold text-gray-600">Draft</span>
                    <div class="relative inline-block w-10 align-middle select-none">
                        <input type="checkbox" id="status_toggle" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer transition-all duration-300 right-4" />
                        <label for="status_toggle" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                    </div>
                    <input type="hidden" name="status" id="status-hidden" value="draft">
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <label class="block text-xs font-bold text-gray-400 uppercase mb-4 tracking-wider">Gambar Utama</label>
                <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-4 text-center group transition-colors hover:border-green-400" id="drop-zone">
                    <input type="file" name="image" id="image-input" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                    <div id="preview-placeholder">
                        <div class="mb-2 flex justify-center">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <p class="text-gray-400 text-xs font-medium">Klik atau seret gambar ke sini</p>
                    </div>
                    <img id="preview-img" src="#" class="hidden mx-auto rounded-lg max-h-48 object-cover shadow-sm">
                </div>
                
                <!-- Tanggal -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <label for="tanggal_terbit" class="block text-sm font-bold text-gray-700 uppercase tracking-wider mb-2">Tanggal Terbit</label>
                    <input type="date" name="tanggal_terbit" id="tanggal_terbit" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 transition" 
                        value="{{ date('Y-m-d') }}" required>
                </div>
                
            </div>
        </div>
        
		<!-- Tambahkan input ini di dalam form Anda -->
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

<!-- Quill 2.0 JS -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

<script>
    // 1. Kustom Ikon Undo/Redo
    const icons = Quill.import('ui/icons');
    icons['undo'] = `<svg viewBox="0 0 18 18"><polygon class="ql-fill ql-stroke" points="6 10 4 12 2 10 6 10"></polygon><path class="ql-stroke" d="M8.09,13.91A4.6,4.6,0,0,0,9,14,5,5,0,1,0,4,9"></path></svg>`;
    icons['redo'] = `<svg viewBox="0 0 18 18"><polygon class="ql-fill ql-stroke" points="12 10 14 12 16 10 12 10"></polygon><path class="ql-stroke" d="M9.91,13.91A4.6,4.6,0,0,1,9,14,5,5,0,1,1,14,9"></path></svg>`;

    // 2. Konfigurasi Toolbar (Termasuk Warna dan Tabel)
    const quill = new Quill('#editor-container', {
        theme: 'snow',
        placeholder: 'Tulis isi artikel Anda di sini...',
        modules: {
            table: true,
            history: { userOnly: true },
            toolbar: {
                container: [
                    ['undo', 'redo'],
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }], // FITUR WARNA TEKS & BACKGROUND
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'align': [] }],
                    ['blockquote', 'code-block'],
                    ['link', 'image'],
                    ['clean']
                ],
                handlers: {
                    'undo': function() { this.quill.history.undo(); },
                    'redo': function() { this.quill.history.redo(); }
                }
            }
        }
    });

    // 3. Modul Manajemen Tabel
    const tableModule = quill.getModule('table');

    function tableAction(action) {
        // Mendapatkan range seleksi saat ini
        const selection = quill.getSelection();
        if (!selection) return;

        // Cek apakah posisi kursor berada dalam format tabel
        const [line] = quill.getLine(selection.index);
        if (line.statics.blotName.includes('table') || line.parent.statics.blotName.includes('table')) {
            switch(action) {
                case 'insertRowBelow': tableModule.insertRowBelow(); break;
                case 'insertColumnRight': tableModule.insertColumnRight(); break;
                case 'deleteRow': tableModule.deleteRow(); break;
                case 'deleteColumn': tableModule.deleteColumn(); break;
                case 'deleteTable': tableModule.deleteTable(); break;
            }
        }
    }

    // Listener Tombol Insert Table Utama
    document.getElementById('insert-table').addEventListener('click', () => {
        tableModule.insertTable(3, 3);
    });

    // Logic Switch Status
    const toggle = document.getElementById('status_toggle');
    const statusHidden = document.getElementById('status-hidden');
    const statusText = document.getElementById('status-text');

    toggle.addEventListener('change', function() {
        if(this.checked) {
            statusHidden.value = 'publish';
            statusText.innerText = 'Published';
            statusText.classList.add('text-green-600');
        } else {
            statusHidden.value = 'draft';
            statusText.innerText = 'Draft';
            statusText.classList.remove('text-green-600');
        }
    });

    // Preview Image Upload
    document.getElementById('image-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = document.getElementById('preview-img');
                img.src = e.target.result;
                img.classList.remove('hidden');
                document.getElementById('preview-placeholder').classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    });

    // Handle Form Submit
    document.getElementById('main-form').onsubmit = function() {
        // Sinkronisasi konten Quill ke hidden input
        document.getElementById('isi-input').value = quill.root.innerHTML;
        return true;
    };
	
	//manajemen unggah file
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