<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffAttendance extends Model
{
    protected $fillable = ['teacher_id', 'attendance_date', 'check_in', 'check_out', 'status', 'late_minutes', 'remarks'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}