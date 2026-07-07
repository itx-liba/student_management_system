<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
});

// admin route
Route::resource('classes', ClassController::class);
Route::resource('sections', SectionController::class);
Route::resource('subjects', SubjectController::class);
Route::resource('teachers', TeacherController::class);
Route::resource('students', StudentController::class);
Route::resource('timetables', TimetableController::class);
Route::resource('fee-structures', FeeStructureController::class);
Route::resource('fee-invoices', FeeInvoiceController::class);
Route::resource('fee-payments', FeePaymentController::class);
Route::resource('exams', ExamController::class);
Route::resource('grade-rules', GradeRuleController::class);
Route::resource('notices', NoticeController::class);
Route::resource('admissions', AdmissionApplicationController::class);
Route::resource('documents', StudentDocumentController::class);
Route::resource('staff-attendance', StaffAttendanceController::class);
Route::resource('teacher-tasks', TeacherTaskController::class);

// teacher route
Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
Route::get('/marks', [MarksController::class, 'index'])->name('marks.index');
Route::post('/marks', [MarksController::class, 'store'])->name('marks.store');
Route::resource('materials', StudyMaterialController::class);
Route::resource('notices', NoticeController::class);
Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');
Route::get('/staff-attendance', [StaffAttendanceController::class, 'index'])->name('staff-attendance.index');
Route::post('/staff-attendance/check-in', [StaffAttendanceController::class, 'checkIn'])->name('staff-attendance.check-in');
Route::post('/staff-attendance/check-out', [StaffAttendanceController::class, 'checkOut'])->name('staff-attendance.check-out');

// student route

Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
Route::get('/fees', [FeeController::class, 'index'])->name('fees.index');
Route::get('/results', [ResultController::class, 'index'])->name('results.index');
Route::get('/timetable', [TimetableController::class, 'index'])->name('timetable.index');
Route::get('/notices', [NoticeController::class, 'index'])->name('notices.index');
Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');

// public admision route

Route::get('/admission', [AdmissionController::class, 'create'])->name('admission.create');
Route::post('/admission', [AdmissionController::class, 'store'])->name('admission.store');
