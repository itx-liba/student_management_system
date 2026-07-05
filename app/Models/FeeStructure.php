<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    protected $fillable = [
        'class_id',
        'title',
        'amount',
        'due_day',
    ];

    public function class()
    {
        return $this->belongsTo(StudentClass::class, 'class_id');
    }
}