<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Nilai Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Input Nilai Siswa</h3>

                    <form method="POST" action="{{ route('admin.grades.store') }}">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="academic_year" :value="__('Tahun Ajaran')" />
                            <x-text-input id="academic_year" class="block mt-1 w-full" type="text"
                                name="academic_year" :value="old('academic_year', date('Y') . '/' . (date('Y') + 1))" required placeholder="Cth: 2025/2026" />
                            <x-input-error :messages="$errors->get('academic_year')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="semester" :value="__('Semester')" />
                            <select id="semester" name="semester" required
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Pilih Semester</option>
                                <option value="Ganjil" {{ old('semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil
                                </option>
                                <option value="Genap" {{ old('semester') == 'Genap' ? 'selected' : '' }}>Genap</option>
                            </select>
                            <x-input-error :messages="$errors->get('semester')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="teaching_assignment_id" :value="__('Penugasan Mengajar (Kelas - Mapel - Guru)')" />
                            <select id="teaching_assignment_id" name="teaching_assignment_id" required
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Pilih Penugasan Mengajar</option>
                                @foreach ($teachingAssignments as $ta)
                                    <option value="{{ $ta->id }}"
                                        {{ old('teaching_assignment_id') == $ta->id ? 'selected' : '' }}>
                                        {{ $ta->schoolClass->name ?? 'N/A' }} - {{ $ta->subject->name ?? 'N/A' }}
                                        ({{ $ta->teacher->name ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('teaching_assignment_id')" class="mt-2" />
                            @if ($teachingAssignments->isEmpty())
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">Tidak ada penugasan mengajar
                                    tersedia. Harap tambahkan penugasan mengajar terlebih dahulu.</p>
                            @endif
                        </div>

                        <div class="mb-4">
                            <x-input-label for="student_id" :value="__('Siswa')" />
                            <select id="student_id" name="student_id" required
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Pilih Siswa</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}"
                                        {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->user->name ?? 'N/A' }} (NIS: {{ $student->nis }})</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                            @if ($students->isEmpty())
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">Tidak ada siswa ditemukan. Harap
                                    tambahkan siswa terlebih dahulu.</p>
                            @endif
                        </div>

                        <div class="mb-4">
                            <x-input-label for="grade_type" :value="__('Jenis Nilai')" />
                            <select id="grade_type" name="grade_type" required
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Pilih Jenis Nilai</option>
                                <option value="Tugas" {{ old('grade_type') == 'Tugas' ? 'selected' : '' }}>Tugas
                                </option>
                                <option value="Ulangan Harian"
                                    {{ old('grade_type') == 'Ulangan Harian' ? 'selected' : '' }}>Ulangan Harian
                                </option>
                                <option value="UTS" {{ old('grade_type') == 'UTS' ? 'selected' : '' }}>UTS</option>
                                <option value="UAS" {{ old('grade_type') == 'UAS' ? 'selected' : '' }}>UAS</option>
                                <option value="Nilai Akhir" {{ old('grade_type') == 'Nilai Akhir' ? 'selected' : '' }}>
                                    Nilai Akhir</option>
                            </select>
                            <x-input-error :messages="$errors->get('grade_type')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="score" :value="__('Nilai (0-100)')" />
                            <x-text-input id="score" class="block mt-1 w-full" type="number" step="0.01"
                                name="score" :value="old('score')" required min="0" max="100" />
                            <x-input-error :messages="$errors->get('score')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="graded_by_teacher" :value="__('Input Oleh Guru')" />
                            <x-text-input id="graded_by_teacher"
                                class="block mt-1 w-full bg-gray-100 dark:bg-gray-700 cursor-not-allowed" type="text"
                                :value="$loggedInTeacher->name ?? 'N/A'" readonly disabled />
                            <input type="hidden" name="graded_by_teacher_id" value="{{ $loggedInTeacher->id ?? '' }}">
                        </div>

                        <div class="mb-6">
                            <x-input-label for="notes" :value="__('Catatan (Opsional)')" />
                            <textarea id="notes" name="notes"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Simpan Nilai') }}
                            </x-primary-button>
                            <a href="{{ route('admin.grades.index') }}"
                                class="ml-4 inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Batal') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
