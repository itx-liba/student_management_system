<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\StaffAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Auth::user()->teacher;

        $query = StaffAttendance::where('teacher_id', $teacher->id);

        if ($request->filled('month') && $request->filled('year')) {
            $query->whereMonth('attendance_date', $request->month)
                ->whereYear('attendance_date', $request->year);
        }

        return view('teacher.staff-attendance.index', [
            'records' => $query->latest('attendance_date')->paginate(30),
        ]);
    }

    public function show(StaffAttendance $staffAttendance)
    {
        abort_unless($staffAttendance->teacher_id === Auth::user()->teacher->id, 403);

        return view('teacher.staff-attendance.show', compact('staffAttendance'));
    }
}