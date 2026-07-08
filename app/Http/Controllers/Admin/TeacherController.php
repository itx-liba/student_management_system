<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index()
    {
        return view('admin.teachers.index', [
            'teachers' => Teacher::with('user')->latest()->paginate(15),
        ]);
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['nullable', 'min:6'],
            'employee_no' => ['required', 'string', 'max:50', 'unique:teachers,employee_no'],
            'father_name' => ['nullable', 'string', 'max:150'],
            'cnic' => ['nullable', 'string', 'max:30'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'joining_date' => ['nullable', 'date'],
        ]);

        $roleId = Role::where('name', 'teacher')->value('id');

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'] ?? 'password'),
            'role_id' => $roleId,
            'status' => 'active',
        ]);

        Teacher::create([
            'user_id' => $user->id,
            'employee_no' => $data['employee_no'],
            'father_name' => $data['father_name'] ?? null,
            'cnic' => $data['cnic'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'joining_date' => $data['joining_date'] ?? null,
        ]);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

    public function show(Teacher $teacher)
    {
        return view('admin.teachers.show', compact('teacher'));
    }

    public function edit(Teacher $teacher)
    {
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $data = $request->validate([
            'employee_no' => ['required', 'string', 'max:50', 'unique:teachers,employee_no,' . $teacher->id],
            'father_name' => ['nullable', 'string', 'max:150'],
            'cnic' => ['nullable', 'string', 'max:30'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'joining_date' => ['nullable', 'date'],
        ]);

        $teacher->update($data);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->user?->delete();
        $teacher->delete();

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }
}