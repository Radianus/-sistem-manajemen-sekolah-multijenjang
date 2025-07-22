<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Jadwal Pelajaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4 sm:mb-0">Daftar Jadwal
                            Pelajaran</h3>
                        @role('admin_sekolah')
                            {{-- Hanya admin yang bisa melihat tombol tambah --}}
                            <a href="{{ route('admin.schedules.create') }}"
                                class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 w-full sm:w-auto">
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah Jadwal
                            </a>
                        @endrole
                    </div>

                    <div class="mb-4 flex flex-col sm:flex-row items-center sm:space-x-4">
                        <label for="academic_year_filter"
                            class="text-gray-700 dark:text-gray-300 mr-2 mb-2 sm:mb-0">Tahun Ajaran:</label>
                        <select id="academic_year_filter"
                            onchange="window.location.href = '?academic_year=' + this.value"
                            class="border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            @php
                                $currentYear = \Carbon\Carbon::now()->year;
                                $years = [];
                                for ($i = -2; $i <= 2; $i++) {
                                    $years[] = $currentYear + $i . '/' . ($currentYear + $i + 1);
                                }
                            @endphp
                            @foreach ($years as $year)
                                <option value="{{ $year }}" {{ $academicYear == $year ? 'selected' : '' }}>
                                    {{ $year }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Flash messages will be handled by SweetAlert in app.blade.php --}}

                    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="py-3 px-6">ID</th>
                                    <th scope="col" class="py-3 px-6">Kelas</th>
                                    <th scope="col" class="py-3 px-6">Mapel</th>
                                    <th scope="col" class="py-3 px-6">Guru</th>
                                    <th scope="col" class="py-3 px-6">Hari</th>
                                    <th scope="col" class="py-3 px-6">Waktu</th>
                                    <th scope="col" class="py-3 px-6">Ruangan</th>
                                    @role('admin_sekolah')
                                        {{-- Hanya admin yang melihat kolom aksi --}}
                                        <th scope="col" class="py-3 px-6">Aksi</th>
                                    @endrole
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($schedules as $schedule)
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <th scope="row"
                                            class="py-4 px-6 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                            {{ $schedule->id }}
                                        </th>
                                        <td class="py-4 px-6">
                                            {{ $schedule->schoolClass->name ?? 'N/A' }}
                                        </td>
                                        <td class="py-4 px-6">
                                            {{ $schedule->teachingAssignment->subject->name ?? 'N/A' }}
                                        </td>
                                        <td class="py-4 px-6">
                                            {{ $schedule->teachingAssignment->teacher->name ?? 'N/A' }}
                                        </td>
                                        <td class="py-4 px-6">
                                            {{ $schedule->day_of_week }}
                                        </td>
                                        <td class="py-4 px-6">
                                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                        </td>
                                        <td class="py-4 px-6">
                                            {{ $schedule->room_number ?? '-' }}
                                        </td>
                                        @role('admin_sekolah')
                                            {{-- Hanya admin yang melihat kolom aksi --}}
                                            <td class="py-4 px-6 flex items-center space-x-3">
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
                                        @endrole
                                    </tr>
                                @empty
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td colspan="{{ Auth::user()->hasRole('admin_sekolah') ? '8' : '7' }}"
                                            class="py-4 px-6 text-center text-gray-500 dark:text-gray-400">
                                            {{-- colspan disesuaikan --}}
                                            Tidak ada jadwal ditemukan untuk tahun ajaran {{ $academicYear }}.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $schedules->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
