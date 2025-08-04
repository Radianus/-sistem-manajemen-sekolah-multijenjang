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
                <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead
                            class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-gray-800 dark:to-gray-900 text-gray-700 dark:text-gray-300 uppercase text-xs font-semibold">
                            <tr>
                                <th class="px-6 py-3 text-left">Acara</th>
                                <th class="px-6 py-3 text-left">Tanggal</th>
                                <th class="px-6 py-3 text-left">Jenis</th>
                                <th class="px-6 py-3 text-left">Lokasi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach ($events as $event)
                                <tr class="hover:bg-blue-50 dark:hover:bg-gray-800 transition-colors duration-200">
                                    <td class="px-6 py-4 font-semibold text-gray-800 dark:text-white">
                                        {{ $event->title }}
                                        @if ($event->description)
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                {{ Str::limit($event->description, 100) }}
                                            </p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                        {{ $event->start_date->format('d M Y') }}
                                        @if ($event->end_date && $event->end_date != $event->start_date)
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                s/d {{ $event->end_date->format('d M Y') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                            {{ $event->event_type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                        {{ $event->location ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
