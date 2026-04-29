<script>
  // Fungsi ini akan berjalan otomatis saat halaman selesai dimuat
  window.addEventListener('DOMContentLoaded', function() {
    if (!navigator.onLine) {
        // Membuat kotak peringatan secara dinamis
        const box = document.createElement('div');
        box.style = "position:fixed; top:0; left:0; width:100%; height:100%; background:white; z-index:9999; display:flex; justify-content:center; align-items:center; text-align:center; flex-direction:column; font-family:sans-serif;";
        
        box.innerHTML = `
            <h2 style="color:#dc2626;">Koneksi Internet Terputus</h2>
            <p style="color:#4b5563;">Silakan periksa koneksi Anda.</p>
            <button onclick="location.reload()" style="margin-top:15px; padding:8px 20px; background:#16a34a; color:white; border:none; border-radius:5px; cursor:pointer;">Coba Lagi</button>
        `;
        
        document.body.appendChild(box);
    }
  });
</script>