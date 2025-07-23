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
    git clone [https://github.com/Radianus/akademika.git](https://github.com/Radianus/akademika.git)
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
        APP_URL=http://localhost:8000 # Pastikan port sesuai dengan php artisan serve
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

## Dokumentasi Pengguna (User Guide)

Dokumen ini ditujukan untuk pengguna akhir sistem Akademika (Administrator, Guru, Siswa, Orang Tua) agar dapat memahami dan menggunakan fitur-fitur yang tersedia.

### 1. Pendahuluan

Selamat datang di Akademika, Sistem Manajemen Sekolah Multijenjang. Akademika dirancang untuk mempermudah pengelolaan data dan proses akademik di sekolah Anda, dari manajemen siswa, nilai, absensi, hingga jadwal pelajaran dan komunikasi internal.

### 2. Login & Peran Pengguna

Untuk mengakses sistem, silakan kunjungi alamat aplikasi Anda (misal: `http://localhost:8000/login`). Gunakan kredensial yang disediakan di bagian "Kredensial Login untuk Pengujian". Setelah login, Anda akan diarahkan ke Dashboard yang disesuaikan dengan peran Anda.

### 3. Fitur Umum (Untuk Semua Peran)

* **Dashboard:** Halaman ringkasan informasi yang relevan dengan peran Anda.
* **Pengumuman:** Melihat pengumuman penting dari sekolah. Admin dan Guru dapat membuat pengumuman.
* **Pesan Internal:** Mengirim dan menerima pesan pribadi antar pengguna di dalam sistem.
* **Kalender Akademik:** Melihat jadwal acara dan kegiatan penting sekolah. Admin dan Guru dapat membuat acara.
* **Mode Gelap (Dark Mode):** Klik ikon bulan/matahari di kanan atas navbar untuk mengubah tema aplikasi.
* **Notifikasi:** Ikon lonceng di navbar akan menunjukkan jumlah notifikasi baru. Klik untuk melihat daftar dan menandai sudah dibaca.
* **Profil Pengguna:** Akses melalui nama Anda di kanan atas navbar. Anda dapat mengubah nama, email, password, dan mengunggah gambar profil (avatar).

### 4. Panduan Administrator

Sebagai Administrator, Anda memiliki kendali penuh atas sistem.

* **Dashboard Admin:** Menampilkan statistik umum (jumlah pengguna, kelas, siswa, dll.) dan link cepat ke modul manajemen.
* **Manajemen Pengguna:** Menambah, mengedit, menghapus akun pengguna (Admin, Guru, Siswa, Orang Tua).
* **Manajemen Kelas:** Menambah, mengedit, menghapus daftar kelas sekolah.
* **Manajemen Siswa:** Menambah profil siswa baru (bisa membuat akun user baru atau memilih yang sudah ada), mengedit, menghapus data siswa.
* **Manajemen Mata Pelajaran:** Menambah, mengedit, menghapus mata pelajaran.
* **Manajemen Penugasan Mengajar:** Menentukan guru mana mengajar mata pelajaran apa di kelas mana.
* **Manajemen Nilai:** Memasukkan, melihat, mengedit, menghapus semua catatan nilai siswa.
* **Manajemen Absensi:** Mencatat, melihat, mengedit, menghapus semua catatan absensi siswa.
* **Manajemen Jadwal Pelajaran:** Membuat, melihat, mengedit, menghapus jadwal pelajaran mingguan dengan deteksi tabrakan.
* **Pengaturan Sistem:** Mengatur nama sekolah, alamat, email, telepon, dan tahun ajaran saat ini.
* **Laporan Rapor & Ringkasan Nilai:** Mencetak rapor siswa dan melihat ringkasan nilai berdasarkan filter.

### 5. Panduan Guru

Sebagai Guru, Anda dapat mengelola aspek akademik yang menjadi tanggung jawab Anda.

* **Dashboard Guru:** Menampilkan ringkasan kelas yang diajar, mata pelajaran diampu, dan jumlah siswa yang diajar.
* **Input Nilai:** Memasukkan dan mengelola nilai siswa untuk mata pelajaran yang Anda ajarkan.
* **Absensi Siswa:** Mencatat dan melihat absensi siswa di kelas yang Anda ajarkan.
* **Jadwal Mengajar:** Melihat jadwal pelajaran Anda.
* **Materi & Tugas:** Membuat tugas baru, mengelola tugas yang Anda berikan, melihat pengumpulan siswa, dan memberikan nilai pada pengumpulan.
* **Pengumuman & Kalender Akademik:** Melihat pengumuman dan acara yang relevan untuk guru dan seluruh sekolah.

### 6. Panduan Siswa

Sebagai Siswa, Anda dapat memantau progres akademik pribadi Anda.

* **Dashboard Siswa:** Menampilkan NIS, kelas, dan link cepat ke jadwal, nilai, dan absensi pribadi Anda.
* **Jadwal Pelajaran Saya:** Melihat jadwal pelajaran kelas Anda.
* **Nilai Saya:** Melihat semua nilai mata pelajaran Anda.
* **Absensi Saya:** Melihat catatan kehadiran Anda.
* **Materi & Tugas:** Melihat tugas yang diberikan untuk kelas Anda, mengumpulkan tugas, dan melihat umpan balik serta nilai tugas yang sudah dinilai.
* **Pengumuman & Kalender Akademik:** Melihat pengumuman dan acara yang relevan untuk siswa dan seluruh sekolah.

### 7. Panduan Orang Tua

Sebagai Orang Tua, Anda dapat memantau perkembangan akademik anak Anda.

* **Dashboard Orang Tua:** Menampilkan daftar anak-anak yang terhubung dengan akun Anda, dengan link cepat ke nilai, absensi, dan jadwal kelas setiap anak.
* **Nilai Anak:** Melihat nilai-nilai anak Anda.
* **Absensi Anak:** Melihat catatan kehadiran anak Anda.
* **Jadwal Kelas Anak:** Melihat jadwal pelajaran kelas anak Anda.
* **Pengumuman & Kalender Akademik:** Melihat pengumuman dan acara yang relevan untuk orang tua dan seluruh sekolah.

## Dokumentasi Pengembang (Developer Guide)

Dokumen ini ditujukan bagi pengembang yang ingin memahami, memelihara, atau memperluas proyek Akademika.

### 1. Struktur Proyek

Akademika dibangun menggunakan framework Laravel dengan arsitektur MVC (Model-View-Controller).

* **`app/Models`**: Definisi model Eloquent untuk tabel database (`User`, `SchoolClass`, `Student`, `Subject`, `TeachingAssignment`, `Grade`, `Attendance`, `Schedule`, `Announcement`, `CalendarEvent`, `Message`, `Setting`).
* **`app/Http/Controllers`**:
    * `Admin/*Controller.php`: Controller untuk modul manajemen yang sebagian besar diakses Admin, dengan scoping untuk peran lain.
    * `MessageController.php`, `NotificationController.php`, `ProfileController.php`, `AssignmentController.php`, `Auth/*Controller.php`: Controller umum atau khusus otentikasi.
* **`database/migrations`**: Skema database untuk semua tabel.
* **`database/factories`**: Factory untuk membuat data dummy secara efisien.
* **`database/seeders`**: Seeder untuk mengisi database dengan data awal.
* **`routes/web.php`**: Definisi semua rute aplikasi.
* **`resources/views`**: Semua template Blade.
    * `layouts/app.blade.php`: Layout utama aplikasi.
    * `components/*.blade.php`: Komponen Blade yang dapat digunakan kembali (dari Laravel Breeze).
    * `admin/*/*.blade.php`: View untuk modul manajemen admin (misal: `admin/users/index.blade.php`).
    * `messages/*.blade.php`, `notifications/*.blade.php`, `assignments/*.blade.php`, `submissions/*.blade.blade.php`, `reports/*.blade.php`: View untuk modul lainnya.
* **`resources/js/app.js`**: Skrip JavaScript utama, mengimpor Alpine.js, SweetAlert2, dan JavaScript kustom.
* **`resources/css/app.css`**: File CSS utama, mengimpor Tailwind CSS.

### 2. Relasi Database Penting

Berikut adalah beberapa relasi kunci antar model:

* **`User`**:
    * `hasMany(Notification::class)`: Notifikasi yang diterima user.
    * `hasMany(Message::class, 'sender_id')`: Pesan yang dikirim user.
    * `hasMany(Message::class, 'receiver_id')`: Pesan yang diterima user.
    * `hasOne(Student::class)`: Jika user adalah siswa.
    * `belongsToMany(Student::class, 'parent_student', 'parent_user_id', 'student_id')`: Jika user adalah orang tua (anak-anaknya).
    * Spatie `HasRoles` trait.

* **`Student`**:
    * `belongsTo(User::class)`: Akun user terkait.
    * `belongsTo(SchoolClass::class)`: Kelas siswa.
    * `belongsToMany(User::class, 'parent_student', 'student_id', 'parent_user_id')`: Orang tua siswa.
    * `hasMany(Grade::class)`: Nilai-nilai siswa.
    * `hasMany(Attendance::class)`: Absensi siswa.
    * `hasMany(Submission::class)`: Pengumpulan tugas siswa.

* **`SchoolClass`**: `hasMany(TeachingAssignment::class)`, `hasMany(Student::class)`, `belongsTo(User::class, 'homeroom_teacher_id')`.

* **`TeachingAssignment`**: `belongsTo(SchoolClass::class)`, `belongsTo(Subject::class)`, `belongsTo(User::class, 'teacher_id')`.

* **`Assignment`**: `belongsTo(TeachingAssignment::class)`, `belongsTo(User::class, 'assigned_by_user_id')`, `hasMany(Submission::class)`.

* **`Submission`**: `belongsTo(Assignment::class)`, `belongsTo(Student::class)`, `belongsTo(User::class, 'graded_by_user_id')`.

### 3. Pengelolaan Peran dan Izin (Spatie Laravel Permission)

* **Peran (Roles):** `admin_sekolah`, `guru`, `siswa`, `orang_tua`.
* **Pemberian Izin (Permissions):** Didefinisikan di `RolesAndPermissionsSeeder.php`.
* **Pengecekan Akses:**
    * **Route Middleware:** `role:nama_role`, `role:role1|role2`, `can:permission_name` di `routes/web.php`.
    * **Controller:** `abort_if(!auth()->user()->hasRole('role'), 403);` atau `abort_if(auth()->user()->cannot('permission'), 403);`.
    * **Blade Views:** `@role('nama_role') ... @endrole`, `@can('permission_name') ... @endcan` untuk menampilkan/menyembunyikan elemen UI.

### 4. Konfigurasi Frontend (Tailwind CSS & Alpine.js)

* **Tailwind CSS:** Digunakan untuk styling. Konfigurasi di `tailwind.config.js` (`darkMode: 'class'`).
* **Alpine.js:** Digunakan untuk interaktivitas frontend sederhana.
    * **State Global:** `sidebarOpen` dan `currentTheme` dikelola di `x-data` pada `div` terluar di `layouts/app.blade.php`.
    * **Komunikasi Antar Komponen:** Menggunakan `$dispatch('event-name')` dari komponen anak dan `$el.addEventListener('event-name', ...)` di komponen induk.
    * **Dropdown Dinamis:** Contoh di form tugas (Materi & Tugas) dan jadwal pelajaran, memfilter opsi dropdown berdasarkan pilihan lain.

### 5. Penanganan Upload File

* File di-*upload* ke `storage/app/public` (melalui `Storage::disk('public')->store('folder_tujuan')`).
* Untuk bisa diakses publik melalui URL, jalankan `php artisan storage:link`.
* Akses file di Blade melalui `Storage::url($filePath)`.

### 6. Masalah Umum & Pemecahan Masalah

* **`419 Page Expired` (CSRF Token):**
    * Pastikan `@csrf` ada di semua form `POST`/`PUT`/`PATCH`/`DELETE`.
    * Lakukan Hard Refresh browser (`Ctrl+Shift+R` / `Cmd+Shift+R`) untuk mendapatkan token baru.
    * Pastikan `APP_KEY` terisi di `.env`.
    * Pastikan `php artisan optimize:clear` dijalankan secara berkala.
* **`Unknown column 'nama_relasi.nama_kolom'`:**
    * Terjadi saat `orderBy()` atau `where()` pada query utama mencoba mengurutkan/memfilter berdasarkan kolom dari tabel relasi tanpa `join` eksplisit.
    * **Solusi:** Tambahkan `->join('nama_tabel_relasi', 'tabel_utama.fk_id', '=', 'nama_tabel_relasi.id')->select('tabel_utama.*')` sebelum `orderBy()` atau `where()` yang menggunakan kolom relasi tersebut.
* **`compact(): Undefined variable $nama_variabel`:**
    * Berarti variabel `$nama_variabel` tidak didefinisikan di *controller* sebelum dikirim ke *view* melalui `return view('...', compact('nama_variabel'))`.
    * **Solusi:** Pastikan `nama_variabel = ...;` ada di *controller*.
* **`TypeError: Cannot read properties of null` (JavaScript/DOM):**
    * Terjadi saat JavaScript mencoba mengakses elemen HTML yang belum dimuat atau tidak ada di DOM (`document.getElementById('ID_ELEMEN_ANDA')` mengembalikan `null`).
    * **Solusi:** Pastikan skrip JavaScript yang berinteraksi dengan DOM diletakkan di bagian akhir `<body>` *setelah* semua elemen HTML sudah didefinisikan.
* **FOUC (Flash of Unstyled Content) pada Dark Mode:**
    * Halaman sebentar putih sebelum beralih ke *dark mode*.
    * **Solusi:** Pastikan skrip JavaScript pendeteksi tema di `localStorage` diletakkan **inline di bagian `<head>`** file `layouts/app.blade.php`.
* **`Call to a member function isEmpty() on array`:**
    * Terjadi saat `isEmpty()` dipanggil pada PHP array biasa, bukan objek Collection.
    * **Solusi:** Gunakan `empty($array)` atau `count($array) === 0` untuk mengecek array kosong.
* **`Undefined property: Illuminate\Database\Eloquent\Relations\HasMany::$each`:**
    * Terjadi saat `->each()` dipanggil langsung pada objek relasi (seperti `->hasMany()`), bukan pada hasil query Collection.
    * **Solusi:** Tambahkan `->get()` sebelum `->each` (misal: `->notifications()->unread()->get()->each->markAsRead();`).
* **URL Tanpa Port (`http://localhost/` alih-alih `http://localhost:8000/`):**
    * Terjadi jika `APP_URL` di `.env` tidak menyertakan *port*.
    * **Solusi:** Atur `APP_URL=http://localhost:8000` di `.env`, lalu jalankan `php artisan config:clear` dan *restart* `php artisan serve`.
* **IIS 404 Not Found (Bukan dari Laravel):**
    * Jika aplikasi berjalan di IIS dan Anda mendapatkan 404, itu berarti IIS tidak meneruskan request ke `index.php` Laravel.
    * **Solusi:** Pastikan modul URL Rewrite terinstal di IIS dan ada file `web.config` yang benar di folder `public` proyek Laravel Anda.

## Hubungi Kami

* **Ranus di Facebook:**  https://web.facebook.com/nanu.ranusate

## Lisensi

Proyek ini dilisensikan di bawah Lisensi MIT.

---
````
