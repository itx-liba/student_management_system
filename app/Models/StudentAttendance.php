<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    protected $fillable = ['student_id', 'class_id', 'section_id', 'attendance_date', 'status', 'marked_by', 'remarks'];
    public function student()
{
    return $this->belongsTo(Student::class);
}
    }

