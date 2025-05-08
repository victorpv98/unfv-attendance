<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Schedule;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Registra la asistencia mediante la lectura del código QR
     */
    public function registerByQr(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
            'schedule_id' => 'required|exists:schedules,id',
        ]);
        
        // Buscamos al estudiante por su código QR
        $student = Student::where('qr_code', $request->qr_code)->first();
        
        if (!$student) {
            return response()->json(['message' => 'Código QR inválido'], 404);
        }
        
        $schedule = Schedule::find($request->schedule_id);
        
        // Verificamos si el estudiante está matriculado en el curso
        $isEnrolled = $student->courses()
            ->where('course_id', $schedule->course_id)
            ->where('semester', $schedule->semester)
            ->exists();
            
        if (!$isEnrolled) {
            return response()->json([
                'message' => 'El estudiante no está matriculado en este curso'
            ], 403);
        }
        
        // Registramos la asistencia
        $now = Carbon::now();
        $status = 'present';
        
        // Si llega después de 15 minutos, lo marcamos como tardanza
        $startTime = Carbon::parse($schedule->start_time);
        if ($now->diffInMinutes($startTime) > 15 && $now > $startTime) {
            $status = 'late';
        }
        
        $attendance = Attendance::updateOrCreate(
            [
                'schedule_id' => $schedule->id,
                'student_id' => $student->id,
                'date' => $now->toDateString(),
            ],
            [
                'time' => $now->toTimeString(),
                'status' => $status,
                'registered_by' => auth()->id(),
            ]
        );
        
        return response()->json([
            'message' => 'Asistencia registrada correctamente',
            'student' => $student->user->name,
            'status' => $status,
        ]);
    }
    
    /**
     * Muestra el reporte de asistencia de un curso
     */
    public function report(Schedule $schedule, Request $request)
    {
        $date = $request->date ?? Carbon::now()->toDateString();
        
        // Obtenemos los estudiantes matriculados en el curso
        $students = $schedule->course->students()
            ->where('semester', $schedule->semester)
            ->get();
        
        // Obtenemos las asistencias para la fecha especificada
        $attendances = Attendance::where('schedule_id', $schedule->id)
            ->where('date', $date)
            ->get()
            ->keyBy('student_id');
        
        // Calculamos el resumen de asistencias
        $summary = [
            'present' => $attendances->where('status', 'present')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'absent' => $students->count() - $attendances->count(),
        ];
        
        // Calculamos los porcentajes
        $totalStudents = $students->count();
        if ($totalStudents > 0) {
            $summary['presentPercentage'] = round(($summary['present'] / $totalStudents) * 100);
            $summary['latePercentage'] = round(($summary['late'] / $totalStudents) * 100);
            $summary['absentPercentage'] = round(($summary['absent'] / $totalStudents) * 100);
        } else {
            $summary['presentPercentage'] = 0;
            $summary['latePercentage'] = 0;
            $summary['absentPercentage'] = 0;
        }
        
        return view('teachers.attendance-report', compact('schedule', 'students', 'attendances', 'summary'));
    }
    
    /**
     * Actualiza el estado de asistencia de un estudiante
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'schedule_id' => 'required|exists:schedules,id',
            'date' => 'required|date',
            'status' => 'required|in:present,late,absent',
        ]);
        
        if ($request->status == 'absent') {
            // Si el estado es ausente, eliminamos el registro de asistencia si existe
            Attendance::where('student_id', $request->student_id)
                ->where('schedule_id', $request->schedule_id)
                ->where('date', $request->date)
                ->delete();
        } else {
            // Si el estado es presente o tardanza, actualizamos o creamos el registro
            Attendance::updateOrCreate(
                [
                    'student_id' => $request->student_id,
                    'schedule_id' => $request->schedule_id,
                    'date' => $request->date,
                ],
                [
                    'status' => $request->status,
                    'time' => Carbon::now()->toTimeString(),
                    'registered_by' => Auth::id(),
                ]
            );
        }
        
        return redirect()->back()->with('success', 'Estado de asistencia actualizado correctamente.');
    }
}