<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\StaffAttendance;
use App\Models\TeacherTask;
use App\Models\Timetable;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
{

    $teacher = Auth::user()->teacher;

    $todayTimetable = Timetable::with(['subject', 'class', 'section'])
            ->where('teacher_id', $teacher->id)
            ->where('day_name', now()->format('l'))
            ->orderBy('start_time')
            ->get();

        return view('teacher.dashboard', [
            'teacher' => $teacher,
            'todayTimetable' => $todayTimetable,
            'pendingTasks' => TeacherTask::where('teacher_id', $teacher->id)
                ->whereIn('status', ['pending', 'in_progress'])
                ->count(),
            'overdueTasks' => TeacherTask::where('teacher_id', $teacher->id)
                ->where('deadline_at', '<', now())
                ->where('status', '!=', 'completed')
                ->count(),
            'recentAttendance' => StaffAttendance::where('teacher_id', $teacher->id)
                ->latest('attendance_date')
                ->take(5)
                ->get(),
        ]);
    }
}