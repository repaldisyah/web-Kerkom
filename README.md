# Nusa Karsa Event

Aplikasi internal untuk memantau piutang dan mencatat pembayaran cabang Nusa Karsa Event.

## Menjalankan lokal

1. Jalankan Apache dan MySQL melalui XAMPP.
2. Impor `database/nusa_karsa.sql` di phpMyAdmin.
3. Salin `api/config.example.php` menjadi `api/config.php`, lalu isi kredensial MySQL lokal.
4. Buka `http://localhost/web-Kerkom/`.

## Struktur proyek

- `halaman.html` — dashboard utama.
- `Html/` — halaman login, cabang, dan pembayaran.
- `Css/` — stylesheet per halaman.
- `Js/` — JavaScript per halaman.
- `api/` — endpoint PHP dan autentikasi berbasis sesi.
- `database/` — skema, data awal, dan migration MySQL. Folder ini tidak boleh dipublikasikan ke server.
- `assets/` — logo aplikasi.

## Keamanan lokal

`api/config.php` berisi kredensial lokal dan sengaja tidak dilacak Git. Jangan unggah folder `database/` atau `.git/` ke server publik.

## Peran akun

- `super_admin` — melihat seluruh cabang, seluruh pelanggan, riwayat, dan laporan tahunan.
- `admin_cabang` — hanya melihat cabang, pelanggan, riwayat, serta laporan cabangnya sendiri.
- `pelanggan` — hanya melihat profil data dan riwayat pembayarannya sendiri. Akun ini harus dihubungkan ke `users.customer_id` setelah menjalankan migration akun pelanggan.
