@extends('layouts.web')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">Semua Berita</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($news as $newsItem)
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-md hover:shadow-lg transition-shadow overflow-hidden border border-gray-100 dark:border-gray-700">
                    @if ($newsItem->image_path)
                        <img src="{{ Storage::url($newsItem->image_path) }}" alt="{{ $newsItem->title }}"
                            class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500">
                            <span class="text-sm">Tidak ada gambar</span>
                        </div>
                    @endif

                    <div class="p-5">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-1">
                            {{ $newsItem->title }}
                        </h3>

                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                            {{ $newsItem->published_at->format('d M Y') }} oleh {{ $newsItem->author->name ?? 'Admin' }}
                        </p>

                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            {{ Str::limit(strip_tags($newsItem->content), 100) }}
                        </p>

                        <a href="{{ route('web.news.show', $newsItem->slug) }}"
                            class="mt-4 inline-block text-blue-600 dark:text-blue-400 hover:underline transition">
                            Baca Selengkapnya →
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-gray-600 dark:text-gray-300">Belum ada berita yang diterbitkan saat ini.</p>
            @endforelse
        </div>

        <div class="mt-8 text-center">
            {{ $news->links('pagination::tailwind') }}
        </div>
    </div>
@endsection
