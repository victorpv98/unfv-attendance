@extends('layouts.app')

@section('header')
    Profesores
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fs-1 fw-semibold">Gestión de Profesores</h2>
    <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Nuevo Profesor
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
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col" class="fw-medium text-uppercase text-muted small">ID</th>
                        <th scope="col" class="fw-medium text-uppercase text-muted small">Nombre</th>
                        <th scope="col" class="fw-medium text-uppercase text-muted small">Email</th>
                        <th scope="col" class="fw-medium text-uppercase text-muted small">Código</th>
                        <th scope="col" class="fw-medium text-uppercase text-muted small">Facultad</th>
                        <th scope="col" class="fw-medium text-uppercase text-muted small">Especialidad</th>
                        <th scope="col" class="fw-medium text-uppercase text-muted small text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                        <tr>
                            <td>{{ $teacher->id }}</td>
                            <td class="fw-medium">{{ $teacher->user->name }}</td>
                            <td class="text-muted">{{ $teacher->user->email }}</td>
                            <td class="text-muted">{{ $teacher->code }}</td>
                            <td class="text-muted">{{ $teacher->faculty->name }}</td>
                            <td class="text-muted">{{ $teacher->specialty }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-sm btn-outline-primary me-1">Editar</a>
                                <form action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro de eliminar este profesor?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No hay profesores registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($teachers) && $teachers->hasPages())
            <div class="mt-3">
                {{ $teachers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection