<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_class_id',
        'teaching_assignment_id',
        'day_of_week',
        'start_time',
        'end_time',
        'room_number',
        'academic_year',
    ];

    protected $casts = [
        'start_time' => 'datetime', // Cast ke Carbon untuk kemudahan format
        'end_time' => 'datetime',   // Cast ke Carbon untuk kemudahan format
    ];

    /**
     * Get the class associated with the schedule.
     */
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    /**
     * Get the teaching assignment associated with the schedule.
     */
    public function teachingAssignment()
    {
        return $this->belongsTo(TeachingAssignment::class);
    }
}
