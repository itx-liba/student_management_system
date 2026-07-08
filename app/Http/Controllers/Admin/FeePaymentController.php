<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeInvoice;
use App\Models\FeePayment;
use Illuminate\Http\Request;

class FeePaymentController extends Controller
{
    public function index()
    {
        return view('admin.fee-payments.index', [
            'feePayments' => FeePayment::latest()->paginate(15),
        ]);
    }

    public function create()
    {
        return view('admin.fee-payments.create', [
            'invoices' => FeeInvoice::with('student')->whereIn('status', ['unpaid', 'partial'])->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fee_invoice_id' => ['required', 'exists:fee_invoices,id'],
            'payment_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:1'],
            'payment_method' => ['required', 'max:50'],
        ]);

        $invoice = FeeInvoice::findOrFail($data['fee_invoice_id']);

        FeePayment::create([
            'fee_invoice_id' => $invoice->id,
            'payment_date' => $data['payment_date'],
            'amount' => $data['amount'],
            'payment_method' => $data['payment_method'],
            'received_by' => auth()->id(),
        ]);

        $invoice->paid_amount += $data['amount'];
        $payable = $invoice->amount + $invoice->fine - $invoice->discount;
        $invoice->status = $invoice->paid_amount >= $payable ? 'paid' : 'partial';
        $invoice->save();

        return redirect()->route('admin.fee-payments.index')->with('success', 'Payment saved.');
    }

    public function show(FeePayment $feePayment)
    {
        return view('admin.fee-payments.show', compact('feePayment'));
    }

    public function edit(FeePayment $feePayment)
    {
        return view('admin.fee-payments.edit', compact('feePayment'));
    }

    public function update(Request $request, FeePayment $feePayment)
    {
        $feePayment->update($request->validate([
            'payment_date' => ['required', 'date'],
            'payment_method' => ['required', 'max:50'],
        ]));

        return redirect()->route('admin.fee-payments.index')->with('success', 'Payment updated.');
    }

    public function destroy(FeePayment $feePayment)
    {
        $feePayment->delete();
        return redirect()->route('admin.fee-payments.index')->with('success', 'Payment deleted.');
    }
}