@extends('layouts.app')

@section('header')
    Cursos
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-1 fw-semibold">Gestión de Cursos</h2>
        <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Nuevo Curso
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
                            <th scope="col" class="fw-medium text-uppercase text-muted small">Código</th>
                            <th scope="col" class="fw-medium text-uppercase text-muted small">Facultad</th>
                            <th scope="col" class="fw-medium text-uppercase text-muted small">Créditos</th>
                            <th scope="col" class="fw-medium text-uppercase text-muted small">Ciclo</th>
                            <th scope="col" class="fw-medium text-uppercase text-muted small text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                            <tr>
                                <td>{{ $course->id }}</td>
                                <td class="fw-medium">{{ $course->name }}</td>
                                <td class="text-muted">{{ $course->code }}</td>
                                <td class="text-muted">{{ $course->faculty->name }}</td>
                                <td class="text-muted">{{ $course->credits }}</td>
                                <td class="text-muted">{{ $course->cycle }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-sm btn-outline-primary me-1">Editar</a>
                                    <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro de eliminar este curso?')">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No hay cursos registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(isset($courses) && $courses->hasPages())
                <div class="mt-3">
                    {{ $courses->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection