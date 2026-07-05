<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamMark extends Model
{
    protected $fillable = ['exam_id', 'student_id', 'subject_id', 'total_marks', 'obtained_marks', 'remarks'];
}

