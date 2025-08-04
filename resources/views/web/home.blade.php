@extends('layouts.web')

@section('content')
    {{-- Hero Slider --}}
    @if ($sliders->isNotEmpty())
        <div x-data="{ currentSlide: 0, sliders: {{ Js::from($sliders) }} }" class="relative w-full h-screen overflow-hidden">
            <div class="absolute inset-0 flex transition-transform ease-in-out duration-500"
                :style="'transform: translateX(-' + currentSlide * 100 + '%)'">
                @foreach ($sliders as $slider)
                    <div class="flex-shrink-0 w-full h-full bg-cover bg-center"
                        style="background-image: url('{{ Storage::url($slider->image_path) }}');">
                        <div class="bg-black bg-opacity-50 w-full h-full flex items-center justify-center">
                            <div class="text-center text-white p-6 max-w-2xl mx-auto">
                                <h2 class="text-4xl md:text-5xl font-extrabold mb-2">{{ $slider->title }}</h2>
                                @if ($slider->subtitle)
                                    <p class="text-lg md:text-xl mb-4">{{ $slider->subtitle }}</p>
                                @endif
                                @if ($slider->link_url)
                                    <a href="{{ $slider->link_url }}"
                                        class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow transition duration-200">Lihat
                                        Lebih Lanjut</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Slider Nav --}}
            <div class="absolute bottom-5 left-1/2 -translate-x-1/2 flex space-x-2">
                @foreach ($sliders as $index => $slider)
                    <button @click="currentSlide = {{ $index }}"
                        :class="{
                            'bg-white border-gray-700': currentSlide === {{ $index }},
                            'bg-gray-300': currentSlide !== {{ $index }}
                        }"
                        class="w-3 h-3 border rounded-full transition duration-300"></button>
                @endforeach
            </div>

            {{-- Tombol Next/Prev --}}
            <button @click="currentSlide = (currentSlide - 1 + sliders.length) % sliders.length"
                class="absolute top-1/2 left-4 -translate-y-1/2 text-white p-2 rounded-full bg-black bg-opacity-50 hover:bg-opacity-75 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button @click="currentSlide = (currentSlide + 1) % sliders.length"
                class="absolute top-1/2 right-4 -translate-y-1/2 text-white p-2 rounded-full bg-black bg-opacity-50 hover:bg-opacity-75 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    @else
        <div class="bg-gray-200 dark:bg-gray-700 h-screen flex items-center justify-center">
            <p class="text-gray-500 dark:text-gray-400">Tidak ada slider yang tersedia saat ini.</p>
        </div>
    @endif
    {{-- Selamat Datang --}}
    <section class="bg-white dark:bg-gray-900 py-20 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-extrabold mb-6 text-center text-gray-800 dark:text-white">Selamat Datang di Sistem
                Sekolahku</h1>
            <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 text-center">Sistem informasi sekolah yang memudahkan
                pengelolaan data dan informasi sekolah.</p>
            <div class="text-center">
                <a href="{{ route('web.about') }}"
                    class="inline-block px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow transition">Pelajari
                    Lebih Lanjut</a>
            </div>
        </div>
    </section>
    {{-- Berita Terbaru --}}
    <section class="bg-gray-50 dark:bg-gray-900 py-20 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold mb-12 text-center text-gray-800 dark:text-white">Berita Terbaru</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($latestNews as $newsItem)
                    <div
                        class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm hover:shadow-md transition-all overflow-hidden flex flex-col">
                        @if ($newsItem->image_path)
                            <img src="{{ Storage::url($newsItem->image_path) }}" alt="{{ $newsItem->title }}"
                                class="w-full h-48 object-cover">
                        @else
                            <div
                                class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400">
                                <span>No Image</span>
                            </div>
                        @endif
                        <div class="p-6 flex-grow">
                            <h3 class="text-xl font-semibold text-slate-800 dark:text-white mb-2">
                                {{ $newsItem->title }}
                            </h3>
                            <p class="text-sm text-slate-600 dark:text-gray-400 mb-2">
                                {{ $newsItem->published_at->format('d M Y') }} oleh
                                {{ $newsItem->author->name ?? 'Admin' }}
                            </p>
                            <p class="text-sm text-slate-700 dark:text-gray-300">
                                {{ Str::limit(strip_tags($newsItem->content), 120) }}
                            </p>
                        </div>
                        <div class="px-6 pb-6">
                            <a href="{{ route('web.news.show', $newsItem->slug) }}"
                                class="inline-block text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 transition">Baca
                                Selengkapnya →</a>
                        </div>
                    </div>
                @empty
                    <p class="col-span-full text-center text-gray-500 dark:text-gray-400">Belum ada berita terbaru saat
                        ini.
                    </p>
                @endforelse
            </div>
            <div class="text-center mt-12">
                <a href="{{ route('web.news.index') }}"
                    class="inline-block px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow transition">Lihat
                    Semua Berita</a>
            </div>
        </div>
    </section>

    {{-- Galeri Sekolah --}}
    <section class="bg-gray-100 dark:bg-gray-800 py-20 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold mb-12 text-center text-gray-800 dark:text-white">Galeri Sekolah</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @forelse ($latestGallery as $image)
                    <div class="rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-all">
                        <a href="{{ Storage::url($image->image_path) }}" data-lightbox="gallery"
                            data-title="{{ $image->title }}">
                            <img src="{{ Storage::url($image->image_path) }}" alt="{{ $image->title }}"
                                class="w-full h-48 object-cover transition-transform duration-300 hover:scale-105">
                        </a>
                    </div>
                @empty
                    <p class="col-span-full text-center text-gray-500 dark:text-gray-400">Belum ada gambar di galeri
                        saat
                        ini.</p>
                @endforelse
            </div>
            @if (count($latestGallery) > 0)
                <div class="text-center mt-12">
                    <a href="{{ route('web.gallery.index') }}"
                        class="inline-block px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow transition">Lihat
                        Galeri Lainnya</a>
                </div>
            @endif
        </div>
    </section>

    {{-- Pengumuman Penting --}}
    <section class="bg-gray-50 dark:bg-gray-900 py-20 transition-colors">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold mb-12 text-center text-gray-800 dark:text-white">Pengumuman Penting</h2>
            <div class="space-y-6">
                @forelse($importantAnnouncements as $announcement)
                    <div
                        class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-6 rounded-xl shadow-sm hover:shadow-md transition">
                        <h3 class="text-xl font-semibold text-slate-800 dark:text-white">{{ $announcement->title }}
                        </h3>
                        <p class="text-sm text-slate-600 dark:text-gray-400 mb-2">
                            {{ $announcement->published_at->format('d M Y') }}
                        </p>
                        <p class="text-slate-700 dark:text-gray-300">
                            {{ Str::limit(strip_tags($announcement->content), 200) }}</p>
                        <a href="{{ route('admin.announcements.index') }}"
                            class="mt-4 inline-block text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 transition">Lihat
                            Detail →</a>
                    </div>
                @empty
                    <p class="text-center text-gray-500 dark:text-gray-400">Tidak ada pengumuman penting saat ini.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
