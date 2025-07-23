<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Assignment;
use App\Models\Submission;
use Carbon\Carbon;
use App\Models\Notification;

class CheckUngradedSubmissions extends Command
{
    protected $signature = 'submissions:check-ungraded';
    protected $description = 'Checks for ungraded submissions after their due date and notifies relevant teachers.';

    public function handle()
    {
        $this->info('Mulai pengecekan tugas yang belum dinilai...');

        // --- DEBUG POINT 1 ---
        // Cek waktu saat ini yang digunakan Carbon
        // Uncomment baris di bawah ini, jalankan command, dan lihat outputnya.
        // dd('Waktu saat ini:', Carbon::now()->toDateTimeString());


        // Ambil semua tugas yang sudah melewati due_date DAN belum dikirim notifikasi
        $overdueAssignments = Assignment::where('due_date', '<', Carbon::now())
            ->where('is_graded_notification_sent', false)
            ->get();

        // Cek apakah ada tugas yang ditemukan oleh query di atas
        // Uncomment baris di bawah ini, jalankan command, dan lihat outputnya.
        // Jika kosong, berarti tidak ada tugas yang memenuhi kriteria (due_date di masa lalu DAN is_graded_notification_sent = false).
        // dd('Tugas jatuh tempo yang ditemukan:', $overdueAssignments->pluck('title', 'id', 'due_date', 'is_graded_notification_sent'));


        if ($overdueAssignments->isEmpty()) {
            $this->info('Tidak ada tugas jatuh tempo yang belum dikirim notifikasi.');
            return Command::SUCCESS;
        }

        $notifiedTeachersCount = 0;
        // $notifiedAssignmentsCount = 0; // Variabel ini tidak digunakan, bisa dihapus

        foreach ($overdueAssignments as $assignment) {
            $this->info("Memproses tugas: {$assignment->title} (ID: {$assignment->id})");

            // --- DEBUG POINT 3 ---
            // Cek pengumpulan untuk tugas ini yang sudah diserahkan tapi BELUM dinilai
            $ungradedSubmissions = Submission::where('assignment_id', $assignment->id)
                ->whereNotNull('submission_date') // Sudah diserahkan
                ->whereNull('score')         // Belum dinilai
                ->get();

            // Uncomment baris di bawah ini, jalankan command, dan lihat outputnya.
            // Jika kosong, berarti siswa belum mengumpulkan atau sudah dinilai.
            // dd("Pengumpulan belum dinilai untuk tugas '{$assignment->title}':", $ungradedSubmissions->pluck('id', 'student_id', 'grade', 'submitted_at'));


            if ($ungradedSubmissions->isNotEmpty()) {
                $this->info("Ditemukan " . $ungradedSubmissions->count() . " pengumpulan belum dinilai untuk tugas: {$assignment->title}");

                // --- DEBUG POINT 4 ---
                // Cek apakah guru terkait ditemukan
                $teacher = $assignment->teachingAssignment->teacher;
                // Uncomment baris di bawah ini, jalankan command, dan lihat outputnya.
                // Jika null, berarti relasi guru tidak terhubung dengan benar ke tugas.
                // dd('Guru terkait:', $teacher ? $teacher->name : 'Tidak ditemukan');


                if ($teacher) {
                    $this->info("Guru terkait: {$teacher->name} (ID: {$teacher->id})");

                    // Cek apakah notifikasi serupa sudah ada untuk tugas ini dan guru ini
                    $existingNotification = Notification::where('user_id', $teacher->id)
                        ->where('type', 'ungraded_submission')
                        ->where('related_id', $assignment->id)
                        ->whereNull('read_at')
                        ->first();

                    if (!$existingNotification) {
                        // --- DEBUG POINT 6 ---
                        // Cek data notifikasi sebelum disimpan
                        $notification = new Notification();
                        $notification->user_id = $teacher->id;
                        $notification->type = 'ungraded_submission';
                        $notification->title = 'Tugas Belum Dinilai: ' . $assignment->title;
                        $notification->message = 'Ada ' . $ungradedSubmissions->count() . ' pengumpulan tugas "' . $assignment->title . '" di kelas ' . $assignment->teachingAssignment->schoolClass->name . ' yang belum Anda nilai. Batas waktu: ' . Carbon::parse($assignment->due_date)->format('d M Y H:i');
                        $notification->link = route('admin.assignments.show', $assignment->id); // <-- UBAH INI

                        // Uncomment baris di bawah ini, jalankan command, dan lihat outputnya.
                        // dd('Data notifikasi yang akan disimpan:', $notification->toArray());

                        $notification->save();

                        $this->info("Notifikasi dikirim ke Guru: {$teacher->name} untuk tugas: {$assignment->title}");
                        $notifiedTeachersCount++;
                    } else {
                        $this->info("Notifikasi sudah ada untuk Guru: {$teacher->name} dan tugas: {$assignment->title}. Melewatkan.");
                    }
                } else {
                    $this->warn("Tidak ditemukan guru untuk tugas: {$assignment->title}. Pastikan relasi teachingAssignment dan teacher sudah benar.");
                }
            } else {
                $this->info("Tugas '{$assignment->title}' tidak memiliki pengumpulan yang belum dinilai. (Mungkin belum dikumpul atau sudah dinilai semua)");
                // Untuk pengujian, jangan aktifkan baris ini dulu agar bisa diulang
                $assignment->is_graded_notification_sent = true;
                $assignment->save();
            }
        }

        $this->info('Pengecekan tugas yang belum dinilai selesai. Total guru dinotifikasi: ' . $notifiedTeachersCount);
        return Command::SUCCESS;
    }
}
