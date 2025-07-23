<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Tugas Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Informasi Tugas/Materi</h3>

                    <form method="POST" action="{{ route('admin.assignments.store') }}" enctype="multipart/form-data">
                        {{-- Tambah enctype untuk upload file --}}
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="title" :value="__('Judul Tugas/Materi')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title"
                                :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>
                        <div class="mb-4">
                            <x-input-label for="assignment_type" :value="__('Tipe Tugas')" />
                            <select id="assignment_type" name="assignment_type" required
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                @php
                                    $assignmentTypes = ['Individu', 'Kelompok', 'Proyek', 'Presentasi', 'Quiz'];
                                @endphp
                                <option value="">Pilih Tipe Tugas</option>
                                @foreach ($assignmentTypes as $type)
                                    <option value="{{ $type }}"
                                        {{ old('assignment_type') == $type ? 'selected' : '' }}>{{ $type }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('assignment_type')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Deskripsi')" />
                            <textarea id="description" name="description"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                rows="5" required>{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="teaching_assignment_id" :value="__('Kelas & Mata Pelajaran (Penugasan)')" />
                            <select id="teaching_assignment_id" name="teaching_assignment_id" required
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Pilih Penugasan Mengajar</option>
                                @foreach ($teachingAssignments as $ta)
                                    <option value="{{ $ta->id }}"
                                        {{ old('teaching_assignment_id') == $ta->id ? 'selected' : '' }}>
                                        {{ $ta->schoolClass->name ?? 'N/A' }} - {{ $ta->subject->name ?? 'N/A' }} (Guru:
                                        {{ $ta->teacher->name ?? 'N/A' }})
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
                            <x-input-label for="due_date" :value="__('Batas Waktu Pengumpulan')" />
                            <x-text-input id="due_date" class="block mt-1 w-full" type="datetime-local" name="due_date"
                                :value="old('due_date')" required />
                            <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="max_score" :value="__('Skor Maksimum (Opsional)')" />
                            <x-text-input id="max_score" class="block mt-1 w-full" type="number" step="0.01"
                                name="max_score" :value="old('max_score')" min="0" max="100" />
                            <x-input-error :messages="$errors->get('max_score')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="assignment_file" :value="__('Lampirkan File (Opsional)')" />
                            <input id="assignment_file" name="assignment_file" type="file"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                            <x-input-error :messages="$errors->get('assignment_file')" class="mt-2" />
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Format: PDF, DOC, DOCX, PPT, PPTX,
                                XLS, XLSX, TXT, ZIP, RAR (Max 10MB)</p>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Simpan Tugas') }}
                            </x-primary-button>
                            <a href="{{ route('admin.assignments.index') }}"
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
