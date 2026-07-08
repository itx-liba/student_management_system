<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index()
    {
        return view('admin.students.index', [
            'students' => Student::with(['class', 'section'])->latest()->paginate(15),
        ]);
    }

    public function create()
    {
        return view('admin.students.create', [
            'classes' => StudentClass::orderBy('name')->get(),
            'sections' => Section::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'unique:users,email'],
            'password' => ['nullable', 'min:6'],
            'class_id' => ['required', 'exists:classes,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'roll_no' => ['required', 'string', 'max:50'],
            'father_name' => ['required', 'string', 'max:150'],
            'b_form' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:30'],
            'parent_phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'gender' => ['nullable', 'in:male,female,other'],
            'date_of_birth' => ['nullable', 'date'],
            'admission_date' => ['nullable', 'date'],
            'status' => ['nullable', 'in:active,inactive,left'],
        ]);

        if (!empty($data['email'])) {
            $roleId = Role::where('name', 'student')->value('id');

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password'] ?? 'password'),
                'role_id' => $roleId,
                'status' => 'active',
            ]);

            $data['user_id'] = $user->id;
        }

        unset($data['email'], $data['password']);

        Student::create($data);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student created successfully.');
    }

    public function show(Student $student)
    {
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        return view('admin.students.edit', [
            'student' => $student,
            'classes' => StudentClass::orderBy('name')->get(),
            'sections' => Section::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'class_id' => ['required', 'exists:classes,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'roll_no' => ['required', 'string', 'max:50'],
            'father_name' => ['required', 'string', 'max:150'],
            'b_form' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:30'],
            'parent_phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'gender' => ['nullable', 'in:male,female,other'],
            'date_of_birth' => ['nullable', 'date'],
            'admission_date' => ['nullable', 'date'],
            'status' => ['nullable', 'in:active,inactive,left'],
        ]);

        $student->update($data);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->user?->delete();
        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
    }
}