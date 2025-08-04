@extends('layouts.web')
@section('content')
    <div class="container mx-auto p-4">
        <article class="bg-white rounded-lg shadow-md p-6">
            <header class="mb-6 text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Hubungi Kami</h1>
                <p class="text-sm text-gray-600">Kami siap membantu Anda. Silakan hubungi kami melalui informasi di bawah
                    ini.</p>
            </header>

            <div class="prose max-w-none text-gray-800 dark:text-gray-200">
                <p class="mt-4">
                    <strong>Nama Sekolah:</strong> {{ $globalSettings->school_name ?? 'Akademika' }}<br>
                    <strong>Alamat:</strong> {{ $globalSettings->school_address ?? 'Jalan Sekolah No. 123' }}<br>
                    <strong>Telepon:</strong> {{ $globalSettings->school_phone ?? '021-12345678' }}<br>
                    <strong>Email:</strong> {{ $globalSettings->school_email ?? 'info@akademika.sch.id' }}
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
