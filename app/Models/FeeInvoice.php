<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeInvoice extends Model
{
    protected $fillable = ['student_id', 'invoice_no', 'month', 'year', 'amount', 'discount', 'fine', 'paid_amount', 'due_date', 'status'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function payable()
    {
        return $this->amount + $this->fine - $this->discount;
    }

    public function remaining()
    {
        return $this->payable() - $this->paid_amount;
    }
}

