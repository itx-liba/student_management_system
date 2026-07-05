<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeePayment extends Model
{
    protected $fillable = ['fee_invoice_id', 'payment_date', 'amount', 'payment_method', 'received_by'];
}

