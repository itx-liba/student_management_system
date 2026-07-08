<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TeacherTask;
use Illuminate\Http\Request;

class TeacherTaskController extends Controller
{
    public function index()
    {
        return view('admin.teacher-tasks.index', [
            'tasks' => TeacherTask::latest()->paginate(15),
        ]);
    }

    public function create()
    {
        return view('admin.teacher-tasks.create', [
            'teachers' => Teacher::with('user')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'teacher_id' => ['required', 'exists:teachers,id'],
            'title' => ['required', 'max:200'],
            'description' => ['nullable'],
            'deadline_at' => ['required', 'date'],
            'priority' => ['required', 'in:low,medium,high'],
        ]);

        $data['assigned_by'] = auth()->id();

        TeacherTask::create($data);

        return redirect()->route('admin.teacher-tasks.index')->with('success', 'Task assigned.');
    }

    public function show(TeacherTask $teacherTask)
    {
        return view('admin.teacher-tasks.show', compact('teacherTask'));
    }

    public function edit(TeacherTask $teacherTask)
    {
        return view('admin.teacher-tasks.edit', [
            'teacherTask' => $teacherTask,
            'teachers' => Teacher::with('user')->get(),
        ]);
    }

    public function update(Request $request, TeacherTask $teacherTask)
    {
        $data = $request->validate([
            'teacher_id' => ['required', 'exists:teachers,id'],
            'title' => ['required', 'max:200'],
            'description' => ['nullable'],
            'deadline_at' => ['required', 'date'],
            'priority' => ['required', 'in:low,medium,high'],
            'status' => ['required', 'in:pending,in_progress,completed'],
            'completion_note' => ['nullable'],
        ]);

        $data['completed_at'] = $data['status'] === 'completed' ? now() : null;

        $teacherTask->update($data);

        return redirect()->route('admin.teacher-tasks.index')->with('success', 'Task updated.');
    }

    public function destroy(TeacherTask $teacherTask)
    {
        $teacherTask->delete();
        return redirect()->route('admin.teacher-tasks.index')->with('success', 'Task deleted.');
    }
}