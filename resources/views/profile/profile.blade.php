@extends('layouts.app')

@section('title', 'Profil Pengguna')

@section('content')

<div class="pt-16 pb-16 min-h-screen flex items-start justify-center p-4">

    <div class="w-full max-w-4xl bg-white p-6 sm:p-10 rounded-xl shadow-2xl border border-gray-100">
        <div class="flex flex-col md:flex-row items-center md:items-start space-y-8 md:space-y-0 md:space-x-10">

            <!-- Bagian Kiri: Foto, Nama, dan Peran -->
            <div class="flex flex-col items-center w-full md:w-1/3 p-4 bg-indigo-50 rounded-lg shadow-inner">
                <!-- Foto Profil -->
                <div class="w-36 h-36 rounded-full overflow-hidden border-4 border-indigo-300 shadow-xl bg-gray-200 ring-2 ring-indigo-500">
                    <!-- Tampilkan foto profil jika ada, jika tidak, tampilkan avatar default -->
                    @if (isset($profile->foto_profil))
                        <img src="{{ asset('storage/' . $profile->foto_profil) }}" alt="Foto Profil Pengguna" class="w-full h-full object-cover">
                    @else
                        <svg class="h-full w-full text-indigo-400 p-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 20.993c-.346 1.135-4.438 2.059-9.796 2.059-5.358 0-9.45-.924-9.796-2.059.006-.016.011-.033.016-.049 3.004-.842 6.326-1.399 9.78-1.399 3.454 0 6.776.557 9.78 1.399.005.016.01.033.016.049zM12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z" />
                        </svg>
                    @endif
                </div>

                <!-- Nama dan Peran -->
                <h2 class="mt-4 text-3xl font-extrabold text-gray-900 text-center">{{ $profile->user->name}}</h2>
                <p class="text-lg text-indigo-700 font-semibold mt-1">{{ $profile->peran ?? 'Pengguna Aplikasi' }}</p>

                <!-- Tombol Edit (Disarankan: hanya untuk pemilik profil) -->
                @auth
                    {{-- Ganti Auth::user()->is_admin dengan pengecekan apakah user adalah pemilik profil --}}
                    @if(Auth::id() === (int)$profile->user_id || auth()->user()->is_admin)
                        <a data-key="edit_profil" href="{{ route('profile.edit') }}" class="inline-block mt-6 px-6 py-2 border border-transparent text-base font-medium rounded-full shadow-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out transform hover:scale-105">
                            Edit Profil
                        </a>
                    @endif
                @endauth
            </div>

            <!-- Bagian Kanan: Detail Informasi -->
            <div class="flex-1 space-y-8 w-full md:w-2/3">

                <!-- Tentang Saya (Biografi) -->
                <div class="border-b pb-4">
                    <h3 data-key="tentang_saya" class="text-2xl font-bold text-gray-800 mb-3 border-l-4 border-indigo-500 pl-3">Tentang Saya</h3>
                    <p class="text-gray-600 leading-relaxed text-base italic">
                        {{ $profile->biografi ?? 'Belum ada biografi yang ditambahkan.' }}
                    </p>
                </div>

                <!-- Detail Kontak dan Pribadi -->
                <div class="space-y-4">
                    <h3 data-key="profil_kontak" class="text-2xl font-bold text-gray-800 mb-4 border-l-4 border-indigo-500 pl-3">Detail Kontak & Pribadi</h3>

                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">

                        <!-- Email -->
                        <div class="flex items-center text-gray-700 bg-gray-50 p-3 rounded-lg shadow-sm">
                            <svg class="w-6 h-6 mr-3 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="truncate font-semibold">{{ $profile->user->email ?? 'Tidak Tersedia' }}</dd>
                            </div>
                        </div>

                        <!-- Telepon -->
                        <div class="flex items-center text-gray-700 bg-gray-50 p-3 rounded-lg shadow-sm">
                            <svg class="w-6 h-6 mr-3 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.717 21 3 14.283 3 6V5z"></path></svg>
                            <div>
                                <dt data-key="no_telp" class="text-sm font-medium text-gray-500">Nomor Telepon</dt>
                                <dd class="font-semibold">{{ $profile->nomor_telepon ?? 'Belum Diisi' }}</dd>
                            </div>
                        </div>

                        <!-- Tanggal Lahir -->
                        <div class="flex items-center text-gray-700 bg-gray-50 p-3 rounded-lg shadow-sm">
                            <svg class="w-6 h-6 mr-3 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <div>
                                <dt data-key="ttl" class="text-sm font-medium text-gray-500">Tempat & Tanggal Lahir</dt>
								<!-- Elemen dd untuk menampung hasil JavaScript -->
								<!-- Kami mengirimkan data mentah dari backend ke atribut data untuk JS ambil -->
								<dd id="tgllahir" 
									class="font-semibold text-gray-800"
									data-tempat="{{ $profile->tlahir ?? 'Belum Diisi' }}"
									data-isodate="{{ $profile->tgllahir->toIso8601String() ?? '' }}"> 
									Memuat tanggal... 
								</dd>
               
                            </div>
                        </div>

                        <!-- Lokasi (Kota/Negara) -->
                        <div class="flex items-center text-gray-700 bg-gray-50 p-3 rounded-lg shadow-sm">
                            <svg class="w-6 h-6 mr-3 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <div>
                                <dt data-key="lokasi" class="text-sm font-medium text-gray-500">Lokasi</dt>
                                <dd class="font-semibold">{{ $profile->lokasi ?? 'Kota, Negara' }}</dd>
                            </div>
                        </div>

                    </dl>

                    <!-- Alamat Lengkap (Field terpisah) -->
                    <div class="mt-4 bg-gray-50 p-4 rounded-lg shadow-sm border-t-2 border-indigo-100">
                        <dt class="text-sm font-medium text-gray-500 flex items-center mb-1">
                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-2a3 3 0 013-3h2a3 3 0 013 3v2"></path></svg>
                            <span data-key="alamat">Alamat</span>
                        </dt>
                        <dd class="text-gray-800 font-medium leading-relaxed">{{ $profile->alamat_lengkap ?? 'Belum ada detail alamat lengkap.' }}</dd>
                    </div>

                </div>

                <!-- Bagian Sosial Media -->
                <div class="pt-4 border-t">
                    <h3 data-key="sosmed" class="text-xl font-bold text-gray-800 mb-3">Tautan Sosial Media</h3>
                    <div class="flex space-x-6">
                        @if (isset($profile->linkedin_url))
                            <a href="{{ $profile->linkedin_url }}" target="_blank" rel="noopener noreferrer" class="text-gray-500 hover:text-blue-600 transition-colors transform hover:scale-110">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/8/81/LinkedIn_icon.svg" alt="LinkedIn" class="w-8 h-8">
                            </a>
                        @endif
                        @if (isset($profile->github_url))
                            <a href="{{ $profile->github_url }}" target="_blank" rel="noopener noreferrer" class="text-gray-500 hover:text-gray-900 transition-colors transform hover:scale-110">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/9/91/Octicons-mark-github.svg" alt="GitHub" class="w-8 h-8">
                            </a>
                        @endif
                        @if (!isset($profile->linkedin_url) && !isset($profile->github_url))
                            <p data-key="tidakada_sosmed" class="text-gray-500 italic text-sm">Tidak ada tautan sosial media yang ditambahkan.</p>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>


<script>
// mengubah format tanggal ke indonesia tanpa array ****
// Pastikan DOM sudah siap
    document.addEventListener('DOMContentLoaded', function() {
        const ddElement = document.getElementById("tgllahir");
        
		
        if (!ddElement) return;

        // 1. Ambil data dari atribut data HTML
        const tempatLahir = ddElement.getAttribute('data-tempat');
        const tanggalISO = ddElement.getAttribute('data-isodate');

        if (!tanggalISO) {
            ddElement.innerHTML = tempatLahir + ", Tanggal belum diisi";
            return;
        }

        // 2. Buat objek Date dari string ISO (Anda bisa gunakan new Date(tanggalISO))
        var tanggallahir = new Date(tanggalISO);

        // 3. GUNAKAN 'undefined' UNTUK LOCALE OTOMATIS
        // Ini akan mengambil locale dari pengaturan browser pengguna.
        var formatDinamis = new Intl.DateTimeFormat('Id', { 
            day: 'numeric', 
            month: 'long', 
            year: 'numeric' 
        }); 

        // 4. Format tanggal
        var hasilTanggal = formatDinamis.format(tanggallahir);

        // 5. Gabungkan dan injeksi ke elemen dd
        ddElement.innerHTML = `${tempatLahir}, ${hasilTanggal}`;
    });

</script>

@endsection