<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'teaching_assignment_id',
        'due_date',
        'max_score',
        'file_path',
        'assigned_by_user_id',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'max_score' => 'decimal:2',
    ];

    /**
     * Get the teaching assignment associated with the assignment.
     */
    public function teachingAssignment()
    {
        return $this->belongsTo(TeachingAssignment::class);
    }

    /**
     * Get the user who assigned the assignment.
     */
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by_user_id');
    }

    /**
     * Get the submissions for the assignment.
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    /**
     * Check if the assignment is overdue.
     */
    public function isOverdue()
    {
        return $this->due_date && $this->due_date->isPast();
    }
}