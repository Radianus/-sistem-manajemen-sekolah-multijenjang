<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Tugas & Pengumpulan Anda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Tugas: {{ $assignment->title }}
                    </h3>
                    <div
                        class="mb-6 p-6 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-700 space-y-3">

                        <div class="grid sm:grid-cols-2 gap-4">
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                <span class="font-semibold">📚 Kelas Anda:</span>
                                {{ $assignment->teachingAssignment->schoolClass->name ?? 'N/A' }}
                            </p>
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                <span class="font-semibold">📘 Mata Pelajaran:</span>
                                {{ $assignment->teachingAssignment->subject->name ?? 'N/A' }}
                            </p>
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                <span class="font-semibold">👨‍🏫 Guru Pengajar:</span>
                                {{ $assignment->teachingAssignment->teacher->name ?? 'N/A' }}
                            </p>
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                <span class="font-semibold">🕒 Batas Waktu:</span>
                                {{ $assignment->due_date->format('d-m-Y H:i') }}
                                @if ($assignment->isOverdue())
                                    <span
                                        class="ml-2 bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300">
                                        Terlambat
                                    </span>
                                @endif
                            </p>
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                <span class="font-semibold">🎯 Skor Maksimum:</span> {{ $assignment->max_score ?? '-' }}
                            </p>
                            @if ($assignment->file_path)
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    <span class="font-semibold">📎 File Tugas:</span>
                                    <a href="{{ Storage::url($assignment->file_path) }}" target="_blank"
                                        class="text-blue-500 hover:underline">
                                        Unduh File
                                    </a>
                                </p>
                            @endif
                        </div>

                        <div class="pt-2">
                            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line leading-relaxed">
                                {{ $assignment->description }}
                            </p>
                        </div>

                    </div>


                    <h4 class="font-bold text-xl text-gray-900 dark:text-gray-100 mt-8 mb-4">Status Pengumpulan Anda
                    </h4>

                    @if ($submission)
                        <div
                            class="p-4 border border-green-300 dark:border-green-700 rounded-md bg-green-50 dark:bg-green-900">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-semibold text-green-800 dark:text-green-200">✅ Anda sudah mengumpulkan
                                    tugas ini</span>
                                <span
                                    class="text-sm text-gray-500">{{ $submission->submission_date->format('d-m-Y H:i') }}</span>
                            </div>

                            @if ($submission->file_path)
                                <p class="text-sm mb-1 text-gray-700 dark:text-gray-300">
                                    <strong>File:</strong> <a href="{{ Storage::url($submission->file_path) }}"
                                        target="_blank" class="text-blue-500 hover:underline">Unduh</a>
                                </p>
                            @endif

                            @if ($submission->content)
                                <p class="text-sm mb-1 text-gray-700 dark:text-gray-300">
                                    <strong>Konten:</strong> {{ Str::limit($submission->content, 100) }}
                                </p>
                            @endif

                            <p class="text-sm mb-1 text-gray-700 dark:text-gray-300">
                                <strong>Nilai:</strong> {{ $submission->score ?? '⏳ Belum Dinilai' }}
                            </p>

                            @if ($submission->feedback)
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    <strong>Umpan Balik:</strong> {{ $submission->feedback }}
                                </p>
                            @endif
                        </div>
                    @else
                        @if ($assignment->isOverdue())
                            <div
                                class="p-4 bg-red-50 dark:bg-red-900 border border-red-300 dark:border-red-700 rounded-md text-red-800 dark:text-red-200">
                                <strong>⛔ Tugas sudah melewati batas waktu.</strong><br>
                                Anda tidak dapat mengumpulkan tugas ini lagi.
                            </div>
                        @else
                            <div
                                class="p-4 bg-yellow-50 dark:bg-yellow-900 border border-yellow-300 dark:border-yellow-700 rounded-md text-yellow-800 dark:text-yellow-200 mb-6">
                                <strong>⚠️ Anda belum mengumpulkan tugas ini.</strong><br>
                                Silakan isi form di bawah untuk mengumpulkan tugas.
                            </div>

                            {{-- Form Pengumpulan --}}
                            <form method="POST" action="{{ route('admin.assignments.submit', $assignment) }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <x-input-label for="submission_file" :value="__('Upload File (Opsional)')" />
                                        <input id="submission_file" name="submission_file" type="file"
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0 file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                                        <x-input-error :messages="$errors->get('submission_file')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="submission_content" :value="__('Konten (Opsional)')" />
                                        <textarea id="submission_content" name="submission_content"
                                            class="block w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                            rows="5">{{ old('submission_content') }}</textarea>
                                        <x-input-error :messages="$errors->get('submission_content')" class="mt-2" />
                                    </div>

                                    <x-primary-button>
                                        {{ __('Kumpul Tugas') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        @endif
                    @endif
                    <div class="flex items-center justify-start mt-6 space-x-3">
                        <a href="{{ route('admin.assignments.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Kembali ke Daftar Tugas') }}
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
