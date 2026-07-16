<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamMark;
use App\Models\GradeRule;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        $exams = Exam::where('class_id', $student->class_id)
            ->where('is_published', true)
            ->latest()
            ->get();

        $results = $exams->map(function ($exam) use ($student) {
            $marks = ExamMark::with('subject')
                ->where('exam_id', $exam->id)
                ->where('student_id', $student->id)
                ->get();

            $totalObtained = $marks->sum('obtained_marks');
            $totalMax = $marks->sum('total_marks');
            $percentage = $totalMax ? round(($totalObtained / $totalMax) * 100, 2) : 0;

            $grade = GradeRule::where('min_percentage', '<=', $percentage)
                ->where('max_percentage', '>=', $percentage)
                ->first();

            return [
                'exam' => $exam,
                'marks' => $marks,
                'percentage' => $percentage,
                'grade' => $grade,
            ];
        });

        return view('student.results.index', compact('results'));
    }
}