<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;

        $classSections = Timetable::where('teacher_id', $teacher->id)
            ->get(['class_id', 'section_id'])
            ->unique(fn ($row) => $row->class_id . '-' . $row->section_id)
            ->values();

        return view('teacher.attendance.index', compact('classSections'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'section_id' => ['required', 'exists:sections,id'],
        ]);

        $students = Student::where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->orderBy('roll_no')
            ->get();

        return view('teacher.attendance.create', [
            'students' => $students,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'attendance_date' => ['required', 'date'],
            'students' => ['required', 'array'],
            'students.*.student_id' => ['required', 'exists:students,id'],
            'students.*.status' => ['required', 'in:present,absent,late,leave'],
        ]);

        foreach ($data['students'] as $row) {
            StudentAttendance::updateOrCreate(
                [
                    'student_id' => $row['student_id'],
                    'attendance_date' => $data['attendance_date'],
                ],
                [
                    'status' => $row['status'],
                ]
            );
        }

        return redirect()->route('teacher.attendance.index')->with('success', 'Attendance saved.');
    }
}