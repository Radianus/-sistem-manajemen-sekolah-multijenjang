<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cetak Rapor Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Pilih Kriteria Rapor</h3>

                    <form method="GET" action="{{ route('admin.reports.generateReportCard') }}">
                        <div class="mb-4">
                            <x-input-label for="academic_year" :value="__('Tahun Ajaran')" />
                            <select id="academic_year" name="academic_year" required
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                @foreach ($academicYears as $year)
                                    <option value="{{ $year }}"
                                        {{ old('academic_year', $globalSettings->current_academic_year ?? date('Y') . '/' . (date('Y') + 1)) == $year ? 'selected' : '' }}>
                                        {{ $year }}</option>
                                @endforeach
                            </select>
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

                        <div class="mb-6">
                            <x-input-label for="student_id" :value="__('Pilih Siswa')" />
                            <select id="student_id" name="student_id" required
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Pilih Siswa</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}"
                                        {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->user->name ?? 'N/A' }} (Kelas:
                                        {{ $student->schoolClass->name ?? 'N/A' }})</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                            @if ($students->isEmpty())
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">Tidak ada siswa tersedia untuk
                                    peran Anda.</p>
                            @endif
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Generate Rapor') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
