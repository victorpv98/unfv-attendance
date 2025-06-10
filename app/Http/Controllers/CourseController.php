<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Faculty;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::with('faculty')->paginate(10);
        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $faculties = Faculty::all();
        return view('admin.courses.create', compact('faculties'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:courses',
            'faculty_id' => 'required|exists:faculties,id',
            'credits' => 'required|integer|min:1',
            'cycle' => 'required|string|max:50',
        ]);

        Course::create($request->all());

        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        return view('admin.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        $faculties = Faculty::all();
        return view('admin.courses.edit', compact('course', 'faculties'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:courses,code,' . $course->id,
            'faculty_id' => 'required|exists:faculties,id',
            'credits' => 'required|integer|min:1',
            'cycle' => 'required|string|max:50',
        ]);

        $course->update($request->all());

        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        try {
            $course->delete();
            return redirect()->route('admin.courses.index')
                ->with('success', 'Curso eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('admin.courses.index')
                ->with('error', 'No se puede eliminar el curso porque tiene registros asociados.');
        }
    }

    /**
     * Display students enrolled in a course.
     */
    public function students(Course $course)
    {
        $students = $course->students()->paginate(10);
        return view('admin.courses.students', compact('course', 'students'));
    }

    /**
     * Enroll students in a course.
     */
    public function enroll(Request $request, Course $course)
    {
        $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',
            'semester' => 'required|string|max:20'
        ]);

        try {
            $studentIds = $request->student_ids;
            $semester = $request->semester;

            // Verificar qué estudiantes ya están matriculados en este curso y semestre específico
            $alreadyEnrolled = DB::table('course_student')
                ->where('course_id', $course->id)
                ->whereIn('student_id', $studentIds)
                ->where('semester', $semester)
                ->pluck('student_id')
                ->toArray();

            $newEnrollments = array_diff($studentIds, $alreadyEnrolled);

            if (!empty($newEnrollments)) {
                // Preparar datos para inserción manual
                $insertData = [];
                foreach ($newEnrollments as $studentId) {
                    $insertData[] = [
                        'course_id' => $course->id,
                        'student_id' => $studentId,
                        'semester' => $semester,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }

                // Insertar directamente en la tabla
                DB::table('course_student')->insert($insertData);

                $message = count($newEnrollments) . ' estudiante(s) matriculado(s) exitosamente.';
                
                if (!empty($alreadyEnrolled)) {
                    $message .= ' ' . count($alreadyEnrolled) . ' estudiante(s) ya estaban matriculados en este semestre.';
                }
            } else {
                $message = 'Todos los estudiantes seleccionados ya estaban matriculados en este semestre.';
            }

            return redirect()->route('admin.courses.students', $course)
                            ->with('success', $message);

        } catch (\Exception $e) {
            \Log::error('Error al matricular estudiantes: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                            ->with('error', 'Error al matricular estudiantes. Por favor, inténtelo de nuevo.')
                            ->withInput();
        }
    }

    /**
     * Enroll students in a course (método alternativo para compatibilidad).
     */
    public function enrollStudents(Request $request, Course $course)
    {
        return $this->enroll($request, $course);
    }

    /**
     * Unenroll a student from a course.
     */
    public function unenroll(Course $course, $studentId)
    {
        try {
            $student = Student::findOrFail($studentId);
            $course->students()->detach($studentId);

            return redirect()->route('admin.courses.students', $course)
                            ->with('success', 'Estudiante desmatriculado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Error al desmatricular estudiante.');
        }
    }

    /**
     * Unenroll a student from a course (método alternativo para compatibilidad).
     */
    public function unenrollStudent(Course $course, $student)
    {
        return $this->unenroll($course, $student);
    }

    /**
     * Display courses report for admin.
     */
    public function adminReport()
    {
        $courses = Course::withCount('students')->paginate(10);
        return view('admin.reports.courses', compact('courses'));
    }
}