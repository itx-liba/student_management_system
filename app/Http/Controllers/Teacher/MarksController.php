<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamMark;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarksController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;

        $subjectIds = Timetable::where('teacher_id', $teacher->id)
            ->pluck('subject_id')
            ->unique();

        return view('teacher.marks.index', [
            'exams' => Exam::latest()->get(),
            'subjects' => Subject::whereIn('id', $subjectIds)->get(),
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'exam_id' => ['required', 'exists:exams,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
        ]);

        $exam = Exam::findOrFail($request->exam_id);

        $students = Student::where('class_id', $exam->class_id)
            ->orderBy('roll_no')
            ->get();

        $existingMarks = ExamMark::where('exam_id', $exam->id)
            ->where('subject_id', $request->subject_id)
            ->get()
            ->keyBy('student_id');

        return view('teacher.marks.create', [
            'exam' => $exam,
            'subject_id' => $request->subject_id,
            'students' => $students,
            'existingMarks' => $existingMarks,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'exam_id' => ['required', 'exists:exams,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'marks' => ['required', 'array'],
            'marks.*.student_id' => ['required', 'exists:students,id'],
            'marks.*.total_marks' => ['required', 'numeric', 'min:1'],
            'marks.*.obtained_marks' => ['required', 'numeric', 'min:0'],
            'marks.*.remarks' => ['nullable', 'max:255'],
        ]);

        foreach ($data['marks'] as $row) {
            ExamMark::updateOrCreate(
                [
                    'exam_id' => $data['exam_id'],
                    'student_id' => $row['student_id'],
                    'subject_id' => $data['subject_id'],
                ],
                [
                    'total_marks' => $row['total_marks'],
                    'obtained_marks' => $row['obtained_marks'],
                    'remarks' => $row['remarks'] ?? null,
                ]
            );
        }

        return back()->with('success', 'Marks saved.');
    }
}