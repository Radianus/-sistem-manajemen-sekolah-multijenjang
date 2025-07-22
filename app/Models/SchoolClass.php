<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model // Pastikan namanya SchoolClass
{
    use HasFactory;

    // Pastikan nama tabelnya 'classes' karena Laravel akan mencari 'school_classes' jika tidak dispesifikasi
    protected $table = 'classes'; // <-- TAMBAHKAN BARIS INI

    protected $fillable = [
        'name',
        'level',
        'grade_level',
        'academic_year',
        'homeroom_teacher_id',
    ];

    /**
     * Get the homeroom teacher for the class.
     */
    public function homeroomTeacher()
    {
        return $this->belongsTo(User::class, 'homeroom_teacher_id');
    }


    public function teachingAssignments()
    {
        return $this->hasMany(TeachingAssignment::class, 'school_class_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'school_class_id');
    }
}
