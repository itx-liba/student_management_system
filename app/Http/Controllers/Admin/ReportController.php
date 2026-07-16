<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeInvoice;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\Teacher;
use App\Models\TeacherTask;

class ReportController extends Controller
{
    public function index()
    {
        $attendanceTotal = StudentAttendance::count();
        $presentTotal = StudentAttendance::where('status', 'present')->count();

        return view('admin.reports.index', [
            'totalStudents' => Student::count(),
            'totalTeachers' => Teacher::count(),
            'attendancePercent' => $attendanceTotal
                ? round(($presentTotal / $attendanceTotal) * 100, 2)
                : 0,
            'pendingFees' => FeeInvoice::whereIn('status', ['unpaid', 'partial'])
                ->get()
                ->sum(fn ($invoice) => $invoice->remaining()),
            'paidFees' => FeeInvoice::where('status', 'paid')->sum('paid_amount'),
            'overdueTasks' => TeacherTask::where('deadline_at', '<', now())
                ->where('status', '!=', 'completed')
                ->count(),
        ]);
    }

    public function attendance()
    {
        return view('admin.reports.attendance', [
            'rows' => StudentAttendance::latest()->paginate(30),
        ]);
    }

    public function fees()
    {
        return view('admin.reports.fees', [
            'invoices' => FeeInvoice::with('student')->latest()->paginate(30),
        ]);
    }

    public function tasks()
{
    return view('admin.reports.tasks', [
        'tasks' => TeacherTask::with('teacher.user')->latest()->paginate(30),
    ]);
}
}