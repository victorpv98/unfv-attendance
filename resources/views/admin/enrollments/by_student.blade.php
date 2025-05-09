@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Matrículas por Estudiante</h1>
        <div>
            <a href="{{ route('admin.enrollments.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Nueva Matrícula
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.enrollments.index') }}">
                        <i class="fas fa-list me-1"></i> Todas las Matrículas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.enrollments.byCourse') }}">
                        <i class="fas fa-book me-1"></i> Por Curso
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.enrollments.byStudent') }}">
                        <i class="fas fa-user-graduate me-1"></i> Por Estudiante
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            @if($students->isEmpty())
                <div class="alert alert-info">
                    No hay estudiantes registrados actualmente.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Estudiante</th>
                                <th>Código</th>
                                <th>Cursos Matriculados</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                <tr>
                                    <td>{{ $student->user->name }}</td>
                                    <td>{{ $student->code }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $student->courses_count }}</span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#studentCoursesModal{{ $student->id }}">
                                            <i class="fas fa-eye me-1"></i> Ver Cursos
                                        </button>
                                        
                                        <!-- Modal -->
                                        <div class="modal fade" id="studentCoursesModal{{ $student->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $student->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel{{ $student->id }}">
                                                            Cursos de {{ $student->user->name }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @if($student->courses->isEmpty())
                                                            <p class="text-muted">El estudiante no está matriculado en ningún curso este semestre.</p>
                                                        @else
                                                            <ul class="list-group">
                                                                @foreach($student->courses as $course)
                                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                        <div>
                                                                            <strong>{{ $course->name }}</strong>
                                                                            <br>
                                                                            <small class="text-muted">{{ $course->code }} - {{ $course->faculty->name }}</small>
                                                                        </div>
                                                                        <form action="{{ route('admin.courses.unenroll', [$course->id, $student->id]) }}" method="POST">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de querer eliminar esta matrícula?')">
                                                                                <i class="fas fa-trash"></i>
                                                                            </button>
                                                                        </form>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection