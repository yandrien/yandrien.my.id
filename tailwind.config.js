/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
	"./resources/views/**/*.blade.php", // <-- Tambahkan ini untuk memastikan folder views & layouts terbaca
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

