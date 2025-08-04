@extends('layouts.web')
@section('content')
    <div class="container mx-auto p-4">
        <article class="bg-white rounded-lg shadow-md p-6">
            <header class="mb-6 text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Kalender Akademik</h1>
                <p class="text-sm text-gray-600">Daftar acara dan kegiatan penting untuk tahun {{ $currentYear }}.</p>
            </header>

            @if ($events->isEmpty())
                <p class="text-center text-gray-600 dark:text-gray-400">Tidak ada acara kalender untuk tahun ini.</p>
            @else
                <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="py-3 px-6">Acara</th>
                                <th scope="col" class="py-3 px-6">Tanggal</th>
                                <th scope="col" class="py-3 px-6">Jenis</th>
                                <th scope="col" class="py-3 px-6">Lokasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($events as $event)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="py-4 px-6 font-medium text-gray-900 dark:text-white">
                                        {{ $event->title }}
                                        @if ($event->description)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ Str::limit($event->description, 100) }}</p>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6">
                                        {{ $event->start_date->format('d M Y') }}
                                        @if ($event->end_date && $event->end_date != $event->start_date)
                                            s/d {{ $event->end_date->format('d M Y') }}
                                        @endif
                                    </td>
                                    <td class="py-4 px-6">{{ $event->event_type }}</td>
                                    <td class="py-4 px-6">{{ $event->location ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-8">
                    {{ $events->links() }}
                </div>
            @endif

            <div class="mt-8 text-right">
                <a href="{{ route('web.home') }}"
                    class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md transition-colors">←
                    Kembali ke Beranda</a>
            </div>
        </article>
    </div>
@endsection

{{-- Additional styles for the calendar --}}
