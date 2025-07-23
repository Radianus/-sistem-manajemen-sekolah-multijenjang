 @php
     $teacherId = Auth::id(); // Langsung gunakan ID user yang login
     $assignedClasses = \App\Models\SchoolClass::whereHas('teachingAssignments', function ($query) use ($teacherId) {
         return $query->where('teacher_id', $teacherId);
     })->count();
     $assignedSubjects = \App\Models\TeachingAssignment::where('teacher_id', $teacherId)
         ->distinct('subject_id')
         ->count();
     $studentsTaught = \App\Models\Student::whereHas('schoolClass.teachingAssignments', function ($query) use (
         $teacherId,
     ) {
         return $query->where('teacher_id', $teacherId);
     })
         ->distinct('students.id')
         ->count(); // Gunakan 'students.id' untuk distinct pada tabel students
     $activeTeachingAssignments = \App\Models\TeachingAssignment::where('teacher_id', $teacherId)->count();

     // Untuk pengumuman dan acara kalender, filter juga berdasarkan siapa yang membuat jika diperlukan
     $announcementsForTeacher = \App\Models\Announcement::active()
         ->where(function ($query) {
             $query->targetedTo('all')->orWhere('target_roles', 'like', '%guru%'); // Perbaiki ini
         })
         ->count();
     $upcomingEventsForTeacher = \App\Models\CalendarEvent::activeBetween(
         \Carbon\Carbon::now()->startOfMonth(),
         \Carbon\Carbon::now()->endOfMonth(),
     )
         ->where(function ($query) {
             $query
                 ->targetedTo('all') // Gunakan scope targetedTo yang sudah ada
                 ->orWhere('target_roles', 'like', '%guru%'); // Perbaiki ini
         })
         ->count();
     $currentAcademicYear = $globalSettings->current_academic_year ?? date('Y') . '/' . (date('Y') + 1);
 @endphp

 <div class="mt-8">
     <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
         👋 Hai, Guru! Ini ringkasan kamu — <span class="text-base font-medium text-gray-500">Tahun Ajaran
             {{ $currentAcademicYear }}</span>
     </h3>

     <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
         @php
             $cards = [
                 [
                     'label' => 'Penugasan Mengajar',
                     'count' => $activeTeachingAssignments,
                     'icon' => '📚',
                     'route' => 'admin.teaching_assignments.index',
                 ],
                 ['label' => 'Nilai Siswa', 'count' => '', 'icon' => '📝', 'route' => 'admin.grades.index'],
                 ['label' => 'Absensi', 'count' => '', 'icon' => '🧾', 'route' => 'admin.attendances.index'],
                 ['label' => 'Jadwal Mengajar', 'count' => '', 'icon' => '📅', 'route' => 'admin.schedules.index'],
                 [
                     'label' => 'Pengumuman',
                     'count' => $announcementsForTeacher,
                     'icon' => '📢',
                     'route' => 'admin.announcements.index',
                 ],
                 [
                     'label' => 'Acara Bulan Ini',
                     'count' => $upcomingEventsForTeacher,
                     'icon' => '🎉',
                     'route' => 'admin.calendar_events.index',
                 ],
             ];
         @endphp

         @foreach ($cards as $card)
             <a href="{{ route($card['route'], ['teacher_id' => $teacherId]) }}"
                 class="group block p-6 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border border-gray-200 dark:border-gray-700 rounded-2xl shadow-md hover:shadow-xl transition-shadow duration-300">
                 <div class="text-4xl mb-4">{{ $card['icon'] }}</div>
                 <h4 class="text-lg font-semibold text-gray-800 dark:text-white">
                     {{ $card['label'] }} {{ $card['count'] !== '' ? ": $card[count]" : '' }}
                 </h4>
                 <p class="text-sm text-gray-600 dark:text-gray-300 mt-2 group-hover:underline">
                     Klik untuk melihat detail
                 </p>
             </a>
         @endforeach
     </div>
 </div>
