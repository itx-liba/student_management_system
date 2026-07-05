<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherTask extends Model
{
    protected $fillable = ['teacher_id', 'assigned_by', 'title', 'description', 'deadline_at', 'priority', 'status', 'completed_at', 'completion_note'];
    protected $casts = ['deadline_at' => 'datetime', 'completed_at' => 'datetime'];
}

