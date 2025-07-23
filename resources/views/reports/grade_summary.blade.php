<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ringkasan Nilai') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 print:max-w-full print:px-0">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg print:shadow-none print:rounded-none">
                <div
                    class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 print:border-0 print:p-0">
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">RINGKASAN NILAI SISWA</h1>
                        <p class="text-lg text-gray-700 dark:text-gray-300">Tahun Ajaran: {{ $academicYear }} | Semester:
                            {{ $semester }}</p>
                        @if ($classId)
                            @php
                                $className = \App\Models\SchoolClass::find($classId)->name ?? 'Semua Kelas';
                            @endphp
                            <p class="text-lg text-gray-700 dark:text-gray-300">Kelas: {{ $className }}</p>
                        @else
                            <p class="text-lg text-gray-700 dark:text-gray-300">Kelas: Semua Kelas</p>
                        @endif
                    </div>

                    @if (empty($reportSummary))
                        <p class="text-gray-600 dark:text-gray-400 text-center">Tidak ada data nilai ditemukan untuk
                            kriteria yang dipilih.</p>
                    @else
                        <div class="overflow-x-auto relative shadow-md sm:rounded-lg mb-8">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="py-3 px-6">Siswa</th>
                                        <th scope="col" class="py-3 px-6">Kelas</th>
                                        @foreach ($reportSummary[0]['subjects_data'] ?? [] as $subjectData)
                                            {{-- Ambil dari siswa pertama untuk header --}}
                                            <th scope="col" class="py-3 px-6 text-center">
                                                {{ $subjectData['subject_name'] }}</th>
                                        @endforeach
                                        <th scope="col" class="py-3 px-6 text-center">Rata-rata Keseluruhan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reportSummary as $summary)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <td class="py-4 px-6 font-medium text-gray-900 dark:text-white">
                                                {{ $summary['student_name'] }}</td>
                                            <td class="py-4 px-6">{{ $summary['class_name'] }}</td>
                                            @foreach ($summary['subjects_data'] as $subjectData)
                                                <td class="py-4 px-6 text-center">
                                                    {{ number_format($subjectData['score'], 2) }}</td>
                                            @endforeach
                                            <td class="py-4 px-6 text-center font-bold">
                                                {{ number_format($summary['average_overall'], 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <div class="flex items-center justify-end mt-4 print:hidden">
                        <a href="{{ route('admin.reports.gradeSummaryFilterForm') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Kembali') }}
                        </a>
                        <button onclick="window.print()"
                            class="ml-4 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Cetak Laporan') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
