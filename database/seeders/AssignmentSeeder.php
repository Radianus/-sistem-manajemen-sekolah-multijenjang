<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Assignment;
use App\Models\TeachingAssignment;
use App\Models\User;
use Database\Factories\AssignmentFactory;
use Carbon\Carbon;

class AssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachingAssignments = TeachingAssignment::all();
        $adminOrGuruUsers = User::role(['admin_sekolah', 'guru'])->get();

        if ($teachingAssignments->isEmpty() || $adminOrGuruUsers->isEmpty()) {
            $this->command->info('Tidak ada penugasan mengajar atau admin/guru untuk seed assignments.');
            return;
        }

        // Ambil guru spesifik kita (guru@akademika.com)
        $guruSatu = User::where('email', 'guru@akademika.com')->first();
        $targetTeacherId = $guruSatu ? $guruSatu->id : $adminOrGuruUsers->random()->id;

        // Ambil penugasan spesifik yang dibuat di TeachingAssignmentSeeder untuk guruSatu
        $specificTeachingAssignment = TeachingAssignment::where('teacher_id', $targetTeacherId)->first();

        // Buat tugas yang DIBUAT OLEH guruSatu
        if ($specificTeachingAssignment && $guruSatu) {
            Assignment::firstOrCreate(
                [
                    'title' => 'Tugas Dibuat GuruSatu',
                    'teaching_assignment_id' => $specificTeachingAssignment->id,
                    'assigned_by_user_id' => $guruSatu->id, // Dibuat oleh Guru Satu
                ],
                [
                    'assignment_type' => 'Individu',
                    'description' => 'Tugas ini dibuat langsung oleh guru@akademika.com.',
                    'due_date' => Carbon::now()->addDays(5), // Masih akan datang
                    'max_score' => 100,
                    'is_graded_notification_sent' => false,
                ]
            );
            $this->command->info("Tugas 'Tugas Dibuat GuruSatu' dibuat.");
        }

        // Buat tugas yang terkait dengan PENUGASAN MENGAJAR GuruSatu (walaupun dibuat admin)
        if ($specificTeachingAssignment && $adminOrGuruUsers->count() > 1) { // Perlu admin/guru lain yang membuat
            $adminUser = User::where('email', 'admin@akademika.com')->first();
            $creatorId = $adminUser ? $adminUser->id : $adminOrGuruUsers->where('id', '!=', $targetTeacherId)->random()->id;

            Assignment::firstOrCreate(
                [
                    'title' => 'Tugas Diampu GuruSatu',
                    'teaching_assignment_id' => $specificTeachingAssignment->id, // Terkait TA Guru Satu
                    'assigned_by_user_id' => $creatorId, // Dibuat oleh admin/guru lain
                ],
                [
                    'assignment_type' => 'Proyek',
                    'description' => 'Tugas proyek yang diampu oleh guru@akademika.com.',
                    'due_date' => Carbon::now()->addDays(10),
                    'max_score' => 100,
                    'is_graded_notification_sent' => false,
                ]
            );
            $this->command->info("Tugas 'Tugas Diampu GuruSatu' dibuat.");
        }

        // Buat beberapa tugas spesifik yang sudah jatuh tempo dan perlu dinotifikasi
        // Contoh: Membuat 3 tugas yang due_date-nya di masa lalu
        for ($i = 0; $i < 3; $i++) {
            $ta = $teachingAssignments->random();
            $assignedBy = $adminOrGuruUsers->random();
            if ($ta && $assignedBy) {
                Assignment::firstOrCreate(
                    [
                        'title' => 'Tugas Segera Dinilai ' . ($i + 1),
                        'teaching_assignment_id' => $ta->id,
                        'assigned_by_user_id' => $assignedBy->id,
                    ],
                    [
                        'assignment_type' => fake()->randomElement(['Individu', 'Proyek']),
                        'description' => 'Ini tugas khusus yang jatuh tempo dan perlu dinilai.',
                        'due_date' => Carbon::now()->subDays(rand(1, 7))->subHours(rand(1, 23)), // Due date di masa lalu
                        'max_score' => 100,
                        'is_graded_notification_sent' => false,
                    ]
                );
            }
        }

        // Buat tugas acak lainnya
        Assignment::factory()->count(15)->create(); // Sisa tugas acak untuk mengisi daftar

        $this->command->info('Assignments seeded: ' . Assignment::count());
    }
}
