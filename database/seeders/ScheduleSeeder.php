<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Schedule;
use App\Models\SchoolClass;
use App\Models\TeachingAssignment;
use Database\Factories\ScheduleFactory;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = SchoolClass::all();
        $teachingAssignments = TeachingAssignment::all();

        if ($classes->isEmpty() || $teachingAssignments->isEmpty()) {
            $this->command->info('Tidak cukup data kelas atau penugasan mengajar untuk seed schedules. Pastikan seeder sebelumnya sudah dijalankan.');
            return;
        }

        $daysOfWeek = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $academicYear = '2025/2026';
        $seededCount = 0;

        foreach ($classes as $class) {
            foreach ($daysOfWeek as $day) {
                // --- PERBAIKI BAGIAN INI ---
                $assignmentsForClass = $teachingAssignments->where('school_class_id', $class->id);

                // Pastikan ada penugasan mengajar untuk kelas ini sebelum mengambil secara acak
                if ($assignmentsForClass->isEmpty()) {
                    continue; // Lewati kelas ini jika tidak ada penugasan mengajar
                }

                // Ambil beberapa penugasan secara acak dari yang tersedia untuk kelas ini
                $tasToSeed = $assignmentsForClass->shuffle()->take(rand(1, 3)); // Ambil 1-3 penugasan per hari per kelas

                foreach ($tasToSeed as $ta) { // Loop melalui penugasan yang akan di-seed
                    // --- AKHIR PERBAIKAN ---

                    $startTime = Carbon::createFromTime(rand(7, 15), rand(0, 59), 0);
                    $endTime = $startTime->copy()->addMinutes(rand(45, 90));

                    // Check for time conflicts before creating (manual check for seeder)
                    $conflictExists = Schedule::where('school_class_id', $class->id)
                        ->where('day_of_week', $day)
                        ->where('academic_year', $academicYear)
                        ->where(function ($query) use ($startTime, $endTime) {
                            $query->where('start_time', '<', $endTime->format('H:i:s'))
                                ->where('end_time', '>', $startTime->format('H:i:s'));
                        })->exists();

                    if (!$conflictExists) {
                        try {
                            Schedule::factory()->create([
                                'school_class_id' => $class->id,
                                'teaching_assignment_id' => $ta->id,
                                'day_of_week' => $day,
                                'start_time' => $startTime->format('H:i:s'),
                                'end_time' => $endTime->format('H:i:s'),
                                'academic_year' => $academicYear,
                            ]);
                            $seededCount++;
                        } catch (\Throwable $e) {
                            $this->command->error('Error seeding schedule for class ' . $class->name . ', subject ' . ($ta->subject->name ?? 'N/A') . ', day ' . $day . ': ' . $e->getMessage());
                        }
                    }
                } // End foreach ($tasToSeed as $ta)
            } // End foreach ($daysOfWeek as $day)
            if ($seededCount >= 100) {
                $this->command->info('Reached ' . $seededCount . ' schedules. Stopping early.');
                break 1; // Keluar dari loop kelas jika sudah cukup banyak
            }
        } // End foreach ($classes as $class)
        $this->command->info('Schedules seeded: ' . $seededCount);
    }
}
