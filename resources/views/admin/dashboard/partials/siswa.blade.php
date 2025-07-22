 @php
     $student = Auth::user()->student; // Dapatkan objek siswa dari user yang login
     $nis = $student->nis ?? 'N/A';
     $nisn = $student->nisn ?? 'N/A';
     $className = $student->schoolClass->name ?? 'Belum Ada Kelas';
     $studentId = $student->id ?? null;

     // Ambil jadwal khusus untuk siswa ini
     $studentSchedules = collect([]);
     if ($student && $student->schoolClass) {
         $studentSchedules = \App\Models\Schedule::with(['teachingAssignment.subject', 'teachingAssignment.teacher'])
             ->where('school_class_id', $student->schoolClass->id)
             ->where('academic_year', date('Y') . '/' . (date('Y') + 1)) // Filter tahun ajaran
             ->orderByRaw("FIELD(day_of_week, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
             ->orderBy('start_time')
             ->get();
     }
 @endphp
 <div
     class="p-6 bg-gradient-to-r from-purple-500 to-indigo-500 dark:from-gray-800 dark:to-gray-900 rounded-xl text-white shadow-md">
     <h3 class="text-2xl font-bold mb-2">🎓 Halo, {{ Auth::user()->name }}!</h3>
     <p class="text-sm">Berikut ringkasan akun sekolahmu</p>
     <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-4">
         <div class="bg-white bg-opacity-10 rounded-lg p-4">
             <p class="text-sm">NIS</p>
             <h4 class="text-lg font-bold">{{ $nis }}</h4>
         </div>
         <div class="bg-white bg-opacity-10 rounded-lg p-4">
             <p class="text-sm">NISN</p>
             <h4 class="text-lg font-bold">{{ $nisn }}</h4>
         </div>
         <div class="bg-white bg-opacity-10 rounded-lg p-4 col-span-2 md:col-span-1">
             <p class="text-sm">Kelas</p>
             <h4 class="text-lg font-bold">{{ $className }}</h4>
         </div>
     </div>
     <div class="mt-6 flex gap-4 flex-wrap">
         <a href="{{ route('admin.grades.index', ['student_id' => $studentId]) }}"
             class="bg-white text-purple-700 px-4 py-2 rounded-lg shadow hover:bg-purple-100 transition">
             📑 Lihat Nilai
         </a>
         <a href="{{ route('admin.attendances.index', ['student_id' => $studentId]) }}"
             class="bg-white text-purple-700 px-4 py-2 rounded-lg shadow hover:bg-purple-100 transition">
             📆 Cek Absensi
         </a>
     </div>
 </div>

 <div class="mt-10">
     <h4 class="text-xl font-bold text-purple-700 dark:text-purple-300 mb-4">📚 Jadwal Pelajaran
         ({{ date('Y') }}/{{ date('Y') + 1 }})</h4>

     @if ($studentSchedules->isEmpty())
         <p class="text-gray-600 dark:text-gray-300">Belum ada jadwal tersedia untuk kelasmu.</p>
     @else
         @foreach ($studentSchedules->groupBy('day_of_week') as $day => $schedulesPerDay)
             <div class="mb-6">
                 <h5 class="text-lg font-semibold text-purple-600 mb-2">{{ $day }}</h5>
                 <div class="space-y-3">
                     @foreach ($schedulesPerDay as $schedule)
                         <div
                             class="bg-white dark:bg-gray-800 border-l-4 border-purple-400 p-4 rounded-lg shadow flex flex-col sm:flex-row sm:justify-between">
                             <div>
                                 <h6 class="text-md font-semibold text-gray-800 dark:text-white">
                                     {{ $schedule->teachingAssignment->subject->name ?? 'Mata Pelajaran' }}
                                 </h6>
                                 <p class="text-sm text-gray-600 dark:text-gray-300">
                                     Guru: {{ $schedule->teachingAssignment->teacher->name ?? '-' }}
                                 </p>
                                 <p class="text-sm text-gray-600 dark:text-gray-300">
                                     Ruang: {{ $schedule->room_number ?? '-' }}
                                 </p>
                             </div>
                             <div class="text-sm font-semibold text-purple-600 mt-2 sm:mt-0">
                                 {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                                 {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                             </div>
                         </div>
                     @endforeach
                 </div>
             </div>
         @endforeach
     @endif
 </div>
