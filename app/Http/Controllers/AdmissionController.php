<?php

namespace App\Http\Controllers;

use App\Models\AdmissionApplication;
use App\Models\StudentClass;
use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    public function create()
    {
        return view('admission.create', ['classes' => StudentClass::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'father_name' => ['required', 'string', 'max:150'],
            'b_form' => ['nullable', 'string', 'max:50'],
            'phone' => ['required', 'string', 'max:30'],
            'previous_school' => ['nullable', 'string', 'max:150'],
            'desired_class_id' => ['required', 'exists:classes,id'],
            'address' => ['nullable', 'string'],
        ]);

        AdmissionApplication::create($data);

        return redirect()->route('admission.create')->with('success', 'Admission form submitted successfully.');
    }
}

