<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Schedule;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
        
        // Verificamos si el estudiante está matriculado en el curso (SIN semester)
        $isEnrolled = $student->courses()
            ->where('course_id', $schedule->course_id)
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
     * Registra la asistencia mediante la lectura del código de barras
     */
    public function registerByBarcode(Request $request)
    {
        try {
            $request->validate([
                'barcode' => 'required|string',
                'schedule_id' => 'required|exists:schedules,id'
            ]);

            $barcode = trim($request->input('barcode'));
            $scheduleId = $request->input('schedule_id');

            Log::info('Registro de asistencia por código de barras', [
                'barcode' => $barcode,
                'schedule_id' => $scheduleId,
                'user_id' => auth()->id()
            ]);

            // Buscar el estudiante por su código (code o qr_code si existe)
            $student = Student::with(['user', 'faculty'])
                ->where('code', $barcode)
                ->orWhere('qr_code', $barcode)
                ->first();

            if (!$student) {
                Log::warning('Estudiante no encontrado', ['barcode' => $barcode]);
                return response()->json([
                    'success' => false,
                    'message' => 'Estudiante no encontrado con el código: ' . $barcode
                ], 404);
            }

            // Obtener el horario
            $schedule = Schedule::with('course')->findOrFail($scheduleId);

            Log::info('Horario encontrado', [
                'schedule_id' => $schedule->id,
                'course_id' => $schedule->course_id,
                'course_name' => $schedule->course->name
            ]);

            // Verificar que el estudiante esté matriculado en el curso
            // CORREGIDO: No usar semester del schedule, sino verificar en course_student
            $isEnrolled = DB::table('course_student')
                ->where('course_id', $schedule->course_id)
                ->where('student_id', $student->id)
                ->exists();

            if (!$isEnrolled) {
                Log::warning('Estudiante no matriculado', [
                    'student_id' => $student->id,
                    'course_id' => $schedule->course_id
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'El estudiante ' . $student->user->name . ' no está matriculado en este curso.'
                ], 403);
            }

            $today = now()->toDateString();
            $currentTime = now();

            // Verificar si ya tiene asistencia registrada hoy
            $existingAttendance = Attendance::where('student_id', $student->id)
                ->where('schedule_id', $scheduleId)
                ->where('date', $today)
                ->first();

            if ($existingAttendance) {
                Log::info('Asistencia ya registrada', [
                    'student_id' => $student->id,
                    'existing_time' => $existingAttendance->time,
                    'existing_status' => $existingAttendance->status
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'La asistencia de ' . $student->user->name . ' ya fue registrada hoy a las ' . $existingAttendance->time
                ], 400);
            }

            // Determinar el estado de la asistencia
            $startTime = Carbon::createFromFormat('H:i:s', $schedule->start_time);
            $lateThreshold = $startTime->copy()->addMinutes(15); // 15 minutos de tolerancia

            // Comparar solo la hora, no la fecha
            $currentTimeOnly = Carbon::createFromFormat('H:i:s', $currentTime->format('H:i:s'));
            $status = $currentTimeOnly->lte($lateThreshold) ? 'present' : 'late';

            Log::info('Determinando estado de asistencia', [
                'start_time' => $startTime->format('H:i:s'),
                'current_time' => $currentTimeOnly->format('H:i:s'),
                'late_threshold' => $lateThreshold->format('H:i:s'),
                'status' => $status
            ]);

            // Registrar la asistencia
            $attendance = Attendance::create([
                'student_id' => $student->id,
                'schedule_id' => $scheduleId,
                'date' => $today,
                'time' => $currentTime->format('H:i:s'),
                'status' => $status,
                'registered_by' => auth()->id()
            ]);

            Log::info('Asistencia registrada exitosamente', [
                'attendance_id' => $attendance->id,
                'student_id' => $student->id,
                'student_name' => $student->user->name,
                'status' => $status,
                'time' => $attendance->time
            ]);

            return response()->json([
                'success' => true,
                'student' => $student->user->name,
                'code' => $student->code,
                'status' => $status,
                'time' => $attendance->time,
                'message' => $status === 'present' 
                    ? 'Asistencia registrada correctamente.' 
                    : 'Tardanza registrada (después de 15 minutos).'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación', [
                'errors' => $e->errors(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Datos de entrada inválidos.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error registrando asistencia por código de barras: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor. Por favor inténtelo nuevamente.',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    /**
     * Muestra el reporte de asistencia de un curso
     */
    public function report(Schedule $schedule, Request $request)
    {
        $date = $request->date ?? Carbon::now()->toDateString();
        
        // Obtenemos los estudiantes matriculados en el curso (SIN filtro de semester)
        $students = $schedule->course->students()->get();
        
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
        try {
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
            
            return response()->json([
                'success' => true,
                'message' => 'Estado de asistencia actualizado correctamente.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error actualizando estado de asistencia', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado de asistencia.'
            ], 500);
        }
    }

    /**
     * Export attendance data
     */
    public function export(Schedule $schedule)
    {
        try {
            // Implementar exportación aquí
            return redirect()->back()->with('info', 'Función de exportación en desarrollo.');
            
        } catch (\Exception $e) {
            Log::error('Error exporting attendance', [
                'schedule_id' => $schedule->id,
                'message' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Error al exportar los datos de asistencia.');
        }
    }

    /**
     * Admin report for all attendances
     */
    public function adminReport()
    {
        try {
            $attendances = Attendance::with(['student.user', 'schedule.course'])
                ->orderBy('date', 'desc')
                ->orderBy('time', 'desc')
                ->paginate(20);

            return view('admin.reports.attendance', compact('attendances'));

        } catch (\Exception $e) {
            Log::error('Error generating admin attendance report', [
                'message' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Error al generar el reporte de asistencias.');
        }
    }
}