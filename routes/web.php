<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BarcodeController; 
use App\Http\Controllers\EnrollmentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

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
        // Códigos de barras 
        Route::get('/my-barcode', [StudentController::class, 'myBarcode'])->name('students.my-barcode');
        Route::get('/barcode-image/{student}', [StudentController::class, 'barcodeImage'])->name('students.barcode-image');
        
        // Cursos y asistencias
        Route::get('/my-courses', [StudentController::class, 'myCourses'])->name('students.my-courses');
        Route::get('/course-attendances/{course}', [StudentController::class, 'courseAttendances'])->name('students.course-attendances');
        Route::get('/my-attendances', [StudentController::class, 'myAttendances'])->name('students.my-attendances');
    });
    
    // Rutas para profesores
    Route::middleware([\App\Http\Middleware\CheckRole::class.':teacher'])->prefix('teacher')->group(function () {
        // Horarios y códigos de barras 
        Route::get('/my-schedules', [TeacherController::class, 'mySchedules'])->name('teachers.my-schedules');
        Route::get('/scan-barcode/{schedule}', [TeacherController::class, 'scanBarcode'])->name('teachers.scan-barcode');
        
        // Asistencias 
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
        
        // Rutas adicionales para estudiantes del curso 
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


// Ruta básica de prueba
Route::get('/test', function () {
    return 'Laravel funciona correctamente!';
});

// Ruta de diagnóstico básico
Route::get('/debug-basic', function () {
    try {
        $info = [];
        $info[] = "✅ PHP Version: " . PHP_VERSION;
        $info[] = "✅ Laravel loaded successfully";
        $info[] = "✅ Environment: " . app()->environment();
        
        // Test database connection
        try {
            $pdo = DB::connection()->getPdo();
            $info[] = "✅ Database connection successful";
            $info[] = "📊 Database driver: " . $pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);
            
            // Test basic query
            $result = DB::select('SELECT version()');
            $info[] = "📊 PostgreSQL version: " . $result[0]->version ?? 'Unknown';
        } catch (Exception $e) {
            $info[] = "❌ Database error: " . $e->getMessage();
        }
        
        // Test file permissions
        $storage_writable = is_writable(storage_path());
        $info[] = $storage_writable ? "✅ Storage writable" : "❌ Storage not writable";
        
        $cache_writable = is_writable(storage_path('framework/cache'));
        $info[] = $cache_writable ? "✅ Cache writable" : "❌ Cache not writable";
        
        // Test environment variables
        $info[] = "🔧 APP_ENV: " . config('app.env');
        $info[] = "🔧 APP_DEBUG: " . (config('app.debug') ? 'true' : 'false');
        $info[] = "🔧 APP_KEY: " . (config('app.key') ? 'Set' : 'Not set');
        $info[] = "🔧 DATABASE_URL: " . (env('DATABASE_URL') ? 'Set' : 'Not set');
        
        return '<html><head><title>Debug Basic</title></head><body><pre>' . implode("\n", $info) . '</pre></body></html>';
        
    } catch (Exception $e) {
        return '<html><head><title>Debug Error</title></head><body><pre>❌ Fatal error: ' . $e->getMessage() . "\n\nStack trace:\n" . $e->getTraceAsString() . '</pre></body></html>';
    }
});

// Ruta de setup para producción
Route::get('/setup-production', function () {
    try {
        $output = [];
        
        // Verificar conexión a BD
        $output[] = "🔍 Testing database connection...";
        $pdo = DB::connection()->getPdo();
        $output[] = "✅ Database connection successful!";
        
        // Verificar si existe tabla migrations
        try {
            $migrations = DB::table('migrations')->count();
            $output[] = "📊 Found $migrations migrations in database";
        } catch (Exception $e) {
            $output[] = "⚠️ Migrations table doesn't exist, will be created";
        }
        
        // Ejecutar migraciones
        $output[] = "📦 Running migrations...";
        Artisan::call('migrate', ['--force' => true]);
        $migrationOutput = Artisan::output();
        $output[] = "Migration output: " . trim($migrationOutput);
        $output[] = "✅ Migrations completed";
        
        // Crear tablas adicionales si es necesario
        try {
            $output[] = "📋 Creating additional tables...";
            
            // Verificar si necesitamos crear tabla de sesiones
            if (!DB::getSchemaBuilder()->hasTable('sessions')) {
                Artisan::call('session:table');
                $output[] = "📝 Session table migration created";
            }
            
            // Verificar si necesitamos crear tabla de jobs
            if (!DB::getSchemaBuilder()->hasTable('jobs')) {
                Artisan::call('queue:table');
                $output[] = "📝 Queue table migration created";
            }
            
            // Ejecutar migraciones adicionales
            Artisan::call('migrate', ['--force' => true]);
            $output[] = "✅ Additional tables created";
            
        } catch (Exception $e) {
            $output[] = "⚠️ Additional tables error: " . $e->getMessage();
        }
        
        // Limpiar y cachear configuración
        $output[] = "⚡ Optimizing application...";
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');
        $output[] = "✅ Application optimized";
        
        $output[] = "";
        $output[] = "🎉 Setup completed successfully!";
        $output[] = "";
        $output[] = "You can now visit: https://unfv-attendance.onrender.com";
        
        return '<html><head><title>Setup Production</title></head><body><pre>' . implode("\n", $output) . '</pre></body></html>';
        
    } catch (\Exception $e) {
        return '<html><head><title>Setup Error</title></head><body><pre>❌ Setup failed: ' . $e->getMessage() . "\n\nStack trace:\n" . $e->getTraceAsString() . '</pre></body></html>';
    }
});

Route::get('/setup-database/{secret}', function ($secret) {
    // Verificar secret key para seguridad
    if ($secret !== env('SETUP_SECRET', 'default-secret-key')) {
        abort(404);
    }
    
    $output = [];
    
    try {
        // Ejecutar migraciones
        Artisan::call('migrate', ['--force' => true]);
        $output[] = 'Migraciones ejecutadas: ' . Artisan::output();
        
        // Crear tablas adicionales
        Artisan::call('session:table');
        Artisan::call('queue:table');  
        Artisan::call('cache:table');
        
        // Ejecutar nuevas migraciones
        Artisan::call('migrate', ['--force' => true]);
        $output[] = 'Tablas adicionales creadas';
        
        // Optimizar
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');
        $output[] = 'Aplicación optimizada';
        
    } catch (Exception $e) {
        $output[] = 'Error: ' . $e->getMessage();
    }
    
    return response()->json([
        'status' => 'success',
        'messages' => $output
    ]);
})->name('setup.database');