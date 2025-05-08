@extends('layouts.app')

@section('header')
    Horarios
@endsection

@section('content')
<div class="container">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestión de Horarios</h1>
        <a href="{{ route('schedules.create') }}" class="btn btn-primary">
            <i class="fas fa-plus fa-sm"></i> Nuevo Horario
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Lista de Horarios</h6>
            <div class="ml-auto">
                <form action="{{ route('schedules.index') }}" method="GET" class="d-flex">
                    <select name="semester" class="form-select me-2">
                        <option value="">Todos los semestres</option>
                        @foreach($semesters as $sem)
                            <option value="{{ $sem }}" {{ request('semester') == $sem ? 'selected' : '' }}>
                                {{ $sem }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Curso</th>
                            <th>Profesor</th>
                            <th>Aula</th>
                            <th>Día</th>
                            <th>Horario</th>
                            <th>Semestre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $schedule)
                            <tr>
                                <td>{{ $schedule->id }}</td>
                                <td>{{ $schedule->course->name }}</td>
                                <td>{{ $schedule->teacher->user->name }}</td>
                                <td>{{ $schedule->classroom }}</td>
                                <td>{{ __($schedule->day) }}</td>
                                <td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                                <td>{{ $schedule->semester }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('schedules.show', $schedule) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('schedules.destroy', $schedule) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este horario?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No hay horarios registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{ $schedules->links() }}
        </div>
    </div>
</div>
@endsection