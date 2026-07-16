<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\StaffAttendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class StaffAttendanceController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;

        $today = StaffAttendance::where('teacher_id', $teacher->id)
            ->whereDate('attendance_date', now()->toDateString())
            ->first();

        return view('teacher.staff-attendance.index', [
            'today' => $today,
            'records' => StaffAttendance::where('teacher_id', $teacher->id)
                ->latest('attendance_date')
                ->paginate(30),
        ]);
    }

    public function checkIn()
    {
        $teacher = Auth::user()->teacher;
        $now = Carbon::now();

        $official = Carbon::parse('08:00');
        $lateMinutes = $now->greaterThan($official) ? $official->diffInMinutes($now) : 0;

        StaffAttendance::updateOrCreate(
            [
                'teacher_id' => $teacher->id,
                'attendance_date' => $now->toDateString(),
            ],
            [
                'check_in' => $now->format('H:i:s'),
                'status' => $lateMinutes > 0 ? 'late' : 'present',
                'late_minutes' => $lateMinutes,
            ]
        );

        return back()->with('success', 'Checked in.');
    }

    public function checkOut()
    {
        $teacher = Auth::user()->teacher;
        $today = StaffAttendance::where('teacher_id', $teacher->id)
            ->whereDate('attendance_date', now()->toDateString())
            ->first();

        abort_unless($today, 422, 'You have not checked in today.');

        $today->update(['check_out' => now()->format('H:i:s')]);

        return back()->with('success', 'Checked out.');
    }
}