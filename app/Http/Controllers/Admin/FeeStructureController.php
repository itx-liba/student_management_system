<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeStructure;
use App\Models\StudentClass;
use Illuminate\Http\Request;

class FeeStructureController extends Controller
{
    public function index()
    {
        return view('admin.fee-structures.index', [
            'feeStructures' => FeeStructure::latest()->paginate(15),
        ]);
    }

    public function create()
    {
        return view('admin.fee-structures.create', [
            'classes' => StudentClass::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        FeeStructure::create($request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'title' => ['required', 'max:150'],
            'amount' => ['required', 'numeric', 'min:0'],
            'due_day' => ['required', 'integer', 'min:1', 'max:31'],
        ]));

        return redirect()->route('admin.fee-structures.index')->with('success', 'Fee structure created.');
    }

    public function show(FeeStructure $feeStructure)
    {
        return view('admin.fee-structures.show', compact('feeStructure'));
    }

    public function edit(FeeStructure $feeStructure)
    {
        return view('admin.fee-structures.edit', [
            'feeStructure' => $feeStructure,
            'classes' => StudentClass::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, FeeStructure $feeStructure)
    {
        $feeStructure->update($request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'title' => ['required', 'max:150'],
            'amount' => ['required', 'numeric', 'min:0'],
            'due_day' => ['required', 'integer', 'min:1', 'max:31'],
        ]));

        return redirect()->route('admin.fee-structures.index')->with('success', 'Fee structure updated.');
    }

    public function destroy(FeeStructure $feeStructure)
    {
        $feeStructure->delete();
        return redirect()->route('admin.fee-structures.index')->with('success', 'Fee structure deleted.');
    }
}