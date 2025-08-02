@extends('layouts.web')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Semua Berita</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($news as $newsItem)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    @if ($newsItem->image_path)
                        <img src="{{ Storage::url($newsItem->image_path) }}" alt="{{ $newsItem->title }}"
                            class="w-full h-48 object-cover">
                    @endif
                    <div class="p-4">
                        <h3 class="text-xl font-bold mb-2">{{ $newsItem->title }}</h3>
                        <p class="text-sm text-gray-600 mb-2">{{ $newsItem->published_at->format('d M Y') }} oleh
                            {{ $newsItem->author->name ?? 'Admin' }}</p>
                        <p class="text-sm text-gray-700">{{ Str::limit(strip_tags($newsItem->content), 100) }}</p>
                        <a href="{{ route('web.news.show', $newsItem->slug) }}"
                            class="mt-4 inline-block text-blue-600 hover:underline">Baca Selengkapnya</a>
                    </div>
                </div>
            @empty
                <p>Belum ada berita yang diterbitkan saat ini.</p>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $news->links() }}
        </div>
    </div>
@endsection
