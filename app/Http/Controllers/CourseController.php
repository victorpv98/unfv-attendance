<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Faculty;
use Illuminate\Http\Request;

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
    public function enrollStudents(Request $request, Course $course)
    {
        $request->validate([
            'students' => 'required|array',
            'students.*' => 'exists:students,id',
        ]);

        $course->students()->attach($request->students);

        return redirect()->route('admin.courses.students', $course)
            ->with('success', 'Estudiantes matriculados exitosamente.');
    }

    /**
     * Unenroll a student from a course.
     */
    public function unenrollStudent(Course $course, $student)
    {
        $course->students()->detach($student);

        return redirect()->route('admin.courses.students', $course)
            ->with('success', 'Estudiante desmatriculado exitosamente.');
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