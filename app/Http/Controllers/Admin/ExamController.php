<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamMark;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\Subject;
use Illuminate\Http\Request;

class ExamController extends Controller
{
   public function index()
{
    return view('admin.exams.index', [
        'exams' => Exam::with('class')->latest()->paginate(15),
        'classes' => StudentClass::orderBy('name')->get(),
    ]);
}

    public function create()
    {
        return view('admin.exams.create', [
            'classes' => StudentClass::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Exam::create($request->validate([
            'name' => ['required', 'max:150'],
            'class_id' => ['required', 'exists:classes,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'is_published' => ['nullable', 'boolean'],
        ]));

        return redirect()->route('admin.exams.index')->with('success', 'Exam created.');
    }

    public function show(Exam $exam)
    {
        return view('admin.exams.show', compact('exam'));
    }

    public function edit(Exam $exam)
    {
        return view('admin.exams.edit', [
            'exam' => $exam,
            'classes' => StudentClass::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Exam $exam)
    {
        $exam->update($request->validate([
            'name' => ['required', 'max:150'],
            'class_id' => ['required', 'exists:classes,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'is_published' => ['nullable', 'boolean'],
        ]));

        return redirect()->route('admin.exams.index')->with('success', 'Exam updated.');
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();
        return redirect()->route('admin.exams.index')->with('success', 'Exam deleted.');
    }

    public function marks()
    {
        return view('admin.exams.marks', [
            'exams' => Exam::latest()->get(),
            'students' => Student::orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
        ]);
    }

    public function storeMarks(Request $request)
    {
        $data = $request->validate([
            'exam_id' => ['required', 'exists:exams,id'],
            'student_id' => ['required', 'exists:students,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'total_marks' => ['required', 'numeric', 'min:1'],
            'obtained_marks' => ['required', 'numeric', 'min:0'],
            'remarks' => ['nullable', 'max:255'],
        ]);

        ExamMark::updateOrCreate(
            [
                'exam_id' => $data['exam_id'],
                'student_id' => $data['student_id'],
                'subject_id' => $data['subject_id'],
            ],
            $data
        );

        return back()->with('success', 'Marks saved.');
    }
}