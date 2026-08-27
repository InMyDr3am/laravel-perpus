# Perpustakaan

Aplikasi web perpustakaan sederhana & ringan menggunakan **Laravel 12 murni** (tanpa Node/Vite, tanpa paket pihak ketiga). Tampilan memakai Blade + Tailwind CSS (CDN). Database **MySQL**.

## Fitur

- **Dashboard** — ringkasan total buku, anggota, peminjaman aktif, dan keterlambatan.
- **Manajemen Buku** — CRUD buku beserta stok & ketersediaan, pencarian judul/penulis/ISBN.
- **Manajemen Kategori** — CRUD kategori.
- **Manajemen Anggota** — CRUD anggota dengan kode otomatis (`M0001`, ...).
- **Peminjaman & Pengembalian** — transaksi pinjam/kembali dengan jatuh tempo otomatis.
- **Denda keterlambatan** — dihitung otomatis saat pengembalian.
- Autentikasi admin (login).

## Kebutuhan

- PHP 8.2+
- Composer
- MySQL / MariaDB

## Instalasi

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Buat database `perpustakaan`, lalu sesuaikan kredensial di `.env` (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

```bash
php artisan migrate --seed
php artisan serve
```

Buka http://127.0.0.1:8000.

### Akun admin default

- Email: `admin@perpus.test`
- Kata sandi: `password`

## Konfigurasi

Aturan peminjaman ada di `config/library.php` (atau `.env`):

| Env | Default | Keterangan |
|-----|---------|------------|
| `LIBRARY_LOAN_DAYS` | 7 | Lama peminjaman (hari) |
| `LIBRARY_FINE_PER_DAY` | 1000 | Denda per hari (Rupiah) |

## Struktur Utama

```
app/
  Http/Controllers/   Controller tipis (resource controllers)
  Http/Requests/      Validasi (Form Requests)
  Models/             Eloquent: Book, Category, Member, Loan
  Services/           LoanService — logika pinjam/kembali & denda
resources/views/      Blade + Tailwind (CDN)
config/library.php    Konfigurasi peminjaman & denda
```

## Testing

```bash
php artisan test
```

Feature test mencakup alur peminjaman, guard stok, dan perhitungan denda (SQLite in-memory).
