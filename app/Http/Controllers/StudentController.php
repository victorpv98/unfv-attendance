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

            // Generar código QR único
            $qrCode = $this->generateUniqueQrCode();

            // Crear el estudiante
            Student::create([
                'user_id' => $user->id,
                'faculty_id' => $request->faculty_id,
                'code' => $request->code,
                'cycle' => $request->cycle,
                'qr_code' => $qrCode,
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
     * Generate a unique QR code for a student.
     */
    private function generateUniqueQrCode()
    {
        $qrCode = Str::random(20);
        
        // Verificar si ya existe un estudiante con ese código QR
        while (Student::where('qr_code', $qrCode)->exists()) {
            $qrCode = Str::random(20);
        }
        
        return $qrCode;
    }

    /**
     * Display student's courses.
     */
    public function myCourses()
    {
        $student = Student::where('user_id', auth()->id())->firstOrFail();
        $courses = $student->courses()->with('faculty')->get();
        return view('student.courses', compact('courses'));
    }

    /**
     * Display student's attendances for a specific course.
     */
    public function courseAttendances(Course $course)
    {
        $student = Student::where('user_id', auth()->id())->firstOrFail();
        
        // Verificar si el estudiante está matriculado en el curso
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
        
        return view('student.course-attendances', compact('course', 'attendances'));
    }

    /**
     * Display all student's attendances.
     */
    public function myAttendances()
    {
        $student = Student::where('user_id', auth()->id())->firstOrFail();
        $attendances = Attendance::where('student_id', $student->id)
            ->with(['schedule', 'schedule.course'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('student.attendances', compact('attendances'));
    }
}