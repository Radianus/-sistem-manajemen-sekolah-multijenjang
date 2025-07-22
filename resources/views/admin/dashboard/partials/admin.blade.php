 @php
     $totalUsers = \App\Models\User::count();
     $totalClasses = \App\Models\SchoolClass::count();
     $totalStudents = \App\Models\Student::count();
     $totalSubjects = \App\Models\Subject::count();
     $totalTeachingAssignments = \App\Models\TeachingAssignment::count();
     $totalSchedules = \App\Models\Schedule::count();
     $totalGrades = \App\Models\Grade::count();
     $totalAttendances = \App\Models\Attendance::count();

     $totalTeachers = \App\Models\User::role('guru')->count();
     $totalParents = \App\Models\User::role('orang_tua')->count();
     $activeAnnouncements = \App\Models\Announcement::active()->targetedTo('all')->count();
     $upcomingEventsThisMonth = \App\Models\CalendarEvent::activeBetween(
         \Carbon\Carbon::now()->startOfMonth(),
         \Carbon\Carbon::now()->endOfMonth(),
     )->count();

     $currentAcademicYear = $globalSettings->current_academic_year ?? date('Y') . '/' . (date('Y') + 1);
 @endphp
 @php
     $cards = [
         ['label' => 'Pengguna', 'count' => $totalUsers, 'icon' => '👥', 'route' => 'admin.users.index'],
         [
             'label' => 'Guru',
             'count' => $totalTeachers,
             'icon' => '🎓',
             'route' => 'admin.users.index',
             'params' => ['role' => 'guru'],
         ],
         ['label' => 'Siswa', 'count' => $totalStudents, 'icon' => '🧑‍🎒', 'route' => 'admin.students.index'],
         [
             'label' => 'Orang Tua',
             'count' => $totalParents,
             'icon' => '👪',
             'route' => 'admin.users.index',
             'params' => ['role' => 'orang_tua'],
         ],
         ['label' => 'Kelas', 'count' => $totalClasses, 'icon' => '🏫', 'route' => 'admin.classes.index'],
         ['label' => 'Mapel', 'count' => $totalSubjects, 'icon' => '📘', 'route' => 'admin.subjects.index'],
         [
             'label' => 'Penugasan',
             'count' => $totalTeachingAssignments,
             'icon' => '📋',
             'route' => 'admin.teaching_assignments.index',
         ],
         ['label' => 'Jadwal', 'count' => $totalSchedules, 'icon' => '🗓️', 'route' => 'admin.schedules.index'],
         ['label' => 'Nilai', 'count' => $totalGrades, 'icon' => '📝', 'route' => 'admin.grades.index'],
         ['label' => 'Absensi', 'count' => $totalAttendances, 'icon' => '🧾', 'route' => 'admin.attendances.index'],
         [
             'label' => 'Pengumuman',
             'count' => $activeAnnouncements,
             'icon' => '📢',
             'route' => 'admin.announcements.index',
         ],
         [
             'label' => 'Acara Bulan Ini',
             'count' => $upcomingEventsThisMonth,
             'icon' => '🎉',
             'route' => 'admin.calendar_events.index',
         ],
     ];
 @endphp

 <div class="mt-8">
     <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
         🛠️ Ringkasan Admin <span class="text-base text-gray-500">(Tahun Ajaran {{ $currentAcademicYear }})</span>
     </h3>
     <p class="text-gray-600 dark:text-gray-300">Selamat datang, Admin. Kelola semua aspek sekolah dengan efisien.</p>

     <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6 mt-6">
         @foreach ($cards as $card)
             <a href="{{ route($card['route'], $card['params'] ?? []) }}"
                 class="group block p-5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow hover:shadow-lg transition duration-300">
                 <div class="flex items-center justify-between">
                     <span class="text-3xl">{{ $card['icon'] }}</span>
                     <span class="text-2xl font-bold text-gray-800 dark:text-white">{{ $card['count'] }}</span>
                 </div>
                 <div class="mt-4">
                     <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-100 group-hover:underline">
                         {{ $card['label'] }}
                     </h4>
                     <p class="text-sm text-gray-500 dark:text-gray-400">Kelola {{ strtolower($card['label']) }}</p>
                 </div>
             </a>
         @endforeach
     </div>
 </div>
