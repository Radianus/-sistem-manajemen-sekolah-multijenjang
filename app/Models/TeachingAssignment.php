<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeachingAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_class_id',
        'subject_id',
        'teacher_id',
        'academic_year',
    ];

    /**
     * Get the class associated with the teaching assignment.
     */
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    /**
     * Get the subject associated with the teaching assignment.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the teacher (user) associated with the teaching assignment.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
