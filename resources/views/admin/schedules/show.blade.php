<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white leading-tight">
            🗓️ Detail Jadwal Pelajaran
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6 space-y-6">

                <div>
                    <h3 class="text-xl font-semibold text-blue-600 dark:text-blue-400">
                        📚 Kelas: {{ $schedule->schoolClass->name ?? 'N/A' }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        Tahun Ajaran: <strong>{{ $schedule->academic_year }}</strong>
                    </p>
                </div>

                <div class="grid md:grid-cols-2 gap-4 text-sm text-gray-700 dark:text-gray-300">
                    <div>
                        <p class="text-gray-500">Mata Pelajaran:</p>
                        <p class="font-semibold">{{ $schedule->teachingAssignment->subject->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Guru Pengajar:</p>
                        <p class="font-semibold">{{ $schedule->teachingAssignment->teacher->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Hari:</p>
                        <p class="font-semibold">{{ $schedule->day }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Waktu:</p>
                        <p class="font-semibold">
                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                            -
                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500">Ruangan:</p>
                        <p class="font-semibold">{{ $schedule->room ?? 'Tidak Ditentukan' }}</p>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-6 border-t dark:border-gray-700">
                    <a href="{{ route('admin.schedules.index') }}"
                        class="text-sm text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white transition">
                        ← Kembali ke Daftar Jadwal
                    </a>

                    @can('update', $schedule)
                        <a href="{{ route('admin.schedules.edit', $schedule) }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-medium transition">
                            ✏️ Edit Jadwal
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
