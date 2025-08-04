@extends('layouts.web')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl p-8 sm:p-12">
            <header class="mb-10 text-center">
                <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white">📞 Hubungi Kami</h1>
                <p class="mt-2 text-base text-gray-600 dark:text-gray-400">
                    Kami siap membantu Anda. Hubungi kami melalui informasi berikut.
                </p>
            </header>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-gray-800 dark:text-gray-200 text-sm sm:text-base">
                <div class="flex items-start gap-4">
                    <div class="text-blue-600 dark:text-blue-400 text-lg">🏫</div>
                    <div>
                        <div class="font-semibold">Nama Sekolah</div>
                        <div>{{ $globalSettings->school_name ?? 'Akademika' }}</div>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="text-blue-600 dark:text-blue-400 text-lg">📍</div>
                    <div>
                        <div class="font-semibold">Alamat</div>
                        <div>{{ $globalSettings->school_address ?? 'Jalan Sekolah No. 123' }}</div>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="text-blue-600 dark:text-blue-400 text-lg">📞</div>
                    <div>
                        <div class="font-semibold">Telepon</div>
                        <div>{{ $globalSettings->school_phone ?? '021-12345678' }}</div>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="text-blue-600 dark:text-blue-400 text-lg">✉️</div>
                    <div>
                        <div class="font-semibold">Email</div>
                        <div>{{ $globalSettings->school_email ?? 'info@akademika.sch.id' }}</div>
                    </div>
                </div>
            </div>

            <div class="mt-12 text-center">
                <a href="{{ route('web.home') }}"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-lg transition-transform transform hover:scale-105 duration-200">
                    ← Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
@endsection
