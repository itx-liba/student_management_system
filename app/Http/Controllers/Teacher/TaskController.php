<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\TeacherTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;

        return view('teacher.tasks.index', [
            'tasks' => TeacherTask::where('teacher_id', $teacher->id)
                ->latest()
                ->paginate(15),
        ]);
    }

    public function show(TeacherTask $task)
    {
        abort_unless($task->teacher_id === Auth::user()->teacher->id, 403);

        return view('teacher.tasks.show', compact('task'));
    }

    public function update(Request $request, TeacherTask $task)
    {
        abort_unless($task->teacher_id === Auth::user()->teacher->id, 403);

        $data = $request->validate([
            'status' => ['required', 'in:pending,in_progress,completed'],
            'completion_note' => ['nullable'],
        ]);

        $data['completed_at'] = $data['status'] === 'completed' ? now() : null;

        $task->update($data);

        return redirect()->route('teacher.tasks.index')->with('success', 'Task updated.');
    }
}