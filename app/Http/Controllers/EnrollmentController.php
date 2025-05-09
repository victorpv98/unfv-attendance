<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    /**
     * Mostrar el listado de matrículas
     */
    public function index()
    {
        $enrollments = DB::table('course_student')
            ->join('courses', 'course_student.course_id', '=', 'courses.id')
            ->join('students', 'course_student.student_id', '=', 'students.id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->select(
                'course_student.id',
                'courses.name as course_name',
                'courses.code as course_code',
                'users.name as student_name',
                'students.code as student_code',
                'course_student.semester',
                'course_student.created_at'
            )
            ->orderBy('course_student.created_at', 'desc')
            ->paginate(15);

        return view('admin.enrollments.index', compact('enrollments'));
    }

    /**
     * Mostrar el formulario para crear una nueva matrícula
     */
    public function create()
    {
        $courses = Course::orderBy('name')->get();
        $students = Student::with('user')->get()->sortBy('user.name');
        $currentSemester = date('Y') . '-' . (date('n') <= 6 ? 'I' : 'II');
        
        return view('admin.enrollments.create', compact('courses', 'students', 'currentSemester'));
    }

    /**
     * Almacenar una nueva matrícula
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'semester' => 'required|string|max:10',
        ]);

        $course = Course::findOrFail($validated['course_id']);
        $studentIds = $validated['student_ids'];
        $semester = $validated['semester'];

        // Verificar si ya existen matriculados
        $existingEnrollments = DB::table('course_student')
            ->where('course_id', $course->id)
            ->whereIn('student_id', $studentIds)
            ->where('semester', $semester)
            ->get()
            ->pluck('student_id')
            ->toArray();

        // Filtrar estudiantes ya matriculados
        $newStudentIds = array_diff($studentIds, $existingEnrollments);
        
        // Matricular nuevos estudiantes
        foreach ($newStudentIds as $studentId) {
            DB::table('course_student')->insert([
                'course_id' => $course->id,
                'student_id' => $studentId,
                'semester' => $semester,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $enrolledCount = count($newStudentIds);
        $duplicateCount = count($studentIds) - $enrolledCount;

        return redirect()->route('admin.enrollments.index')
            ->with('success', "Se matricularon $enrolledCount estudiantes en el curso. $duplicateCount estudiantes ya estaban matriculados.");
    }

    /**
     * Eliminar una matrícula
     */
    public function destroy($id)
    {
        DB::table('course_student')->where('id', $id)->delete();
        
        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Matrícula eliminada correctamente.');
    }

    /**
     * Mostrar matriculados por curso
     */
    public function byCourse()
    {
        $courses = Course::withCount(['students' => function($query) {
            $query->where('semester', date('Y') . '-' . (date('n') <= 6 ? 'I' : 'II'));
        }])->orderBy('name')->paginate(15);
        
        return view('admin.enrollments.by_course', compact('courses'));
    }

    /**
     * Mostrar matriculados por estudiante
     */
    public function byStudent()
    {
        $students = Student::with(['user', 'courses' => function($query) {
            $query->where('semester', date('Y') . '-' . (date('n') <= 6 ? 'I' : 'II'));
        }])->withCount(['courses' => function($query) {
            $query->where('semester', date('Y') . '-' . (date('n') <= 6 ? 'I' : 'II'));
        }])->paginate(15);
        
        return view('admin.enrollments.by_student', compact('students'));
    }
}