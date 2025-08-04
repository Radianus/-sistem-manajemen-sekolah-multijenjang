<?php

use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeachingAssignmentController;
use App\Http\Controllers\Admin\GradeController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\CalendarEventController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\HeroSliderController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\WebController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['web', 'forcelogout'])->group(function () {
    Route::get('/', fn() => view('welcome'));
});


Route::get('/', [WebController::class, 'home'])->name('web.home');
Route::get('/berita', [WebController::class, 'newsIndex'])->name('web.news.index');
Route::get('/berita/{slug}', [WebController::class, 'newsShow'])->name('web.news.show');
Route::get('/galeri', [WebController::class, 'galleryIndex'])->name('web.gallery.index'); // <-- TAMBAHKAN INI

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Notifikasi
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsReadBulk');
    Route::resource('messages', MessageController::class);
    Route::get('messages/{message}/reply', [MessageController::class, 'reply'])->name('messages.reply');
});



// ----------- ROUTE KHUSUS ADMIN SAJA -----------
Route::middleware(['auth', 'role:admin_sekolah'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('classes', ClassController::class);
    Route::resource('students', StudentController::class);
    Route::resource('subjects', SubjectController::class);
    Route::resource('teaching_assignments', TeachingAssignmentController::class)->parameters(['teaching_assignments' => 'assignment']);

    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

    Route::resource('news', NewsController::class);
    Route::resource('hero_sliders', HeroSliderController::class)->parameters(['hero_sliders' => 'slider']);
    Route::resource('galleries', GalleryController::class);
});

// ----------- ROUTE UNTUK ADMIN DAN GURU -----------
// Contoh: Guru bisa input nilai/absensi/jadwalnya sendiri, tapi admin juga bisa akses.
Route::middleware(['auth', 'role:admin_sekolah|guru|siswa|orang_tua'])->prefix('admin')->name('admin.')->group(function () {
    // Modul Manajemen Nilai
    Route::resource('grades', GradeController::class);
    // Modul Manajemen Absensi
    Route::resource('attendances', AttendanceController::class);
    // Modul Manajemen Jadwal Pelajaran
    Route::resource('schedules', ScheduleController::class);
    Route::resource('announcements', AnnouncementController::class);
    Route::resource('calendar_events', CalendarEventController::class);
    Route::get('reports/report-card-filter', [ReportController::class, 'showReportCardFilterForm'])->name('reports.reportCardFilterForm');
    Route::get('reports/report-card', [ReportController::class, 'generateReportCard'])->name('reports.generateReportCard');
    Route::get('reports/grade-summary-filter', [ReportController::class, 'showGradeSummaryFilterForm'])->name('reports.gradeSummaryFilterForm');
    Route::get('reports/grade-summary', [ReportController::class, 'generateGradeSummary'])->name('reports.generateGradeSummary');


    // Tugas (Assignments)
    Route::resource('assignments', AssignmentController::class);
    // Custom route untuk submit tugas
    Route::post('assignments/{assignment}/submit', [AssignmentController::class, 'submitAssignment'])->name('assignments.submit');
    // Custom route untuk form penilaian tugas
    Route::get('submissions/{submission}/grade', [AssignmentController::class, 'showSubmissionForGrading'])->name('submissions.grade');
    // Custom route untuk proses penilaian
    Route::put('submissions/{submission}/grade', [AssignmentController::class, 'gradeSubmission'])->name('submissions.update_grade');
});

require __DIR__ . '/auth.php';