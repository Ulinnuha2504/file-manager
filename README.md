# Ascallyn File Manager

Pengelola file berbasis web untuk mengunggah dan mengunduh file melalui browser, tanpa perlu memindahkan file menggunakan flashdisk.

[Lihat aplikasi](https://ascallyn.my.id/file/) · [Lisensi MIT](LICENSE)

## Tentang proyek

Ascallyn File Manager adalah proyek kecil karya Muhammad Ulinnuha. Pada aplikasi, judul yang ditampilkan adalah **Lab File Manager**. Proyek ini menyediakan antarmuka sederhana untuk berbagi dan mengelola file melalui halaman web.

## Fitur

- Form unggah file melalui browser.
- Daftar file dengan kolom nama dan ukuran.
- Antarmuka untuk mengunduh dan menghapus file.
- Dialog konfirmasi sebelum penghapusan.
- Indikator pemrosesan saat unggah dan hapus.
- Tampilan gelap dengan aksen biru dan hijau.
- Tata letak yang menyesuaikan layar ponsel.

## Penggunaan

1. Buka halaman aplikasi.
2. Pilih file, kemudian tekan **Upload**.
3. Tunggu hingga proses selesai.
4. Gunakan tindakan unduh atau hapus pada daftar file yang tersedia.

## Teknologi

Antarmuka menggunakan HTML, CSS, dan JavaScript. Detail backend, kebutuhan server, dependensi, dan penyimpanan mengikuti source code yang disertakan dalam repository.

## Menjalankan aplikasi

Repository perlu memuat source code aplikasi agar dapat dijalankan. Berkas README dan LICENSE saja tidak membentuk aplikasi yang dapat dipasang.

Sebelum menjalankan salinan sendiri:

1. Periksa entry point dan kebutuhan runtime pada source code.
2. Sesuaikan konfigurasi serta lokasi penyimpanan file.
3. Pastikan direktori penyimpanan dapat ditulis oleh aplikasi.
4. Jalankan pada server yang mendukung backend aplikasi.
5. Uji unggah, unduh, dan hapus menggunakan file percobaan.

**Catatan dokumentasi:** README ini disusun dari halaman publik aplikasi. Source code backend belum ditinjau; versi runtime, struktur folder, kebutuhan database, batas ukuran unggahan, serta perintah instalasi belum diverifikasi. Lengkapi bagian ini sesuai implementasi sebelum merilis versi yang siap dipasang.

## Catatan penggunaan dan publikasi

Jangan masukkan file unggahan pengguna, data pribadi, password, token, atau konfigurasi server rahasia ke repository publik. Jika konfigurasi diperlukan, sediakan contoh dengan nilai dummy.

Untuk penggunaan pada server publik, periksa kontrol akses, validasi jenis dan ukuran file, perlindungan operasi hapus, serta pencegahan eksekusi file unggahan. Halaman publik saja tidak cukup untuk memastikan kontrol tersebut telah diterapkan.

## Kontribusi

Laporan masalah dan usulan pengembangan dapat disampaikan melalui tab **Issues** pada repository ini. Untuk perubahan kode, jelaskan tujuan perubahan dan langkah pengujiannya dalam pull request.

## Pengembang

**Muhammad Ulinnuha**  
Website: [ascallyn.my.id](https://ascallyn.my.id/)

## Lisensi

Proyek ini menggunakan [MIT License](LICENSE). Penggunaan, penyalinan, modifikasi, distribusi, dan penggunaan komersial diizinkan sesuai ketentuan lisensi, termasuk mempertahankan pemberitahuan hak cipta dan teks izin lisensi.

Lisensi proyek tidak menggantikan lisensi komponen pihak ketiga yang mungkin disertakan. Hak atas file yang diunggah tetap mengikuti pemilik atau pemegang hak masing-masing.