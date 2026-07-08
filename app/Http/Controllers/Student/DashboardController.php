<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\FeeInvoice;
use App\Models\Notice;
use App\Models\StudentAttendance;
use App\Models\Timetable;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        $attendanceTotal = StudentAttendance::where('student_id', $student->id)->count();
        $presentTotal = StudentAttendance::where('student_id', $student->id)
            ->where('status', 'present')
            ->count();

        $pendingFees = FeeInvoice::where('student_id', $student->id)
            ->whereIn('status', ['unpaid', 'partial'])
            ->get()
            ->sum(fn ($invoice) => $invoice->remaining());

        $todayTimetable = Timetable::with(['subject', 'teacher.user'])
            ->where('class_id', $student->class_id)
            ->where('section_id', $student->section_id)
            ->where('day_name', now()->format('l'))
            ->orderBy('start_time')
            ->get();

        $notices = Notice::where(function ($query) use ($student) {
                $query->whereIn('audience', ['all', 'students'])
                    ->orWhere(function ($q) use ($student) {
                        $q->where('audience', 'class')->where('class_id', $student->class_id);
                    });
            })
            ->latest()
            ->take(5)
            ->get();

        return view('student.dashboard', [
            'student' => $student,
            'attendancePercent' => $attendanceTotal ? round(($presentTotal / $attendanceTotal) * 100, 1) : 0,
            'pendingFees' => $pendingFees,
            'todayTimetable' => $todayTimetable,
            'notices' => $notices,
        ]);
    }
}