@extends('layouts.web')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6 text-center">Galeri Sekolah</h1>

        @if ($gallery->isEmpty())
            <p class="text-center text-gray-600">Belum ada gambar di galeri saat ini.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($gallery as $item)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden dark:bg-gray-800">
                        <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->title }}"
                            class="w-full h-48 object-cover">
                        <div class="p-4 text-center">
                            <h3 class="text-lg font-semibold dark:text-white">{{ $item->title }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                {{ $item->event_date ? $item->event_date->format('d M Y') : 'N/A' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-8">
                {{ $gallery->links() }}
            </div>
        @endif
    </div>
@endsection
