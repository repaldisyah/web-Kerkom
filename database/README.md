# Menjalankan backend lokal

1. Jalankan **Apache** dan **MySQL** dari XAMPP.
2. Buka `http://localhost/phpmyadmin`, lalu impor file `nusa_karsa.sql`.
3. Sesuaikan `../api/config.php` bila username, password, port, atau nama database MySQL Anda berbeda.
4. Letakkan folder proyek ini di `C:/xampp/htdocs/web-Kerkom` (atau atur virtual host ke folder proyek), kemudian buka `http://localhost/web-Kerkom/halaman.html`.

Untuk database yang sudah pernah diimpor, jalankan migration sesuai urutan nama file. Fitur akun pelanggan membutuhkan `migration_add_customer_account.sql`; token riwayat pembayaran membutuhkan `migration_add_payment_token.sql`.

## Akun awal

- Username: `admin`
- Email: `admin@nusakarsa.com`
- Password: `Admin123!`

Ganti password akun ini sebelum aplikasi dipakai secara nyata.

## Endpoint awal

- `POST /api/login.php` — membuat sesi admin.
- `POST /api/logout.php` — menghapus sesi.
- `GET /api/session.php` — membaca status sesi.
- `GET /api/dashboard.php` — ringkasan dan daftar piutang terbaru; wajib login.
- `GET /api/branches.php` — cabang yang diizinkan untuk akun operasional.
- `GET /api/customers.php` — pelanggan sesuai lingkup akun.
- `GET /api/payment-history.php` — riwayat pembayaran sesuai lingkup akun.
- `GET /api/reports.php?year=2026` — rekap tahunan untuk admin pusat atau cabang.
