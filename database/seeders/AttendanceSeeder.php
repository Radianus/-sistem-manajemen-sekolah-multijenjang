<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\TeachingAssignment;
use App\Models\User;
use Database\Factories\AttendanceFactory; // Import AttendanceFactory
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::all();
        $teachingAssignments = TeachingAssignment::all();
        $teachers = User::role('guru')->get();

        if ($students->isEmpty() || $teachingAssignments->isEmpty() || $teachers->isEmpty()) {
            $this->command->info('Tidak cukup data siswa, penugasan mengajar, atau guru untuk seed attendances. Pastikan seeder sebelumnya sudah dijalankan.');
            return;
        }

        $statusOptions = ['Hadir', 'Izin', 'Sakit', 'Alpha'];
        $seededCount = 0;

        // Iterate through teaching assignments and create attendance for some students
        foreach ($teachingAssignments as $ta) {
            $studentsInClass = $students->where('school_class_id', $ta->school_class_id);

            if ($studentsInClass->isEmpty()) {
                continue;
            }

            foreach ($studentsInClass->take(5) as $student) { // Ambil 5 siswa per kelas-mapel
                // Buat 3 catatan absensi untuk siswa ini dalam rentang tanggal tertentu
                for ($i = 0; $i < 3; $i++) {
                    $date = Carbon::today()->subDays(rand(0, 30))->format('Y-m-d'); // Dalam 30 hari terakhir
                    $status = $statusOptions[array_rand($statusOptions)];
                    $recordedByTeacher = $teachers->random();

                    // Pastikan tidak ada duplikasi berdasarkan unique constraint
                    if (!Attendance::where('student_id', $student->id)
                        ->where('teaching_assignment_id', $ta->id)
                        ->whereDate('date', $date)
                        ->exists()) {
                        Attendance::factory()->create([
                            'student_id' => $student->id,
                            'teaching_assignment_id' => $ta->id,
                            'date' => $date,
                            'status' => $status,
                            'notes' => ($status != 'Hadir' ? fake()->sentence(3) : null),
                            'recorded_by_teacher_id' => $recordedByTeacher->id,
                        ]);
                        $seededCount++;
                    }
                }
            }
            if ($seededCount >= 100) break; // Batasi total absensi untuk demo
        }
        $this->command->info('Attendance records seeded: ' . $seededCount);
    }
}
