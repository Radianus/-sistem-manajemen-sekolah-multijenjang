<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'student_id',
        'submission_date',
        'file_path',
        'content',
        'score',
        'feedback',
        'graded_by_user_id',
    ];

    protected $casts = [
        'submission_date' => 'datetime',
        'score' => 'decimal:2',
    ];

    /**
     * Get the assignment that the submission belongs to.
     */
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    /**
     * Get the student who made the submission.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the user who graded the submission.
     */
    public function gradedBy()
    {
        return $this->belongsTo(User::class, 'graded_by_user_id');
    }
}