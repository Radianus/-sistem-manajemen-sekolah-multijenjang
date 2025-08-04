@extends('layouts.web')
@section('content')
    <div class="container mx-auto p-4">
        <article class="bg-white rounded-lg shadow-md p-6">
            <header class="mb-6 text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Tentang {{ $globalSettings->school_name ?? 'Akademika' }}
                </h1>
            </header>

            <div class="prose max-w-none text-gray-800 dark:text-gray-200">
                <p>
                    [Konten statis tentang sekolah Anda di sini.]
                </p>
                <p>
                    Akademika adalah sistem manajemen sekolah yang dirancang untuk mempermudah proses administrasi dan
                    akademik. Dengan fitur-fitur seperti manajemen nilai, absensi, jadwal pelajaran, pengumuman, dan materi
                    tugas, kami berupaya menciptakan lingkungan belajar yang efisien dan terintegrasi.
                </p>
                <p>
                    Misi kami adalah menyediakan alat yang powerful bagi guru, siswa, dan orang tua untuk berkolaborasi dan
                    memantau kemajuan akademik dengan mudah.
                </p>
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('web.home') }}"
                    class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md transition-colors">Kembali
                    ke Beranda</a>
            </div>
        </article>
    </div>
@endsection
