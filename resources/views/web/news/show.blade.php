@extends('layouts.web')

@section('content')
    <div class="container mx-auto p-4">
        <article class="bg-white rounded-lg shadow-md p-6">
            <header class="mb-6">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $news->title }}</h1>
                <p class="text-sm text-gray-600">Dipublikasikan pada: {{ $news->published_at->format('d M Y') }} oleh
                    {{ $news->author->name ?? 'Admin' }}</p>
            </header>

            @if ($news->image_path)
                <img src="{{ Storage::url($news->image_path) }}" alt="{{ $news->title }}"
                    class="w-full h-auto object-cover rounded-lg mb-6">
            @endif

            <div class="prose max-w-none">
                {!! nl2br(e($news->content)) !!}
            </div>

            <div class="mt-8 text-right">
                <a href="{{ route('web.news.index') }}" class="text-blue-600 hover:underline">← Kembali ke Daftar
                    Berita</a>
            </div>
        </article>
    </div>
@endsection
