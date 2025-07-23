<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Materi & Tugas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4 sm:mb-0">Daftar Materi &
                            Tugas</h3>
                        @if (Auth::user()->hasRole('admin_sekolah') || Auth::user()->hasRole('guru'))
                            <a href="{{ route('admin.assignments.create') }}"
                                class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 w-full sm:w-auto">
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Buat Tugas Baru
                            </a>
                        @endif
                    </div>

                    {{-- Filter Kelas (Hanya untuk Admin dan Guru) --}}
                    @if (Auth::user()->hasRole('admin_sekolah') || Auth::user()->hasRole('guru'))
                        <div class="mb-4 flex flex-col sm:flex-row items-center sm:space-x-4">
                            <label for="class_filter" class="text-gray-700 dark:text-gray-300 mr-2 mb-2 sm:mb-0">Filter
                                Kelas:</label>
                            <select id="class_filter" name="class_id"
                                onchange="window.location.href = '{{ route('admin.assignments.index') }}?class_id=' + this.value"
                                {{-- PERBAIKAN DI SINI --}}
                                class="border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Semua Kelas</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}"
                                        {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if ($assignments->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">Tidak ada tugas/materi ditemukan.</p>
                    @else
                        <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="py-3 px-6">Judul Tugas</th>
                                        <th scope="col" class="py-3 px-6">Kelas</th>
                                        <th scope="col" class="py-3 px-6">Mata Pelajaran</th>
                                        <th scope="col" class="py-3 px-6">Batas Waktu</th>
                                        <th scope="col" class="py-3 px-6">Skor Max</th>
                                        <th scope="col" class="py-3 px-6">Dibuat Oleh</th>
                                        @if (Auth::user()->hasRole('siswa'))
                                            <th scope="col" class="py-3 px-6">Status Pengumpulan</th>
                                        @endif
                                        <th scope="col" class="py-3 px-6">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($assignments as $assignment)
                                        <tr
                                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <td class="py-4 px-6 font-medium text-gray-900 dark:text-white">
                                                {{ $assignment->title }} ({{ $assignment->assignment_type }})
                                                @if ($assignment->isOverdue())
                                                    <span
                                                        class="ml-2 bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300">Terlambat</span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-6">
                                                {{ $assignment->teachingAssignment->schoolClass->name ?? 'N/A' }}</td>
                                            <td class="py-4 px-6">
                                                {{ $assignment->teachingAssignment->subject->name ?? 'N/A' }}</td>
                                            <td class="py-4 px-6">{{ $assignment->due_date->format('d-m-Y H:i') }}</td>
                                            <td class="py-4 px-6">{{ $assignment->max_score ?? '-' }}</td>
                                            <td class="py-4 px-6">{{ $assignment->assignedBy->name ?? 'N/A' }}</td>
                                            @if (Auth::user()->hasRole('siswa'))
                                                <td class="py-4 px-6">
                                                    @if ($assignment->submissions_exists)
                                                        <span
                                                            class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">Sudah
                                                            Kumpul</span>
                                                    @else
                                                        <span
                                                            class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300">Belum
                                                            Kumpul</span>
                                                    @endif
                                                </td>
                                            @endif
                                            <td class="py-4 px-6 flex items-center space-x-3">
                                                <a href="{{ route('admin.assignments.show', $assignment) }}"
                                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Lihat
                                                    Detail</a>
                                                @if (Auth::user()->hasRole('admin_sekolah') ||
                                                        (Auth::user()->hasRole('guru') && Auth::id() == $assignment->assigned_by_user_id))
                                                    <a href="{{ route('admin.assignments.edit', $assignment) }}"
                                                        class="font-medium text-yellow-600 dark:text-yellow-500 hover:underline">Edit</a>
                                                    <form
                                                        action="{{ route('admin.assignments.destroy', $assignment) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini? Ini juga akan menghapus semua pengumpulan terkait.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="font-medium text-red-600 dark:text-red-500 hover:underline">Hapus</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $assignments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
