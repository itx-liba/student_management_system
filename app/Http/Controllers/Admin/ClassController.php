<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentClass;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        return view('admin.classes.index', [
            'classes' => StudentClass::latest()->paginate(15),
        ]);
    }

    public function create()
    {
        return view('admin.classes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:classes,name'],
        ]);

        StudentClass::create($data);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class created successfully.');
    }

    public function show(StudentClass $class)
    {
        return view('admin.classes.show', compact('class'));
    }

    public function edit(StudentClass $class)
    {
        return view('admin.classes.edit', compact('class'));
    }

    public function update(Request $request, StudentClass $class)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:classes,name,' . $class->id],
        ]);

        $class->update($data);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class updated successfully.');
    }

    public function destroy(StudentClass $class)
    {
        $class->delete();

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class deleted successfully.');
    }
}