<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Absensi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Edit Catatan Absensi:
                        {{ $attendance->student->user->name ?? 'N/A' }}
                        ({{ $attendance->date->format('d-m-Y') ?? '-' }})</h3>

                    <form method="POST" action="{{ route('admin.attendances.update', $attendance) }}">
                        @csrf
                        @method('PUT') {{-- Penting: Gunakan method PUT untuk update --}}

                        <div class="mb-4">
                            <x-input-label for="date" :value="__('Tanggal Absensi')" />
                            <x-text-input id="date" class="block mt-1 w-full" type="date" name="date"
                                :value="old('date', $attendance->date->format('Y-m-d'))" required max="{{ date('Y-m-d') }}" />
                            <x-input-error :messages="$errors->get('date')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="teaching_assignment_id" :value="__('Penugasan Mengajar (Kelas - Mapel - Guru)')" />
                            <select id="teaching_assignment_id" name="teaching_assignment_id" required
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Pilih Penugasan Mengajar</option>
                                @foreach ($teachingAssignments as $ta)
                                    <option value="{{ $ta->id }}"
                                        {{ old('teaching_assignment_id', $attendance->teaching_assignment_id) == $ta->id ? 'selected' : '' }}>
                                        {{ $ta->schoolClass->name ?? 'N/A' }} - {{ $ta->subject->name ?? 'N/A' }}
                                        ({{ $ta->teacher->name ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('teaching_assignment_id')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="student_id" :value="__('Siswa')" />
                            <select id="student_id" name="student_id" required
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Pilih Siswa</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}"
                                        {{ old('student_id', $attendance->student_id) == $student->id ? 'selected' : '' }}>
                                        {{ $student->user->name ?? 'N/A' }} (NIS: {{ $student->nis }})</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="status" :value="__('Status Kehadiran')" />
                            <select id="status" name="status" required
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Pilih Status</option>
                                <option value="Hadir"
                                    {{ old('status', $attendance->status) == 'Hadir' ? 'selected' : '' }}>Hadir
                                </option>
                                <option value="Izin"
                                    {{ old('status', $attendance->status) == 'Izin' ? 'selected' : '' }}>Izin</option>
                                <option value="Sakit"
                                    {{ old('status', $attendance->status) == 'Sakit' ? 'selected' : '' }}>Sakit
                                </option>
                                <option value="Alpha"
                                    {{ old('status', $attendance->status) == 'Alpha' ? 'selected' : '' }}>Alpha
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="notes" :value="__('Catatan (Opsional)')" />
                            <textarea id="notes" name="notes"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('notes', $attendance->notes) }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Perbarui Absensi') }}
                            </x-primary-button>
                            <a href="{{ route('admin.attendances.index') }}"
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
