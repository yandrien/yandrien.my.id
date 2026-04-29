<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Akun Baru</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Tambahkan Font Awesome untuk ikon mata -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            /* Hapus background default karena akan diatur oleh Tailwind */
        }
    </style>
</head>
<body class="bg-black text-white flex items-center justify-center min-h-screen p-4">

    <!-- Kontainer utama dengan efek interaktif, diubah ke warna gelap -->
    <div class="bg-gray-900 p-8 rounded-lg shadow-2xl w-full max-w-md transform transition-all duration-300 hover:scale-105">
        <div class="flex flex-col items-center">
		@if(session('error'))
			<span class="text-[12px] leading-none text-red-600 font-bold absolute top-4 left-1/2 transform -translate-x-1/2">
				{{ session('error') }}
			</span>
		@endif
            <svg class="h-8 w-8 text-indigo-400 mb-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
            </svg>
            <h2 class="text-2xl font-bold text-center text-white mb-2">Buat Akun Baru</h2>
            <p class="text-gray-400 text-center mb-2">
                Sudah punya akun?
                <a href="{{ route('gate') }}" class="text-indigo-400 hover:text-indigo-300 font-semibold transition duration-300">Masuk di sini</a>
            </p>
        </div>

        <form class="mt-4 space-y-4" action="{{ route('saveme') }}" method="POST">
            <!-- Token CSRF untuk keamanan (asumsi Laravel Blade) -->
            @csrf

            <!-- Input Nama -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-300">Nama Lengkap</label>
                <div class="mt-1">
                    <input id="name" name="name" type="text" required
                        class="w-full px-4 py-2 border border-gray-700 rounded-lg shadow-sm bg-gray-800 text-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Masukkan nama Anda" value="{{ old('name') }}">
                </div>
                @error('name')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Input Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-300">Alamat Email</label>
                <div class="mt-1">
                    <input id="email" name="email" type="email" autocomplete="email" required
                        class="w-full px-4 py-2 border border-gray-700 rounded-lg shadow-sm bg-gray-800 text-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="contoh@email.com" value="{{ old('email') }}">
                </div>
                @error('email')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Input Kata Sandi -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-300">Kata Sandi</label>
                <div class="mt-1 relative">
                    <input id="password" name="password" type="password" autocomplete="new-password" required
                        class="w-full px-4 py-2 border border-gray-700 rounded-lg shadow-sm bg-gray-800 text-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 pr-10"
                        placeholder="Minimal 8 karakter">
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" id="toggle-password">
                        <i class="fas fa-eye text-gray-400 hover:text-gray-200"></i>
                    </span>
                </div>
                @error('password')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Input Konfirmasi Kata Sandi -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-300">Konfirmasi Kata Sandi</label>
                <div class="mt-1 relative">
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                        class="w-full px-4 py-2 border border-gray-700 rounded-lg shadow-sm bg-gray-800 text-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 pr-10"
                        placeholder="Ulangi kata sandi Anda">
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" id="toggle-password-confirmation">
                        <i class="fas fa-eye text-gray-400 hover:text-gray-200"></i>
                    </span>
                </div>
            </div>

            <!-- Tombol Submit -->
            <div>
                <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform transition-all duration-300 hover:scale-105">
                    Daftar
                </button>
            </div>
        </form>
    </div>
        
    <script>
        // JavaScript untuk fungsionalitas show/hide password
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('toggle-password');
        const passwordConfirmationInput = document.getElementById('password_confirmation');
        const togglePasswordConfirmation = document.getElementById('toggle-password-confirmation');

        // Fungsi untuk mengelola toggle pada password utama
        if (togglePassword) {
            togglePassword.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Toggle ikon mata
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
        }

        // Fungsi untuk mengelola toggle pada konfirmasi password
        if (togglePasswordConfirmation) {
            togglePasswordConfirmation.addEventListener('click', function () {
                const type = passwordConfirmationInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordConfirmationInput.setAttribute('type', type);
                
                // Toggle ikon mata
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
        }
    </script>
        
</body>
</html>
