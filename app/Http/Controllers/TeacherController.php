<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = Teacher::with(['user', 'faculty'])->paginate(10);
        return view('admin.teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $faculties = Faculty::all();
        return view('admin.teachers.create', compact('faculties'));
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
            'code' => 'required|string|max:20|unique:teachers',
            'faculty_id' => 'required|exists:faculties,id',
            'specialty' => 'required|string|max:100',
        ]);

        DB::beginTransaction();
        try {
            // Crear el usuario
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'teacher',
            ]);

            // Crear el profesor
            Teacher::create([
                'user_id' => $user->id,
                'faculty_id' => $request->faculty_id,
                'code' => $request->code,
                'specialty' => $request->specialty,
            ]);

            DB::commit();
            return redirect()->route('admin.teachers.index')
                ->with('success', 'Profesor creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al crear el profesor: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        $teacher->load(['user', 'faculty', 'schedules']);
        return view('admin.teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        $faculties = Faculty::all();
        return view('admin.teachers.edit', compact('teacher', 'faculties'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $teacher->user_id,
            'code' => 'required|string|max:20|unique:teachers,code,' . $teacher->id,
            'faculty_id' => 'required|exists:faculties,id',
            'specialty' => 'required|string|max:100',
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

            $teacher->user->update($userData);

            // Actualizar el profesor
            $teacher->update([
                'faculty_id' => $request->faculty_id,
                'code' => $request->code,
                'specialty' => $request->specialty,
            ]);

            DB::commit();
            return redirect()->route('admin.teachers.index')
                ->with('success', 'Profesor actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar el profesor: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        try {
            DB::beginTransaction();
            
            // Verificar si el profesor tiene horarios asignados
            if ($teacher->schedules()->count() > 0) {
                throw new \Exception('No se puede eliminar el profesor porque tiene horarios asignados.');
            }
            
            // Obtener el usuario asociado
            $user = $teacher->user;
            
            // Eliminar el profesor
            $teacher->delete();
            
            // Eliminar el usuario
            $user->delete();
            
            DB::commit();
            return redirect()->route('admin.teachers.index')
                ->with('success', 'Profesor eliminado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.teachers.index')
                ->with('error', 'Error al eliminar el profesor: ' . $e->getMessage());
        }
    }

    /**
     * Display teacher's schedules.
     */
    public function mySchedules()
    {
        $teacher = Teacher::where('user_id', auth()->id())->firstOrFail();
        $schedules = $teacher->schedules()->with(['course', 'course.faculty'])->get();
        
        // Obtener semestres únicos o un array vacío si no hay horarios
        $semesters = $schedules->isEmpty() ? collect() : $schedules->pluck('semester')->unique()->values();
        
        return view('teachers.my-schedules', compact('schedules', 'semesters'));
    }

    /**
     * Show QR scanner for a schedule.
     */
    public function scanQr($schedule)
    {
        $teacher = Teacher::where('user_id', auth()->id())->firstOrFail();
        $schedule = $teacher->schedules()->findOrFail($schedule);
        $attendances = \App\Models\Attendance::where('schedule_id', $schedule->id)
            ->whereDate('date', now()->toDateString())
            ->with(['student.user'])
            ->orderBy('time', 'desc')
            ->get();
        return view('teachers.scan-qr', compact('schedule', 'attendances'));
    }
}