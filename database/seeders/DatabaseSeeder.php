<?php

namespace Database\Seeders;

use App\Models\HeroSlider;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);

        // // Kelas dan Mata Pelajaran bisa paralel, tapi seringkali kelas dibutuhkan untuk penugasan.
        // $this->call(SchoolClassSeeder::class);
        // $this->call(SubjectSeeder::class);

        // // Siswa membutuhkan user (dari RolesAndPermissionsSeeder) dan kelas.
        // $this->call(StudentSeeder::class);
        // $this->call(ParentStudentSeeder::class);
        // // Penugasan Mengajar membutuhkan kelas, mata pelajaran, dan guru (user).
        // $this->call(TeachingAssignmentSeeder::class);
        // $this->call(AssignmentSeeder::class);

        // // Nilai dan Absensi membutuhkan siswa dan penugasan mengajar.
        // $this->call(GradeSeeder::class);
        // $this->call(AttendanceSeeder::class);
        // $this->call(AnnouncementSeeder::class);
        // $this->call(ScheduleSeeder::class);
        // $this->call(SettingSeeder::class);
        // $this->call(MessageSeeder::class);
        // $this->call(NotificationSeeder::class);
        // $this->call(MessageAttachmentSeeder::class);
        $this->call(GallerySeeder::class);
        $this->call(NewsSeeder::class);
        $this->call(HeroSliderSeeder::class);
        $this->call(CalendarEventSeeder::class);
    }
}