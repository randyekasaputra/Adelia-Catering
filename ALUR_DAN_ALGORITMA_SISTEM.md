# Alur Sistem dan Penerapan Algoritma 
**Studi Kasus:** Adelia Catering (Tugas E-Commerce & Rekayasa Perangkat Lunak Lanjut)

Berikut adalah dokumentasi mengenai alur sistem secara keseluruhan beserta daftar algoritma yang diimplementasikan di dalam aplikasi, lengkap dengan referensi file *source code*-nya.

---

## A. Alur Sistem Utama (System Flow)

Sistem e-commerce ini membagi alurnya menjadi tiga bagian utama: *User/Customer*, *Admin*, dan *Data Processing*.

### 1. Alur Pelanggan (Front-End)
1. **Melihat Katalog:** Pelanggan mengakses *landing page* dan membuka halaman katalog menu.
2. **Melihat Detail & Rekomendasi:** Saat pelanggan mengklik sebuah menu, sistem menampilkan detail menu dan menjalankan **Algoritma Rekomendasi** untuk menampilkan 3 opsi menu lain yang relevan.
3. **Pemesanan (Checkout):** Pelanggan mengisi formulir (nama, alamat, tanggal acara, pesanan). Saat dikirim, sistem menjalankan **Algoritma Validasi Jadwal** untuk memastikan kuota di tanggal tersebut masih tersedia dan tidak mendadak.
4. **Pembayaran:** Sistem mengarahkan pelanggan ke halaman pembayaran (terintegrasi dengan *Payment Gateway Midtrans*) untuk membayar DP atau Lunas.
5. **Webhook Status:** Sistem menerima *callback* otomatis dari Midtrans untuk memperbarui status pesanan menjadi `paid`, `pending`, atau `cancelled`.

### 2. Alur Admin (Back-End)
1. **Dashboard & Analitik:** Admin *login* dan langsung disajikan data analitik otomatis. Sistem menjalankan **Algoritma Prediksi**, **Algoritma Distribusi Hari**, dan **Algoritma Agregasi** untuk menampilkan tren bisnis.
2. **Manajemen Pemesanan:** Admin melihat daftar pesanan yang masuk, bisa memfilter berdasarkan status, serta mencetak struk pemesanan.
3. **Manajemen Katalog:** Admin dapat menambah, mengubah, atau menghapus menu beserta fotonya.
4. **Manajemen Kalender:** Admin dapat mengatur batas maksimal pesanan per hari, batas minimal H-x pemesanan, dan memblokir tanggal tertentu (misal: libur). Sistem akan me-render kalender ketersediaan menggunakan **Algoritma Pembangkit Kalender**.

### 3. Alur Pra-Pemrosesan Data (Offline Script)
Sistem memiliki utilitas berbasis Python untuk migrasi data awal:
1. File PDF spesifikasi menu di-ekstrak menjadi gambar dan teks.
2. Skrip menjalankan **Algoritma Parsing (Regex)** untuk memisahkan antara teks nama menu, harga, dan mengkategorikannya berdasarkan halaman PDF.

---

## B. Daftar Algoritma yang Diterapkan

Sistem ini menerapkan beberapa jenis algoritma mulai dari algoritma rule-based sederhana, struktur data, hingga *predictive analytics* dasar yang cocok untuk studi RPL Lanjut dan *E-Commerce*.

### 1. Algoritma Content-Based Filtering (Rekomendasi Menu)
*   **Tujuan:** Mendorong *up-selling* atau *cross-selling* dengan memberikan alternatif produk kepada pelanggan.
*   **Cara Kerja:** Mencari item dalam database yang memiliki atribut `category` yang sama dengan item yang sedang dilihat, mengecualikan item itu sendiri, lalu mengambil 3 data teratas. Jika kurang dari 3, sistem akan mengisi sisanya dengan kategori acak.
*   **File Implementasi:** `app/Http/Controllers/FrontController.php` (berada di dalam fungsi `menuDetail()`)

### 2. Algoritma Simple Moving Average / SMA (Prediksi Pendapatan)
*   **Tujuan:** Memberikan *business intelligence* kepada admin mengenai estimasi pendapatan bulan depan.
*   **Cara Kerja:** Algoritma membaca deret waktu pendapatan (Time Series) dengan *window* N=3 (3 bulan ke belakang). Menjumlahkan total penjualan (status *paid/completed*), mengabaikan bulan yang kosong (nol), lalu mencari nilai rata-ratanya sebagai nilai prediksi bulan depan.
*   **File Implementasi:** `app/Http/Controllers/AdminController.php` (berada di dalam fungsi `dashboard()`, pada blok *Analitik Prediktif - Prediksi Pendapatan*)

### 3. Algoritma Agregasi Data (Pencarian Menu Terlaris)
*   **Tujuan:** Mengetahui produk *best-seller* di platform e-commerce.
*   **Cara Kerja:** Algoritma pengelompokan (*Grouping & Sorting*) yang berjalan di level Database (SQL). Mengelompokkan tabel `order_items` berdasarkan `menu_id`, menjumlahkan (SUM) nilai `qty`, dan mengurutkannya secara *descending* (menurun) untuk mendapatkan Top 5.
*   **File Implementasi:** `app/Http/Controllers/AdminController.php` (berada di dalam fungsi `dashboard()`, pada blok *Menu Terlaris - Ranking Aggregation*)

### 4. Algoritma Distribusi Frekuensi (Analisis Hari Tersibuk)
*   **Tujuan:** Mengoptimalkan jam kerja dan suplai bahan baku di dapur katering berdasarkan hari paling padat orderan.
*   **Cara Kerja:** Mengekstrak indeks hari (1-7 / Minggu-Sabtu) dari *timestamp* pesanan yang masuk menggunakan fungsi waktu `DAYOFWEEK()`. Lalu menghitung kemunculannya (*Count*) dan membuat peta persentase distribusi kepadatan pesanan untuk setiap harinya.
*   **File Implementasi:** `app/Http/Controllers/AdminController.php` (berada di dalam fungsi `dashboard()`, pada blok *Hari Tersibuk - Frequency Distribution*)

### 5. Algoritma Rule-Based Validasi Ketersediaan (Booking Calendar)
*   **Tujuan:** Mencegah sistem *overbooking* (kelebihan pesanan) yang dapat mengacaukan operasional katering.
*   **Cara Kerja:** Berjalan setiap kali pelanggan melakukan *checkout*. Memeriksa 3 kondisi secara kondisional (If-Else Rules):
    1. Memastikan hari pemesanan >= `min_advance_days` (Pesanan tidak boleh mendadak).
    2. Memastikan tanggal tidak ada di tabel `blocked_dates`.
    3. Memastikan `Count(orders)` pada tanggal tersebut < `max_orders_per_day`.
    Jika salah satu aturan dilanggar, *checkout* akan ditolak dan me-return error.
*   **File Implementasi:** `app/Http/Controllers/FrontController.php` (berada di dalam fungsi `processCheckout()`, pada blok *Booking Calendar Validation*)

### 6. Algoritma Ekstraksi & Regex Parsing (Data Mining)
*   **Tujuan:** Mengotomatisasi *data-entry* katalog dari brosur cetak (PDF) langsung menjadi data aplikasi.
*   **Cara Kerja:** Menggunakan *Regular Expression* (`Regex`) seperti `^Rp\s?[\d.,]+` untuk mendeteksi mana baris yang merupakan "Harga" dan "Nama". Kemudian menggunakan metode spasial untuk menyatukan menu dengan lokasi foto di koordinat yang sesuai pada halaman PDF.
*   **File Implementasi:** `parse_menus.py` (berada di root folder)
