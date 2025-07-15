@extends('layouts.app')

@section('header')
    Horarios
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fs-1 fw-semibold text-primary">Gestión de Horarios</h2>
    <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Nuevo Horario
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex">
            <div class="flex-shrink-0">
                <svg width="20" height="20" class="text-success" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ms-3 flex-grow-1">
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex">
            <div class="flex-shrink-0">
                <svg width="20" height="20" class="text-danger" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ms-3 flex-grow-1">
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

<div class="card shadow border-0 rounded-3">
    <div class="card-header bg-light border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 text-secondary">
                <i class="fas fa-calendar-alt me-2"></i>Listado de Horarios
            </h5>
            <form action="{{ route('admin.schedules.index') }}" method="GET" class="d-flex align-items-center">
                <select name="semester" class="form-select form-select-sm me-2" style="width: 200px;">
                    <option value="">Todos los semestres</option>
                    @foreach($semesters as $sem)
                        <option value="{{ $sem }}" {{ request('semester') == $sem ? 'selected' : '' }}>
                            {{ $sem }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-filter me-1"></i>Filtrar
                </button>
            </form>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-primary bg-opacity-10">
                    <tr>
                        <th scope="col" class="fw-semibold text-primary small py-3">ID</th>
                        <th scope="col" class="fw-semibold text-primary small py-3">Curso</th>
                        <th scope="col" class="fw-semibold text-primary small py-3">Profesor</th>
                        <th scope="col" class="fw-semibold text-primary small py-3">Aula</th>
                        <th scope="col" class="fw-semibold text-primary small py-3">Día</th>
                        <th scope="col" class="fw-semibold text-primary small py-3">Horario</th>
                        <th scope="col" class="fw-semibold text-primary small py-3">Semestre</th>
                        <th scope="col" class="fw-semibold text-primary small py-3 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $schedule)
                        <tr class="border-bottom">
                            <td class="py-3">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $loop->iteration }}</span>
                            </td>
                            <td class="fw-medium py-3">{{ $schedule->course->name }}</td>
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                        <i class="fas fa-user text-primary small"></i>
                                    </div>
                                    <span class="text-dark">{{ $schedule->teacher->user->name }}</span>
                                </div>
                            </td>
                            <td class="py-3">
                                <span class="badge bg-info bg-opacity-10 text-info">
                                    <i class="fas fa-door-open me-1"></i>{{ $schedule->classroom }}
                                </span>
                            </td>
                            <td class="py-3">
                                <span class="badge bg-warning text-dark">{{ __($schedule->day) }}</span>
                            </td>
                            <td class="py-3">
                                <div class="small">
                                    <strong class="text-dark">{{ \Carbon\Carbon::parse($schedule->start_time)->format('g:ia') }}</strong>
                                    <span class="text-muted">-</span>
                                    <strong class="text-dark">{{ \Carbon\Carbon::parse($schedule->end_time)->format('g:ia') }}</strong>
                                </div>
                            </td>
                            <td class="py-3">
                                <span class="badge bg-success">{{ $schedule->semester }}</span>
                            </td>
                            <td class="text-end py-3">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.schedules.edit', $schedule) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Editar horario">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.schedules.destroy', $schedule) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger" 
                                                title="Eliminar horario"
                                                onclick="return confirm('¿Estás seguro de eliminar este horario?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-calendar-times fa-3x mb-3 text-muted opacity-50"></i>
                                    <h5 class="text-muted">No hay horarios registrados</h5>
                                    <p class="mb-3">No se encontraron horarios para los criterios seleccionados</p>
                                    <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i> Crear Primer Horario
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($schedules) && $schedules->hasPages())
            <div class="card-footer bg-light border-top">
                <div class="d-flex justify-content-center">
                    {{ $schedules->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection