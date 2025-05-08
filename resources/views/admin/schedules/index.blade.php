@extends('layouts.app')

@section('header')
    Horarios
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fs-1 fw-semibold">Gestión de Horarios</h2>
    <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Nuevo Horario
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <span>{{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <span>{{ session('error') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow border-0">
    <div class="card-body">
        <div class="mb-3 d-flex justify-content-end">
            <form action="{{ route('admin.schedules.index') }}" method="GET" class="d-flex align-items-center">
                <select name="semester" class="form-select me-2">
                    <option value="">Todos los semestres</option>
                    @foreach($semesters as $sem)
                        <option value="{{ $sem }}" {{ request('semester') == $sem ? 'selected' : '' }}>
                            {{ $sem }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">
                    Filtrar
                </button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col" class="fw-medium text-uppercase text-muted small">ID</th>
                        <th scope="col" class="fw-medium text-uppercase text-muted small">Curso</th>
                        <th scope="col" class="fw-medium text-uppercase text-muted small">Profesor</th>
                        <th scope="col" class="fw-medium text-uppercase text-muted small">Aula</th>
                        <th scope="col" class="fw-medium text-uppercase text-muted small">Día</th>
                        <th scope="col" class="fw-medium text-uppercase text-muted small">Horario</th>
                        <th scope="col" class="fw-medium text-uppercase text-muted small">Semestre</th>
                        <th scope="col" class="fw-medium text-uppercase text-muted small text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule->id }}</td>
                            <td class="fw-medium">{{ $schedule->course->name }}</td>
                            <td class="text-muted">{{ $schedule->teacher->user->name }}</td>
                            <td class="text-muted">{{ $schedule->classroom }}</td>
                            <td class="text-muted">{{ __($schedule->day) }}</td>
                            <td class="text-muted">{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                            <td class="text-muted">{{ $schedule->semester }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.schedules.edit', $schedule) }}" class="btn btn-sm btn-outline-primary me-1">Editar</a>
                                <a href="{{ route('admin.schedules.show', $schedule) }}" class="btn btn-sm btn-outline-info me-1">Ver</a>
                                <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro de eliminar este horario?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No hay horarios registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($schedules) && $schedules->hasPages())
            <div class="mt-3">
                {{ $schedules->links() }}
            </div>
        @endif
    </div>
</div>
@endsection