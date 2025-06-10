<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\Student;
use App\Models\User;
use App\Models\Course;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::with(['user', 'faculty'])->paginate(10);
        return view('admin.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $faculties = Faculty::all();
        $cycles = range(1, 10); // Ciclos del 1 al 10
        return view('admin.students.create', compact('faculties', 'cycles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'code' => 'required|string|max:20|unique:students',
            'faculty_id' => 'required|exists:faculties,id',
            'cycle' => 'required|integer|min:1|max:10',
        ]);

        DB::beginTransaction();
        try {
            // Crear el usuario
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'student',
            ]);

            // Crear el estudiante (sin qr_code ya que usaremos el código del estudiante)
            Student::create([
                'user_id' => $user->id,
                'faculty_id' => $request->faculty_id,
                'code' => $request->code,
                'cycle' => $request->cycle,
            ]);

            DB::commit();
            return redirect()->route('admin.students.index')
                ->with('success', 'Estudiante creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al crear el estudiante: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->load(['user', 'faculty', 'courses', 'attendances']);
        return view('admin.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $faculties = Faculty::all();
        $cycles = range(1, 10); // Ciclos del 1 al 10
        return view('admin.students.edit', compact('student', 'faculties', 'cycles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $student->user_id,
            'code' => 'required|string|max:20|unique:students,code,' . $student->id,
            'faculty_id' => 'required|exists:faculties,id',
            'cycle' => 'required|integer|min:1|max:10',
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8',
            ]);
        }

        DB::beginTransaction();
        try {
            // Actualizar el usuario
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $student->user->update($userData);

            // Actualizar el estudiante
            $student->update([
                'faculty_id' => $request->faculty_id,
                'code' => $request->code,
                'cycle' => $request->cycle,
            ]);

            DB::commit();
            return redirect()->route('admin.students.index')
                ->with('success', 'Estudiante actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar el estudiante: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        try {
            DB::beginTransaction();
            
            // Verificar si el estudiante tiene asistencias o está matriculado en cursos
            if ($student->attendances()->count() > 0) {
                throw new \Exception('No se puede eliminar el estudiante porque tiene asistencias registradas.');
            }
            
            if ($student->courses()->count() > 0) {
                throw new \Exception('No se puede eliminar el estudiante porque está matriculado en cursos.');
            }
            
            // Obtener el usuario asociado
            $user = $student->user;
            
            // Eliminar el estudiante
            $student->delete();
            
            // Eliminar el usuario
            $user->delete();
            
            DB::commit();
            return redirect()->route('admin.students.index')
                ->with('success', 'Estudiante eliminado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.students.index')
                ->with('error', 'Error al eliminar el estudiante: ' . $e->getMessage());
        }
    }

    /**
     * Generate barcode image for student.
     */
    public function barcodeImage(Student $student)
    {
        try {
            // Verificar que el usuario tenga permisos para ver este código de barras
            if (auth()->user()->role === 'student' && auth()->user()->student->id !== $student->id) {
                abort(403, 'No autorizado para ver este código de barras.');
            }
            
            // Usar el código del estudiante directamente
            $studentCode = $student->code;
            
            // Crear un hash único basado en el código del estudiante
            // Esto cambiará solo si el código del estudiante cambia
            $codeHash = md5($studentCode . $student->updated_at);
            
            // Generar código de barras usando Code 39 (en lugar de Code 128)
            // Instalar con: composer require picqer/php-barcode-generator
            $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
            $barcode = $generator->getBarcode($studentCode, $generator::TYPE_CODE_39);
            
            return response($barcode)
                ->header('Content-Type', 'image/png')
                ->header('Cache-Control', 'public, max-age=3600') // Cache por 1 hora
                ->header('ETag', $codeHash) // ETag basado en el código del estudiante
                ->header('Last-Modified', $student->updated_at->format('D, d M Y H:i:s \G\M\T'));
                
        } catch (\Exception $e) {
            // En caso de error, devolver una imagen de error o código por defecto
            abort(500, 'Error al generar código de barras: ' . $e->getMessage());
        }
    }

    /**
     * Show student's barcode page.
     */
    public function myBarcode()
    {
        $student = Student::where('user_id', auth()->id())->firstOrFail();
        return view('students.my-barcode', compact('student'));
    }

    /**
     * Display student's courses.
     */
    public function myCourses(Request $request)
    {
        $student = Student::where('user_id', auth()->id())->firstOrFail();
        
        // Obtener el semestre actual (puedes determinar esto según tu lógica de negocio)
        $currentSemester = '2025-I'; // Ajusta esto según cómo determinas el semestre actual
        
        // Obtener los cursos del estudiante para el semestre actual desde la tabla pivote
        $courses = $student->courses()
            ->with(['faculty', 'schedules', 'schedules.teacher.user'])
            ->wherePivot('semester', $currentSemester)
            ->get();
        
        // Calcular el porcentaje de asistencia para cada curso
        $coursesAttendance = [];
        foreach ($courses as $course) {
            // Aquí tu lógica para calcular asistencia
            $totalClasses = $course->schedules->count(); // O tu forma de calcular clases totales
            
            $attendedClasses = Attendance::where('student_id', $student->id)
                ->whereHas('schedule', function ($query) use ($course) {
                    $query->where('course_id', $course->id);
                })
                ->count();
            
            $percentage = $totalClasses > 0 ? round(($attendedClasses / $totalClasses) * 100) : 0;
            
            $coursesAttendance[$course->id] = [
                'total' => $totalClasses,
                'attended' => $attendedClasses,
                'percentage' => $percentage
            ];
        }
        
        // Obtener semestres pasados desde la tabla pivote
        $pastSemesters = DB::table('course_student')
            ->where('student_id', $student->id)
            ->where('semester', '!=', $currentSemester)
            ->distinct()
            ->pluck('semester')
            ->toArray();
        
        // Gestionar cursos de semestres anteriores si se selecciona uno
        $pastCourses = collect();
        $pastCoursesAttendance = [];
        
        if ($request->has('semester') && in_array($request->semester, $pastSemesters)) {
            $selectedSemester = $request->semester;
            
            $pastCourses = $student->courses()
                ->with('faculty')
                ->wherePivot('semester', $selectedSemester)
                ->get();
                
            // Calcular asistencia para cursos pasados
            foreach ($pastCourses as $course) {
                // Lógica para calcular asistencia de cursos pasados
                // Esto sería similar al cálculo de arriba
                
                $pastCoursesAttendance[$course->id] = [
                    'percentage' => 85 // Reemplaza con el cálculo real
                ];
            }
        }
        
        return view('students.my-courses', compact(
            'courses', 
            'currentSemester', 
            'coursesAttendance', 
            'pastSemesters', 
            'pastCourses', 
            'pastCoursesAttendance'
        ));
    }

    /**
     * Display student's attendances for a specific course.
     */
    public function courseAttendances(Course $course)
    {
        $student = Student::where('user_id', auth()->id())->firstOrFail();
        
        if (!$student->courses->contains($course->id)) {
            abort(403, 'No estás matriculado en este curso.');
        }
        
        $attendances = Attendance::where('student_id', $student->id)
            ->whereHas('schedule', function ($query) use ($course) {
                $query->where('course_id', $course->id);
            })
            ->with('schedule')
            ->orderBy('created_at', 'desc')
            ->get();
        
            return view('students.course-attendances', compact('course', 'attendances'));
    }

    /**
     * Display all student's attendances.
     */
    public function myAttendances(Request $request)
    {
        $student = Student::where('user_id', auth()->id())->firstOrFail();
        
        // Obtener los cursos del estudiante para el filtro
        $courses = $student->courses()->get();
        
        // Definir los meses para el filtro
        $months = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];
        
        // Construir la consulta base para las asistencias
        $query = Attendance::where('student_id', $student->id)
            ->with(['schedule', 'schedule.course', 'schedule.teacher.user']);
        
        // Aplicar filtros si existen
        if ($request->filled('course_id')) {
            $query->whereHas('schedule', function ($q) use ($request) {
                $q->where('course_id', $request->course_id);
            });
        }
        
        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month);
        }
        
        // Obtener los resultados paginados
        $attendances = $query->orderBy('date', 'desc')
                            ->orderBy('time', 'desc')
                            ->paginate(10);
        
        // Calcular el resumen general de asistencias
        $allAttendances = Attendance::where('student_id', $student->id);
        
        // Aplicar los mismos filtros al resumen
        if ($request->filled('course_id')) {
            $allAttendances->whereHas('schedule', function ($q) use ($request) {
                $q->where('course_id', $request->course_id);
            });
        }
        
        if ($request->filled('month')) {
            $allAttendances->whereMonth('date', $request->month);
        }
        
        $allAttendances = $allAttendances->get();
        
        $totalAttendances = $allAttendances->count();
        $presentCount = $allAttendances->where('status', 'present')->count();
        $lateCount = $allAttendances->where('status', 'late')->count();
        $absentCount = $allAttendances->where('status', 'absent')->count();
        
        $summary = [
            'present' => $presentCount,
            'late' => $lateCount,
            'absent' => $absentCount,
            'presentPercentage' => $totalAttendances > 0 ? round(($presentCount / $totalAttendances) * 100) : 0,
            'latePercentage' => $totalAttendances > 0 ? round(($lateCount / $totalAttendances) * 100) : 0,
            'absentPercentage' => $totalAttendances > 0 ? round(($absentCount / $totalAttendances) * 100) : 0,
        ];
        
        // Generar resumen por curso
        $coursesSummary = collect();
        
        foreach ($courses as $course) {
            $courseAttendances = Attendance::where('student_id', $student->id)
                ->whereHas('schedule', function ($query) use ($course) {
                    $query->where('course_id', $course->id);
                });
            
            // Aplicar filtro de mes al resumen por curso si existe
            if ($request->filled('month')) {
                $courseAttendances->whereMonth('date', $request->month);
            }
            
            $courseAttendances = $courseAttendances->get();
            
            $totalCourseAttendances = $courseAttendances->count();
            
            if ($totalCourseAttendances > 0) {
                $coursesPresentCount = $courseAttendances->where('status', 'present')->count();
                $coursesLateCount = $courseAttendances->where('status', 'late')->count();
                $coursesAbsentCount = $courseAttendances->where('status', 'absent')->count();
                
                $course->present_count = $coursesPresentCount;
                $course->late_count = $coursesLateCount;
                $course->absent_count = $coursesAbsentCount;
                $course->attendance_percentage = round((($coursesPresentCount + $coursesLateCount) / $totalCourseAttendances) * 100);
                $course->present_percentage = round(($coursesPresentCount / $totalCourseAttendances) * 100);
                $course->late_percentage = round(($coursesLateCount / $totalCourseAttendances) * 100);
                $course->absent_percentage = round(($coursesAbsentCount / $totalCourseAttendances) * 100);
                
                $coursesSummary->push($course);
            }
        }
        
        return view('students.my-attendances', compact(
            'attendances', 
            'courses', 
            'months', 
            'summary', 
            'coursesSummary'
        ));
    }
}