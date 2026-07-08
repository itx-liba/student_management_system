<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Timetable;
use Illuminate\Support\Facades\Auth;

class TimetableController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        $timetable = Timetable::with(['subject', 'teacher.user'])
            ->where('class_id', $student->class_id)
            ->where('section_id', $student->section_id)
            ->orderBy('day_name')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_name');

        return view('student.timetable.index', compact('timetable'));
    }
}