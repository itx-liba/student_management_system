<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdmissionController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\SectionController as AdminSectionController;
use App\Http\Controllers\Admin\SubjectController as AdminSubjectController;
use App\Http\Controllers\Admin\TeacherController as AdminTeacherController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\TimetableController as AdminTimetableController;
use App\Http\Controllers\Admin\FeeStructureController;
use App\Http\Controllers\Admin\FeeInvoiceController;
use App\Http\Controllers\Admin\FeePaymentController;
use App\Http\Controllers\Admin\ExamController as AdminExamController;
use App\Http\Controllers\Admin\GradeRuleController;
use App\Http\Controllers\Admin\NoticeController as AdminNoticeController;
use App\Http\Controllers\Admin\AdmissionApplicationController;
use App\Http\Controllers\Admin\StudentDocumentController;
use App\Http\Controllers\Admin\StaffAttendanceController as AdminStaffAttendanceController;
use App\Http\Controllers\Admin\TeacherTaskController;
use App\Http\Controllers\Admin\ReportController;

use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\AttendanceController as TeacherAttendanceController;
use App\Http\Controllers\Teacher\MarksController;
use App\Http\Controllers\Teacher\StudyMaterialController;
use App\Http\Controllers\Teacher\NoticeController as TeacherNoticeController;
use App\Http\Controllers\Teacher\TaskController;
use App\Http\Controllers\Teacher\StaffAttendanceController as TeacherStaffAttendanceController;

use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\AttendanceController as StudentAttendanceController;
use App\Http\Controllers\Student\FeeController;
use App\Http\Controllers\Student\ResultController;
use App\Http\Controllers\Student\TimetableController as StudentTimetableController;
use App\Http\Controllers\Student\NoticeController as StudentNoticeController;
use App\Http\Controllers\Student\DocumentController as StudentPortalDocumentController;

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/admission', [AdmissionController::class, 'create'])->name('admission.create');
Route::post('/admission', [AdmissionController::class, 'store'])->name('admission.store');

/*
|--------------------------------------------------------------------------
| Admin routes — all named admin.*
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('classes', ClassController::class);
    Route::resource('sections', AdminSectionController::class);
    Route::resource('subjects', AdminSubjectController::class);
    Route::resource('teachers', AdminTeacherController::class);
    Route::resource('students', AdminStudentController::class);
    Route::resource('timetables', AdminTimetableController::class);
    Route::resource('fee-structures', FeeStructureController::class);
    Route::resource('fee-invoices', FeeInvoiceController::class);
    Route::resource('fee-payments', FeePaymentController::class);
    Route::resource('exams', AdminExamController::class);
    Route::resource('grade-rules', GradeRuleController::class);
    Route::resource('notices', AdminNoticeController::class);
    Route::resource('admissions', AdmissionApplicationController::class);
    Route::resource('documents', StudentDocumentController::class);
    Route::resource('staff-attendance', AdminStaffAttendanceController::class);
    Route::resource('teacher-tasks', TeacherTaskController::class);

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/attendance', [ReportController::class, 'attendance'])->name('reports.attendance');
    Route::get('/reports/fees', [ReportController::class, 'fees'])->name('reports.fees');
    Route::get('/reports/tasks', [ReportController::class, 'tasks'])->name('reports.tasks');
});

/*
|--------------------------------------------------------------------------
| Teacher routes — all named teacher.*
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');

    Route::get('/attendance', [TeacherAttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [TeacherAttendanceController::class, 'store'])->name('attendance.store');

    Route::get('/marks', [MarksController::class, 'index'])->name('marks.index');
    Route::post('/marks', [MarksController::class, 'store'])->name('marks.store');

    Route::resource('materials', StudyMaterialController::class);
    Route::resource('notices', TeacherNoticeController::class);

    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');

    Route::get('/staff-attendance', [TeacherStaffAttendanceController::class, 'index'])->name('staff-attendance.index');
    Route::post('/staff-attendance/check-in', [TeacherStaffAttendanceController::class, 'checkIn'])->name('staff-attendance.check-in');
    Route::post('/staff-attendance/check-out', [TeacherStaffAttendanceController::class, 'checkOut'])->name('staff-attendance.check-out');
});

/*
|--------------------------------------------------------------------------
| Student routes — all named student.*
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/attendance', [StudentAttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/fees', [FeeController::class, 'index'])->name('fees.index');
    Route::get('/results', [ResultController::class, 'index'])->name('results.index');
    Route::get('/timetable', [StudentTimetableController::class, 'index'])->name('timetable.index');
    Route::get('/notices', [StudentNoticeController::class, 'index'])->name('notices.index');
    Route::get('/documents', [StudentPortalDocumentController::class, 'index'])->name('documents.index');
});