<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['class_id', 'name'];

    public function class()
    {
        return $this->belongsTo(StudentClass::class, 'class_id');
    }
}

