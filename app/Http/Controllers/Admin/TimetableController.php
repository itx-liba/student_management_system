<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\StudentClass;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Timetable;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    public function index()
{
    return view('admin.timetables.index', [
        'timetables' => Timetable::with(['class', 'section', 'subject', 'teacher.user'])->latest()->paginate(15),
        'classes' => StudentClass::orderBy('name')->get(),
        'sections' => Section::orderBy('name')->get(),
        'subjects' => Subject::orderBy('name')->get(),
        'teachers' => Teacher::with('user')->get(),
    ]);
}

    public function create()
    {
        return view('admin.timetables.create', [
            'classes' => StudentClass::orderBy('name')->get(),
            'sections' => Section::orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
            'teachers' => Teacher::with('user')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Timetable::create($request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'day_name' => ['required', 'max:20'],
            'start_time' => ['required'],
            'end_time' => ['required'],
        ]));

        return redirect()->route('admin.timetables.index')->with('success', 'Timetable created.');
    }

    public function show(Timetable $timetable)
    {
        return view('admin.timetables.show', compact('timetable'));
    }

    public function edit(Timetable $timetable)
    {
        return view('admin.timetables.edit', [
            'timetable' => $timetable,
            'classes' => StudentClass::orderBy('name')->get(),
            'sections' => Section::orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
            'teachers' => Teacher::with('user')->get(),
        ]);
    }

    public function update(Request $request, Timetable $timetable)
    {
        $timetable->update($request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'day_name' => ['required', 'max:20'],
            'start_time' => ['required'],
            'end_time' => ['required'],
        ]));

        return redirect()->route('admin.timetables.index')->with('success', 'Timetable updated.');
    }

    public function destroy(Timetable $timetable)
    {
        $timetable->delete();
        return redirect()->route('admin.timetables.index')->with('success', 'Timetable deleted.');
    }
}