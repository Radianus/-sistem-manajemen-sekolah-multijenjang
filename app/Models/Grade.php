<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'teaching_assignment_id',
        'score',
        'grade_type',
        'semester',
        'academic_year',
        'graded_by_teacher_id',
        'notes',
    ];

    /**
     * Get the student associated with the grade.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the teaching assignment associated with the grade.
     */
    public function teachingAssignment()
    {
        return $this->belongsTo(TeachingAssignment::class);
    }

    /**
     * Get the teacher who graded the assignment.
     */
    public function gradedByTeacher()
    {
        return $this->belongsTo(User::class, 'graded_by_teacher_id');
    }
}
