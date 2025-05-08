<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // CAMBIO: Agregamos código para recuperar las facultades con conteos
        $faculties = Faculty::withCount(['courses', 'students'])->paginate(10);
        return view('admin.faculties.index', compact('faculties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // CAMBIO: Agregamos código para mostrar el formulario de creación
        return view('admin.faculties.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // CAMBIO: Agregamos validación y creación de facultad
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:faculties',
        ]);

        Faculty::create($request->all());

        return redirect()->route('admin.faculties.index')
            ->with('success', 'Facultad creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Faculty $faculty)
    {
        // CAMBIO: Utilizamos model binding y retornamos vista
        return view('admin.faculties.show', compact('faculty'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Faculty $faculty)
    {
        // CAMBIO: Utilizamos model binding y retornamos vista
        return view('admin.faculties.edit', compact('faculty'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Faculty $faculty)
    {
        // CAMBIO: Agregamos validación y actualización
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:faculties,code,' . $faculty->id,
        ]);

        $faculty->update($request->all());

        return redirect()->route('admin.faculties.index')
            ->with('success', 'Facultad actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faculty $faculty)
    {
        // CAMBIO: Agregamos manejo de errores y eliminación
        try {
            $faculty->delete();
            return redirect()->route('admin.faculties.index')
                ->with('success', 'Facultad eliminada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('admin.faculties.index')
                ->with('error', 'No se puede eliminar la facultad porque tiene registros asociados.');
        }
    }
}