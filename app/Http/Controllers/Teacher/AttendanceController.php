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
    public function index(Request $request)
    {
        $teacher = Auth::user()->teacher;

        $classSections = Timetable::with(['class', 'section'])
            ->where('teacher_id', $teacher->id)
            ->get(['class_id', 'section_id'])
            ->unique(fn ($row) => $row->class_id . '-' . $row->section_id)
            ->values();

        $students = null;
        $existingAttendance = collect();
        $date = $request->get('attendance_date', now()->toDateString());

        if ($request->filled('class_id') && $request->filled('section_id')) {
            $students = Student::where('class_id', $request->class_id)
                ->where('section_id', $request->section_id)
                ->orderBy('roll_no')
                ->get();

            $existingAttendance = StudentAttendance::whereIn('student_id', $students->pluck('id'))
                ->whereDate('attendance_date', $date)
                ->get()
                ->keyBy('student_id');
        }

        return view('teacher.attendance.index', [
            'classSections' => $classSections,
            'students' => $students,
            'existingAttendance' => $existingAttendance,
            'selectedClassId' => $request->get('class_id'),
            'selectedSectionId' => $request->get('section_id'),
            'date' => $date,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'attendance_date' => ['required', 'date'],
            'class_id' => ['required', 'exists:classes,id'],
            'section_id' => ['required', 'exists:sections,id'],
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
                    'class_id' => $data['class_id'],
                    'section_id' => $data['section_id'],
                    'status' => $row['status'],
                    'marked_by' => Auth::id(),
                ]
            );
        }

        return redirect()->route('teacher.attendance.index', [
            'class_id' => $data['class_id'],
            'section_id' => $data['section_id'],
            'attendance_date' => $data['attendance_date'],
        ])->with('success', 'Attendance saved.');
    }
}