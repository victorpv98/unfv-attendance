<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Schedule;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Schedule::with(['course', 'teacher.user']);
        
        // Filtrar por semestre si se proporciona
        if ($request->has('semester') && $request->semester) {
            $query->where('semester', $request->semester);
        }
        
        $schedules = $query->paginate(10);
        
        // Obtener todos los semestres únicos para el filtro
        $semesters = Schedule::select('semester')->distinct()->pluck('semester');
        
        return view('admin.schedules.index', compact('schedules', 'semesters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::all();
        $teachers = Teacher::with('user')->get();
        $days = [
            'monday' => 'Lunes',
            'tuesday' => 'Martes',
            'wednesday' => 'Miércoles',
            'thursday' => 'Jueves',
            'friday' => 'Viernes',
            'saturday' => 'Sábado',
        ];
        
        return view('admin.schedules.create', compact('courses', 'teachers', 'days'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'teacher_id' => 'required|exists:teachers,id',
            'classroom' => 'required|string|max:50',
            'day' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'semester' => 'required|string|max:20',
        ]);

        // Verificar si existe un horario que se solape
        $existingSchedule = $this->checkScheduleOverlap(
            $request->teacher_id,
            $request->day,
            $request->start_time,
            $request->end_time,
            null
        );

        if ($existingSchedule) {
            return back()->withInput()->with('error', 'El horario se solapa con otro existente del mismo profesor.');
        }

        // Verificar si el aula está ocupada en ese horario
        $existingClassroom = $this->checkClassroomOverlap(
            $request->classroom,
            $request->day,
            $request->start_time,
            $request->end_time,
            null
        );

        if ($existingClassroom) {
            return back()->withInput()->with('error', 'El aula ya está ocupada en ese horario.');
        }

        Schedule::create($request->all());

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Horario creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        $schedule->load(['course', 'teacher.user']);
        return view('admin.schedules.show', compact('schedule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {
        $courses = Course::all();
        $teachers = Teacher::with('user')->get();
        $days = [
            'monday' => 'Lunes',
            'tuesday' => 'Martes',
            'wednesday' => 'Miércoles',
            'thursday' => 'Jueves',
            'friday' => 'Viernes',
            'saturday' => 'Sábado',
        ];
        
        return view('admin.schedules.edit', compact('schedule', 'courses', 'teachers', 'days'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'teacher_id' => 'required|exists:teachers,id',
            'classroom' => 'required|string|max:50',
            'day' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'semester' => 'required|string|max:20',
        ]);

        // Verificar si existe un horario que se solape
        $existingSchedule = $this->checkScheduleOverlap(
            $request->teacher_id,
            $request->day,
            $request->start_time,
            $request->end_time,
            $schedule->id
        );

        if ($existingSchedule) {
            return back()->withInput()->with('error', 'El horario se solapa con otro existente del mismo profesor.');
        }

        // Verificar si el aula está ocupada en ese horario
        $existingClassroom = $this->checkClassroomOverlap(
            $request->classroom,
            $request->day,
            $request->start_time,
            $request->end_time,
            $schedule->id
        );

        if ($existingClassroom) {
            return back()->withInput()->with('error', 'El aula ya está ocupada en ese horario.');
        }

        $schedule->update($request->all());

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Horario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        try {
            $schedule->delete();
            return redirect()->route('admin.schedules.index')
                ->with('success', 'Horario eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('admin.schedules.index')
                ->with('error', 'No se puede eliminar el horario porque tiene asistencias registradas.');
        }
    }

    /**
     * Check if a schedule overlaps with an existing one for the same teacher.
     */
    private function checkScheduleOverlap($teacherId, $day, $startTime, $endTime, $excludeId = null)
    {
        $query = Schedule::where('teacher_id', $teacherId)
            ->where('day', $day)
            ->where(function($q) use ($startTime, $endTime) {
                // Caso 1: El nuevo horario comienza durante un horario existente
                $q->where(function($q1) use ($startTime, $endTime) {
                    $q1->where('start_time', '<=', $startTime)
                       ->where('end_time', '>', $startTime);
                })
                // Caso 2: El nuevo horario termina durante un horario existente
                ->orWhere(function($q2) use ($startTime, $endTime) {
                    $q2->where('start_time', '<', $endTime)
                       ->where('end_time', '>=', $endTime);
                })
                // Caso 3: El nuevo horario contiene completamente un horario existente
                ->orWhere(function($q3) use ($startTime, $endTime) {
                    $q3->where('start_time', '>=', $startTime)
                       ->where('end_time', '<=', $endTime);
                });
            });
            
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->first();
    }

    /**
     * Check if a classroom is already booked for the given time slot.
     */
    private function checkClassroomOverlap($classroom, $day, $startTime, $endTime, $excludeId = null)
    {
        $query = Schedule::where('classroom', $classroom)
            ->where('day', $day)
            ->where(function($q) use ($startTime, $endTime) {
                // Misma lógica que checkScheduleOverlap
                $q->where(function($q1) use ($startTime, $endTime) {
                    $q1->where('start_time', '<=', $startTime)
                       ->where('end_time', '>', $startTime);
                })
                ->orWhere(function($q2) use ($startTime, $endTime) {
                    $q2->where('start_time', '<', $endTime)
                       ->where('end_time', '>=', $endTime);
                })
                ->orWhere(function($q3) use ($startTime, $endTime) {
                    $q3->where('start_time', '>=', $startTime)
                       ->where('end_time', '<=', $endTime);
                });
            });
            
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->first();
    }
}