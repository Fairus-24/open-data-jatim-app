# Open Data Jatim App

Aplikasi PHP modern untuk mengeksplorasi data dari API portal Open Data Provinsi Jawa Timur. Aplikasi ini dibangun dengan desain yang premium (Dark Mode & Glassmorphism) menggunakan vanilla CSS.

## Fitur
* **Integrasi API**: Mengambil data menggunakan `cURL` PHP.
* **Smart Fallback**: Apabila API resmi dari pemerintah sedang *down* atau mengembalikan status `403 Forbidden` (membutuhkan autentikasi), aplikasi secara otomatis akan membaca dari sumber data lokal (`mock_data.json`) sehingga aplikasi tetap berjalan dengan baik.
* **Desain UI Modern**: Menggunakan efek Glassmorphism, animasi interaktif, serta skema warna premium (Dark Mode).

## Cara Menjalankan Aplikasi

Aplikasi ini dapat dijalankan dengan server PHP apa saja (seperti XAMPP, Laragon, dll.) atau menggunakan server bawaan (Built-in Web Server) dari PHP.

1. Buka terminal atau Command Prompt.
2. Arahkan ke direktori proyek (`cd "d:\Open Data Jatim"`).
3. Jalankan perintah berikut:
   ```bash
   php -S localhost:8000
   ```
4. Buka browser dan akses alamat: `http://localhost:8000`

## Struktur File
* `index.php`: File utama berisi struktur HTML dan logika PHP untuk melakukan panggilan API dengan cURL.
* `style.css`: File styling menggunakan Vanilla CSS dengan desain modern.
* `mock_data.json`: File data JSON lokal sebagai cadangan (fallback) yang memuat sampel data statistik Provinsi Jawa Timur.
* `README.md`: Dokumentasi proyek.
