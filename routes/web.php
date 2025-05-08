<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\QrController;
use Illuminate\Support\Facades\Auth;

// Ruta principal - redirige al dashboard si está autenticado, o al login si no
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');

// Rutas de autenticación
require __DIR__.'/auth.php';

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Rutas para estudiantes
    Route::middleware(['checkRole:student'])->prefix('student')->group(function () {
        // QR
        Route::get('/my-qr', [QrController::class, 'myQr'])->name('students.my-qr');
        Route::get('/qr-image/{student}', [QrController::class, 'qrImage'])->name('students.qr-image');
        Route::post('/regenerate-qr', [QrController::class, 'regenerateQr'])->name('students.regenerate-qr');
        
        // Cursos y asistencias
        Route::get('/my-courses', [StudentController::class, 'myCourses'])->name('students.my-courses');
        Route::get('/course-attendances/{course}', [StudentController::class, 'courseAttendances'])->name('students.course-attendances');
        Route::get('/my-attendances', [StudentController::class, 'myAttendances'])->name('students.my-attendances');
    });
    
    // Rutas para profesores
    Route::middleware(['checkRole:teacher'])->prefix('teacher')->group(function () {
        // Horarios y QR
        Route::get('/my-schedules', [TeacherController::class, 'mySchedules'])->name('teachers.my-schedules');
        Route::get('/scan-qr/{schedule}', [TeacherController::class, 'scanQr'])->name('teachers.scan-qr');
        
        // Asistencias
        Route::post('/register-attendance', [AttendanceController::class, 'registerByQr'])->name('attendance.register-by-qr');
        Route::get('/attendance-report/{schedule}', [AttendanceController::class, 'report'])->name('attendance.report');
        Route::post('/update-attendance-status', [AttendanceController::class, 'updateStatus'])->name('attendance.update-status');
        Route::get('/export-attendance/{schedule}', [AttendanceController::class, 'export'])->name('attendance.export');
    });
    
    // Rutas para administradores
    Route::middleware(['checkRole:admin'])->prefix('admin')->group(function () {
        // Dashboard del administrador
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
        
        // Gestión de facultades
        Route::resource('faculties', FacultyController::class);
        
        // Gestión de cursos
        Route::resource('courses', CourseController::class);
        Route::get('/courses/{course}/students', [CourseController::class, 'students'])->name('courses.students');
        Route::post('/courses/{course}/enroll', [CourseController::class, 'enrollStudents'])->name('courses.enroll');
        Route::delete('/courses/{course}/unenroll/{student}', [CourseController::class, 'unenrollStudent'])->name('courses.unenroll');
        
        // Gestión de profesores
        Route::resource('teachers', TeacherController::class);
        
        // Gestión de estudiantes
        Route::resource('students', StudentController::class);
        
        // Gestión de horarios
        Route::resource('schedules', ScheduleController::class);
        
        // Reportes
        Route::get('/reports/attendance', [AttendanceController::class, 'adminReport'])->name('admin.reports.attendance');
        Route::get('/reports/courses', [CourseController::class, 'adminReport'])->name('admin.reports.courses');
    });
});