<?php

    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Artisan;
    use Illuminate\Support\Facades\DB;
    use App\Http\Controllers\{
        DashboardController,
        FacultyController,
        StudentController,
        TeacherController,
        CourseController,
        ScheduleController,
        AttendanceController,
        BarcodeController,
        EnrollmentController
    };

    // Página principal
    Route::get('/', fn() => Auth::check() ? redirect()->route('dashboard') : redirect()->route('login'))->name('home');

    // Rutas de autenticación
    require __DIR__.'/auth.php';

    // Rutas protegidas
    Route::middleware(['auth'])->group(function () {

        // Dashboard general
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        /**
         * === ESTUDIANTE ===
         */
        Route::middleware([\App\Http\Middleware\CheckRole::class.':student'])->prefix('student')->group(function () {
            Route::get('/my-barcode', [StudentController::class, 'myBarcode'])->name('students.my-barcode');
            Route::get('/barcode-image/{student}', [StudentController::class, 'barcodeImage'])->name('students.barcode-image');
            Route::get('/my-courses', [StudentController::class, 'myCourses'])->name('students.my-courses');
            Route::get('/course-attendances/{course}', [StudentController::class, 'courseAttendances'])->name('students.course-attendances');
            Route::get('/my-attendances', [StudentController::class, 'myAttendances'])->name('students.my-attendances');
        });

        /**
         * === PROFESOR ===
         */
        Route::middleware([\App\Http\Middleware\CheckRole::class.':teacher'])->prefix('teacher')->group(function () {
            Route::get('/my-schedules', [TeacherController::class, 'mySchedules'])->name('teachers.my-schedules');
            Route::get('/scan-barcode/{schedule}', [TeacherController::class, 'scanBarcode'])->name('teachers.scan-barcode');
            Route::put('/schedules/{schedule}/tolerance', [TeacherController::class, 'updateTolerance'])->name('teachers.schedules.update-tolerance');

            Route::post('/register-attendance-barcode', [AttendanceController::class, 'registerByBarcode'])
                ->name('attendance.register-by-barcode');
            
            Route::get('/attendance-report/{schedule}', [AttendanceController::class, 'report'])->name('attendance.report');
            Route::post('/update-attendance-status', [AttendanceController::class, 'updateStatus'])->name('attendance.update-status');
            Route::get('/export-attendance/{schedule}', [AttendanceController::class, 'export'])->name('attendance.export');
        });

        /**
         * === ADMINISTRADOR ===
         */
        Route::middleware([\App\Http\Middleware\CheckRole::class.':admin'])->prefix('admin')->group(function () {

            Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');

            Route::resource('faculties', FacultyController::class)->names('admin.faculties');
            Route::resource('courses', CourseController::class)->names('admin.courses');
            Route::get('/courses/{course}/students', [CourseController::class, 'students'])->name('admin.courses.students');
            Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll'])->name('admin.courses.enroll');
            Route::delete('/courses/{course}/students/{student}', [CourseController::class, 'unenroll'])->name('admin.courses.unenroll');

            Route::resource('teachers', TeacherController::class)->names('admin.teachers');
            Route::resource('students', StudentController::class)->names('admin.students');
            Route::resource('schedules', ScheduleController::class)->names('admin.schedules');

            Route::resource('enrollments', EnrollmentController::class)->except(['show', 'edit', 'update'])->names('admin.enrollments');
            Route::get('/enrollments/by-course', [EnrollmentController::class, 'byCourse'])->name('admin.enrollments.byCourse');
            Route::get('/enrollments/by-student', [EnrollmentController::class, 'byStudent'])->name('admin.enrollments.byStudent');

            Route::get('/reports/attendance', [AttendanceController::class, 'adminReport'])->name('admin.reports.attendance');
            Route::get('/reports/courses', [CourseController::class, 'adminReport'])->name('admin.reports.courses');
            Route::get('/reports/barcodes', [BarcodeController::class, 'barcodeReport'])->name('admin.reports.barcodes');
        });
    });

    /**
     * === RUTAS DE PRUEBA Y DIAGNÓSTICO ===
     */
    Route::get('/test', fn() => 'Laravel funciona correctamente!');

    Route::get('/debug-basic', function () {
        try {
            $info = [];
            $info[] = "✅ PHP Version: " . PHP_VERSION;
            $info[] = "✅ Laravel loaded successfully";
            $info[] = "✅ Environment: " . app()->environment();

            try {
                $pdo = DB::connection()->getPdo();
                $info[] = "✅ Database connection successful";
                $info[] = "📊 Database driver: " . $pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);
                $result = DB::select('SELECT version()');
                $info[] = "📊 PostgreSQL version: " . $result[0]->version ?? 'Unknown';
            } catch (Exception $e) {
                $info[] = "❌ Database error: " . $e->getMessage();
            }

            $info[] = is_writable(storage_path()) ? "✅ Storage writable" : "❌ Storage not writable";
            $info[] = is_writable(storage_path('framework/cache')) ? "✅ Cache writable" : "❌ Cache not writable";
            $info[] = "🔧 APP_ENV: " . config('app.env');
            $info[] = "🔧 APP_DEBUG: " . (config('app.debug') ? 'true' : 'false');
            $info[] = "🔧 APP_KEY: " . (config('app.key') ? 'Set' : 'Not set');
            $info[] = "🔧 DATABASE_URL: " . (env('DATABASE_URL') ? 'Set' : 'Not set');

            return response('<pre>' . implode("\n", $info) . '</pre>', 200)->header('Content-Type', 'text/html');
        } catch (Exception $e) {
            return response('<pre>❌ Fatal error: ' . $e->getMessage() . "\n\n" . $e->getTraceAsString() . '</pre>', 500)->header('Content-Type', 'text/html');
        }
    });

    Route::get('/setup-production', function () {
        try {
            $output = [];
            DB::connection()->getPdo();
            $output[] = "✅ DB connected";

            if (!DB::getSchemaBuilder()->hasTable('migrations')) {
                $output[] = "⚠️ Migrations table missing";
            }

            Artisan::call('migrate', ['--force' => true]);
            $output[] = Artisan::output();

            if (!DB::getSchemaBuilder()->hasTable('sessions')) {
                Artisan::call('session:table');
                $output[] = "📝 Session table created";
            }

            if (!DB::getSchemaBuilder()->hasTable('jobs')) {
                Artisan::call('queue:table');
                $output[] = "📝 Jobs table created";
            }

            Artisan::call('migrate', ['--force' => true]);
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
            $output[] = "✅ App optimized";

            return response('<pre>' . implode("\n", $output) . '</pre>', 200)->header('Content-Type', 'text/html');
        } catch (\Exception $e) {
            return response('<pre>❌ Setup failed: ' . $e->getMessage() . '</pre>', 500)->header('Content-Type', 'text/html');
        }
    });

    Route::get('/setup-database/{secret}', function ($secret) {
        if ($secret !== env('SETUP_SECRET', 'default-secret-key')) {
            abort(404);
        }

        $output = [];
        try {
            Artisan::call('migrate', ['--force' => true]);
            Artisan::call('session:table');
            Artisan::call('queue:table');
            Artisan::call('cache:table');
            Artisan::call('migrate', ['--force' => true]);
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
            $output[] = '✅ Setup completed successfully';
        } catch (Exception $e) {
            $output[] = '❌ Error: ' . $e->getMessage();
        }

        return response()->json(['status' => 'success', 'messages' => $output]);
    })->name('setup.database');