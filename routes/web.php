<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BarcodeController; // Cambiado de QrController
use App\Http\Controllers\EnrollmentController;
use Illuminate\Support\Facades\Auth;

// Ruta principal 
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
    Route::middleware([\App\Http\Middleware\CheckRole::class.':student'])->prefix('student')->group(function () {
        // Códigos de barras - CORREGIDO: usar StudentController
        Route::get('/my-barcode', [StudentController::class, 'myBarcode'])->name('students.my-barcode');
        Route::get('/barcode-image/{student}', [StudentController::class, 'barcodeImage'])->name('students.barcode-image');
        // ELIMINADO: regenerate-barcode ya no se necesita
        
        // Cursos y asistencias
        Route::get('/my-courses', [StudentController::class, 'myCourses'])->name('students.my-courses');
        Route::get('/course-attendances/{course}', [StudentController::class, 'courseAttendances'])->name('students.course-attendances');
        Route::get('/my-attendances', [StudentController::class, 'myAttendances'])->name('students.my-attendances');
    });
    
    // Rutas para profesores
    Route::middleware([\App\Http\Middleware\CheckRole::class.':teacher'])->prefix('teacher')->group(function () {
        // Horarios y códigos de barras - CORREGIDO: scan-barcode
        Route::get('/my-schedules', [TeacherController::class, 'mySchedules'])->name('teachers.my-schedules');
        Route::get('/scan-barcode/{schedule}', [TeacherController::class, 'scanBarcode'])->name('teachers.scan-barcode');
        
        // Asistencias - CORREGIDO: register-by-barcode
        Route::post('/register-attendance-barcode', [AttendanceController::class, 'registerByBarcode'])->name('attendance.register-by-barcode');
        Route::get('/attendance-report/{schedule}', [AttendanceController::class, 'report'])->name('attendance.report');
        Route::post('/update-attendance-status', [AttendanceController::class, 'updateStatus'])->name('attendance.update-status');
        Route::get('/export-attendance/{schedule}', [AttendanceController::class, 'export'])->name('attendance.export');
    });
    
    // Rutas para administradores
    Route::middleware([\App\Http\Middleware\CheckRole::class.':admin'])->prefix('admin')->group(function () {
        // Dashboard del administrador
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
        
        // Gestión de facultades
        Route::resource('faculties', FacultyController::class)->names([
            'index' => 'admin.faculties.index',
            'create' => 'admin.faculties.create',
            'store' => 'admin.faculties.store',
            'show' => 'admin.faculties.show',
            'edit' => 'admin.faculties.edit',
            'update' => 'admin.faculties.update',
            'destroy' => 'admin.faculties.destroy',
        ]);
        
        // Gestión de cursos
        Route::resource('courses', CourseController::class)->names([
            'index' => 'admin.courses.index',
            'create' => 'admin.courses.create',
            'store' => 'admin.courses.store',
            'show' => 'admin.courses.show',
            'edit' => 'admin.courses.edit',
            'update' => 'admin.courses.update',
            'destroy' => 'admin.courses.destroy',
        ]);
        
        // Rutas adicionales para estudiantes del curso - CORREGIDO: sin /admin/ duplicado
        Route::get('/courses/{course}/students', [CourseController::class, 'students'])->name('admin.courses.students');
        Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll'])->name('admin.courses.enroll');
        Route::delete('/courses/{course}/students/{student}', [CourseController::class, 'unenroll'])->name('admin.courses.unenroll');
        
        // Gestión de profesores 
        Route::resource('teachers', TeacherController::class)->names([
            'index' => 'admin.teachers.index',
            'create' => 'admin.teachers.create',
            'store' => 'admin.teachers.store',
            'show' => 'admin.teachers.show',
            'edit' => 'admin.teachers.edit',
            'update' => 'admin.teachers.update',
            'destroy' => 'admin.teachers.destroy',
        ]);
        
        // Gestión de estudiantes
        Route::resource('students', StudentController::class)->names([
            'index' => 'admin.students.index',
            'create' => 'admin.students.create',
            'store' => 'admin.students.store',
            'show' => 'admin.students.show',
            'edit' => 'admin.students.edit',
            'update' => 'admin.students.update',
            'destroy' => 'admin.students.destroy',
        ]);
        
        // Gestión de horarios
        Route::resource('schedules', ScheduleController::class)->names([
            'index' => 'admin.schedules.index',
            'create' => 'admin.schedules.create',
            'store' => 'admin.schedules.store',
            'show' => 'admin.schedules.show',
            'edit' => 'admin.schedules.edit',
            'update' => 'admin.schedules.update',
            'destroy' => 'admin.schedules.destroy',
        ]);

        // Gestión de matrículas
        Route::resource('enrollments', EnrollmentController::class)->except(['show', 'edit', 'update'])->names([
            'index' => 'admin.enrollments.index',
            'create' => 'admin.enrollments.create',
            'store' => 'admin.enrollments.store',
            'destroy' => 'admin.enrollments.destroy',
        ]);
        Route::get('/enrollments/by-course', [EnrollmentController::class, 'byCourse'])->name('admin.enrollments.byCourse');
        Route::get('/enrollments/by-student', [EnrollmentController::class, 'byStudent'])->name('admin.enrollments.byStudent');
        
        // Reportes
        Route::get('/reports/attendance', [AttendanceController::class, 'adminReport'])->name('admin.reports.attendance');
        Route::get('/reports/courses', [CourseController::class, 'adminReport'])->name('admin.reports.courses');
        Route::get('/reports/barcodes', [BarcodeController::class, 'barcodeReport'])->name('admin.reports.barcodes');
    });
});