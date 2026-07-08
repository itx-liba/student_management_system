<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeInvoice;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\StudentClass;
use App\Models\Teacher;
use App\Models\TeacherTask;

class DashboardController extends Controller
{
    public function index()
    {
        $attendanceTotal = StudentAttendance::count();
        $presentTotal = StudentAttendance::where('status', 'present')->count();

        return view('admin.dashboard', [
            'totalStudents' => Student::count(),
            'totalTeachers' => Teacher::count(),
            'totalClasses' => StudentClass::count(),
            'attendancePercent' => $attendanceTotal ? round(($presentTotal / $attendanceTotal) * 100, 1) : 0,
            'pendingFees' => FeeInvoice::whereIn('status', ['unpaid', 'partial'])
                ->get()
                ->sum(fn ($invoice) => $invoice->remaining()),
            'recentStudents' => Student::latest()->take(5)->get(),
            'pendingTasks' => TeacherTask::whereIn('status', ['pending', 'in_progress'])->count(),
            'overdueTasks' => TeacherTask::where('deadline_at', '<', now())
                ->where('status', '!=', 'completed')
                ->count(),
        ]);
    }
}