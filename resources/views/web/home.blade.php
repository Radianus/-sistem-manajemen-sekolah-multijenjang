@extends('layouts.web')

@section('content')
    {{-- Hero Slider --}}
    @if ($sliders->isNotEmpty())
        <div x-data="{ currentSlide: 0, sliders: {{ Js::from($sliders) }} }" class="relative w-full h-[80vh] overflow-hidden">
            <div class="absolute inset-0 flex transition-transform ease-in-out duration-500"
                :style="'transform: translateX(-' + currentSlide * 100 + '%)'">
                @foreach ($sliders as $index => $slider)
                    <div class="flex-shrink-0 w-full h-full bg-cover bg-center"
                        style="background-image: url('{{ Storage::url($slider->image_path) }}');">
                        <div class="bg-black bg-opacity-50 w-full h-full flex items-center justify-center">
                            <div class="text-center text-white p-6">
                                <h2 class="text-4xl md:text-5xl font-bold mb-2">{{ $slider->title }}</h2>
                                @if ($slider->subtitle)
                                    <p class="text-lg md:text-xl mb-4">{{ $slider->subtitle }}</p>
                                @endif
                                @if ($slider->link_url)
                                    <a href="{{ $slider->link_url }}"
                                        class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md transition-colors">Lihat
                                        Lebih Lanjut</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Slider Nav --}}
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2">
                @foreach ($sliders as $index => $slider)
                    <button @click="currentSlide = {{ $index }}"
                        :class="{
                            'bg-white': currentSlide === {{ $index }},
                            'bg-gray-400': currentSlide !==
                                {{ $index }}
                        }"
                        class="w-2 h-2 rounded-full"></button>
                @endforeach
            </div>

            {{-- Tombol Next/Prev --}}
            <button @click="currentSlide = (currentSlide - 1 + sliders.length) % sliders.length"
                class="absolute top-1/2 left-4 -translate-y-1/2 text-white p-2 rounded-full bg-black bg-opacity-50 hover:bg-opacity-75 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button @click="currentSlide = (currentSlide + 1) % sliders.length"
                class="absolute top-1/2 right-4 -translate-y-1/2 text-white p-2 rounded-full bg-black bg-opacity-50 hover:bg-opacity-75 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    @endif

    <div class="bg-gray-50 dark:bg-gray-900 py-16 transition-colors">
        {{-- Bagian Berita Terbaru --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold mb-8 text-center">Berita Terbaru</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($latestNews as $newsItem)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden flex flex-col transition-colors">
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
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $newsItem->title }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                {{ $newsItem->published_at->format('d M Y') }} oleh
                                {{ $newsItem->author->name ?? 'Admin' }}</p>
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                {{ Str::limit(strip_tags($newsItem->content), 120) }}</p>
                        </div>
                        <div class="p-6 pt-0">
                            <a href="{{ route('web.news.show', $newsItem->slug) }}"
                                class="inline-block text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">Baca
                                Selengkapnya →</a>
                        </div>
                    </div>
                @empty
                    <p class="col-span-full text-center text-gray-600 dark:text-gray-400">Belum ada berita terbaru saat ini.
                    </p>
                @endforelse
            </div>
            <div class="text-center mt-12">
                <a href="{{ route('web.news.index') }}"
                    class="inline-block px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md transition-colors">Lihat
                    Semua Berita</a>
            </div>
        </div>
    </div>

    {{-- Bagian Pengumuman Penting --}}
    <div class="bg-gray-100 dark:bg-gray-900 py-16 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold mb-8 text-center">Pengumuman Penting</h2>
            <div class="space-y-4">
                @forelse($importantAnnouncements as $announcement)
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md transition-colors">
                        <h3 class="text-xl font-semibold mb-2 text-gray-900 dark:text-white">{{ $announcement->title }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $announcement->published_at->format('d M Y') }}</p>
                        <p class="mt-2 text-gray-700 dark:text-gray-300">
                            {{ Str::limit(strip_tags($announcement->content), 200) }}</p>
                        <a href="{{ route('admin.announcements.index') }}"
                            class="mt-4 inline-block text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">Lihat
                            Detail →</a>
                    </div>
                @empty
                    <p class="text-center text-gray-600 dark:text-gray-400">Tidak ada pengumuman penting saat ini.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
