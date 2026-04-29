 <script>
  if (!navigator.onLine) {
    document.write('<div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: #fff; display: flex; justify-content: center; align-items: center;">Internet terputus, periksa koneksi internet Anda!</div>');
    throw new Error('Internet terputus');
  }
</script>
 
 <!DOCTYPE html>
<html lang="id">
<head>
@include('offline-check')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portofolio Yandrien Wohangara</title>
    <!-- Tailwind CSS CDN untuk styling modern -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome untuk ikon media sosial -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Pengaturan font Inter -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0fdf4; /* Warna latar belakang hijau sangat muda */
        }
        
        /* Definisi keyframes untuk animasi soft blur */
        @keyframes soft-blur-animate {
            0% { filter: blur(4px); }
            50% { filter: blur(0px); }
            100% { filter: blur(4px); }
        }

        /* Menerapkan animasi pada elemen gambar di hero section */
        .hero-image-animate {
            animation: soft-blur-animate 10s ease-in-out infinite;
        }

        /* Definisi keyframes untuk animasi detak halus pada seluruh hero section */
        @keyframes pulse-subtle {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.01); }
        }

        /* Menerapkan animasi pulse pada hero section */
        .hero-section-pulse {
            animation: pulse-subtle 4s ease-in-out infinite;
        }

        /* Definisi keyframes untuk efek fade-in */
        @keyframes fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Kelas untuk transisi fade-in */
        .fade-in {
            animation: fade-in 1s ease-in-out;
        }
    </style>
</head>
<body class="text-gray-800">

    <!-- HEADER: Berisi logo dan navigasi utama -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4 py-1 flex justify-between items-center">
            <!-- Nama/Logo Portofolio -->
            <img src="{{ asset('images/AT-logo.png') }}" alt="AT Logo" class="h-6 w-6 md:h-8 md:w-8 mr-2">
            <!-- Navigasi -->
            <nav class="space-x-4 md:space-x-8">
                <a href="#" class="text-gray-600 hover:text-green-600 font-semibold transition duration-300">Beranda</a>
                <a href="#" class="text-gray-600 hover:text-green-600 font-semibold transition duration-300">Tentang Saya</a>
                <!-- Dropdown Menu Produk -->
				<div class="relative inline-block group">
				<button class="text-gray-600 hover:text-green-600 font-semibold transition duration-300 flex items-center focus:outline-none">Produk
				<svg class="ml-1 w-3 h-3 text-gray-500 group-hover:text-green-600 transition duration-300" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
				</svg>
				</button>
				<!-- Perhatikan bahwa kelas 'mt-2' sudah dihapus dari div di bawah ini -->
				<div class="absolute hidden group-hover:block bg-white shadow-lg rounded-md w-36 py-2 text-gray-700 z-50">
				<!-- Kelas hover di bawah ini telah diubah menjadi 'hover:bg-green-100' -->
				<a href="#" class="block px-4 py-2 hover:bg-green-100 transition duration-300">Kamus</a>
				<a href="#" class="block px-4 py-2 hover:bg-green-100 transition duration-300">iSales</a>
				</div>
				</div>
                <a href="#" class="text-gray-600 hover:text-green-600 font-semibold transition duration-300">Kontak</a>
				{{-- Logika Blade untuk Tombol Login/Keluar --}}
				@guest
				<a href="#" id="login-link" class="text-gray-600 hover:text-green-600 font-semibold transition duration-300">Login</a>
				@endguest

				@auth
				<a href="{{ route('logout') }}" id="logout-link" class="text-gray-600 hover:text-red-600 font-semibold transition duration-300">Keluar</a>
				@endauth
            </nav>
        </div>
    </header>

    <main>
        <!-- HERO SECTION: Bagian paling atas dengan slogan dan gambar -->
        <section class="bg-gradient-to-r from-green-100 to-green-500 py-16 md:py-24 text-center text-white relative overflow-hidden rounded-bl-[100px] rounded-tr-[100px] mx-2 mt-4 hero-section-pulse">
            <div class="absolute inset-0 z-0 opacity-90">
                <!-- Gambar hero dengan id agar bisa diakses oleh JavaScript -->
                <img src="{{ asset('images/welcomeimage-forest.png') }}" id="welcomeimage" alt="Foto" class="w-full h-full object-cover hero-image-animate">
            </div>
            <div class="relative z-10 container mx-auto px-4">
                <!-- Slogan utama -->
                <h1 class="text-2xl md:text-4xl font-extrabold mb-4 drop-shadow-lg">
                    Artificial Technology
                </h1>
                <!-- Deskripsi singkat -->
                <p class="text-lg md:text-xl font-medium mb-8 max-w-2xl mx-auto">
                    Tiada hari tanpa belajar dan berkarya. Jelajahi karya-karya saya.
                </p>
                <!-- Tombol aksi (Call to Action) -->
                <a href="#" class="bg-green-700 hover:bg-green-800 text-white font-bold py-3 px-8 rounded-full shadow-lg transition duration-300 transform hover:scale-105">
                    Lihat Portofolio
                </a>
            </div>
        </section>

        <!-- BAGIAN KONTEN: Artikel Terbaru -->
        <section class="py-16 px-4 md:px-8">
            <div class="container mx-auto">
                <h2 class="text-3xl md:text-4xl font-bold text-center text-green-700 mb-12">Artikel Terbaru</h2>
                <!-- Kontainer grid untuk menampilkan kartu artikel -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Kartu Artikel 1 -->
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 transform hover:scale-105">
                        <img src="https://placehold.co/600x400/e5e7eb/374151?text=GAMBAR+1" alt="Artikel 1" class="rounded-t-xl w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">5 Kiat Belajar Coding untuk Pemula</h3>
                            <p class="text-sm text-gray-500 mb-4">28 Agustus 2025</p>
                            <p class="text-gray-600 text-sm leading-relaxed mb-4">Panduan praktis untuk memulai perjalanan Anda di dunia pemrograman dengan langkah yang tepat.</p>
                            <a href="#" class="text-green-600 font-semibold hover:underline">Baca Selengkapnya</a>
                        </div>
                    </div>
                    <!-- Kartu Artikel 2 -->
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 transform hover:scale-105">
                        <img src="https://placehold.co/600x400/e5e7eb/374151?text=GAMBAR+2" alt="Artikel 2" class="rounded-t-xl w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">Mengoptimalkan Desain Aplikasi Modern</h3>
                            <p class="text-sm text-gray-500 mb-4">20 Agustus 2025</p>
                            <p class="text-gray-600 text-sm leading-relaxed mb-4">Pelajari prinsip-prinsip dasar untuk menciptakan antarmuka pengguna yang intuitif dan menarik.</p>
                            <a href="#" class="text-green-600 font-semibold hover:underline">Baca Selengkapnya</a>
                        </div>
                    </div>
                    <!-- Kartu Artikel 3 -->
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 transform hover:scale-105">
                        <img src="https://placehold.co/600x400/e5e7eb/374151?text=GAMBAR+3" alt="Artikel 3" class="rounded-t-xl w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">Membangun Portofolio yang Kuat</h3>
                            <p class="text-sm text-gray-500 mb-4">15 Agustus 2025</p>
                            <p class="text-gray-600 text-sm leading-relaxed mb-4">Bagaimana cara menyoroti proyek terbaik Anda untuk menarik perhatian klien atau perekrut.</p>
                            <a href="#" class="text-green-600 font-semibold hover:underline">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
	
	
	

    <!-- FOOTER: Bagian bawah berisi informasi kontak dan hak cipta -->
    <footer class="bg-green-900 text-white py-12 mt-16 rounded-t-xl">
        <div class="container mx-auto px-4 text-center">
            <h3 class="text-xl font-bold mb-4">Yandrien Wohangara</h3>
            <!-- Informasi sosial media -->
            <div class="flex justify-center items-center space-x-6 mb-6">
                <a href="https://www.facebook.com/yandrien wohangara" target="_blank" class="text-white hover:text-green-300 transition-colors duration-300">
                    <i class="fa-brands fa-square-facebook text-4xl"></i>
                </a>
                <a href="https://www.linkedin.com/in/yandrien woha" target="_blank" class="text-white hover:text-green-300 transition-colors duration-300">
                    <i class="fa-brands fa-linkedin-in text-4xl"></i>
                </a>
                <a href="https://www.instagram.com/yandis" target="_blank" class="text-white hover:text-green-300 transition-colors duration-300">
                    <i class="fa-brands fa-square-instagram text-4xl"></i>
                </a>
                <a href="https://wa.me/6281805342365" target="_blank" class="text-white hover:text-green-300 transition-colors duration-300">
                    <i class="fa-brands fa-square-whatsapp text-4xl"></i>
                </a>
            </div>
            <!-- Alamat -->
            <p class="text-sm mb-2">Sumba Timur - NTT</p>
            <!-- Hak Cipta -->
            <p class="text-xs text-gray-400">Hak Cipta: &copy;2025 Kambaniru</p>
        </div>
    </footer>
	
	<!--popup Login-->
	<div id="login-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[100]">
    <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full mx-4 relative transform transition-all ease-in-out duration-300 scale-95 opacity-0">
        <button id="close-modal-btn" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition duration-300">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Masuk ke Akun Anda</h2>
        
        <div class="space-y-4 mb-6">
			<a href="{{ route('socialite.redirect', ['provider' => 'google']) }}" class="w-full flex items-center justify-center py-2 px-4 rounded-full bg-red-500 text-white font-semibold shadow-md hover:bg-red-600 transition duration-300">
				<i class="fab fa-google text-lg mr-3"></i> Masuk dengan Google
			</a>
			<a href="{{ route('socialite.redirect', ['provider' => 'facebook']) }}" class="w-full flex items-center justify-center py-2 px-4 rounded-full bg-blue-600 text-white font-semibold shadow-md hover:bg-blue-700 transition duration-300">
				<i class="fab fa-facebook text-lg mr-3"></i> Masuk dengan Facebook
			</a>
		</div>
        
        <div class="flex items-center my-4">
            <div class="flex-grow border-t border-gray-300"></div>
            <span class="flex-shrink mx-4 text-gray-500">Atau</span>
            <div class="flex-grow border-t border-gray-300"></div>
        </div>
        
        <form action="{{ route('login') }}" method="POST" class="space-y-4">
		@csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" placeholder="nama@contoh.com">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                <div class="relative mt-1">
                    <input type="password" id="password" name="password" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm pr-10 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" placeholder="********">
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" id="toggle-password">
                        <i class="far fa-eye text-gray-400 hover:text-gray-600"></i>
                    </span>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember_me" name="remember_me" type="checkbox" class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-900">Ingat saya</label>
                </div>
                <a href="#" class="text-sm text-green-600 hover:text-green-500 font-medium">Lupa Kata Sandi?</a>
            </div>
            <div>
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-300">
                    Masuk
                </button>
            </div>
        </form>
    </div>
</div>

	

 	<script>
        // Array berisi URL gambar yang akan dirotasi.
        // Anda dapat mengganti placeholder ini dengan URL gambar Anda sendiri.
        const images = [
            "{{ asset('images/welcomeimage-forest.png') }}",
            "{{ asset('images/rumah-pintar.jpg') }}",
            "{{ asset('images/drone-farming.jpg') }}",
            "{{ asset('images/ai-robot.jpeg') }}",
        ];

        let currentIndex = 0;
        const heroImage = document.getElementById('welcomeimage');

        // Fungsi untuk mengganti gambar.
        function changeImage() {
            // Hapus kelas 'fade-in' untuk memungkinkan animasi berjalan kembali
            heroImage.classList.remove('fade-in');

            // Tambahkan sedikit penundaan sebelum mengubah gambar dan menambahkan kelas lagi
            // Ini agar browser bisa mereset animasi
            setTimeout(() => {
                // Pindah ke gambar berikutnya dalam array
                currentIndex = (currentIndex + 1) % images.length;
                // Ubah atribut 'src' dari elemen gambar
                heroImage.src = images[currentIndex];
                // Tambahkan kelas 'fade-in' untuk memicu animasi
                heroImage.classList.add('fade-in');
            }, 10);
        }

        // Jalankan fungsi changeImage setiap 5 detik (setengah dari durasi animasi blur)
        // Ini memastikan gambar berganti saat efek blur paling tebal
        // sehingga transisi gambar tidak terlihat secara langsung.
        setInterval(changeImage, 10000); // 5000 ms = 5 detik
		
		
		 // Temukan tombol login
		const loginLink = document.getElementById('login-link');
		const loginModal = document.getElementById('login-modal');
		const closeModalBtn = document.getElementById('close-modal-btn');
		const modalContent = loginModal.querySelector('.transform');

		// Pastikan tombol login ditemukan sebelum menambahkan event listener
		if (loginLink) {
		// Fungsi untuk menampilkan modal dengan efek transisi
		function showModal() {
			loginModal.style.display = 'flex';
			setTimeout(() => {
				modalContent.classList.remove('scale-95', 'opacity-0');
				modalContent.classList.add('scale-100', 'opacity-100');
			}, 10);
		}

		// Fungsi untuk menyembunyikan modal dengan efek transisi
		function hideModal() {
			modalContent.classList.remove('scale-100', 'opacity-100');
			modalContent.classList.add('scale-95', 'opacity-0');
			setTimeout(() => {
				loginModal.style.display = 'none';
			}, 300); // Sesuaikan dengan durasi transisi
		}

		// Event listener untuk tombol "Login"
		loginLink.addEventListener('click', (e) => {
			e.preventDefault();
			showModal();
		});

		// Event listener untuk tombol tutup (x) di dalam modal
		closeModalBtn.addEventListener('click', () => {
			hideModal();
		});

		// Event listener untuk mengklik di luar area modal
		loginModal.addEventListener('click', (e) => {
			if (e.target === loginModal) {
				hideModal();
			}
		});
	}

    // Mengubah href link 'Login' agar tidak mengarahkan ke halaman lain
    loginLink.href = '#';
	
// Temukan form login
const loginForm = document.querySelector('#login-modal form');

// Pastikan elemen form ditemukan sebelum menambahkan event listener
if (loginForm) {
    loginForm.addEventListener('submit', function(event) {
        // Mencegah pengiriman formulir bawaan
        event.preventDefault();

        // Ambil data formulir
        const formData = new FormData(loginForm);

        // Hapus pesan error sebelumnya (jika ada)
        const existingError = loginForm.querySelector('.text-red-500');
        if (existingError) {
            existingError.remove();
        }

        fetch(loginForm.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: formData
        })
        .then(response => {
            if (response.status === 401 || response.status === 422) {
                // Tangani respons error dari server
                return response.json().then(errorData => {
                    const errorDiv = document.createElement('div');
                    errorDiv.classList.add('text-red-500', 'text-sm', 'mt-2', 'text-center');
                    errorDiv.textContent = errorData.message || 'Informasi login tidak valid.';
                    loginForm.prepend(errorDiv);
                });
            } else if (response.ok) {
                // Tangani respons sukses
                return window.location.reload();
            } else {
                throw new Error('Terjadi kesalahan yang tidak diketahui.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const errorDiv = document.createElement('div');
            errorDiv.classList.add('text-red-500', 'text-sm', 'mt-2', 'text-center');
            errorDiv.textContent = 'Terjadi kesalahan jaringan atau server.';
            loginForm.prepend(errorDiv);
        });
    });
}
	
	// JavaScript untuk fungsionalitas show/hide password
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('toggle-password');

    togglePassword.addEventListener('click', function () {
        // Toggle the type attribute
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Toggle the eye icon
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash'); // Ganti dengan ikon mata terbuka
    });
    </script>

</body>
</html>
