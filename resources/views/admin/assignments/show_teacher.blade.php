<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
            📘 Detail Tugas & Pengumpulan
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-6">
            <!-- Informasi Tugas -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-10">
                <h3 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    {{ $assignment->title }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-800 dark:text-gray-300">
                    <x-info label="Kelas" value="{{ $assignment->teachingAssignment->schoolClass->name ?? 'N/A' }}" />
                    <x-info label="Mata Pelajaran"
                        value="{{ $assignment->teachingAssignment->subject->name ?? 'N/A' }}" />
                    <x-info label="Guru Pengajar"
                        value="{{ $assignment->teachingAssignment->teacher->name ?? 'N/A' }}" />
                    <x-info label="Diberikan Oleh" value="{{ $assignment->assignedBy->name ?? 'N/A' }}" />
                    <x-info label="Batas Waktu" :value="$assignment->due_date->format('d-m-Y H:i')" />
                    <x-info label="Skor Maksimum" value="{{ $assignment->max_score ?? '-' }}" />
                    @if ($assignment->file_path)
                        <x-info label="File Tugas" :value="view()->make('components.assignment-file', [
                            'url' => Storage::url($assignment->file_path),
                        ])" />
                    @endif
                </div>

                <hr class="my-6 border-gray-300 dark:border-gray-700">

                <div class="prose dark:prose-invert max-w-none text-sm">
                    {!! nl2br(e($assignment->description)) !!}
                </div>

                <div class="mt-6 flex flex-wrap justify-end gap-3">
                    <a href="{{ route('admin.assignments.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-xs font-semibold rounded-md hover:bg-gray-700">
                        Kembali ke Daftar Tugas
                    </a>
                    @if (Auth::user()->hasRole('admin_sekolah') ||
                            (Auth::user()->hasRole('guru') && Auth::id() == $assignment->assigned_by_user_id))
                        <a href="{{ route('admin.assignments.edit', $assignment) }}"
                            class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white text-xs font-semibold rounded-md hover:bg-yellow-700">
                            Edit Tugas
                        </a>
                    @endif
                </div>
            </div>

            <!-- Pengumpulan Tugas -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-4">📥 Pengumpulan Tugas Siswa</h4>

                @if ($submissions->isEmpty())
                    <p class="text-gray-600 dark:text-gray-400">Belum ada pengumpulan tugas.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead
                                class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-left uppercase text-xs">
                                <tr>
                                    <th class="px-4 py-3">Siswa</th>
                                    <th class="px-4 py-3">Tanggal Kumpul</th>
                                    <th class="px-4 py-3">File/Konten</th>
                                    <th class="px-4 py-3">Nilai</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($submissions as $submission)
                                    <tr class="bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-4 py-3">{{ $submission->student->user->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-3">{{ $submission->submission_date->format('d-m-Y H:i') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @if ($submission->file_path)
                                                <a href="{{ Storage::url($submission->file_path) }}"
                                                    class="text-blue-500 hover:underline" target="_blank">Unduh File</a>
                                            @elseif($submission->content)
                                                {{ Str::limit($submission->content, 50) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">{{ $submission->score ?? '-' }}</td>
                                        <td class="px-4 py-3">
                                            @if ($submission->score)
                                                <span
                                                    class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 px-2 py-0.5 rounded-full text-xs font-medium">Sudah
                                                    Dinilai</span>
                                            @else
                                                <span
                                                    class="bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 px-2 py-0.5 rounded-full text-xs font-medium">Menunggu
                                                    Penilaian</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <a href="{{ route('admin.submissions.grade', $submission) }}"
                                                class="text-green-600 dark:text-green-400 hover:underline font-medium">Nilai</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $submissions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
