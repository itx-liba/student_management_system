<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $student = Auth::user()->student;

        $query = StudentAttendance::where('student_id', $student->id);

        if ($request->filled('month') && $request->filled('year')) {
            $query->whereMonth('attendance_date', $request->month)
                ->whereYear('attendance_date', $request->year);
        }

        $total = (clone $query)->count();
        $present = (clone $query)->where('status', 'present')->count();

        return view('student.attendance.index', [
            'records' => $query->latest('attendance_date')->paginate(30),
            'attendancePercent' => $total ? round(($present / $total) * 100, 1) : 0,
        ]);
    }
}