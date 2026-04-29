@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')

<div class="bg-gray-100 flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-2xl bg-white p-8 rounded-2xl shadow-lg border border-gray-200">
        <!-- Judul dan Deskripsi -->
        <div class="text-center mb-6">
            <h1 data-key="edit_profil_title" class="text-3xl font-bold text-gray-800">Edit Profil Anda</h1>
            <p data-key="des_edit" class="text-gray-500 mt-2">Perbarui semua informasi profil Anda di bawah ini.</p>
        </div>

        <!-- Pesan Sukses -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Formulir Edit Profil -->
        <form id="profileForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- START: Bagian layout 2 Kolom (Menggunakan flex-row-reverse untuk Foto di Kanan) -->
            <div class="md:flex md:flex-row-reverse">
                
                <!-- Bagian 1 (Urutan 1 Mobile): Foto Profil -->
                <!-- Di desktop, ini menjadi kolom KANAN (karena md:flex-row-reverse) -->
                <div class="flex-none w-full md:w-1/3 flex flex-col items-center justify-start space-y-3 mb-6 md:mb-0">
                    <div class="flex flex-col items-center space-y-3">
                        @if ($profile->foto_profil)
                            <!-- Ukuran diperbesar menjadi h-24 w-24 -->
                            <img src="{{ asset('storage/' . $profile->foto_profil) }}" alt="Foto Profil"
                                class="h-24 w-24 rounded-full object-cover shadow-md cursor-pointer ring-4 ring-indigo-500 ring-opacity-50" onclick="document.getElementById('foto_profil').click();"
                                id="profile_photo_preview">
                        @else
                            <span class="inline-block h-24 w-24 rounded-full overflow-hidden bg-gray-100 ring-4 ring-gray-300 ring-opacity-50">
                                <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 20.993c-.346 1.135-4.438 2.059-9.796 2.059-5.358 0-9.45-.924-9.796-2.059.006-.016.011-.033.016-.049 3.004-.842 6.326-1.399 9.78-1.399 3.454 0 6.776.557 9.78 1.399.005.016.01.033.016.049zM12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z" />
                                </svg>
                            </span>
                        @endif
                        
                        <label for="foto_profil" class="relative cursor-pointer bg-indigo-500 py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm leading-4 font-medium text-white hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            <span data-key="ubah_foto_button">Ubah Foto</span>
                            <input id="foto_profil" name="foto_profil" type="file" class="sr-only">
                        </label>
                    </div>
                    <p class="mt-2 text-xs text-gray-500 text-center" id="filename-display">JPG, PNG, atau WEBP, max. 1 MB.</p>
                    @error('foto_profil')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Bagian 2 (Urutan 2 Mobile): Field Formulir -->
                <!-- Di desktop, ini menjadi kolom KIRI (mengambil lebar fleksibel) -->
                <div class="flex-grow space-y-6 md:w-2/3">
                    <!-- Field Nama -->
                    <div>
                        <label data-key="edit_name" for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                        <div class="mt-1">
                            <input data-key="nama_placeholder" type="text" id="name" name="name" required
                                    value="{{ old('name', $user->name) }}"
                                    class="w-full px-4 py-2 border @error('name') border-red-500 @else border-gray-300 @enderror rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                    placeholder="Masukkan nama Anda">
                        </div>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Field Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <div class="mt-1">
                            <input data-key="email_placeholder" type="email" id="email" name="email" required
                                    value="{{ old('email', $user->email) }}"
                                    class="w-full px-4 py-2 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                    placeholder="Masukkan email Anda">
                        </div>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Field Nomor Telepon -->
                    <div>
                        <label data-key="no_telp" for="nomor_telepon" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <div class="mt-1">
                            <input data-key="telp_placeholder" type="text" id="nomor_telepon" name="nomor_telepon" onkeydown="justNumberModern(event)"
                                    value="{{ old('nomor_telepon', $profile->nomor_telepon) }}"
                                    class="w-full px-4 py-2 border @error('nomor_telepon') border-red-500 @else border-gray-300 @enderror rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                    placeholder="Masukkan nomor telepon">
                        </div>
                        @error('nomor_telepon')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Field TTL (Perbaikan Horizontal) -->
                    <div>
                        <label data-key="ttl" for="tlahir" class="block text-sm font-medium text-gray-700">Tempat & Tanggal Lahir</label>
                        <!-- Menggunakan Flexbox responsif untuk tata letak horizontal -->
                        <div class="flex flex-col sm:flex-row mt-1 space-y-2 sm:space-y-0 sm:space-x-4">
                            <!-- Input Tempat Lahir -->
                            <div class="flex-1">
                                <input data-key="tlahir_paceholder" type="text" id="tlahir" name="tlahir"
                                    value="{{ old('tlahir', $profile->tlahir) }}"
                                    class="w-full px-4 py-2 border @error('tlahir') border-red-500 @else border-gray-300 @enderror rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                    placeholder="Tempat Lahir">
                            </div>
                            
                            <!-- Input Tanggal Lahir -->
                            <div class="flex-1">
                                <input data-key="tgllahir_placeholder" type="date" id="tgllahir" name="tgllahir" required
                                    value="{{ old('tgllahir', (isset($profile->tgllahir) && $profile->tgllahir) ? \Carbon\Carbon::parse($profile->tgllahir)->format('Y-m-d') : '') }}"
                                    class="w-full px-4 py-2 border @error('tgllahir') border-red-500 @else border-gray-300 @enderror rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                    placeholder="Tanggal Lahir">
									<!-- menggunakan \Carbon\Carbon::parse($profile->tgllahir)->format('Y-m-d').
									Ini memastikan bahwa meskipun data di database berupa objek tanggal atau string dengan format lain, 
									Laravel akan mengubahnya menjadi format standar yang dipahami oleh input HTML5 (YYYY-MM-DD) -->
                            </div>
                        </div>
                        @error('tlahir')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        @error('tgllahir')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
					
					<!-- field alamat_lengkap -->
					 <div>
                        <label data-key="alamat" for="alamat_lengkap" class="block text-sm font-medium text-gray-700">Alamat</label>
                        <div class="mt-1">
                            
							<textarea data-key="alamat_placeholder" id="alamat_lengkap" name="alamat_lengkap" rows="2"
                                class="w-full px-4 py-2 border @error('alamat_lengkap') border-red-500 @else border-gray-300 @enderror rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                placeholder="Masukkan alamat lengkap...">{{ old('alamat_lengkap', $profile->alamat_lengkap) }}</textarea>
                        </div>
                        @error('alamat_lengkap')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            </div>
            <!-- END: Bagian layout 2 Kolom -->

            <hr class="border-t border-gray-200">

            <div>
                <label data-key="edit_peran" for="peran" class="block text-sm font-medium text-gray-700">Peran</label>
                <div class="mt-1">
                    <input data-key="peran_placeholder" type="text" id="peran" name="peran"
                            value="{{ old('peran', $profile->peran) }}"
                            class="w-full px-4 py-2 border @error('peran') border-red-500 @else border-gray-300 @enderror rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            placeholder="Contoh: Pengembang Web, Desainer Grafis">
                </div>
                @error('peran')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label data-key="edit_biografi" for="biografi" class="block text-sm font-medium text-gray-700">Biografi</label>
                <div class="mt-1">
                    <textarea data-key="placeholder_biografi" id="biografi" name="biografi" rows="4"
                                class="w-full px-4 py-2 border @error('biografi') border-red-500 @else border-gray-300 @enderror rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                placeholder="Ceritakan sedikit tentang diri Anda...">{{ old('biografi', $profile->biografi) }}</textarea>
                </div>
                @error('biografi')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label data-key="lokasi" for="lokasi" class="block text-sm font-medium text-gray-700">Lokasi</label>
                <div class="mt-1">
                    <input data-key="lokasi_placeholder" type="text" id="lokasi" name="lokasi"
                            value="{{ old('lokasi', $profile->lokasi) }}"
                            class="w-full px-4 py-2 border @error('lokasi') border-red-500 @else border-gray-300 @enderror rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            placeholder="Contoh: Jakarta, Indonesia">
                </div>
                @error('lokasi')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label data-key="edit_linkedin" for="linkedin_url" class="block text-sm font-medium text-gray-700">URL LinkedIn</label>
                <div class="mt-1">
                    <input type="url" id="linkedin_url" name="linkedin_url"
                            value="{{ old('linkedin_url', $profile->linkedin_url) }}"
                            class="w-full px-4 py-2 border @error('linkedin_url') border-red-500 @else border-gray-300 @enderror rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            placeholder="https://www.linkedin.com/in/namaanda">
                </div>
                @error('linkedin_url')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="github_url" class="block text-sm font-medium text-gray-700">URL GitHub</label>
                <div class="mt-1">
                    <input type="url" id="github_url" name="github_url"
                            value="{{ old('github_url', $profile->github_url) }}"
                            class="w-full px-4 py-2 border @error('github_url') border-red-500 @else border-gray-300 @enderror rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            placeholder="https://github.com/namaanda">
                </div>
                @error('github_url')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Bagian Tombol Aksi -->
			<div class="pt-4 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
				<!-- Tombol Simpan -->
				<button type="submit"
						class="flex-1 flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
					<span data-key="simpan_button">Simpan Perubahan</span>
				</button>

				<!-- Tombol Batal (Hover Merah) -->
				<a href="{{ route('profile') }}"
				   class="flex-1 flex justify-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-red-600 hover:text-white hover:border-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 text-center">
					<span data-key="batal_button">Batal</span>
				</a>
			</div>
        </form>

        <div class="mt-6 text-center text-sm text-gray-500">
            <p data-key="note_edit_profil">Pastikan semua data yang Anda masukkan akurat sebelum disimpan.</p>
        </div>
    </div>
</div>

   <script>
        // 1. Dapatkan referensi ke elemen input file dan elemen img
        const fileInput = document.getElementById('foto_profil');
        const photoPreview = document.getElementById('profile_photo_preview');
        const filenameDisplay = document.getElementById('filename-display');
        
        // 2. Tambahkan event listener untuk mendengarkan perubahan pada input file
        fileInput.addEventListener('change', function(event) {
            
            // Periksa apakah ada file yang dipilih
            if (event.target.files && event.target.files[0]) {
                const newFile = event.target.files[0];
                
                // 3. Buat FileReader untuk membaca file
                const reader = new FileReader();
                
                // Callback ketika FileReader selesai membaca
                reader.onload = function(e) {
                    // 4. Atur atribut 'src' dari elemen img ke data URL dari file baru
                    photoPreview.src = e.target.result;
                };
                
                // Mulai membaca file (sebagai Data URL)
                reader.readAsDataURL(newFile);

                // Tampilkan nama file
                filenameDisplay.textContent = `File baru dipilih: ${newFile.name}`;
                filenameDisplay.classList.remove('text-gray-500');
                filenameDisplay.classList.add('text-indigo-600', 'font-semibold');

            } else {
                // Jika pengguna membatalkan pemilihan file, kembalikan ke foto sebelumnya
                photoPreview.src = currentPhotoUrl;
                filenameDisplay.textContent = "Belum ada file dipilih.";
                filenameDisplay.classList.add('text-gray-500');
                filenameDisplay.classList.remove('text-indigo-600', 'font-semibold');
            }
        });
        
        // Inisialisasi awal untuk memastikan foto lama ditampilkan jika ada
        const currentPhotoUrlElement = document.getElementById('profile_photo_preview');
        // Asumsi 'currentPhotoUrl' sudah diset di bagian <script> di dalam body
        if (typeof currentPhotoUrl !== 'undefined' && currentPhotoUrlElement) {
             currentPhotoUrlElement.src = currentPhotoUrl;
        }

	//filter masukan keyboard hanya angka saja
	// Fungsi Modern menggunakan event.key (Dianjurkan)
        function justNumberModern(event) {
            const allowedKeys = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '+'];
            const key = event.key;
            const inputElement = event.target;

            // Izinkan tombol khusus (Backspace, Delete, Arrow Keys, Tab, Enter)
            if (event.ctrlKey || event.metaKey || 
                event.key === 'Backspace' || 
                event.key === 'Delete' || 
                event.key.startsWith('Arrow') ||
                event.key === 'Tab' || 
                event.key === 'Enter') {
                return true;
            }

            // Blokir input jika tombol yang ditekan BUKAN angka atau BUKAN tanda plus
            if (!allowedKeys.includes(key)) {
                event.preventDefault();
                return false;
            }

            // **LOGIKA TAMBAHAN UNTUK TANDA PLUS:**
            // Tanda plus hanya boleh muncul sebagai karakter pertama (di awal string)
            if (key === '+') {
                // Jika input sudah berisi tanda plus atau jika kursor TIDAK di awal (posisi 0)
                if (inputElement.value.includes('+') || inputElement.selectionStart !== 0) {
                    event.preventDefault(); // Blokir tanda plus
                    return false;
                }
            }
            
            return true;
        }
    </script>
@endsection
