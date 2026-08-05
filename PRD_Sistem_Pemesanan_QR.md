# Product Requirements Document (PRD)
## Sistem Pemesanan QR Kafe (QR Menu & Ordering System)

**Disiapkan untuk:** Tim Development Antigravity
**Tumpukan Teknologi (Tech Stack):** Frontend: Vue.js | Backend: Laravel | Database: MySQL/PostgreSQL

---

### 1. Latar Belakang & Tujuan
Proyek ini bertujuan untuk membangun sistem pemesanan mandiri berbasis Kode QR untuk operasional kafe/restoran. Mengacu pada referensi alur (seperti metode "Pesan di meja, bayar di kasir"), sistem ini akan menghilangkan antrean pemesanan, mempercepat distribusi pesanan ke dapur, dan meminimalisir kesalahan pencatatan.

### 2. Alur Pengguna Utama (User Flow)
1. **Pemindaian & Validasi:** Pelanggan memindai QR Code di meja (QR hanya memuat parameter `nomor_meja` atau URL dasar).
2. **Input Kode Outlet:** Pelanggan diarahkan ke *Web App* dan wajib memasukkan **Kode Outlet** (misal: S2LZ6B) yang tertera pada stiker meja untuk memvalidasi lokasi.
3. **Eksplorasi Menu:** Setelah kode valid, katalog menu digital untuk outlet tersebut ditampilkan.
4. **Pemesanan:** Pelanggan memilih menu, menyesuaikan varian/opsi (*add-on*), dan menekan "Pesan".
5. **Sinkronisasi Real-Time:** Pesanan langsung muncul di layar/dashboard Kasir dan Dapur.
6. **Penyelesaian:** Pelanggan menikmati hidangan, lalu melakukan pembayaran di kasir dengan menyebutkan nomor meja.

### 3. Kebutuhan Fungsional (Functional Requirements)

#### A. Sisi Pelanggan (Customer Frontend - Vue.js)
* **Form Input Kode Outlet:** Layar awal yang meminta pengguna memasukkan kode unik outlet dengan validasi *real-time* ke *database*.
* **Katalog Menu Digital:** Menampilkan daftar kategori, foto produk, nama, harga, dan deskripsi sesuai dengan outlet yang diinput.
* **Manajemen Keranjang (Cart):** Menyimpan item sementara berbasis *session/cookie* perangkat pelanggan.
* **Modifikasi Pesanan:** Mendukung pilihan dinamis (contoh: Level Pedas, Pilihan Topping, Dingin/Panas).
* **Validasi Meja:** Mengunci nomor meja berdasarkan parameter QR.
* **Status Pesanan (Live Tracking):** Layar sukses yang menampilkan status ("Menunggu Diproses", "Sedang Disiapkan").

#### B. Sisi Kasir & Admin (Backend & Dashboard - Laravel)
* **Dashboard Kasir (POS Interface):**
  * Tampilan *real-time* pesanan masuk (menggunakan Laravel Reverb / Pusher).
  * Fitur penyelesaian pembayaran (ubah status *Unpaid* menjadi *Paid*).
  * Integrasi cetak struk otomatis ke printer thermal.
* **Manajemen Menu & Outlet (CRUD):** Tambah/edit produk berdasarkan outlet tertentu. Manajemen kode unik untuk setiap outlet.
* **Manajemen Meja:** Generator Kode QR per meja.
* **Laporan Penjualan:** Ringkasan pendapatan dan produk terlaris harian/bulanan.

### 4. Pedoman UI/UX (UI/UX Guidelines)
Pengembangan antarmuka harus menjunjung tinggi prinsip desain produk digital yang intuitif. 
* **Fokus pada Layar Input Kode:** Form input kode outlet harus memiliki *micro-interactions* yang baik (contoh: *auto-capitalize* huruf besar, pesan *error* yang jelas jika salah input, dan ukuran *field* yang besar) agar tambahan langkah ini tidak merusak *frictionless experience*.
* **Mobile-First Design:** Elemen tombol harus *thumb-friendly* (mudah dijangkau jempol).
* **Visual Hierarchy:** Harga dan tombol "Tambah" harus paling menonjol. Foto produk menggunakan resolusi optimal namun terkompresi agar pemuatan halaman cepat.

### 5. Kebutuhan Non-Fungsional
* **Performa:** Waktu muat halaman menu awal di bawah 2 detik.
* **Keamanan:** Mencegah manipulasi pesanan lintas-outlet dengan memvalidasi *session* kode outlet di Laravel.
* **Skalabilitas:** Arsitektur database wajib menggunakan relasi Multi-tenant untuk memisahkan data antar outlet.
