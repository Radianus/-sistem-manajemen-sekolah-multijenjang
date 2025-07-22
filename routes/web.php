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
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;

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

Route::get('/', function () {
    return view('welcome');
});

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
});

require __DIR__ . '/auth.php';
