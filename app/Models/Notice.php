<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = ['title', 'body', 'audience', 'class_id', 'section_id', 'published_by'];
}

