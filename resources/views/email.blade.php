<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi?</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white p-8 rounded-lg shadow-2xl w-full max-w-sm transform transition duration-500 hover:scale-105">
        <div class="flex flex-col items-center">
            <svg class="h-12 w-12 text-indigo-600 mb-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
            </svg>
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-2">Lupa Kata Sandi?</h2>
            <p class="text-gray-600 text-center mb-6">Silakan masukkan alamat email yang terdaftar untuk menerima tautan reset kata sandi.</p>
        </div>

        <!-- Form Permintaan Reset Kata Sandi -->
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            
            <!-- Input Email -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">Alamat Email</label>
                <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300" required>
            </div>
            
            <!-- Tombol Kirim -->
            <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-300 transform hover:scale-105">
                Kirim Tautan Reset
            </button>
        </form>

        <div class="mt-6 text-center text-sm">
            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold transition duration-300">&larr; Kembali ke halaman masuk</a>
        </div>
    </div>
</body>
</html>
