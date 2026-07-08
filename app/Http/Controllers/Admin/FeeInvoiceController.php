<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeInvoice;
use App\Models\Student;
use Illuminate\Http\Request;

class FeeInvoiceController extends Controller
{
    public function index()
    {
        return view('admin.fee-invoices.index', [
            'feeInvoices' => FeeInvoice::with('student')->latest()->paginate(15),
        ]);
    }

    public function create()
    {
        return view('admin.fee-invoices.create', [
            'students' => Student::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'month' => ['required', 'max:20'],
            'year' => ['required', 'integer'],
            'amount' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'fine' => ['nullable', 'numeric', 'min:0'],
            'due_date' => ['required', 'date'],
        ]);

        $data['invoice_no'] = 'INV-' . now()->format('YmdHis');
        $data['discount'] = $data['discount'] ?? 0;
        $data['fine'] = $data['fine'] ?? 0;

        FeeInvoice::create($data);

        return redirect()->route('admin.fee-invoices.index')->with('success', 'Invoice created.');
    }

    public function show(FeeInvoice $feeInvoice)
    {
        return view('admin.fee-invoices.show', compact('feeInvoice'));
    }

    public function edit(FeeInvoice $feeInvoice)
    {
        return view('admin.fee-invoices.edit', [
            'feeInvoice' => $feeInvoice,
            'students' => Student::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, FeeInvoice $feeInvoice)
    {
        $feeInvoice->update($request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'month' => ['required', 'max:20'],
            'year' => ['required', 'integer'],
            'amount' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'fine' => ['nullable', 'numeric', 'min:0'],
            'due_date' => ['required', 'date'],
            'status' => ['required', 'in:unpaid,partial,paid'],
        ]));

        return redirect()->route('admin.fee-invoices.index')->with('success', 'Invoice updated.');
    }

    public function destroy(FeeInvoice $feeInvoice)
    {
        $feeInvoice->delete();
        return redirect()->route('admin.fee-invoices.index')->with('success', 'Invoice deleted.');
    }
}