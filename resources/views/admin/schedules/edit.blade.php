<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Jadwal Pelajaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Edit Jadwal:
                        {{ $schedule->schoolClass->name ?? 'N/A' }} ({{ $schedule->day_of_week }})</h3>
                    @role('admin_sekolah')
                        <form method="POST" action="{{ route('admin.schedules.update', $schedule) }}" x-data="{
                            selectedClassId: '{{ old('school_class_id', $schedule->school_class_id) }}',
                            selectedTeachingAssignmentId: '{{ old('teaching_assignment_id', $schedule->teaching_assignment_id) }}',
                            teachingAssignments: [],
                            allTeachingAssignments: {{ Js::from($teachingAssignments) }},
                            filterTeachingAssignments() {
                                if (this.selectedClassId) {
                                    this.teachingAssignments = this.allTeachingAssignments.filter(ta => ta.school_class.id == this.selectedClassId);
                                } else {
                                    this.teachingAssignments = [];
                                }
                            }
                        }"
                            x-init="filterTeachingAssignments()">
                            @csrf
                            @method('PUT') {{-- Penting: Gunakan method PUT untuk update --}}

                            <div class="mb-4">
                                <x-input-label for="academic_year" :value="__('Tahun Ajaran')" />
                                <select id="academic_year" name="academic_year" required
                                    class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    @foreach ($academicYears as $year)
                                        <option value="{{ $year }}"
                                            {{ old('academic_year', $schedule->academic_year) == $year ? 'selected' : '' }}>
                                            {{ $year }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('academic_year')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="school_class_id" :value="__('Kelas')" />
                                <select id="school_class_id" name="school_class_id" required
                                    class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">Pilih Kelas</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}"
                                            {{ old('school_class_id', $schedule->school_class_id) == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('school_class_id')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="teaching_assignment_id" :value="__('Mata Pelajaran & Guru (Penugasan)')" />
                                <select id="teaching_assignment_id" name="teaching_assignment_id" required
                                    class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">Pilih Penugasan Mengajar</option>
                                    @foreach ($teachingAssignments as $ta)
                                        <option value="{{ $ta->id }}"
                                            {{ old('teaching_assignment_id', $schedule->teaching_assignment_id) == $ta->id ? 'selected' : '' }}
                                            data-class-id="{{ $ta->school_class_id }}">
                                            {{ $ta->subject->name ?? 'N/A' }} oleh {{ $ta->teacher->name ?? 'N/A' }}
                                            (Kelas: {{ $ta->schoolClass->name ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('teaching_assignment_id')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="day_of_week" :value="__('Hari')" />
                                <select id="day_of_week" name="day_of_week" required
                                    class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">Pilih Hari</option>
                                    @php
                                        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                                    @endphp
                                    @foreach ($days as $day)
                                        <option value="{{ $day }}"
                                            {{ old('day_of_week', $schedule->day_of_week) == $day ? 'selected' : '' }}>
                                            {{ $day }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('day_of_week')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="start_time" :value="__('Waktu Mulai')" />
                                <x-text-input id="start_time" class="block mt-1 w-full" type="time" name="start_time"
                                    :value="old('start_time', $schedule->start_time->format('H:i'))" required />
                                <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="end_time" :value="__('Waktu Selesai')" />
                                <x-text-input id="end_time" class="block mt-1 w-full" type="time" name="end_time"
                                    :value="old('end_time', $schedule->end_time->format('H:i'))" required />
                                <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                            </div>

                            <div class="mb-6">
                                <x-input-label for="room_number" :value="__('Nomor Ruangan (Opsional)')" />
                                <x-text-input id="room_number" class="block mt-1 w-full" type="text" name="room_number"
                                    :value="old('room_number', $schedule->room_number)" placeholder="Cth: Lab Komputer, Ruang A1" />
                                <x-input-error :messages="$errors->get('room_number')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button class="ml-4">
                                    {{ __('Perbarui Jadwal') }}
                                </x-primary-button>
                                <a href="{{ route('admin.schedules.index') }}"
                                    class="ml-4 inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    {{ __('Batal') }}
                                </a>
                            </div>
                        </form>
                    @else
                        {{-- Pesan jika bukan admin --}}
                        <div class="p-4 bg-red-100 dark:bg-red-900 rounded-lg text-red-800 dark:text-red-200 text-center">
                            <p class="font-semibold text-lg mb-2">Akses Ditolak</p>
                            <p>Anda tidak memiliki izin untuk mengedit jadwal pelajaran.</p>
                            <a href="{{ route('admin.schedules.index') }}"
                                class="mt-4 inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Kembali ke Daftar Jadwal') }}
                            </a>
                        </div>
                    @endrole
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
