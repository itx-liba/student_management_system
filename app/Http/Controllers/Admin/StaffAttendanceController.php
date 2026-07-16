<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffAttendance;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StaffAttendanceController extends Controller
{
    public function index()
{
    return view('admin.staff-attendance.index', [
        'rows' => StaffAttendance::with('teacher.user')->latest()->paginate(15),
        'teachers' => Teacher::with('user')->get(),
    ]);
}

    public function create()
    {
        return view('admin.staff-attendance.create', [
            'teachers' => Teacher::with('user')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'teacher_id' => ['required', 'exists:teachers,id'],
            'attendance_date' => ['required', 'date'],
            'check_in' => ['nullable'],
            'check_out' => ['nullable'],
            'status' => ['required', 'in:present,late,half_day,casual_leave,medical_leave,without_pay_leave,absent'],
            'remarks' => ['nullable', 'max:255'],
        ]);

        if (!empty($data['check_in'])) {
            $official = Carbon::parse('08:00');
            $checkIn = Carbon::parse($data['check_in']);
            $data['late_minutes'] = $checkIn->greaterThan($official)
                ? $official->diffInMinutes($checkIn)
                : 0;
        }

        StaffAttendance::updateOrCreate(
            [
                'teacher_id' => $data['teacher_id'],
                'attendance_date' => $data['attendance_date'],
            ],
            $data
        );

        return redirect()->route('admin.staff-attendance.index')->with('success', 'Staff attendance saved.');
    }

    public function show(StaffAttendance $staffAttendance)
    {
        return view('admin.staff-attendance.show', compact('staffAttendance'));
    }

    public function edit(StaffAttendance $staffAttendance)
    {
        return view('admin.staff-attendance.edit', [
            'staffAttendance' => $staffAttendance,
            'teachers' => Teacher::with('user')->get(),
        ]);
    }

    public function update(Request $request, StaffAttendance $staffAttendance)
    {
        $staffAttendance->update($request->validate([
            'check_in' => ['nullable'],
            'check_out' => ['nullable'],
            'status' => ['required', 'in:present,late,half_day,casual_leave,medical_leave,without_pay_leave,absent'],
            'remarks' => ['nullable', 'max:255'],
        ]));

        return redirect()->route('admin.staff-attendance.index')->with('success', 'Staff attendance updated.');
    }

    public function destroy(StaffAttendance $staffAttendance)
    {
        $staffAttendance->delete();
        return redirect()->route('admin.staff-attendance.index')->with('success', 'Staff attendance deleted.');
    }
}