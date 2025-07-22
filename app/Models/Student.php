<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'nis',
        'nisn',
        'gender',
        'date_of_birth',
        'address',
        'phone_number',
        'user_id',
        'school_class_id',
    ];

    /**
     * Get the user account associated with the student.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the class that the student belongs to.
     */
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }
}
