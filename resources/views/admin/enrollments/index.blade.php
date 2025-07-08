@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-1 fw-semibold text-primary">Gestión de Matrículas</h2>
        <div>
            <a href="{{ route('admin.enrollments.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Nueva Matrícula
            </a>
        </div>
    </div>

    <div class="card shadow border-0 rounded-3">
        <div class="card-header bg-light border-bottom py-3">
            <ul class="nav nav-tabs card-header-tabs border-0">
                <li class="nav-item">
                    <a class="nav-link active border-0 bg-primary text-white rounded-2" href="{{ route('admin.enrollments.index') }}">
                        <i class="fas fa-list me-1"></i> Todas las Matrículas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link border-0 text-secondary" href="{{ route('admin.enrollments.byCourse') }}">
                        <i class="fas fa-book me-1"></i> Por Curso
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link border-0 text-secondary" href="{{ route('admin.enrollments.byStudent') }}">
                        <i class="fas fa-user-graduate me-1"></i> Por Estudiante
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <svg width="20" height="20" class="text-success" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            {{ session('success') }}
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if($enrollments->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-4x text-muted opacity-50 mb-3"></i>
                    <h4 class="text-muted">No hay matrículas registradas</h4>
                    <p class="text-muted mb-4">Comienza creando la primera matrícula del sistema</p>
                    <a href="{{ route('admin.enrollments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Nueva Matrícula
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-primary bg-opacity-10">
                            <tr>
                                <th class="fw-semibold text-primary small py-3">ID</th>
                                <th class="fw-semibold text-primary small py-3">Curso</th>
                                <th class="fw-semibold text-primary small py-3">Estudiante</th>
                                <th class="fw-semibold text-primary small py-3">Semestre</th>
                                <th class="fw-semibold text-primary small py-3">Fecha de Matrícula</th>
                                <th class="fw-semibold text-primary small py-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrollments as $enrollment)
                                <tr class="border-bottom">
                                    <td class="py-3">
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $enrollment->id }}</span>
                                    </td>
                                    <td class="py-3">
                                        <div>
                                            <strong class="text-dark">{{ $enrollment->course_name }}</strong>
                                            <br>
                                            <code class="bg-light px-2 py-1 rounded small">{{ $enrollment->course_code }}</code>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <div>
                                            <strong class="text-dark">{{ $enrollment->student_name }}</strong>
                                            <br>
                                            <small class="text-muted">Código: {{ $enrollment->student_code }}</small>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <span class="badge bg-info">{{ $enrollment->semester }}</span>
                                    </td>
                                    <td class="py-3">
                                        <div>
                                            <strong class="text-dark">{{ \Carbon\Carbon::parse($enrollment->created_at)->format('d/m/Y') }}</strong>
                                            <br>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($enrollment->created_at)->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td class="text-center py-3">
                                        <form action="{{ route('admin.enrollments.destroy', $enrollment->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Eliminar matrícula"
                                                    onclick="return confirm('¿Está seguro de eliminar esta matrícula?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($enrollments->hasPages())
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $enrollments->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection