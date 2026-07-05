<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeRule extends Model
{
    protected $fillable = [
        'grade',
        'min_percentage',
        'max_percentage',
        'remarks',
    ];
}