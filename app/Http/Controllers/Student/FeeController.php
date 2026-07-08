<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\FeeInvoice;
use Illuminate\Support\Facades\Auth;

class FeeController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        return view('student.fees.index', [
            'invoices' => FeeInvoice::where('student_id', $student->id)
                ->latest()
                ->paginate(15),
        ]);
    }

    public function show(FeeInvoice $feeInvoice)
    {
        abort_unless($feeInvoice->student_id === Auth::user()->student->id, 403);

        return view('student.fees.show', compact('feeInvoice'));
    }
}