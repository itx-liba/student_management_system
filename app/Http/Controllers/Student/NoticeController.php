<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Support\Facades\Auth;

class NoticeController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        $notices = Notice::where(function ($query) use ($student) {
                $query->whereIn('audience', ['all', 'students'])
                    ->orWhere(function ($q) use ($student) {
                        $q->where('audience', 'class')->where('class_id', $student->class_id);
                    });
            })
            ->latest()
            ->paginate(15);

        return view('student.notices.index', compact('notices'));
    }

    public function show(Notice $notice)
    {
        return view('student.notices.show', compact('notice'));
    }
}