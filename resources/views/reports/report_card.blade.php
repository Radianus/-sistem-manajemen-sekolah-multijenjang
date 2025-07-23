<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Rapor Siswa') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 print:max-w-full print:px-0">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg print:shadow-none print:rounded-none">
                <div
                    class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 print:border-0 print:p-0">
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">RAPOR SISWA</h1>
                        <p class="text-lg text-gray-700 dark:text-gray-300">Tahun Ajaran: {{ $academicYear }} | Semester:
                            {{ $semester }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-gray-800 dark:text-gray-200 mb-8">
                        <div>
                            <p><strong>Nama Siswa:</strong> {{ $student->user->name ?? 'N/A' }}</p>
                            <p><strong>NIS:</strong> {{ $student->nis ?? 'N/A' }}</p>
                            <p><strong>NISN:</strong> {{ $student->nisn ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p><strong>Kelas:</strong> {{ $student->schoolClass->name ?? 'N/A' }}</p>
                            <p><strong>Wali Kelas:</strong> {{ $student->schoolClass->homeroomTeacher->name ?? 'N/A' }}
                            </p>
                            <p><strong>Tahun Ajaran:</strong> {{ $academicYear }}</p>
                        </div>
                    </div>

                    <h4 class="font-bold text-xl text-gray-900 dark:text-gray-100 mb-4">Nilai Mata Pelajaran</h4>
                    <div class="overflow-x-auto relative shadow-md sm:rounded-lg mb-8">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="py-3 px-6">Mata Pelajaran</th>
                                    <th scope="col" class="py-3 px-6">Guru Pengajar</th>
                                    <th scope="col" class="py-3 px-6 text-center">Tugas</th>
                                    <th scope="col" class="py-3 px-6 text-center">Ulangan Harian</th>
                                    <th scope="col" class="py-3 px-6 text-center">UTS</th>
                                    <th scope="col" class="py-3 px-6 text-center">UAS</th>
                                    <th scope="col" class="py-3 px-6 text-center">Nilai Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reportSubjects as $reportSubject)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="py-4 px-6 font-medium text-gray-900 dark:text-white">
                                            {{ $reportSubject['subject_name'] }}
                                        </td>
                                        <td class="py-4 px-6">{{ $reportSubject['teacher_name'] }}</td>
                                        <td class="py-4 px-6 text-center">
                                            {{ $reportSubject['grades']->where('grade_type', 'Tugas')->first()->score ?? '-' }}
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            {{ $reportSubject['grades']->where('grade_type', 'Ulangan Harian')->first()->score ?? '-' }}
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            {{ $reportSubject['grades']->where('grade_type', 'UTS')->first()->score ?? '-' }}
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            {{ $reportSubject['grades']->where('grade_type', 'UAS')->first()->score ?? '-' }}
                                        </td>
                                        <td class="py-4 px-6 text-center font-bold">
                                            {{ $reportSubject['grades']->where('grade_type', 'Nilai Akhir')->first()->score ?? '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td colspan="7"
                                            class="py-4 px-6 text-center text-gray-500 dark:text-gray-400">
                                            Tidak ada nilai mata pelajaran ditemukan untuk semester ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <h4 class="font-bold text-xl text-gray-900 dark:text-gray-100 mb-4">Ringkasan Absensi</h4>
                    <div class="overflow-x-auto relative shadow-md sm:rounded-lg mb-8">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="py-3 px-6">Status</th>
                                    <th scope="col" class="py-3 px-6">Jumlah Hari</th>
                                    <th scope="col" class="py-3 px-6">Persentase (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="py-4 px-6">Hadir</td>
                                    <td class="py-4 px-6">{{ $attendanceSummary['Hadir'] ?? 0 }}</td>
                                    <td class="py-4 px-6">
                                        {{ number_format($attendanceSummary['Hadir_percent'] ?? 0, 2) }}%</td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="py-4 px-6">Izin</td>
                                    <td class="py-4 px-6">{{ $attendanceSummary['Izin'] ?? 0 }}</td>
                                    <td class="py-4 px-6">
                                        {{ number_format($attendanceSummary['Izin_percent'] ?? 0, 2) }}%</td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="py-4 px-6">Sakit</td>
                                    <td class="py-4 px-6">{{ $attendanceSummary['Sakit'] ?? 0 }}</td>
                                    <td class="py-4 px-6">
                                        {{ number_format($attendanceSummary['Sakit_percent'] ?? 0, 2) }}%</td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="py-4 px-6">Alpha</td>
                                    <td class="py-4 px-6">{{ $attendanceSummary['Alpha'] ?? 0 }}</td>
                                    <td class="py-4 px-6">
                                        {{ number_format($attendanceSummary['Alpha_percent'] ?? 0, 2) }}%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h4 class="font-bold text-xl text-gray-900 dark:text-gray-100 mb-4">Catatan Wali Kelas</h4>
                    <div
                        class="mb-8 p-4 border border-gray-300 dark:border-gray-700 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                        <p>{{ $homeroomTeacherComment }}</p>
                    </div>

                    <div class="flex items-center justify-end mt-4 print:hidden">
                        <a href="{{ route('admin.reports.reportCardFilterForm') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Kembali') }}
                        </a>
                        <button onclick="window.print()"
                            class="ml-4 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Cetak Rapor') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
