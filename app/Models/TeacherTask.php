<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherTask extends Model
{
    protected $fillable = [
        'teacher_id',
        'title',
        'description',
        'deadline_at',
        'priority',
        'status',
        'completion_note',
        'completed_at',
        'assigned_by',
    ];

    protected $casts = [
        'deadline_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}