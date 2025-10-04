 <script>
  if (!navigator.onLine) {
    document.write('<div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: #fff; display: flex; justify-content: center; align-items: center;">Internet terputus, periksa koneksi internet Anda!</div>');
    throw new Error('Internet terputus');
  }
	 //hahahaha
</script>
 
 <!DOCTYPE html>
<html lang="id">
<head>
@include('offline-check')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<!-- Tag meta (digunakan sebagai fallback) -agar warna browser selaras dengan web -->
    <meta name="theme-color" content="#e9f9ed">
    
	
    <title>@yield('title', 'Portofolio')</title>
	
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
		
		.text-outline {
            color: green; /* Warna teks utama */
            text-shadow:
                -1px -1px 0 #fff,
                1px -1px 0 #fff,
                -1px 1px 0 #fff,
                1px 1px 0 #fff;
        }
		
		.text-outline-black {
            text-shadow: 
                -1px -1px 0 #000,
                 1px -1px 0 #000,
                -1px 1px 0 #000,
                 1px 1px 0 #000;
        }
    </style>
</head>
<body>

    <!-- HEADER: Berisi logo dan navigasi utama -->
    <header id="header" class="fixed top-0 inset-x-0 bg-transparent z-50 transition-transform duration-300 ease-in-out">
		<div class="container mx-auto px-4 flex justify-between items-center">
			
			<!-- Grup Logo dan Nama Pengguna yang selalu berdekatan -->
			<div class="flex items-center space-x-2">
				<img src="{{ asset('images/ylaw-logon.png') }}" alt="AT Logo" class="h-6 w-auto md:h-8 md:w-auto mr-2">
				<!-- nama user akan tampil setelah login -->
				@auth
			   <span id="useraktif" class="capitalize font-bold text-outline opacity-70">
				{{ Auth::user()->name }}
				</span>
				@endauth
			</div>
					
			<!-- Menu Mobile yang Tersembunyi -->
		<div id="mobile-menu" class="mobile-menu hidden w-[62.5%] max-w-xs rounded-bl-[50px] fixed top-0 right-0 bg-black bg-opacity-90 shadow-lg py-4 transition-all duration-300 ease-in-out">
			<a href="{{ route('home') }}" class="block px-8 py-2 text-white hover:text-green-600 font-normal transition duration-300 border-b border-gray-700">Beranda</a>
			<a href="{{ route('profile') }}" class="block px-8 py-2 text-white hover:text-green-600 font-normal transition duration-300 border-b border-gray-700">Profil</a>
			<div class="relative w-full">
				<button class="block px-8 py-2 text-white hover:text-green-600 font-normal transition duration-300 w-full text-left">Produk</button>
				<div class="px-12 py-1 text-white font-semibold">
					<a href="#" class="block py-1 hover:text-green-600 transition duration-300">Kamus</a>
					<a href="#" class="block py-1 hover:text-green-600 transition duration-300">iSales</a>
				</div>
			</div>
			<a href="#" id="mcontact-link" class="block px-8 py-2 text-white hover:text-green-600 font-normal transition duration-300 border-t border-gray-700">Kontak</a>
			<a href="#" id="mlogin-link" class="block px-8 py-2 text-white hover:text-green-600 font-normal transition duration-300 border-t border-gray-700"><span id="moutin">Login</span></a>
		</div>
		<!-- Hamburger Menu untuk Mobile -->
			<button id="mobile-menu-toggle" class="md:hidden text-white focus:outline-none">
				<svg class="w-8 h-8 block" style="filter: drop-shadow(0 0 1px #15803d);" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
					<path style="filter: drop-shadow(0 0 1px #15803d);" id="menu-icon-path" class="transition-all duration-300 ease-in-out" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
				</svg>
			</button>	
		
        <nav class="hidden md:flex items-center space-x-4 md:space-x-8 bg-black opacity-50 py-2 px-4 rounded-[10px]">
            <a href="{{ route('home') }}" class="text-white hover:text-green-300 font-semibold transition duration-300 text-sm transform hover:scale-105">Beranda</a>
            <a href="{{ route('profile') }}" class="text-white hover:text-green-300 font-semibold transition-all duration-300 text-sm transform hover:scale-105">Profil</a>
            <div class="relative inline-block group">
            <button class="text-white hover:text-green-300 font-semibold transition duration-300 flex items-center focus:outline-none text-sm transform hover:scale-105">Produk
            <svg class="ml-1 w-3 h-3 text-gray-500 group-hover:text-green-300 transition duration-300 transform hover:scale-105" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
            </button>
            <div class="absolute hidden group-hover:block bg-black shadow-lg rounded-md w-36 py-2 text-white font-semibold z-50">
            <a href="#" class="block px-4 py-2 hover:text-green-300 transition duration-300 text-sm transform hover:scale-105">Kamus</a>
            <a href="#" class="block px-4 py-2 hover:text-green-300 transition duration-300 text-sm transform hover:scale-105">iSales</a>
            </div>
            </div>
            <a href="#" id="contact-link" class="text-white hover:text-green-300 font-semibold transition duration-300 text-sm transform hover:scale-105">Kontak</a>
			<a href="#" id="login-link" class="text-white hover:text-green-300 font-semibold transition duration-300 text-sm transform hover:scale-105"><span id="outin">Login</span></a>

        </nav>
    </div>
	
</header>
    <main class="overflow-x-hidden">
	<!-- Konten unik dari setiap halaman akan dimasukkan di sini -->
	@yield('content') 
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
	<div id="login-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[100] items-center justify-center">
    <div class="bg-white rounded-lg p-8 mx-4 shadow-inner max-w-md w-full max-h-full relative transform transition-all ease-in-out duration-300 scale-95 overflow-y-auto">
        <button id="close-modal-btn" class="absolute top-4 right-4 height-[auto] text-gray-400 hover:text-gray-600 transition duration-300">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <!-- Perubahan di sini: Menambahkan kelas `absolute top-4 left-4` untuk memosisikan elemen -->
            <span id="keterangan" class="text-[12px] leading-none text-red-600 font-bold absolute top-4 left-1/2 transform -translate-x-1/2"></span>
        <h2 class="text-1xl font-bold text-center text-gray-800">Masuk ke Akun Anda</h2>    
        <form action="{{ route('login') }}" method="POST" class="space-y-4">
		@csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="mt-[-1px] block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" placeholder="nama@contoh.com">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                <div class="relative mt-[-1px]">
                    <input type="password" id="password" name="password" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm pr-10 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" placeholder="********">
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" id="toggle-password">
                        <i class="far fa-eye text-gray-400 hover:text-gray-600"></i>
                    </span>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <!--<input id="remember_me" name="remember_me" type="checkbox" class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-900">Ingat saya</label>-->
                </div>
                <a href="{{ route('password.request') }}" class="text-sm text-green-600 hover:text-green-500 font-medium">Lupa Kata Sandi?</a>
            </div>
            <div>
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-300">
                    Masuk
                </button>
            </div>
        </form>
		<div class="flex items-center my-6">
            <div class="flex-grow border-t border-gray-300"></div>
            <span class="flex-shrink mx-4 text-gray-500">Atau</span>
            <div class="flex-grow border-t border-gray-300"></div>
        </div>
		<div class="space-y-4 mb-4">
			<a href="{{ route('socialite.redirect', ['provider' => 'google']) }}" class="w-full flex items-center justify-center py-2 px-4 rounded-full bg-red-500 text-white font-semibold shadow-md hover:bg-red-600 transition duration-300">
				<i class="fab fa-google text-lg mr-3"></i> Masuk dengan Google
			</a>
			<a href="{{ route('socialite.redirect', ['provider' => 'facebook']) }}" class="w-full flex items-center justify-center py-2 px-4 rounded-full bg-blue-600 text-white font-semibold shadow-md hover:bg-blue-700 transition duration-300">
				<i class="fab fa-facebook text-lg mr-3"></i> Masuk dengan Facebook
			</a>
		</div>
		<!-- Tautan Registrasi Manual yang baru ditambahkan -->
        <p class="text-center text-sm text-gray-600">
            Belum punya akun? 
            <a href="{{ route('register') }}" class="font-medium text-green-600 hover:text-green-500">
                Daftar di sini
            </a>
        </p>
    </div>
</div>

<!-- Modal untuk Kontak -->
    <div id="contact-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[100] flex items-center justify-center">
        <div class="bg-white rounded-lg p-8 mx-4 shadow-inner max-w-md w-full max-h-full relative transform transition-all ease-in-out duration-300 scale-95 overflow-y-auto">
            <button id="close-contact-modal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/24/24/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Hubungi Kami</h2>
            <form id="contact-form" action="{{ route('contact.store') }}" method="POST" class="space-y-4">
			@csrf <!-- Ini sangat penting untuk keamanan -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" name="name" class="mt-[-1px] block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" placeholder="Nama Anda">
                </div>
                <div>
                    <label for="contact-email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" class="mt-[-1px] block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" placeholder="nama@contoh.com">
                </div>
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700">Pesan</label>
                    <textarea name="message" rows="4" class="mt-[-1px] block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" placeholder="Tulis pesan Anda di sini..."></textarea>
                </div>
                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-300">
                        Kirim Pesan
                    </button>
                </div>
            </form>
            <div class="mt-8 text-center">
                <p class="text-gray-600 text-sm mb-4">Atau hubungi kami melalui media sosial:</p>
                <div class="flex justify-center items-center space-x-6">
                    <a href="#" class="text-gray-500 hover:text-green-500 transition-colors duration-300">
                        <i class="fa-brands fa-square-facebook text-3xl"></i>
                    </a>
                    <a href="#" class="text-gray-500 hover:text-green-500 transition-colors duration-300">
                        <i class="fa-brands fa-linkedin-in text-3xl"></i>
                    </a>
                    <a href="#" class="text-gray-500 hover:text-green-500 transition-colors duration-300">
                        <i class="fa-brands fa-square-instagram text-3xl"></i>
                    </a>
                    <a href="#" class="text-gray-500 hover:text-green-500 transition-colors duration-300">
                        <i class="fa-brands fa-square-whatsapp text-3xl"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi -->
    <div id="success-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[100] flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-xl max-w-sm w-full mx-4 transform transition-all duration-300 scale-95">
            <div class="flex flex-col items-center justify-center space-y-4 text-center">
				<div class="bg-green-100 rounded-full p-3 mb-1">
                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
				</div>
                <h2 id="modalMessage" class="text-xl font-bold text-gray-800">info</h2>
                
                <button id="closeModal" class="mt-4 px-6 py-2 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                    OK
                </button>
            </div>
        </div>
    </div>

    

<!--Alpine.js adalah fasilitas untuk modal confirm delete custom, bisa ditambahkan di tag head, atau seperti di bawah ini-->
	<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
	
	<script>
		// Array berisi URL gambar yang akan dirotasi.
		// Anda dapat mengganti placeholder ini dengan URL gambar Anda sendiri.
		const images = [
			"{{ asset('images/welcomeimage-forest.png') }}",
			"{{ asset('images/rumah-pintar.jpg') }}",
			"{{ asset('images/drone-farming.jpg') }}",
			"{{ asset('images/ai-robot.jpeg') }}",
		];
				
		// Preload semua gambar
		images.forEach(image => {
			const img = new Image();
			img.src = image;
		});

		let currentIndex = 0;
		const heroImage = document.getElementById('welcomeimage');

		// --- Kode Baru: Konstanta dan Variabel untuk Navigasi ---
		const AUTOSLIDE_INTERVAL_MS = 10000; // Interval default: 10 detik
		const MANUAL_DELAY_MS = 20000;      // Jeda setelah interaksi manual: 20 detik

		const prevButton = document.getElementById('prevImage');
		const nextButton = document.getElementById('nextImage');

		let autoSlideInterval;
		let manualDelayTimeout;
		// --- Akhir Kode Baru ---

		// Fungsi untuk mengganti gambar ke depan (next).
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

		// --- Kode Baru: Fungsi Navigasi Mundur ---
		function prevImage() {
			heroImage.classList.remove('fade-in');
			setTimeout(() => {
				currentIndex = (currentIndex - 1 + images.length) % images.length;
				heroImage.src = images[currentIndex];
				heroImage.classList.add('fade-in');
			}, 10);
		}

		// Fungsi untuk memulai kembali autoslide
		function startAutoSlide() {
			autoSlideInterval = setInterval(changeImage, AUTOSLIDE_INTERVAL_MS);
		}

		// Fungsi untuk menghentikan autoslide dan memulai jeda manual
		function handleManualNavigation() {
			clearInterval(autoSlideInterval);
			clearTimeout(manualDelayTimeout);

			manualDelayTimeout = setTimeout(() => {
				startAutoSlide();
			}, MANUAL_DELAY_MS);
		}

		// Mulai otomatisasi saat halaman dimuat
		startAutoSlide();

		// Tambahkan event listeners ke tombol navigasi
		if (prevButton) {
			prevButton.addEventListener('click', () => {
				prevImage();
				handleManualNavigation();
			});
		}

		if (nextButton) {
			nextButton.addEventListener('click', () => {
				changeImage(); // Menggunakan fungsi yang sudah ada
				handleManualNavigation();
			});
		}
		// --- Akhir Kode Baru ---
		
		
		
//kontrol tombol navigasi menu untuk hp
	const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
	const menuIconPath = document.getElementById('menu-icon-path');

    mobileMenuToggle.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
		
		// Ubah ikon SVG saat menu ditoggle
        if (mobileMenu.classList.contains('hidden')) {
            // Menu tertutup, tampilkan ikon hamburger
            menuIconPath.setAttribute('d', 'M4 6h16M4 12h16M4 18h16');
        } else {
            // Menu terbuka, tampilkan ikon 'X'
            menuIconPath.setAttribute('d', 'M6 18L18 6M6 6l12 12');
        }
    });
	
	// Event listener untuk mengklik di luar area menu
	document.addEventListener('click', (e) => {
		// Periksa apakah menu sedang terbuka
		if (!mobileMenu.classList.contains('hidden')) {
			// Jika elemen yang diklik BUKAN tombol toggle DAN BUKAN bagian dari menu, maka tutup menu
			if (!mobileMenuToggle.contains(e.target) && !mobileMenu.contains(e.target)) {
				mobileMenu.classList.add('hidden');
				menuIconPath.setAttribute('d', 'M4 6h16M4 12h16M4 18h16');
			}
		}
	});

		
		
		 // Temukan tombol login
		const loginLink = document.getElementById('login-link');
		const mloginLink = document.getElementById('mlogin-link');

@guest
		const loginModal = document.getElementById('login-modal');
		const loginContent = loginModal.querySelector('.transform');
		
		const closeModalBtn = document.getElementById('close-modal-btn');
		
		// Pastikan tombol login ditemukan sebelum menambahkan event listener
	if (loginLink) {
		
		// Event listener untuk tombol "Login"
		loginLink.addEventListener('click', (e) => {
			e.preventDefault();
			showModal(loginModal, loginContent);
		});
		
		// Event listener untuk tombol "mobile Login"
		mloginLink.addEventListener('click', (e) => {
			e.preventDefault();
			document.body.style.overflow = 'hidden';
			showModal(loginModal, loginContent);
		});
		
		
		// Event listener untuk tombol tutup (x) di dalam modal
		closeModalBtn.addEventListener('click', () => {
			hideModal(loginModal, loginContent);
		});

		/*// Event listener untuk mengklik di luar area modal
		loginModal.addEventListener('click', (e) => {
			if (e.target === loginModal) {
				hideModal(loginModal, loginContent);
			}
		});*/
		
		// Event listener untuk modal dan modal content
		loginModal.addEventListener('click', (e) => {
			e.stopPropagation(); // Mencegah klik menyebar ke dokumen agar menu hp tidak tertutup
		});
		loginContent.addEventListener('click', (e) => {
			e.stopPropagation(); // Mencegah klik menyebar ke dokumen agar menu hp tidak tertutup
		});
		
		
		/*// Fungsi untuk mendeteksi perangkat seluler
        function isMobileDevice() {
            return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        }

        // Logika ini hanya akan berjalan jika perangkat terdeteksi sebagai HP
        if (isMobileDevice()) {
		const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
		
		
		// Event listener untuk menggeser modal ke atas saat input password mendapatkan fokus
        passwordInput.addEventListener('focus', () => {
            modalContent.classList.add('modal-shift-up');
        });
        
        // Event listener untuk menggeser modal kembali ke bawah saat input password kehilangan fokus
        passwordInput.addEventListener('blur', () => {
            // Memberi sedikit penundaan untuk memastikan user tidak mengklik input lain
            setTimeout(() => {
                // Periksa apakah input yang sedang aktif bukan input password
                if (document.activeElement !== passwordInput) {
                    modalContent.classList.remove('modal-shift-up');
                }
            }, 0);
        });
		} */
	}
	
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
	
	//fasilitas drag untuk modal content 

	let isDragging = false;
	let startY;
	let initialY;

	loginContent.addEventListener('mousedown', (e) => {
		isDragging = true;
		// Catat posisi awal mouse pada sumbu Y
		startY = e.clientY;
		// Catat posisi awal modal
		const style = window.getComputedStyle(loginContent);
		const matrix = new WebKitCSSMatrix(style.transform);
		initialY = matrix.m42;
		loginContent.style.transition = 'none'; // Hapus transisi selama drag
	});

	loginModal.addEventListener('mousemove', (e) => {
		if (!isDragging) return;
		e.preventDefault();

		// Hitung pergerakan Y
		const currentY = e.clientY;
		const deltaY = currentY - startY;
		const newY = initialY + deltaY;

		// Batasi pergerakan di dalam wadah
		const containerHeight = loginModal.clientHeight;
		const contentHeight = loginContent.clientHeight;
		const minTranslateY = -((containerHeight - contentHeight) / 2);
		const maxTranslateY = (containerHeight - contentHeight) / 2;
		
		const clampedY = Math.max(Math.min(newY, maxTranslateY), minTranslateY);

		// Terapkan posisi baru
		loginContent.style.transform = `translateY(${clampedY}px)`;
	});

	loginModal.addEventListener('mouseup', () => {
		isDragging = false;
		loginContent.style.transition = ''; // Kembalikan transisi setelah drag
	});
	
@endguest


//Modal untuk Kontak
	const contactModal = document.getElementById('contact-modal');
	const contactContent = contactModal.querySelector('.transform');
	
	const contactLink = document.getElementById('contact-link');
	const mcontactLink = document.getElementById('mcontact-link');
	const closeContactModalBtn = document.getElementById('close-contact-modal');
	

	// Event Listener untuk modal Kontak
	if (contactLink) {
		contactLink.addEventListener('click', (e) => {
			e.preventDefault();
			if (!mobileMenu.classList.contains('hidden')) {
				toggleMobileMenu();
			}
			showModal(contactModal, contactContent);
		});
	
	
	//dr menu hp
	mcontactLink.addEventListener('click', (e) => { 
		e.preventDefault();
		document.body.style.overflow = 'hidden';
		showModal(contactModal, contactContent);
	});
	
	//close btn
	closeContactModalBtn.addEventListener('click', () => hideModal(contactModal, contactContent));
	
	/*//clik dimana saja utk close
	contactModal.addEventListener('click', (e) => {
		if (e.target === contactModal) {
			hideModal(contactModal, contactContent);
		}
	});*/
	
	// Event listener untuk modal dan modal content
		contactModal.addEventListener('click', (e) => {
			e.stopPropagation(); // Mencegah klik menyebar ke dokumen agar menu hp tidak tertutup
		});
		contactContent.addEventListener('click', (e) => {
			e.stopPropagation(); // Mencegah klik menyebar ke dokumen agar menu hp tidak tertutup
		});
	}
	
	


/////////////////Modal dipakai oleh Login dan Kontak///////////////////////

// Fungsi untuk menampilkan modal dengan efek transisi
	function showModal(modal, modalContent) {
		modal.style.display = 'flex';
		setTimeout(() => {
			modalContent.classList.remove('scale-95', 'opacity-0');
			modalContent.classList.add('scale-100', 'opacity-100');
		}, 200);
	}

	// Fungsi untuk menyembunyikan modal dengan efek transisi
	function hideModal(modal, modalContent) {
		
		modalContent.classList.remove('scale-100', 'opacity-100');
		modalContent.classList.add('scale-95', 'opacity-0');
		
		//utk hp, aktifkan lg scroll pada body
		document.body.style.overflow = 'visible';
		
		setTimeout(() => {
			modal.style.display = 'none';
		}, 0); // Sesuaikan dengan durasi transisi
	}
////////////////////////////////////////////////////////////////	
	
//tampilkan konfirmasi pengiriman pesan kontak
	const urlParams = new URLSearchParams(window.location.search);
	const successMessage = "{{ session('success') }}";
	const modalAlert = document.getElementById('success-modal');
	const closeModalButton = document.getElementById('closeModal');
	const modalMessage = document.getElementById('modalMessage');

	// Tampilkan modal jika parameter "success" ada di URL
	if (successMessage) {
		// Mengambil dan mendekode pesan dari URL
		modalMessage.textContent = decodeURIComponent(successMessage);
		modalAlert.classList.remove('hidden');
	}

	// Sembunyikan modal saat tombol "OK" diklik
	closeModalButton.addEventListener('click', () => {
		modalAlert.classList.add('hidden');
		
		// Hapus parameter 'success' dari URL
		const url = new URL(window.location.href);
		url.searchParams.delete('success');
		window.history.replaceState(null, '', url.toString());
	});



@auth
	  loginLink.href = "{{ route('logout') }}";
	  document.getElementById("outin").innerHTML = "Logout";
	  mloginLink.href = "{{ route('logout') }}";
	  document.getElementById("moutin").innerHTML = "Logout";
@endauth
   
	
	
	//smart HEADER
	document.addEventListener('DOMContentLoaded', function () {
		const header = document.getElementById('header');
		let lastScrollY = window.scrollY;

		window.addEventListener('scroll', () => {
			// Jika menggulir ke bawah, sembunyikan header
			if (lastScrollY < window.scrollY && window.scrollY > 50) {
				header.classList.add('transform', '-translate-y-full');
			} 
			// Jika menggulir ke atas, atau jika guliran kembali ke atas, tampilkan header
			else {
				header.classList.remove('transform', '-translate-y-full');
			}

			lastScrollY = window.scrollY;
		});
	});
	
	//tampilkan login form setelah user mendaftar
	// Periksa apakah ada pesan 'status' dari session
    @if(session('status'))
			const status = "{{ session('status') }}";
			if(status != "back"){
			document.getElementById("keterangan").innerHTML = "{{ session('status') }}";
			}
            loginModal.classList.remove('hidden');
            loginModal.classList.add('flex');
            console.log("Status: {{ session('status') }}");
    @endif
	
    </script>

</body>
</html>
