<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id', 'class_id', 'section_id', 'roll_no', 'name', 'father_name', 'b_form',
        'phone', 'parent_phone', 'address', 'gender', 'date_of_birth', 'admission_date', 'photo', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function class()
    {
        return $this->belongsTo(StudentClass::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}

