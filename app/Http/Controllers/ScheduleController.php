<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Schedule;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = Schedule::with(['course', 'teacher.user']);

        if ($request->has('semester') && $request->semester) {
            $query->where('semester', $request->semester);
        }

        $schedules = $query->paginate(10);
        $semesters = Schedule::select('semester')->distinct()->pluck('semester');

        return view('admin.schedules.index', compact('schedules', 'semesters'));
    }

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
            'tardiness_tolerance' => 'nullable|integer|min:0|max:60', // ✅ Validación nueva
        ]);

        // Validaciones de solapamiento
        if ($this->checkScheduleOverlap($request->teacher_id, $request->day, $request->start_time, $request->end_time)) {
            return back()->withInput()->with('error', 'El horario se solapa con otro existente del mismo profesor.');
        }

        if ($this->checkClassroomOverlap($request->classroom, $request->day, $request->start_time, $request->end_time)) {
            return back()->withInput()->with('error', 'El aula ya está ocupada en ese horario.');
        }

        Schedule::create($request->all());

        return redirect()->route('admin.schedules.index')->with('success', 'Horario creado exitosamente.');
    }

    public function show(Schedule $schedule)
    {
        $schedule->load(['course', 'teacher.user']);
        return view('admin.schedules.show', compact('schedule'));
    }

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
            'tardiness_tolerance' => 'nullable|integer|min:0|max:60', // ✅ Validación nueva
        ]);

        if ($this->checkScheduleOverlap($request->teacher_id, $request->day, $request->start_time, $request->end_time, $schedule->id)) {
            return back()->withInput()->with('error', 'El horario se solapa con otro existente del mismo profesor.');
        }

        if ($this->checkClassroomOverlap($request->classroom, $request->day, $request->start_time, $request->end_time, $schedule->id)) {
            return back()->withInput()->with('error', 'El aula ya está ocupada en ese horario.');
        }

        $schedule->update($request->all());

        return redirect()->route('admin.schedules.index')->with('success', 'Horario actualizado exitosamente.');
    }

    public function destroy(Schedule $schedule)
    {
        try {
            $schedule->delete();
            return redirect()->route('admin.schedules.index')->with('success', 'Horario eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('admin.schedules.index')->with('error', 'No se puede eliminar el horario porque tiene asistencias registradas.');
        }
    }

    public function getDayInSpanishAttribute()
    {
        $dias = [
            'monday' => 'Lunes',
            'tuesday' => 'Martes',
            'wednesday' => 'Miércoles',
            'thursday' => 'Jueves',
            'friday' => 'Viernes',
            'saturday' => 'Sábado',
            'sunday' => 'Domingo'
        ];

        return $dias[strtolower($this->day)] ?? $this->day;
    }

    private function checkScheduleOverlap($teacherId, $day, $startTime, $endTime, $excludeId = null)
    {
        $query = Schedule::where('teacher_id', $teacherId)
            ->where('day', $day)
            ->where(function($q) use ($startTime, $endTime) {
                $q->where(function($q1) use ($startTime) {
                        $q1->where('start_time', '<=', $startTime)
                           ->where('end_time', '>', $startTime);
                    })
                    ->orWhere(function($q2) use ($endTime) {
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

    private function checkClassroomOverlap($classroom, $day, $startTime, $endTime, $excludeId = null)
    {
        $query = Schedule::where('classroom', $classroom)
            ->where('day', $day)
            ->where(function($q) use ($startTime, $endTime) {
                $q->where(function($q1) use ($startTime) {
                        $q1->where('start_time', '<=', $startTime)
                           ->where('end_time', '>', $startTime);
                    })
                    ->orWhere(function($q2) use ($endTime) {
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