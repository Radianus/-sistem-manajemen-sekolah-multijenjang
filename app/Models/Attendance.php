<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'teaching_assignment_id',
        'date',
        'status',
        'notes',
        'recorded_by_teacher_id',
    ];

    protected $casts = [
        'date' => 'date', // Otomatis cast ke objek Carbon
    ];

    /**
     * Get the student associated with the attendance record.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the teaching assignment associated with the attendance record.
     */
    public function teachingAssignment()
    {
        return $this->belongsTo(TeachingAssignment::class);
    }

    /**
     * Get the teacher who recorded the attendance.
     */
    public function recordedByTeacher()
    {
        return $this->belongsTo(User::class, 'recorded_by_teacher_id');
    }
}
