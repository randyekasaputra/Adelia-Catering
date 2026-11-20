# 📘 DOKUMEN MASTER KONSEP SKRIPSI - TEKNIK INFORMATIKA
**Pengembangan Sistem Rekomendasi Paket Menu Katering Berbasis SMCKP dan Dynamic Programming pada Adelia Catering**  
*Pengusul: Randy Eka Saputra (NIM: 231240001371) | Pembimbing: Ir. Adi Sucipto, M.Kom.*

---

## 📌 1. RINGKASAN EKSEKUTIF (ELEVATOR PITCH)

> *"Banyak pelanggan katering datang dengan membawa batasan anggaran (budget) tertentu tanpa mengetahui kombinasi menu apa yang paling optimal. Di sisi lain, pengelola katering membutuhkan pertimbangan yang terukur karena popularitas suatu menu belum tentu sejalan dengan keuntungan yang diperoleh dari penjualannya (Harga Jual dikurangi HPP). Penelitian ini mengembangkan sistem rekomendasi paket menu katering menggunakan metode pengembangan perangkat lunak **Waterfall** yang mengintegrasikan **popularitas menu berdasarkan transaksi penjualan** dan **keuntungan berdasarkan Harga Pokok Produksi (HPP)** ke dalam fungsi tujuan berbobot (**Weighted Sum**).*
>
> *Permasalahan pemilihan paket menu dimodelkan sebagai **Selective Multiple-Choice Knapsack Problem (SMCKP)**—di mana setiap kategori memiliki batas pemilihan paling banyak satu alternatif ($\sum x_{kj} \le 1$) sehingga kategori menu opsional dapat dipilih atau dilewati—dan diselesaikan secara eksak menggunakan **Algoritma Dynamic Programming (DP)** berbasis **Prinsip Optimalitas Bellman** untuk menghasilkan kombinasi menu dengan nilai utilitas maksimum sesuai anggaran pelanggan."*

---

## 🎓 2. IDENTITAS PENELITIAN

* **Nama / NIM:** Randy Eka Saputra / 231240001371
* **Dosen Pembimbing:** Ir. Adi Sucipto, M.Kom.
* **Program Studi:** S1 Teknik Informatika
* **Objek Penelitian:** Adelia Catering Jepara
* **Metode Pengembangan Sistem (SDLC):** **Waterfall** (*Requirement Analysis, System & Software Design, Implementation & Unit Testing, Testing/Verification, Operation & Maintenance*).
* **Fungsi Tujuan Utilitas:** **Weighted Sum** ($u_{ij} = w_1 \cdot \hat{P}_{ij} + w_2 \cdot \hat{\Pi}_{ij}$) yang menggabungkan Popularitas Transaksi ($\hat{P}_{ij}$) dan Keuntungan HPP ($\hat{\Pi}_{ij} = \text{Harga Jual} - \text{HPP}$).
* **Model Optimasi:** **Selective Multiple-Choice Knapsack Problem (SMCKP)** dengan kategori menu opsional ($\sum_{j \in C_k} x_{kj} \le 1$).
* **Algoritma Penyelesaian:** **Dynamic Programming (DP)** berbasis Prinsip Optimalitas Bellman.
* **Arsitektur & Teknologi:** Framework Laravel 10 (PHP 8.2), MySQL Relational Database, Tailwind CSS, JavaScript, Midtrans Payment Gateway, Milestone Order Tracking.

---

## 🔍 3. LATAR BELAKANG & ANALISIS MASALAH (ROOT CAUSE ANALYSIS)

### A. Tiga Persoalan Utama pada Adelia Catering
1. **Kebutuhan Rekomendasi Sesuai Anggaran Pelanggan:** Pelanggan membutuhkan bantuan untuk menentukan kombinasi menu dari berbagai kategori yang sesuai dengan batas anggaran yang dimiliki.
2. **Proses Penyusunan Paket Masih Manual:** Pemilihan dan penyusunan paket menu masih dilakukan secara manual sehingga rekomendasi bergantung pada penentuan pengelola dan memakan waktu.
3. **Keseimbangan Antara Popularitas Menu dan Keuntungan Usaha (HPP):** Pengelola membutuhkan pertimbangan yang lebih terukur karena popularitas menu berdasarkan transaksi penjualan belum tentu sejalan dengan keuntungan yang diperoleh (selisih Harga Jual dengan Harga Pokok Produksi / HPP).

---

## ❓ 4. RUMUSAN MASALAH SKRIPSI (BAB 1)

1. Bagaimana menentukan nilai popularitas dan keuntungan setiap alternatif menu pada Adelia Catering berdasarkan data transaksi penjualan dan perhitungan Harga Pokok Produksi (HPP)?
2. Bagaimana menerapkan model *Selective Multiple-Choice Knapsack Problem* (SMCKP) dengan pendekatan *Weighted Sum* untuk menentukan kombinasi menu berdasarkan popularitas, keuntungan, dan batas anggaran pelanggan, dengan kategori menu yang bersifat opsional?
3. Bagaimana mengimplementasikan algoritma *Dynamic Programming* ke dalam sistem rekomendasi paket menu katering untuk menghasilkan kombinasi menu dengan nilai utilitas maksimum sesuai anggaran pelanggan?
4. Bagaimana hasil pengujian fungsional sistem dan ketepatan solusi optimasi dalam menghasilkan rekomendasi paket menu pada Adelia Catering?

---

## 🎯 5. TUJUAN & MANFAAT PENELITIAN

### A. Tujuan Penelitian
1. Menerapkan metodologi **CRISP-DM** secara end-to-end dalam mengekstrak pengetahuan preferensi menu dari data penjualan 3 tahun.
2. Merumuskan model optimasi matematis *Multi-Choice Knapsack Problem (MCKP)* untuk komposisi 5 hidangan lengkap (karbohidrat, lauk utama, lauk pendamping, sayur, snack).
3. Mengembangkan modul *Package Optimizer* berbasis *Dynamic Programming* dengan algoritma *pseudo-polynomial forward dynamic programming* dan *memoization backtracking*.
4. Mengintegrasikan mesin optimasi ke dalam sistem informasi katering berbasis web (fitur *Quick Recommender* pada admin, slider budget pada katalog publik, dan asisten chatbot interaktif).

### B. Manfaat Penelitian
* **Bagi Adelia Catering (Penjual):** Menjawab permintaan kustom calon pembeli dalam hitungan milidetik dengan paket terlaris dan paling menguntungkan tanpa takut merugi karena salah hitung.
* **Bagi Konsumen (Pembeli):** Memperoleh paket menu lengkap yang transparan, berkualitas tinggi berdasarkan tren riil 3 tahun, dan presisi sesuai budget.
* **Bagi Akademik (Teknik Informatika):** Membuktikan keunggulan komparatif *Dynamic Programming* dibandingkan algoritma heuristik dalam menyelesaikan *Multi-Choice Knapsack Problem* skala menengah pada domain e-commerce makanan.

---

## 🔄 6. METODOLOGI PENELITIAN: CRISP-DM (BAB 3)

Penelitian ini mengadopsi standar industri internasional **CRISP-DM** (*Cross-Industry Standard Process for Data Mining*) yang terdiri dari 6 tahapan siklus:

```
+-------------------------------------------------------------------------------+
|                       METODOLOGI CRISP-DM PADA PENELITIAN                     |
+-------------------------------------------------------------------------------+
  [1] Business Understanding  ──> Analisis kebutuhan penjual & pembeli katering
               │
               ▼
  [2] Data Understanding      ──> Eksplorasi dataset riwayat penjualan 3 tahun
               │
               ▼
  [3] Data Preparation        ──> Cleansing, agregasi Q_i & F_i, normalisasi v_i
               │
               ▼
  [4] Modeling                ──> Formulasi MCKP & Algoritma Dynamic Programming
               │
               ▼
  [5] Evaluation              ──> Uji optimalitas eksak, waktu runtime, zero-overbudget
               │
               ▼
  [6] Deployment              ──> Integrasi Laravel: Admin, Web Slider, Chatbot
```

### 1. Business Understanding (Pemahaman Bisnis)
* Mengidentifikasi proses bisnis pemesanan katering nasi box dan prasmanan pada Adelia Catering.
* Menetapkan tujuan data mining: Menghasilkan rekomendasi komposisi 5 elemen hidangan berdasarkan nominal budget dari pembeli.
* Menetapkan batasan bisnis: Total harga kombinasi $\le$ budget, setiap kategori wajib terwakili tepat 1 item, margin keuntungan tetap terjaga.

### 2. Data Understanding (Pemahaman Data)
* Mengumpulkan dataset riil transaksi penjualan 3 tahun terakhir (format CSV/Excel).
* Menganalisis struktur atribut data:
  * `order_date`: Tanggal dan waktu pesanan.
  * `menu_name`: Nama menu/hidangan.
  * `component_type`: Kategori hidangan (`karbohidrat`, `lauk_utama`, `lauk_pendamping`, `sayur`, `snack`).
  * `quantity`: Jumlah porsi terjual.
  * `unit_price`: Harga satuan hidangan.
* Mengevaluasi persebaran frekuensi menu dan mendeteksi data anomali atau pesanan yang dibatalkan.

### 3. Data Preparation (Penyiapan Data)
* **Data Cleaning:** Menghapus entri pesanan duplikat atau status *cancelled*.
* **Feature Engineering (Ekstraksi Popularitas):**
  Untuk setiap menu $i$, dihitung:
  * $Q_i$: Total volume kuantitas porsi yang terjual selama 3 tahun.
  * $F_i$: Total frekuensi nota pemesanan yang menyertakan menu tersebut.
* **Normalisasi Min-Max Utilitas:**
  Skor utilitas objektif $v_i \in [0, 100]$ dihitung dengan pembobotan pareto (60% volume penjualan, 40% frekuensi pemesanan):
  $$v_i = \left( 0.6 \times \frac{Q_i - Q_{\min}}{Q_{\max} - Q_{\min}} + 0.4 \times \frac{F_i - F_{\min}}{F_{\max} - F_{\min}} \right) \times 100$$
* Nilai $v_i$ disimpan pada tabel `menus` di database sebagai bobot *value* pada Knapsack.

### 4. Modeling (Pemodelan Dynamic Programming)
* Masalah dimodelkan sebagai *Multi-Choice Knapsack Problem (MCKP)*.
* Merancang algoritma *Dynamic Programming* dengan *State Transition* $DP(k, w)$ dan prinsip optimalitas Bellman untuk menemukan kombinasi global optimal secara deterministik.

### 5. Evaluation (Evaluasi Komputasi & Bisnis)
* Evaluasi tingkat keoptimalan solusi (*optimality guarantee* = 100%).
* Evaluasi kepatuhan anggaran (*budget compliance* = 100%, pelanggaran overbudget = 0%).
* Pengukuran waktu eksekusi (*execution runtime* $\le 3\text{ ms}$).
* Pengujian *Black-box* pada fungsionalitas antarmuka sistem.

### 6. Deployment (Penerapan Sistem)
* Integrasi mesin Dynamic Programming ke dalam arsitektur Laravel Service (`PackageOptimizerService.php`).
* Penyediaan akses multi-kanal:
  1. **Admin Panel:** Modul *Quick Budget Recommender* + generator pesan WhatsApp.
  2. **Customer Web:** Slider interaktif di `/packages` dengan visualisasi 5 komponen box.
  3. **Chatbot:** NLP berbasis intent dan keyword matching (contoh: *"rekomendasi paket 25000"*).

---

## 📐 7. PEMODELAN MATEMATIS & ALGORITMA DYNAMIC PROGRAMMING

### A. Formulasi Multi-Choice Knapsack Problem (MCKP)
Didefinisikan $K = 5$ kelas kategori komponen hidangan:
1. $k=1$: `karbohidrat` (Nasi Putih, Nasi Kuning, Nasi Liwet, Nasi Uduk, dll.)
2. $k=2$: `lauk_utama` (Ayam Bakar Madu, Rendang Daging, Bandeng Presto, dll.)
3. $k=3$: `lauk_pendamping` (Sambal Goreng Ati, Telur Balado, Perkedel Kentang, dll.)
4. $k=4$: `sayur` (Capcay Bakso, Tumis Buncis Jagung, Urap Sayur, dll.)
5. $k=5$: `snack` (Roti Buttercream, Roti Boy, Puding Cokelat, Lemper Ayam, dll.)

Setiap kelas $k$ memiliki himpunan item $C_k = \{1, 2, \dots, n_k\}$.  
Setiap item $j \in C_k$ memiliki:
* Harga hidangan: $p_{kj} \in \mathbb{Z}^+$
* Nilai utilitas/popularitas: $v_{kj} \in \mathbb{R}^+$

#### 1. Variabel Keputusan
$$x_{kj} \in \{0, 1\}$$
*(Bernilai 1 jika item ke-$j$ pada kategori ke-$k$ dipilih, 0 jika tidak).*

#### 2. Fungsi Tujuan (Maksimasi Total Utilitas Kepuasan Menu)
$$\max Z = \sum_{k=1}^{K} \sum_{j \in C_k} (v_{kj} \cdot x_{kj})$$

#### 3. Fungsi Kendala (Constraints)
1. **Kendala Pagu Anggaran (*Budget Constraint*):**
   $$\sum_{k=1}^{K} \sum_{j \in C_k} (p_{kj} \cdot x_{kj}) \le W$$
   *(Total biaya tidak boleh melebihi budget $W$ yang diinput).*
2. **Kendala Komposisi Eksak (*Exact One-Item per Category Constraint*):**
   $$\sum_{j \in C_k} x_{kj} = 1, \quad \forall k \in \{1, 2, \dots, K\}$$
   *(Tepat satu hidangan wajib dipilih dari setiap kategori).*

---

### B. Prinsip Optimalitas Bellman & Fungsi Rekurensi DP

Berdasarkan *Bellman's Principle of Optimality*, sub-struktur optimal dari masalah ini menyatakan bahwa: kombinasi optimal pada tahap $k$ dengan biaya $w$ terbentuk dari kombinasi optimal pada tahap $k-1$ dengan biaya $(w - p_{kj})$ ditambah utilitas item terpilih $v_{kj}$.

#### 1. Definisi State
$$DP(k, w)$$
Merepresentasikan **utilitas maksimum** yang dapat dicapai dengan memilih tepat satu item dari masing-masing kategori $1$ sampai $k$ dengan total biaya tepat $w$.

#### 2. Kondisi Batas Awal (Base Case)
$$DP(0, 0) = 0$$
$$DP(0, w) = -\infty, \quad \forall w > 0$$

#### 3. Persamaan Rekurensi (Recurrence Relation)
Untuk setiap tahap $k \in \{1, 2, \dots, K\}$ dan setiap kapasitas biaya $w \in \{0, 1, \dots, W\}$:
$$DP(k, w) = \max_{\substack{j \in C_k \\ w \ge p_{kj}}} \left\{ DP(k-1, w - p_{kj}) + v_{kj} \right\}$$

#### 4. Penelusuran Keputusan (Choice Tracking & Backtracking)
Untuk merekonstruksi menu yang dipilih, matriks penunjuk (*pointer*) disimpan pada setiap transisi:
$$\text{Choice}(k, w) = \arg\max_{\substack{j \in C_k \\ w \ge p_{kj}}} \left\{ DP(k-1, w - p_{kj}) + v_{kj} \right\}$$
$$\text{PrevCost}(k, w) = w - p_{k, \text{Choice}(k, w)}$$

Setelah mencapai tahap akhir $k = K$, dicari biaya terbaik $w^*$ yang memaksimalkan utilitas:
$$w^* = \arg\max_{w \le W} \{ DP(K, w) \}$$

Penelusuran balik (*backtracking*) dilakukan dari $k = K$ turun ke $k = 1$ untuk mengambil 5 item menu terpilih.

---

### C. Alur Kerja Algoritma Dynamic Programming (Flowchart)

```
        +----------------------------------------------------+
        |      Input Budget (W) & Preferensi Lauk Utama      |
        +----------------------------------------------------+
                                  │
                                  ▼
        +----------------------------------------------------+
        |   Ambil Kandidat Menu 5 Kategori (Tabel menus)     |
        +----------------------------------------------------+
                                  │
                                  ▼
        +----------------------------------------------------+
        |      Hitung Batas Minimum: W_min = ∑ min(p_kj)     |
        +----------------------------------------------------+
                                  │
                        [ Apakah W < W_min? ]
                        │                   │
                     [ YA ]               [ TIDAK ]
                        │                   │
                        ▼                   ▼
        +-----------------------+   +---------------------------------------+
        | Return: Budget Kurang |   | Inisialisasi DP Table:                |
        | Tampilkan W_min       |   | dp[0][0] = 0, dp[0][w] = -inf        |
        +-----------------------+   +---------------------------------------+
                                                    │
                                                    ▼
                                    +---------------------------------------+
                                    | Loop Kategori k = 1 s/d 5:            |
                                    |   Untuk setiap state cost di k-1:     |
                                    |     Untuk setiap item j di C_k:       |
                                    |       new_cost = prev_cost + p_kj     |
                                    |       if new_cost <= W:               |
                                    |         dp[k][new_cost] = max(...)    |
                                    +---------------------------------------+
                                                    │
                                                    ▼
                                    +---------------------------------------+
                                    | Cari w* = argmax { dp[5][w] } (w <= W)|
                                    | (Tie-breaker: pilih cost terendah)    |
                                    +---------------------------------------+
                                                    │
                                                    ▼
                                    +---------------------------------------+
                                    | Backtracking dari k = 5 ke k = 1      |
                                    | Ambil 5 hidangan optimal terpilih     |
                                    +---------------------------------------+
                                                    │
                                                    ▼
                                    +---------------------------------------+
                                    | Return Hasil: Rincian Paket, Harga,   |
                                    | Penghematan, Kalori, Waktu Eksekusi   |
                                    +---------------------------------------+
```

---

## ⚖️ 8. PERBANDINGAN KOMPARATIF: DYNAMIC PROGRAMMING VS ALGORITMA GENETIKA

Tabel berikut menjadi argumen ilmiah yang sangat krusial dalam mempertanggungjawabkan pilihan algoritma saat sidang skripsi:

| Parameter Perbandingan | Dynamic Programming (DP) | Algoritma Genetika (GA) |
| :--- | :--- | :--- |
| **Sifat Solusi** | **Deterministik Eksak** (selalu konsisten pada input yang sama) | **Stokastik Probabilistik** (hasil bisa berubah-ubah antar generasi/run) |
| **Jaminan Optimalitas** | **100% Terjamin Global Optimum** (berdasarkan bukti matematis Bellman) | **Mendekati Optimal (Aproksimasi)**, berisiko terjebak *local optimum* |
| **Kepatuhan Anggaran** | **Pasti 100% $\le$ Budget** (tidak pernah overbudget secara mutlak) | Memerlukan fungsi penalti (*penalty function*), masih ada peluang overbudget jika penalti kurang ketat |
| **Waktu Komputasi (*Runtime*)** | **Sangat Cepat ($< 3\text{ ms}$)** | Relatif Lambat ($15 - 50\text{ ms}$) karena evaluasi puluhan generasi populasi |
| **Kebutuhan Tuning Parameter** | **Nol (*Zero Hyperparameters*)** | Sangat sensitif terhadap ukuran populasi ($N$), nilai $P_c$, dan nilai $P_m$ |
| **Kelayakan untuk Aplikasi Real-Time** | **Sangat Unggul** untuk Web & Chatbot berkecepatan tinggi | Cukup, namun membebani CPU server jika diakses serentak |

---

## 👥 9. ARSITEKTUR IMPLEMENTASI DUA SISI

```
+---------------------------------------------------------------------------------------+
|                              DATABASE ADELIA CATERING                                 |
|  • Riwayat Transaksi 3 Tahun  • Skor Utilitas Empiris (vi)  • Katalog Menu 5 Komponen |
+---------------------------------------------------------------------------------------+
                                           │
                                           ▼
                 [ ENGINE DYNAMIC PROGRAMMING (PackageOptimizerService) ]
                 Input: Budget (W) | Kompleksitas: O(K·W·M) | Runtime: ~1 ms
                                           │
              ┌────────────────────────────┴────────────────────────────┐
              ▼                                                         ▼
    [ SISI PENJUAL (ADMIN) ]                                  [ SISI PEMBELI (PUBLIK) ]
• Menu "Quick Budget Recommender"                         • Halaman Katalog "/packages"
• Admin ketik budget dari WhatsApp/telepon                • Pembeli geser slider budget interaktif
• Muncul 5 hidangan rekomendasi optimal                   • Muncul kartu bento 5 hidangan seketika
• Tombol 1-klik "Salin Format WhatsApp"                   • Chatbot interaktif ("paket 25k")
• Langsung kirim penawaran ke calon pembeli!              • Tombol 1-klik "Tambah ke Keranjang"
```

---

## 📊 10. RENCANA PENGUJIAN BAB 4 SKRIPSI

1. **Uji Validasi Optimalitas Eksak (*Exact Optimality Test*):**  
   Membandingkan hasil Dynamic Programming dengan pencarian menyeluruh (*Exhaustive Brute Force Search*) pada sampel katalog untuk membuktikan tingkat akurasi solusi optimal mencapai **100%**.
2. **Uji Kepatuhan Pagu Anggaran (*Zero Overbudget Compliance*):**  
   Menguji algoritma pada 30 variasi nominal budget (dari Rp 18.000 hingga Rp 75.000). Memverifikasi bahwa pelanggaran batas anggaran adalah **0%** (*zero violation*).
3. **Uji Waktu Komputasi (*Execution Time Benchmarking*):**  
   Mengukur waktu eksekusi dalam skala milidetik pada berbagai rentang budget. Membuktikan rata-rata waktu eksekusi $\le 3\text{ ms}$, yang jauh melampaui standar SLA web responsif ($< 200\text{ ms}$).
4. **Uji Efisiensi Biaya (*Budget Utilization Efficiency*):**  
   Mengukur rasio serapan anggaran $\frac{\text{Total Harga}}{\text{Budget}}$ untuk memastikan rekomendasi memaksimalkan nilai kepuasan tanpa pemborosan dana konsumen.
5. **Uji Fungsional Sistem (*Black-Box Testing*):**  
   Menguji seluruh antarmuka admin, website, chatbot, dan integrasi pembayaran Midtrans menggunakan metode *Equivalence Partitioning* dan *Boundary Value Analysis*.

---

## 📚 11. REFERENSI JURNAL ILMIAH (UNTUK BAB 2 SKRIPSI)

1. **Bellman, R. (1957).**  
   *Dynamic Programming.* Princeton University Press, Princeton, NJ. *(Buku rujukan utama penemu Dynamic Programming).*
2. **Pisinger, D. (1995).**  
   *A minimal algorithm for the multiple-choice knapsack problem.* European Journal of Operational Research, 83(2), 394-410.
3. **Martello, S., & Toth, P. (1990).**  
   *Knapsack Problems: Algorithms and Computer Implementations.* John Wiley & Sons.
4. **Chapman, P., Clinton, J., Kerber, R., Khabaza, T., Reinartz, T., Shearer, C., & Wirth, R. (2000).**  
   *CRISP-DM 1.0: Step-by-step data mining guide.* Technical report, The CRISP-DM Consortium.
5. **Kellerer, H., Pferschy, U., & Pisinger, D. (2004).**  
   *Multichoice Knapsack Problem.* In Knapsack Problems (pp. 317-347). Springer, Berlin, Heidelberg.
6. **Han, J., Kamber, M., & Pei, J. (2012).**  
   *Data Mining: Concepts and Techniques (3rd ed.).* Morgan Kaufmann Publishers.

---

## 🤖 12. PROMPT SIAP PAKAI UNTUK CHATGPT

*Salin prompt di bawah ini ke ChatGPT saat menyusun bab-bab skripsi Anda:*

```text
Halo ChatGPT, saya adalah mahasiswa S1 Teknik Informatika yang sedang menyusun skripsi dengan judul:
"Sistem Rekomendasi Paket Menu Katering Berdasarkan Batasan Anggaran dan Analisis Data Transaksi Penjualan 3 Tahun Menggunakan Algoritma Dynamic Programming Berbasis CRISP-DM (Studi Kasus: Adelia Catering)".

Konteks Penelitian Saya:
1. Masalah Bisnis: Calon pembeli katering memiliki batasan dana (budget) kaku per porsi namun bingung memilih menu, sedangkan penjual katering memakan waktu lama menghitung racikan paket secara manual.
2. Metodologi Data Mining: Menggunakan CRISP-DM 6 tahapan untuk mengolah riwayat transaksi 3 tahun Adelia Catering menjadi bobot popularitas objektif menu (vi) berdasarkan volume porsi (Qi) dan frekuensi transaksi (Fi).
3. Model Matematis: Multi-Choice Knapsack Problem (MCKP) dengan 5 kelompok hidangan (karbohidrat, lauk utama, lauk pendamping, sayur, snack) dengan kendala total harga <= budget.
4. Algoritma Solusi: Dynamic Programming (DP) berbasis Prinsip Optimalitas Bellman dengan fungsi rekurensi forward stage-by-stage dan memoization table untuk menghasilkan solusi eksak global optimal secara deterministik dalam waktu < 3 milidetik.
5. Implementasi: Framework Laravel 10 (PHP), terintegrasi ke modul Quick Budget Recommender Admin, Web Slider Publik (/packages), dan Chatbot Interaktif.

Berdasarkan konteks di atas, tolong bantu saya untuk:
[PILIH SALAH SATU]:
- "Membuat draf BAB 1 Pendahuluan (Latar Belakang, Identifikasi Masalah, Rumusan Masalah, Batasan Masalah, Tujuan, Manfaat) secara formal, sistematis, dan bernuansa akademis Teknik Informatika."
- "Membuat draf BAB 3 Metodologi Penelitian yang menguraikan 6 fase CRISP-DM dan formulasi matematis Dynamic Programming untuk MCKP lengkap dengan persamaan rekurensi Bellman."
- "Membuat simulasi 10 pertanyaan kritis dan tajam dari dosen penguji skripsi terkait alasan memilih Dynamic Programming dibanding Algoritma Genetika, beserta jawaban ilmiahnya yang meyakinkan."
```

---
*Dokumen ini merupakan master concept skripsi Adelia Catering yang telah diselaraskan dengan standar akademik Teknik Informatika.*
