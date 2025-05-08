@extends('layouts.app')

@section('header')
    Facultades
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-1 fw-semibold">Lista de Facultades</h2>
        <a href="{{ route('admin.faculties.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Nueva Facultad
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
                            <th scope="col" class="fw-medium text-uppercase text-muted small">Cursos</th>
                            <th scope="col" class="fw-medium text-uppercase text-muted small">Estudiantes</th>
                            <th scope="col" class="fw-medium text-uppercase text-muted small text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($faculties as $faculty)
                            <tr>
                                <td>{{ $faculty->id }}</td>
                                <td class="fw-medium">{{ $faculty->name }}</td>
                                <td class="text-muted">{{ $faculty->code }}</td>
                                <td class="text-muted">{{ $faculty->courses_count ?? 0 }}</td>
                                <td class="text-muted">{{ $faculty->students_count ?? 0 }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.faculties.edit', $faculty) }}" class="btn btn-sm btn-outline-primary me-2">Editar</a>
                                    <form action="{{ route('admin.faculties.destroy', $faculty) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro de eliminar esta facultad?')">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No hay facultades registradas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(isset($faculties) && $faculties->hasPages())
                <div class="mt-3">
                    {{ $faculties->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection