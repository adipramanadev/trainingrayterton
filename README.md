
```markdown
# 🛒 Mini Project POS – Training PT Rayterton

Proyek ini adalah **materi pelatihan Laravel + Flutter** untuk membuat aplikasi **Point of Sale (POS)** sederhana.  
Backend menggunakan **Laravel** (REST API + Laravel Sanctum) dan frontend menggunakan **Flutter**.  

## 🎯 Tujuan Training
- Memahami pembuatan REST API dengan Laravel.
- Menerapkan autentikasi berbasis token menggunakan Laravel Sanctum.
- Mendesain database relasional untuk sistem POS.
- Menghubungkan backend Laravel dengan frontend Flutter.
- Mengimplementasikan proses transaksi penjualan dengan pengurangan stok otomatis.

---

## 🗂️ Fitur Utama
- **Login & Logout** (kasir & admin)
- **Manajemen Produk** (CRUD produk & kategori)
- **Transaksi Penjualan** (keranjang → bayar → stok berkurang)
- **Laporan Harian** (total penjualan & jumlah transaksi per tanggal)

---

## 📦 Struktur Database & Analisa

### 1. **Tabel `users`**
Menyimpan data akun pengguna sistem:
- `id`, `name`, `email`, `password`
- `role` → `admin` atau `cashier`
- Relasi: **1 user** → banyak **sales**.

### 2. **Tabel `categories`**
Mengelompokkan produk:
- `id`, `name`
- Relasi: **1 kategori** → banyak **products**.

### 3. **Tabel `products`**
Data barang yang dijual:
- `sku`, `name`, `category_id`, `price`, `stock`
- Relasi: **1 produk** → banyak **sale_items**.

### 4. **Tabel `sales`**
Data transaksi per nota:
- `user_id`, `total`, `paid`, `change`, `note`
- Relasi: **1 sale** → banyak **sale_items**.

### 5. **Tabel `sale_items`**
Detail barang dalam transaksi:
- `sale_id`, `product_id`, `qty`, `price`
- Menghubungkan produk & penjualan (many-to-many).

**ERD Singkat:**
```

users (1) ----< sales (N)
products (1) ----< sale\_items (N) >---- (1) sales
categories (1) ----< products (N)

````

---

## 🛠️ Teknologi
### Backend
- PHP 8.x
- Laravel 11
- Laravel Sanctum (Auth Token)
- MySQL/MariaDB
- Composer

### Frontend
- Flutter 3.x
- Provider (State Management)
- Dio (HTTP Client)
- Shared Preferences (Local Storage)

---

## 🚀 Instalasi Backend (Laravel)
1. Clone repo:
   ```bash
   git clone https://github.com/adipramanadev/trainingrayterton.git
   cd trainingrayterton/backend
````

2. Install dependencies:

   ```bash
   composer install
   ```
3. Copy `.env`:

   ```bash
   cp .env.example .env
   ```
4. Atur koneksi database di `.env`.
5. Jalankan migrasi & seeder:

   ```bash
   php artisan migrate --seed
   ```
6. Jalankan server:

   ```bash
   php artisan serve
   ```

---

## 📱 Instalasi Frontend (Flutter)

1. Masuk folder frontend:

   ```bash
   cd ../frontend
   ```
2. Install dependencies:

   ```bash
   flutter pub get
   ```
3. Jalankan:

   ```bash
   flutter run
   ```

---

## 🔄 Alur Proses Utama

1. **Login** → dapatkan token kasir/admin.
2. **Pilih Produk** → tambah ke keranjang.
3. **Checkout** → hitung total, bayar, stok berkurang.
4. **Laporan Harian** → filter penjualan berdasarkan tanggal.

---

## 🧪 Pengujian (Test Case)

* **Auth**: Login valid & invalid.
* **Produk**: Tambah produk duplikat SKU → error.
* **Transaksi**:

  * Stok cukup → berhasil, stok berkurang.
  * Stok kurang → error 422.
  * Bayar < total → error 422.
* **Laporan**: Cek penjualan sesuai tanggal.

---

## 📄 Lisensi

Materi ini digunakan untuk kebutuhan **Training Internal PT Rayterton**.

```
