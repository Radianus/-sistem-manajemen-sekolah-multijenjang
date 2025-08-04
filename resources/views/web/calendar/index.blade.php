@extends('layouts.web')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl p-6 sm:p-10">
            <header class="mb-10 text-center">
                <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight">📅 Kalender Akademik</h1>
                <p class="mt-2 text-base text-gray-600 dark:text-gray-400">
                    Rangkaian acara penting untuk tahun <span class="font-semibold">{{ $currentYear }}</span>.
                </p>
            </header>

            @if ($events->isEmpty())
                <div class="py-12 text-center text-gray-500 dark:text-gray-400">
                    <p>🙁 Belum ada acara yang terjadwal untuk tahun ini.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($events as $event)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6 hover:shadow-xl transition-shadow duration-300">
                            <div class="mb-4">
                                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                                    {{ $event->title }}
                                </h2>
                                @if ($event->description)
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        {{ Str::limit($event->description, 100) }}
                                    </p>
                                @endif
                            </div>

                            <div class="flex items-center space-x-2 mb-2">
                                <svg class="w-5 h-5 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 7V3M16 7V3M4 11h16M4 19h16M4 15h16" />
                                </svg>
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    {{ $event->start_date->format('d M Y') }}
                                    @if ($event->end_date && $event->end_date != $event->start_date)
                                        – {{ $event->end_date->format('d M Y') }}
                                    @endif
                                </p>
                            </div>

                            <div class="flex items-center space-x-2 mb-2">
                                <svg class="w-5 h-5 text-green-500 dark:text-green-400" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M5.121 17.804A4 4 0 007 21h10a4 4 0 001.879-3.196l-1.334-8A2 2 0 0015.58 8H8.42a2 2 0 00-1.965 1.804l-1.334 8z" />
                                </svg>
                                <span
                                    class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-100 text-xs font-semibold px-3 py-1 rounded-full">
                                    {{ $event->event_type }}
                                </span>
                            </div>

                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-yellow-500 dark:text-yellow-400" fill="none"
                                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17.657 16.657L13.414 12l4.243-4.243M6.343 7.343L10.586 12l-4.243 4.243" />
                                </svg>
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    {{ $event->location ?? '-' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 flex justify-center">
                    {{ $events->links() }}
                </div>
            @endif

            <div class="mt-12 text-center">
                <a href="{{ route('web.home') }}"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-lg transition-transform transform hover:scale-105 duration-200">
                    ← Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
@endsection
