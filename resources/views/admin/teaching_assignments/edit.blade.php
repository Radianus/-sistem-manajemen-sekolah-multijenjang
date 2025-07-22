<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Penugasan Mengajar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Edit Penugasan:
                        {{ $assignment->schoolClass->name ?? 'N/A' }} - {{ $assignment->subject->name ?? 'N/A' }}</h3>

                    <form method="POST" action="{{ route('admin.teaching_assignments.update', $assignment) }}">
                        @csrf
                        @method('PUT') {{-- Penting: Gunakan method PUT untuk update --}}

                        <div class="mb-4">
                            <x-input-label for="academic_year" :value="__('Tahun Ajaran')" />
                            <x-text-input id="academic_year" class="block mt-1 w-full" type="text"
                                name="academic_year" :value="old('academic_year', $assignment->academic_year)" required placeholder="Cth: 2025/2026" />
                            <x-input-error :messages="$errors->get('academic_year')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="school_class_id" :value="__('Kelas')" />
                            <select id="school_class_id" name="school_class_id" required
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Pilih Kelas</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}"
                                        {{ old('school_class_id', $assignment->school_class_id) == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('school_class_id')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="subject_id" :value="__('Mata Pelajaran')" />
                            <select id="subject_id" name="subject_id" required
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Pilih Mata Pelajaran</option>
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}"
                                        {{ old('subject_id', $assignment->subject_id) == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }} ({{ $subject->level ?? 'Umum' }})</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('subject_id')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="teacher_id" :value="__('Guru Pengajar')" />
                            <select id="teacher_id" name="teacher_id" required
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Pilih Guru</option>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}"
                                        {{ old('teacher_id', $assignment->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('teacher_id')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Perbarui Penugasan') }}
                            </x-primary-button>
                            <a href="{{ route('admin.teaching_assignments.index') }}"
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
