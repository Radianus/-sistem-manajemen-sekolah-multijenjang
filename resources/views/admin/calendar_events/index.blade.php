<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kalender Akademik') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 print:hidden">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4 sm:mb-0">Daftar Acara
                            Kalender</h3>
                        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                            @if (Auth::user()->hasRole('admin_sekolah') || Auth::user()->hasRole('guru'))
                                <a href="{{ route('admin.calendar_events.create') }}"
                                    class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 w-full sm:w-auto">
                                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Tambah Acara
                                </a>
                            @endif
                            <a href="{{ route('admin.calendar_events.exportPdf', ['month' => $currentMonth, 'year' => $currentYear]) }}"
                                class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 w-full sm:w-auto">
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Ekspor ke PDF
                            </a>
                        </div>
                    </div>
                    {{-- Filter Bulan & Tahun --}}
                    <div class="mb-4 flex flex-col sm:flex-row items-center sm:space-x-4">
                        <label for="month_filter"
                            class="text-gray-700 dark:text-gray-300 mr-2 mb-2 sm:mb-0">Bulan:</label>
                        <select id="month_filter"
                            onchange="window.location.href = '?month=' + this.value + '&year={{ $currentYear }}'"
                            class="border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            @foreach ($months as $num => $name)
                                <option value="{{ $num }}" {{ $currentMonth == $num ? 'selected' : '' }}>
                                    {{ $name }}</option>
                            @endforeach
                        </select>

                        <label for="year_filter"
                            class="text-gray-700 dark:text-gray-300 ml-4 mr-2 mb-2 sm:mb-0">Tahun:</label>
                        <select id="year_filter"
                            onchange="window.location.href = '?month={{ $currentMonth }}&year=' + this.value"
                            class="border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            @foreach ($years as $year)
                                <option value="{{ $year }}" {{ $currentYear == $year ? 'selected' : '' }}>
                                    {{ $year }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Flash messages handled by SweetAlert in app.blade.php --}}

                    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="py-3 px-6">Acara</th>
                                    <th scope="col" class="py-3 px-6">Jenis</th>
                                    <th scope="col" class="py-3 px-6">Tanggal</th>
                                    <th scope="col" class="py-3 px-6">Waktu</th>
                                    <th scope="col" class="py-3 px-6">Lokasi</th>
                                    <th scope="col" class="py-3 px-6">Untuk Peran</th>
                                    <th scope="col" class="py-3 px-6">Dibuat Oleh</th>
                                    @if (Auth::user()->hasRole('admin_sekolah') || Auth::user()->hasRole('guru'))
                                        {{-- Hanya admin/guru yang melihat aksi --}}
                                        <th scope="col" class="py-3 px-6">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($events as $event)
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="py-4 px-6 font-medium text-gray-900 dark:text-white">
                                            {{ $event->title }}
                                            @if ($event->description)
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ Str::limit($event->description, 50) }}</p>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6">
                                            {{ $event->event_type }}
                                        </td>
                                        <td class="py-4 px-6">
                                            {{ $event->start_date->format('d-m-Y') }}
                                            @if ($event->end_date && $event->end_date != $event->start_date)
                                                s/d {{ $event->end_date->format('d-m-Y') }}
                                            @endif
                                        </td>
                                        <td class="py-4 px-6">
                                            @if ($event->start_time)
                                                {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }}
                                                @if ($event->end_time)
                                                    - {{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }}
                                                @endif
                                            @else
                                                Sepanjang Hari
                                            @endif
                                        </td>
                                        <td class="py-4 px-6">
                                            {{ $event->location ?? '-' }}
                                        </td>
                                        <td class="py-4 px-6">
                                            {{ $event->target_roles ?? 'Semua' }}
                                        </td>
                                        <td class="py-4 px-6">
                                            {{ $event->creator->name ?? 'N/A' }}
                                        </td>
                                        @if (Auth::user()->hasRole('admin_sekolah') || Auth::user()->hasRole('guru'))
                                            <td class="py-4 px-6 flex items-center space-x-3">
                                                @if (Auth::user()->hasRole('admin_sekolah') || Auth::id() == $event->created_by_user_id)
                                                    <a href="{{ route('admin.calendar_events.edit', $event) }}"
                                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                                    <form action="{{ route('admin.calendar_events.destroy', $event) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus acara ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="font-medium text-red-600 dark:text-red-500 hover:underline">Hapus</button>
                                                    </form>
                                                @else
                                                    <span class="text-gray-400 dark:text-gray-600">Tidak ada aksi</span>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td colspan="{{ Auth::user()->hasRole('admin_sekolah') || Auth::user()->hasRole('guru') ? '8' : '7' }}"
                                            class="py-4 px-6 text-center text-gray-500 dark:text-gray-400">
                                            Tidak ada acara kalender ditemukan untuk bulan
                                            {{ \Carbon\Carbon::create()->month($currentMonth)->isoFormat('MMMM') }}
                                            tahun {{ $currentYear }}.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $events->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
