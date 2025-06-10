# Planned Maintenance System (PMS)

[![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![MySQL](https://img.shields.io/badge/Database-MySQL-4479A1?style=for-the-badge&logo=mysql)](https://www.mysql.com/)
[![Tailwind CSS](https://img.shields.io/badge/CSS-TailwindCSS-38B2AC?style=for-the-badge&logo=tailwind-css)](https://tailwindcss.com/)

Sistem Informasi Manajemen Pemeliharaan Terencana (Planned Maintenance System) adalah aplikasi web berbasis Laravel yang dirancang untuk membantu memonitor dan mengelola running hours sparepart untuk tujuan pelacakan pemeliharaan. Sistem ini menyediakan fitur inventarisasi sparepart, pencatatan running hours, pengelolaan permintaan sparepart, dan laporan aktivitas.

## Fitur Utama

* **Dashboard Interaktif**: Ringkasan visual inventaris, running hours, permintaan sparepart, dan laporan aktivitas. Termasuk pop-up peringatan dini untuk sparepart dengan status `Warning` atau `Danger`.
* **Manajemen Inventaris**:
    * Melihat daftar sparepart berdasarkan kategori (Auxiliary Engine, Main Engine, Pump Engine).
    * Menambah sparepart baru ke inventaris (khusus Admin).
* **Manajemen Running Hours**:
    * Mencatat dan memantau jam terpakai sparepart sejak tanggal pemasangan.
    * Status sparepart (`Safe`, `Warning`, `Danger`) dihitung secara dinamis berdasarkan sisa jam pakai.
    * Kategorisasi jenis maintenance (`Harian`, `Mingguan`, `Bulanan`, `Tahunan`) berdasarkan `Max Running Hour`.
    * Fitur penggantian sparepart untuk item yang kritis (khusus Admin).
* **Manajemen Permintaan Sparepart**:
    * Membuat permintaan sparepart baru.
    * Melihat daftar permintaan sparepart.
    * Admin dapat `Approve` atau `Reject` permintaan.
* **Laporan Aktivitas**:
    * Melihat log semua aktivitas penting dalam sistem.
    * Mengunduh laporan aktivitas dalam format CSV.
* **Otorisasi Berbasis Peran**: Membatasi akses fitur-fitur penting (misalnya, menambah inventaris, approve/reject permintaan, penggantian sparepart) hanya untuk pengguna dengan peran `Admin`.

## Teknologi

* **Backend**: Laravel (PHP)
* **Database**: MySQL
* **Frontend**: Blade Templates, Tailwind CSS, JavaScript (Vanilla JS)
* **Development Environment**: XAMPP / Laragon (Apache, MySQL)

## Instalasi

Ikuti langkah-langkah di bawah ini untuk mengatur dan menjalankan proyek di lingkungan lokal Anda.

### Prasyarat

* PHP >= 8.1
* Composer
* MySQL Database
* Node.js & npm (untuk kompilasi aset frontend)

### Langkah-langkah

1.  **Clone repositori:**
    ```bash
    git clone [https://github.com/your-username/your-repo-name.git](https://github.com/your-username/your-repo-name.git)
    cd your-repo-name
    ```
    *(Ganti `your-username` dan `your-repo-name` dengan detail repositori Anda)*

2.  **Instal dependensi Composer:**
    ```bash
    composer install
    ```

3.  **Buat file `.env`:**
    Salin file `.env.example` menjadi `.env`.
    ```bash
    cp .env.example .env
    ```

4.  **Konfigurasi `.env`:**
    Buka file `.env` dan konfigurasikan detail database Anda:
    ```env
    APP_NAME="Planned Maintenance System"
    APP_ENV=local
    APP_KEY=
    APP_DEBUG=true
    APP_URL=http://localhost:8000

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=maintenance_db # Sesuaikan dengan nama database Anda
    DB_USERNAME=root         # Sesuaikan
    DB_PASSWORD=             # Sesuaikan

    APP_TIMEZONE=Asia/Jakarta # Penting untuk konsistensi waktu WIB
    ```
    * Generate `APP_KEY` jika belum ada: `php artisan key:generate`

5.  **Jalankan Migrasi Database dan Seeder (Opsional, untuk data awal):**
    ```bash
    php artisan migrate
    # Jika Anda memiliki seeder untuk data dummy (misalnya untuk users, spareparts)
    # php artisan db:seed
    # Atau seed spesifik: php artisan db:seed --class=UserSeeder
    ```
    * **Penting**: Pastikan Anda telah menjalankan semua migrasi yang diperlukan, termasuk yang menambahkan kolom `role` ke tabel `users`, `status` dan `quantity` ke `sparepart_requests`, `max_running_hour` dan `status` ke `running_hours`, serta `timestamps` ke tabel `sparepart_ae/me/pe` dan `reports`.

6.  **Instal dependensi NPM dan Kompilasi Aset Frontend:**
    ```bash
    npm install
    npm run dev # Untuk development
    # npm run build # Untuk production
    ```

7.  **Jalankan Server Pengembangan Laravel:**
    ```bash
    php artisan serve
    ```
    Aplikasi akan tersedia di `http://localhost:8000`.

## Penggunaan

* **Akses aplikasi:** Buka `http://localhost:8000` di browser Anda.
* **Login:** Gunakan kredensial pengguna yang ada di database Anda. Untuk mengakses fitur Admin, pastikan akun Anda memiliki `role` `admin` di tabel `users`.
* **Navigasi:** Gunakan menu navigasi untuk menjelajahi Dashboard, Inventaris, Daftar Permintaan, Running Hours, dan Laporan.

## Kontribusi

Kontribusi dipersilakan! Jika Anda menemukan bug atau memiliki saran fitur, silakan buka *issue* atau buat *pull request*.

## Lisensi

[Opsional: Tambahkan informasi lisensi di sini, misalnya MIT License]

---
