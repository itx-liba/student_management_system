<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionApplication extends Model
{
    protected $fillable = ['name', 'father_name', 'b_form', 'phone', 'previous_school', 'desired_class_id', 'address', 'status'];
}
