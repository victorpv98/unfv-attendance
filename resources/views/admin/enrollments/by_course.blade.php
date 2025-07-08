@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fs-2 fw-semibold text-primary mb-0">Matrículas por Curso</h1>
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
                    <a class="nav-link border-0 text-secondary" href="{{ route('admin.enrollments.index') }}">
                        <i class="fas fa-list me-1"></i> Todas las Matrículas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active border-0 bg-primary text-white rounded-2" href="{{ route('admin.enrollments.byCourse') }}">
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
            @if($courses->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-book-open fa-4x text-muted opacity-50 mb-3"></i>
                    <h4 class="text-muted">No hay cursos registrados</h4>
                    <p class="text-muted mb-4">Primero necesitas registrar cursos antes de ver las matrículas</p>
                    <a href="{{ route('admin.courses.create') }}" class="btn btn-outline-primary">
                        <i class="fas fa-plus me-1"></i> Crear Curso
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-primary bg-opacity-10">
                            <tr>
                                <th class="fw-semibold text-primary small py-3">Curso</th>
                                <th class="fw-semibold text-primary small py-3">Código</th>
                                <th class="fw-semibold text-primary small py-3">Escuela</th>
                                <th class="fw-semibold text-primary small py-3 text-center">Estudiantes Matriculados</th>
                                <th class="fw-semibold text-primary small py-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courses as $course)
                                <tr class="border-bottom">
                                    <td class="py-3">
                                        <div>
                                            <strong class="text-dark">{{ $course->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $course->credits }} créditos • Ciclo {{ $course->cycle }}</small>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <code class="bg-light px-2 py-1 rounded">{{ $course->code }}</code>
                                    </td>
                                    <td class="py-3">
                                        <span class="text-secondary">{{ $course->faculty->name }}</span>
                                    </td>
                                    <td class="text-center py-3">
                                        @if($course->students_count > 0)
                                            <span class="badge bg-success fs-6 px-3 py-2">{{ $course->students_count }}</span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-50 fs-6 px-3 py-2">0</span>
                                        @endif
                                    </td>
                                    <td class="text-center py-3">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.courses.students', $course->id) }}" 
                                               class="btn btn-sm btn-outline-primary"
                                               title="Ver estudiantes matriculados">
                                                <i class="fas fa-eye me-1"></i> Ver Estudiantes
                                            </a>
                                            @if($course->students_count > 0)
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-secondary dropdown-toggle dropdown-toggle-split" 
                                                        data-bs-toggle="dropdown" 
                                                        aria-expanded="false">
                                                    <span class="visually-hidden">Toggle Dropdown</span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="#">
                                                            <i class="fas fa-download me-2"></i> Exportar Lista
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#">
                                                            <i class="fas fa-envelope me-2"></i> Enviar Notificación
                                                        </a>
                                                    </li>
                                                </ul>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($courses->hasPages())
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $courses->links() }}
                    </div>
                @endif

                <!-- Resumen estadístico -->
                <div class="row mt-4 pt-3 border-top">
                    <div class="col-md-3">
                        <div class="text-center">
                            <h4 class="text-primary mb-1">{{ $courses->count() }}</h4>
                            <small class="text-muted">Total Cursos</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h4 class="text-success mb-1">{{ $courses->sum('students_count') }}</h4>
                            <small class="text-muted">Total Matrículas</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h4 class="text-info mb-1">{{ $courses->where('students_count', '>', 0)->count() }}</h4>
                            <small class="text-muted">Cursos con Estudiantes</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h4 class="text-warning mb-1">{{ $courses->where('students_count', 0)->count() }}</h4>
                            <small class="text-muted">Cursos Vacíos</small>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection