@extends('layouts.web')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <article
            class="bg-white dark:bg-gray-900 rounded-2xl shadow-md border border-gray-100 dark:border-gray-800 p-6 md:p-8 transition-colors">
            <header class="mb-6">
                <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white leading-tight mb-2">
                    {{ $news->title }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Dipublikasikan pada: {{ $news->published_at->format('d M Y') }} oleh
                    {{ $news->author->name ?? 'Admin' }}
                </p>
            </header>

            @if ($news->image_path)
                <img src="{{ Storage::url($news->image_path) }}" alt="{{ $news->title }}"
                    class="w-full max-h-[450px] object-cover object-center rounded-lg mb-6 shadow-sm">
            @endif

            <div class="prose prose-gray dark:prose-invert max-w-none">
                {!! $news->content !!}

            </div>

            <div class="mt-8 text-right">
                <a href="{{ route('web.news.index') }}"
                    class="text-blue-600 dark:text-blue-400 hover:underline hover:text-blue-800 dark:hover:text-blue-300 transition">
                    ← Kembali ke Daftar Berita
                </a>
            </div>
        </article>
    </div>
@endsection
