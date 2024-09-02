# Aplikasi Rental Bus

Aplikasi Rental Bus ini adalah hasil proyek kelompok yang dirancang untuk memudahkan proses penyewaan bus, baik dari sisi pengguna maupun admin. Proyek ini dikembangkan sebagai bagian dari tugas akhir untuk mata kuliah **Dasar Pengembangan Perangkat Lunak**.

## Tentang Proyek

Proyek ini merupakan kolaborasi tim, di mana saya berperan sebagai programmer yang merancang dan mengimplementasikan sistem berdasarkan Business Requirement Document (BRD) yang telah didiskusikan oleh kelompok. Aplikasi ini dibangun dengan **PHP Vanilla 8.0**, **Bootstrap 5** untuk tampilan antarmuka, dan **MySQL** sebagai basis data. Aplikasi ini diuji coba menggunakan **XAMPP versi 3.3.0**.

## Fitur Utama

- **Validasi Login dan Registrasi Pengguna**: Fitur untuk memastikan bahwa hanya pengguna terdaftar yang dapat mengakses sistem penyewaan.
- **Antarmuka Pengguna untuk Penyewaan Bus**: Pengguna dapat dengan mudah memilih dan menyewa bus melalui tampilan yang sederhana dan responsif.
- **Dashboard Admin untuk Pengelolaan Transaksi**: Admin memiliki kontrol penuh untuk mengelola dan memantau transaksi penyewaan dengan efisien.

## Teknologi yang Digunakan

- **PHP 8.0 (Vanilla)**: Merupakan dasar pengembangan backend tanpa framework tambahan, memberikan fleksibilitas dalam penanganan logika bisnis.
- **Bootstrap 5**: Framework front-end yang memungkinkan desain yang modern dan responsif.
- **MySQL**: Digunakan untuk penyimpanan data yang efisien dan andal.
- **XAMPP 3.3.0**: Server lokal yang digunakan selama pengembangan dan pengujian, dengan Apache 2.4.53 dan MySQL 8.0.28.

## Instalasi dan Penggunaan

Berikut adalah langkah-langkah untuk menginstal dan menjalankan aplikasi ini di lingkungan lokal:

1. **Unduh dan Instal XAMPP**: Pastikan Anda menginstal XAMPP versi 3.3.0 atau lebih tinggi.
2. **Kloning Repositori**: Unduh atau kloning repositori proyek ini ke direktori `htdocs` di dalam folder XAMPP.
   ```bash
   git clone https://github.com/ibrahimhaykal/Aplikasi-Rental-Bus.git
   ```
3. **Buat Database Secara Manual**:
    - Buka phpMyAdmin melalui `http://localhost/phpmyadmin`.
    - Buat database baru dengan nama `rental_bus`.
    - Buat tabel-tabel yang diperlukan dalam database tersebut sesuai dengan struktur yang digunakan dalam kode aplikasi.
4. **Sesuaikan Konfigurasi Database**: Edit file `config.php` untuk menyesuaikan dengan konfigurasi database Anda.
5. **Jalankan Aplikasi**: Buka browser dan akses `http://localhost/rental-bus` untuk mulai menggunakan aplikasi.

```

Jika Anda memerlukan bantuan untuk membuat struktur tabel dalam database, saya bisa membantu merancang SQL-nya berdasarkan kebutuhan aplikasi Anda.

## Kontributor

- **Ibrahim** - Programmer, merancang dan mengimplementasikan sistem.
- **Anggota Kelompok 6 DPPL** - Berperan dalam diskusi BRD dan pengembangan aplikasi lainnya.

---

README ini memberikan deskripsi lengkap tentang proyek, peran dalam pengembangan, teknologi yang digunakan, serta langkah-langkah instalasi dan penggunaan, sehingga memberikan gambaran yang jelas dan profesional mengenai aplikasi rental bus yang telah dikembangkan.
```
