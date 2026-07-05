<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyMaterial extends Model
{
    protected $fillable = ['class_id', 'section_id', 'subject_id', 'uploaded_by', 'title', 'file_path'];
}

