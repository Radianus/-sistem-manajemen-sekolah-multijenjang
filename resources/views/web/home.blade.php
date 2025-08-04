@extends('layouts.web')

@section('content')
    {{-- Hero Slider --}}

    <div class="bg-gray-200 dark:bg-gray-700 h-screen flex items-center justify-center">
        <p class="text-gray-500 dark:text-gray-400">Tidak ada slider yang tersedia saat ini.</p>
    </div>

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
                            <h3 class="text-xl font-semibold text-slate-800 dark:text-white mb-2">{{ $newsItem->title }}
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
                    <p class="col-span-full text-center text-gray-500 dark:text-gray-400">Belum ada berita terbaru saat ini.
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
                    <p class="col-span-full text-center text-gray-500 dark:text-gray-400">Belum ada gambar di galeri saat
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
                        <h3 class="text-xl font-semibold text-slate-800 dark:text-white">{{ $announcement->title }}</h3>
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
