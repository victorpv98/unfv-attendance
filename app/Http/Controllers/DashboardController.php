<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faculty;
use App\Models\Course;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Attendance;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Muestra el dashboard correspondiente según el rol del usuario.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            return $this->adminDashboard();
        } elseif ($user->role === 'teacher') {
            return $this->teacherDashboard();
        } elseif ($user->role === 'student') {
            return $this->studentDashboard();
        }
        
        return view('dashboard.index');
    }
    
    /**
     * Dashboard para administradores.
     */
    public function adminDashboard()
    {
        $faculties = Faculty::count();
        $courses = Course::count();
        $teachers = Teacher::count();
        $students = Student::count();
        
        // Obtener actividades recientes (si existen las tablas)
        $recentActivities = [];
        
        try {
            $recentActivities = Attendance::with(['student.user', 'schedule.course'])
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
        } catch (\Exception $e) {
            // Silenciar errores si no existen las tablas
        }
        
        return view('dashboard.index', compact('faculties', 'courses', 'teachers', 'students', 'recentActivities'));
    }
    
    /**
     * Dashboard para profesores.
     */
    private function teacherDashboard()
    {
        $teacher = Auth::user()->teacher;
        
        if (!$teacher) {
            return redirect()->route('dashboard')->with('error', 'No se encontró información de profesor asociada a tu cuenta.');
        }
        
        // Inicializar variables
        $coursesCount = 0;
        $studentsCount = 0;
        $todayAttendances = 0;
        $todaySchedules = [];
        
        try {
            // Obtener los cursos del profesor
            $coursesCount = Schedule::where('teacher_id', $teacher->id)->distinct('course_id')->count();
            
            // Obtener cantidad de estudiantes
            $studentsCount = \DB::table('course_student')
                ->join('schedules', 'schedules.course_id', '=', 'course_student.course_id')
                ->where('schedules.teacher_id', $teacher->id)
                ->distinct('course_student.student_id')
                ->count();
            
            // Obtener asistencias de hoy
            $todayAttendances = Attendance::whereHas('schedule', function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })
            ->whereDate('date', today())
            ->count();
            
            // Obtener horarios de hoy
            $todaySchedules = Schedule::with('course')
                ->where('teacher_id', $teacher->id)
                ->where('day', strtolower(now()->englishDayOfWeek))
                ->get();
        } catch (\Exception $e) {
            // Silenciar errores si no existen las tablas
        }
        
        return view('dashboard.index', compact('coursesCount', 'studentsCount', 'todayAttendances', 'todaySchedules'));
    }
    
    /**
     * Dashboard para estudiantes.
     */
    private function studentDashboard()
    {
        $student = Auth::user()->student;
        
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'No se encontró información de estudiante asociada a tu cuenta.');
        }
        
        // Inicializar variables
        $coursesCount = 0;
        $attendanceCount = 0;
        $absenceCount = 0;
        $todaySchedules = [];
        $todayAttendances = [];
        
        try {
            // Obtener cursos del estudiante
            $coursesCount = $student->courses()->count();
            
            // Obtener asistencias y faltas
            $attendanceCount = Attendance::where('student_id', $student->id)
                ->whereIn('status', ['present', 'late'])
                ->count();
            
            $absenceCount = Attendance::where('student_id', $student->id)
                ->where('status', 'absent')
                ->count();
            
            // Obtener horarios de hoy
            $todaySchedules = Schedule::with(['course', 'teacher.user'])
                ->whereHas('course.students', function ($query) use ($student) {
                    $query->where('students.id', $student->id);
                })
                ->where('day', strtolower(now()->englishDayOfWeek))
                ->get();
            
            // Obtener asistencias de hoy
            $todayAttendances = Attendance::where('student_id', $student->id)
                ->whereDate('date', today())
                ->get()
                ->keyBy('schedule_id');
        } catch (\Exception $e) {
            // Silenciar errores si no existen las tablas
        }
        
        return view('dashboard.index', compact('coursesCount', 'attendanceCount', 'absenceCount', 'todaySchedules', 'todayAttendances'));
    }
}