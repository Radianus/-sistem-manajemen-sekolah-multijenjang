<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight print:hidden">
            {{ __('Manajemen Jadwal Pelajaran') }}
        </h2>
    </x-slot>

    <div class="py-12 print:p-0">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 print:max-w-full print:px-0">
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg print:shadow-none print:rounded-none print:border-0">
                <div
                    class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 print:border-0 print:p-0">

                    {{-- HEADER KHUSUS PRINT --}}
                    <div class="hidden print:block text-center mb-8 print:mb-4">
                        <h1 class="text-2xl font-bold text-gray-900 mb-2 print:text-black">
                            {{ $globalSettings->school_name ?? config('app.name', 'Akademika') }}
                        </h1>
                        <h2 class="text-xl font-semibold text-gray-800 mb-1 print:text-black">Jadwal Pelajaran</h2>
                        <p class="text-sm text-gray-700">Tahun Ajaran: {{ $academicYear }}</p>
                        @if (request('level') && in_array(request('level'), $levelOptions))
                            <p class="text-sm text-gray-700">Jenjang: {{ request('level') }}</p>
                        @endif
                        @if (request('class_id') && $classes->find(request('class_id')))
                            <p class="text-sm text-gray-700">Kelas: {{ $classes->find(request('class_id'))->name }}</p>
                        @endif
                        @if (request('teacher_id') && $teachers->find(request('teacher_id')))
                            <p class="text-sm text-gray-700">Guru: {{ $teachers->find(request('teacher_id'))->name }}
                            </p>
                        @endif
                    </div>
                    {{-- AKHIR HEADER KHUSUS PRINT --}}

                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 print:hidden">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4 sm:mb-0">Daftar Jadwal
                            Pelajaran</h3>
                        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                            @if (Auth::user()->hasRole('admin_sekolah'))
                                <a href="{{ route('admin.schedules.create') }}"
                                    class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 w-full sm:w-auto print:hidden">
                                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Tambah Jadwal
                                </a>
                            @endif
                            <button onclick="window.print()"
                                class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 w-full sm:w-auto print:hidden">
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 20h12a2 2 0 002-2V8a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2zM16 4h-8v4h8z">
                                    </path>
                                </svg>
                                Cetak Jadwal
                            </button>
                        </div>
                    </div>

                    {{-- Filter Bar --}}
                    <div class="mb-4 flex flex-col sm:flex-row items-center sm:space-x-4 print:hidden">
                        {{-- Filter Tahun Ajaran --}}
                        <label for="academic_year_filter"
                            class="text-gray-700 dark:text-gray-300 mr-2 mb-2 sm:mb-0">Tahun Ajaran:</label>
                        <select id="academic_year_filter"
                            onchange="window.location.href = '?academic_year=' + this.value + '&class_id={{ request('class_id') }}&teacher_id={{ request('teacher_id') }}&level={{ request('level') }}'"
                            class="border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            @php
                                $currentYear = \Carbon\Carbon::now()->year;
                                $years = [];
                                for ($i = -2; $i <= 2; $i++) {
                                    $years[] = $currentYear + $i . '/' . ($currentYear + $i + 1);
                                    if (
                                        $academicYear == $currentYear + $i . '/' . ($currentYear + $i + 1) &&
                                        !request('academic_year')
                                    ) {
                                        request()->merge([
                                            'academic_year' => $currentYear + $i . '/' . ($currentYear + $i + 1),
                                        ]);
                                    }
                                }
                            @endphp
                            @foreach ($years as $year)
                                <option value="{{ $year }}" {{ $academicYear == $year ? 'selected' : '' }}>
                                    {{ $year }}</option>
                            @endforeach
                        </select>

                        {{-- Filter Jenjang (Hanya untuk Admin) --}}
                        @if (Auth::user()->hasRole('admin_sekolah'))
                            <label for="level_filter"
                                class="text-gray-700 dark:text-gray-300 ml-4 mr-2 mb-2 sm:mb-0">Jenjang:</label>
                            <select id="level_filter" name="level"
                                onchange="window.location.href = '?level=' + this.value + '&academic_year={{ request('academic_year', $academicYear) }}&class_id={{ request('class_id') }}&teacher_id={{ request('teacher_id') }}'"
                                class="border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Semua Jenjang</option>
                                @foreach ($levelOptions as $level)
                                    <option value="{{ $level }}"
                                        {{ request('level') == $level ? 'selected' : '' }}>{{ $level }}
                                    </option>
                                @endforeach
                            </select>
                        @endif

                        {{-- Filter Kelas (untuk Admin dan Guru) --}}
                        @if (Auth::user()->hasRole('admin_sekolah') || Auth::user()->hasRole('guru'))
                            <label for="class_filter"
                                class="text-gray-700 dark:text-gray-300 ml-4 mr-2 mb-2 sm:mb-0">Filter Kelas:</label>
                            <select id="class_filter" name="class_id"
                                onchange="window.location.href = '{{ route('admin.schedules.index') }}?class_id=' + this.value + '&academic_year={{ request('academic_year', $academicYear) }}&teacher_id={{ request('teacher_id') }}&level={{ request('level') }}'"
                                class="border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Semua Kelas</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}"
                                        {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        @endif

                        {{-- Filter Guru (Hanya untuk Admin) --}}
                        @if (Auth::user()->hasRole('admin_sekolah'))
                            <label for="teacher_filter"
                                class="text-gray-700 dark:text-gray-300 ml-4 mr-2 mb-2 sm:mb-0">Filter Guru:</label>
                            <select id="teacher_filter" name="teacher_id"
                                onchange="window.location.href = '{{ route('admin.schedules.index') }}?teacher_id=' + this.value + '&academic_year={{ request('academic_year', $academicYear) }}&class_id={{ request('class_id') }}&level={{ request('level') }}'"
                                class="border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Semua Guru</option>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}"
                                        {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    @if ($schedules->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">Tidak ada jadwal ditemukan untuk tahun ajaran
                            {{ $academicYear }}.</p>
                    @else
                        {{-- Tampilan Tabel Jadwal --}}
                        <div
                            class="overflow-x-auto relative shadow-md sm:rounded-lg print:shadow-none print:rounded-none">
                            {{-- Tabel untuk tampilan non-print --}}
                            <table
                                class="w-full text-sm text-left text-gray-500 dark:text-gray-400 border border-gray-300 dark:border-gray-700 print:hidden">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col"
                                            class="py-3 px-6 border-r border-gray-300 dark:border-gray-700">ID</th>
                                        <th scope="col"
                                            class="py-3 px-6 border-r border-gray-300 dark:border-gray-700">Kelas</th>
                                        <th scope="col"
                                            class="py-3 px-6 border-r border-gray-300 dark:border-gray-700">Mapel</th>
                                        <th scope="col"
                                            class="py-3 px-6 border-r border-gray-300 dark:border-gray-700">Guru</th>
                                        <th scope="col"
                                            class="py-3 px-6 border-r border-gray-300 dark:border-gray-700">Hari</th>
                                        <th scope="col"
                                            class="py-3 px-6 border-r border-gray-300 dark:border-gray-700">Waktu</th>
                                        <th scope="col"
                                            class="py-3 px-6 border-r border-gray-300 dark:border-gray-700">Ruangan</th>
                                        @if (Auth::user()->hasRole('admin_sekolah'))
                                            <th scope="col" class="py-3 px-6 print:hidden">Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($schedules as $schedule)
                                        <tr
                                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <th scope="row"
                                                class="py-4 px-6 font-medium text-gray-900 dark:text-white whitespace-nowrap border-r border-gray-300 dark:border-gray-700">
                                                {{ $schedule->id }}
                                            </th>
                                            <td class="py-4 px-6 border-r border-gray-300 dark:border-gray-700">
                                                {{ $schedule->schoolClass->name ?? 'N/A' }}
                                            </td>
                                            <td class="py-4 px-6 border-r border-gray-300 dark:border-gray-700">
                                                {{ $schedule->teachingAssignment->subject->name ?? 'N/A' }}
                                            </td>
                                            <td class="py-4 px-6 border-r border-gray-300 dark:border-gray-700">
                                                {{ $schedule->teachingAssignment->teacher->name ?? 'N/A' }}
                                            </td>
                                            <td class="py-4 px-6 border-r border-gray-300 dark:border-gray-700">
                                                {{ $schedule->day_of_week }}
                                            </td>
                                            <td class="py-4 px-6 border-r border-gray-300 dark:border-gray-700">
                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                            </td>
                                            <td class="py-4 px-6 border-r border-gray-300 dark:border-gray-700">
                                                {{ $schedule->room_number ?? '-' }}
                                            </td>
                                            @if (Auth::user()->hasRole('admin_sekolah'))
                                                <td class="py-4 px-6 flex items-center space-x-3 print:hidden">
                                                    <a href="{{ route('admin.schedules.edit', $schedule) }}"
                                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                                    <form action="{{ route('admin.schedules.destroy', $schedule) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="font-medium text-red-600 dark:text-red-500 hover:underline">Hapus</button>
                                                    </form>
                                                </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <td colspan="{{ Auth::user()->hasRole('admin_sekolah') ? '8' : '7' }}"
                                                class="py-4 px-6 text-center text-gray-500 dark:text-gray-400">
                                                Tidak ada jadwal ditemukan untuk tahun ajaran {{ $academicYear }}.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            {{-- Tabel untuk tampilan print (hidden di layar) --}}
                            <div class="hidden print:block">
                                @php
                                    $groupedSchedules = $schedules
                                        ->groupBy('day_of_week')
                                        ->sortKeysUsing(function ($key1, $key2) {
                                            $order = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                                            $pos1 = array_search($key1, $order);
                                            $pos2 = array_search($key2, $order);
                                            return $pos1 <=> $pos2;
                                        });
                                @endphp
                                @foreach ($groupedSchedules as $day => $schedulesPerDay)
                                    <h4
                                        class="font-semibold text-lg text-gray-900 mt-4 mb-2 border-b border-gray-300 pb-1">
                                        {{ $day }}</h4>
                                    <table class="w-full text-sm text-left text-gray-500 border border-gray-300 mb-4">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="py-2 px-4 border-r border-gray-300">Waktu
                                                </th>
                                                <th scope="col" class="py-2 px-4 border-r border-gray-300">Kelas
                                                </th> {{-- TAMBAH KOLOM KELAS DI SINI --}}
                                                <th scope="col" class="py-2 px-4 border-r border-gray-300">Mata
                                                    Pelajaran</th>
                                                <th scope="col" class="py-2 px-4">Guru</th>
                                                <th scope="col" class="py-2 px-4">Ruangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($schedulesPerDay->sortBy('start_time') as $schedule)
                                                <tr class="border-b border-gray-200">
                                                    <td class="py-2 px-4 border-r border-gray-300">
                                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                                                        -
                                                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                                    </td>
                                                    <td class="py-2 px-4 border-r border-gray-300">
                                                        {{ $schedule->schoolClass->name ?? 'N/A' }}</td>
                                                    {{-- NAMA KELAS --}}
                                                    <td class="py-2 px-4 border-r border-gray-300">
                                                        {{ $schedule->teachingAssignment->subject->name ?? 'N/A' }}
                                                    </td>
                                                    <td class="py-2 px-4 border-r border-gray-300">
                                                        {{ $schedule->teachingAssignment->teacher->name ?? 'N/A' }}
                                                    </td>
                                                    <td class="py-2 px-4">{{ $schedule->room_number ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-4 print:hidden">
                            {{ $schedules->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
