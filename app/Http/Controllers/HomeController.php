<?php

namespace App\Http\Controllers;

use App\Models\StudentClass;
use App\Models\Student;
use App\Models\Teacher;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'classes' => StudentClass::orderBy('name')->get(),
            'totalStudents' => Student::count(),
            'totalTeachers' => Teacher::count(),
            'totalClasses' => StudentClass::count(),
        ]);
    }
}