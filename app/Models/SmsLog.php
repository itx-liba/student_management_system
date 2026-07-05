<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    protected $fillable = ['student_id', 'phone', 'message', 'type', 'status', 'sent_at'];
}

