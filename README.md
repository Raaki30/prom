# 🎓 Prom Night Ticketing Website

Website ini dibuat untuk mempermudah proses pemesanan tiket acara prom night sekolah. Dibangun menggunakan Laravel, sistem ini mendukung pengelolaan data pemesan, validasi bukti pembayaran, dan tampilan dashboard untuk panitia prom.

## 📌 Deskripsi Project

Prom adalah aplikasi berbasis web yang memungkinkan siswa memesan tiket acara prom dengan mengisi data seperti NIS, nama, email, kelas, jumlah tiket, dan metode pembayaran. Setelah pemesanan, pengguna dapat mengunggah bukti pembayaran yang nantinya akan divalidasi oleh admin atau panitia.

Sistem ini dilengkapi dengan autentikasi, upload bukti via API (imgbb), dan halaman admin untuk melihat semua data pemesan secara real time. Admin juga dapat mengubah status validasi setiap pemesanan.

## 🧩 Fitur Utama

- Formulir pemesanan tiket dengan validasi data
- Upload bukti pembayaran berbasis API
- Preview gambar bukti secara langsung sebelum upload
- Panel admin untuk melihat, memfilter, dan memvalidasi pemesanan
- Fitur pencarian data berdasarkan NIS, nama, atau email
- Sistem autentikasi menggunakan Laravel Breeze

## 🗃️ Struktur Data Tiket

Model `Tiket` menyimpan informasi berikut:
- `order_id`: ID unik untuk tiap pemesanan
- `nis`: Nomor Induk Siswa
- `nama`, `email`, `phone`, `kelas`: Identitas pemesan
- `jumlah_tiket`: Jumlah tiket yang dibeli
- `harga`: Total harga berdasarkan jumlah tiket
- `metodebayar`: Metode pembayaran (contoh: transfer bank)
- `bukti`: URL gambar bukti pembayaran
- `status`: Status validasi pembayaran (pending, success, rejected)
- `entry`: Timestamp saat pembelian dilakukan

## 🛠️ Teknologi yang Digunakan

- Laravel 12
- Laravel Breeze (auth scaffolding)
- Tailwind CSS untuk styling
- HTML5 QR Code Reader (planned integration untuk scan tiket)
- SweetAlert2 untuk notifikasi
- imgbb API untuk upload bukti pembayaran
- MySQL sebagai basis data

## 🔒 Keamanan

- Input pengguna divalidasi melalui `Form Request`
- SQL Injection dicegah secara default oleh Laravel (Eloquent dan Query Builder)
- Pengunggahan file dibatasi dan divalidasi
- Autentikasi dan middleware digunakan untuk proteksi halaman admin

## 🔎 Rencana Pengembangan Selanjutnya

- QR code scanner untuk validasi tiket saat event berlangsung
- Fitur export data pemesan ke Excel
- Statistik penjualan dan pendapatan
- Email notifikasi setelah pemesanan berhasil
- Halaman landing prom untuk promosi acara

---

Made with ❤️ by [Raaki30](https://github.com/Raaki30)
