@extends('layouts.web')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6 text-center">Galeri Sekolah</h1>

        @if ($gallery->isEmpty())
            <p class="text-center text-gray-600 dark:text-gray-400">Belum ada gambar di galeri saat ini.</p>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($gallery as $item)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden dark:bg-gray-800">
                        <a href="{{ Storage::url($item->image_path) }}" data-lightbox="gallery"
                            data-title="{{ $item->title }}">
                            <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->title }}"
                                class="w-full h-48 object-cover">
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="mt-8">
                {{ $gallery->links() }}
            </div>
        @endif
    </div>
@endsection
