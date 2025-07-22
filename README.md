# Akademika: Sistem Manajemen Sekolah Multijenjang

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

**Akademika** adalah sebuah sistem manajemen sekolah berbasis web yang dirancang untuk mengelola berbagai aspek operasional sekolah, mulai dari tingkat SD, SMP, hingga SMA/SMK. Sistem ini menyediakan antarmuka yang berbeda dan fungsionalitas yang disesuaikan untuk peran Administrator, Guru, Siswa, dan Orang Tua.

## Deskripsi Proyek

Proyek Akademika bertujuan untuk menyederhanakan proses administrasi dan akademik di sekolah dengan menyediakan platform terpadu. Dengan fokus pada responsivitas dan kemudahan penggunaan, Akademika menghadirkan pengalaman digital yang efisien untuk seluruh komunitas sekolah.

## Fitur-Fitur Utama

* **Manajemen Pengguna Lengkap (CRUD):** Mengelola akun Administrator, Guru, Siswa, dan Orang Tua dengan peran dan hak akses yang berbeda. Mendukung pembuatan user baru secara otomatis saat membuat profil siswa.
* **Manajemen Kelas (CRUD):** Mengelola daftar kelas dan penugasan wali kelas.
* **Manajemen Mata Pelajaran (CRUD):** Mengelola daftar mata pelajaran yang diajarkan di sekolah.
* **Manajemen Siswa (CRUD):** Mengelola data profil siswa (NIS, NISN, dll.), terintegrasi dengan akun pengguna dan kelas.
* **Manajemen Penugasan Mengajar (CRUD):** Menentukan mata pelajaran apa diajarkan oleh guru mana di kelas mana.
* **Manajemen Penilaian (CRUD):** Memasukkan dan melihat nilai siswa berdasarkan mata pelajaran dan jenis penilaian.
* **Manajemen Absensi (CRUD):** Mencatat dan melihat status kehadiran siswa.
* **Manajemen Jadwal Pelajaran (CRUD):** Membuat dan melihat jadwal pelajaran mingguan dengan deteksi tabrakan jadwal kelas dan guru.
* **Papan Pengumuman (CRUD):** Membuat dan melihat pengumuman yang ditargetkan untuk peran pengguna tertentu.
* **Kalender Akademik (CRUD):** Mengelola dan melihat acara-acara penting sekolah.
* **Pengaturan Sistem:** Mengelola informasi dasar sekolah (nama, alamat, tahun ajaran).
* **Dashboard Spesifik Peran:** Tampilan dashboard yang disesuaikan untuk Administrator, Guru, dan Siswa.
* **Kontrol Akses Berbasis Peran:** Membatasi akses user ke modul dan data tertentu sesuai perannya.
* **Mode Gelap (Dark Mode):** Fitur mode gelap yang dapat diaktifkan/dinonaktifkan pengguna dengan transisi yang mulus.
* **Notifikasi In-App:** Lonceng notifikasi dengan hitungan pesan/kejadian baru yang belum dibaca.
* **Pesan Internal:** Fitur kirim/terima pesan antar pengguna dalam sistem.
* **Responsif:** Tampilan yang menyesuaikan di berbagai ukuran layar (desktop, tablet, mobile).

## Teknologi yang Digunakan

* **Backend:** PHP 8.2+ dengan Framework Laravel 10/11
* **Database:** MySQL
* **Frontend:** HTML, Tailwind CSS, Alpine.js
* **Manajemen Peran & Izin:** Spatie Laravel Permission
* **Notifikasi Interaktif:** SweetAlert2
* **Package Management:** Composer (PHP), NPM (Node.js)
* **Aset Bundling:** Vite

## Panduan Instalasi

Ikuti langkah-langkah di bawah ini untuk mengatur proyek Akademika di lingkungan lokal Anda.

### Prasyarat

Pastikan Anda memiliki hal-hal berikut terinstal di sistem Anda:

* PHP (versi 8.2 atau lebih tinggi direkomendasikan)
* Composer
* Node.js & NPM (direkomendasikan versi LTS)
* MySQL Server (atau database lain yang kompatibel dengan Laravel seperti PostgreSQL, SQLite)
* Git (opsional, untuk kloning repositori)

### Langkah-langkah Instalasi

1.  **Kloning Repositori (jika Anda belum memiliki kodenya):**
    ```bash
    git clone [https://github.com/nama_user_github_anda/akademika.git](https://github.com/nama_user_github_anda/akademika.git)
    cd akademika
    ```

2.  **Instal Dependensi Composer (Backend):**
    ```bash
    composer install
    ```

3.  **Instal Dependensi NPM (Frontend):**
    ```bash
    npm install
    ```

4.  **Konfigurasi Environment (`.env`):**

    * Salin file `.env.example` dan ubah namanya menjadi `.env`:
        ```bash
        cp .env.example .env
        ```
    * Buat `APP_KEY` baru:
        ```bash
        php artisan key:generate
        ```
    * Buka file `.env` dan konfigurasikan detail database Anda:
        ```dotenv
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=akademika_db # Ganti dengan nama database yang Anda inginkan
        DB_USERNAME=root         # Ganti dengan username database Anda
        DB_PASSWORD=             # Ganti dengan password database Anda (kosongkan jika tidak ada)
        ```
    * **Buat database kosong** dengan nama yang sama (`akademika_db`) di MySQL server Anda secara manual (menggunakan phpMyAdmin, MySQL Workbench, atau CLI).

5.  **Jalankan Migrasi Database dan Seeders:**
    Perintah ini akan membuat semua tabel database yang diperlukan dan mengisi data dummy.
    **PERINGATAN:** Perintah ini akan **MENGHAPUS SEMUA DATA** yang ada di database Anda (`akademika_db`) sebelum membuat ulang tabel dan mengisi data.
    ```bash
    php artisan migrate:fresh --seed
    ```

6.  **Jalankan Server Pengembangan Laravel:**
    ```bash
    php artisan serve
    ```
    Anda akan melihat pesan `INFO Server running on [http://127.0.0.1:8000]`.

7.  **Jalankan Vite Development Server (untuk Frontend):**
    Buka terminal baru di direktori proyek yang sama, dan jalankan:
    ```bash
    npm run dev
    ```
    Biarkan terminal ini tetap terbuka selama Anda mengembangkan.

8.  **Akses Aplikasi:**
    Buka browser Anda dan kunjungi `http://127.0.0.1:8000`.

## Kredensial Login untuk Pengujian

Setelah menjalankan `php artisan migrate:fresh --seed`, Anda dapat menggunakan kredensial berikut untuk menguji berbagai peran:

* **Administrator:**
    * **Email:** `admin@akademika.com`
    * **Password:** `password`

* **Guru (Akan diminta mengubah password saat login pertama):**
    * **Email:** `guru@akademika.com`
    * **Password:** `password`
    * (Juga tersedia `guru2@akademika.com`, `guru3@akademika.com`)

* **Siswa (Akan diminta mengubah password saat login pertama):**
    * **Email:** `siswa@akademika.com`
    * **Password:** `password`
    * (Juga tersedia `siswa2@akademika.com`, `siswa3@akademika.com`)

* **Orang Tua (Akan diminta mengubah password saat login pertama):**
    * **Email:** `ortu@akademika.com`
    * **Password:** `password`
    * (Juga tersedia `ortu2@akademika.com`, `ortu3@akademika.com`)

## Kontribusi

Kontribusi dalam bentuk *pull request*, laporan *bug*, atau saran fitur sangat disambut baik.

## Hubungi Kami

* **Ranus di Facebook:** Ranusate

## Lisensi

Proyek ini dilisensikan di bawah Lisensi MIT.