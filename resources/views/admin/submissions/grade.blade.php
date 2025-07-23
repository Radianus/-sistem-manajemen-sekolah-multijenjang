<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
            📝 Nilai Pengumpulan Tugas
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto px-6">
            <!-- Card Info -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-8">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">
                    {{ $submission->student->user->name ?? 'N/A' }}
                    <span class="text-sm text-gray-500">(NIS: {{ $submission->student->nis ?? 'N/A' }})</span>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700 dark:text-gray-300">
                    <x-info label="Kelas" :value="$submission->assignment->teachingAssignment->schoolClass->name ?? 'N/A'" />
                    <x-info label="Mata Pelajaran" :value="$submission->assignment->teachingAssignment->subject->name ?? 'N/A'" />
                    <x-info label="Judul Tugas" :value="$submission->assignment->title" />
                    <x-info label="Tanggal Kumpul" :value="$submission->submission_date->format('d-m-Y H:i')" />
                </div>

                @if ($submission->file_path || $submission->content)
                    <div class="mt-6 border-t pt-4 space-y-3 text-sm">
                        @if ($submission->file_path)
                            <div>
                                <span class="font-medium text-gray-600 dark:text-gray-400">File:</span>
                                <a href="{{ Storage::url($submission->file_path) }}" target="_blank"
                                    class="text-blue-500 hover:underline">Unduh File</a>
                            </div>
                        @endif
                        @if ($submission->content)
                            <div>
                                <span class="font-medium text-gray-600 dark:text-gray-400">Konten:</span>
                                <p class="mt-1 text-gray-800 dark:text-gray-200">{{ $submission->content }}</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Form Penilaian -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <form method="POST" action="{{ route('admin.submissions.update_grade', $submission) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-5">
                        <x-input-label for="score" :value="'Nilai (Skor Maks: ' . ($submission->assignment->max_score ?? 'N/A') . ')'" />
                        <x-text-input id="score" name="score" type="number" step="0.01" class="mt-1 w-full"
                            :value="old('score', $submission->score)" required min="0" :max="$submission->assignment->max_score ?? 100" />
                        <x-input-error :messages="$errors->get('score')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <x-input-label for="feedback" value="Umpan Balik Guru (Opsional)" />
                        <textarea id="feedback" name="feedback" rows="3"
                            class="mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 rounded-md shadow-sm focus:ring focus:ring-indigo-500 dark:focus:ring-indigo-600 focus:border-indigo-500 dark:focus:border-indigo-600">{{ old('feedback', $submission->feedback) }}</textarea>
                        <x-input-error :messages="$errors->get('feedback')" class="mt-2" />
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.assignments.show', $submission->assignment->id) }}"
                            class="px-4 py-2 bg-gray-600 text-white text-xs rounded-md hover:bg-gray-700">
                            Batal
                        </a>
                        <x-primary-button>
                            Simpan Nilai
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
