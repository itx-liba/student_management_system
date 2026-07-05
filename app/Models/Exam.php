<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = ['name', 'class_id', 'start_date', 'end_date', 'is_published'];
}
