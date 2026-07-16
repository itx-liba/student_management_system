<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionApplication;
use Illuminate\Http\Request;

class AdmissionApplicationController extends Controller
{
    public function index()
    {
        return view('admin.admissions.index', [
            'admissions' => AdmissionApplication::latest()->paginate(15),
        ]);
    }

    public function create()
    {
        return redirect()->route('admission.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('admission.create');
    }

    public function show(AdmissionApplication $admission)
    {
        return view('admin.admissions.show', compact('admission'));
    }

    public function edit(AdmissionApplication $admission)
    {
        return view('admin.admissions.edit', compact('admission'));
    }

    public function update(Request $request, AdmissionApplication $admission)
    {
        $admission->update($request->validate([
            'status' => ['required', 'in:pending,approved,rejected'],
        ]));

        return redirect()->route('admin.admissions.index')->with('success', 'Application updated.');
    }

    public function destroy(AdmissionApplication $admission)
    {
        $admission->delete();
        return redirect()->route('admin.admissions.index')->with('success', 'Application deleted.');
    }

    public function desiredClass()
{
    return $this->belongsTo(StudentClass::class, 'desired_class_id');
}
}