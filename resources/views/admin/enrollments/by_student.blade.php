@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fs-2 fw-semibold text-primary mb-0">Matrículas por Estudiante</h1>
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
                    <a class="nav-link border-0 text-secondary" href="{{ route('admin.enrollments.byCourse') }}">
                        <i class="fas fa-book me-1"></i> Por Curso
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active border-0 bg-primary text-white rounded-2" href="{{ route('admin.enrollments.byStudent') }}">
                        <i class="fas fa-user-graduate me-1"></i> Por Estudiante
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            @if($students->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-user-graduate fa-4x text-muted opacity-50 mb-3"></i>
                    <h4 class="text-muted">No hay estudiantes registrados</h4>
                    <p class="text-muted mb-4">Registra estudiantes para comenzar a gestionar matrículas</p>
                    <a href="{{ route('admin.students.create') }}" class="btn btn-outline-primary">
                        <i class="fas fa-user-plus me-1"></i> Registrar Estudiante
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-primary bg-opacity-10">
                            <tr>
                                <th class="fw-semibold text-primary small py-3">Estudiante</th>
                                <th class="fw-semibold text-primary small py-3">Código</th>
                                <th class="fw-semibold text-primary small py-3 text-center">Cursos Matriculados</th>
                                <th class="fw-semibold text-primary small py-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                <tr class="border-bottom">
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                            <div>
                                                <strong class="text-dark">{{ $student->user->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $student->user->email ?? 'Sin email' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <code class="bg-light px-2 py-1 rounded">{{ $student->code }}</code>
                                    </td>
                                    <td class="text-center py-3">
                                        @if($student->courses_count > 0)
                                            <span class="badge bg-success fs-6">{{ $student->courses_count }} curso{{ $student->courses_count > 1 ? 's' : '' }}</span>
                                        @else
                                            <span class="badge bg-secondary">Sin cursos</span>
                                        @endif
                                    </td>
                                    <td class="text-center py-3">
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#studentCoursesModal{{ $student->id }}"
                                                title="Ver cursos del estudiante">
                                            <i class="fas fa-eye me-1"></i> Ver Cursos
                                        </button>
                                        
                                        <!-- Modal -->
                                        <div class="modal fade" id="studentCoursesModal{{ $student->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $student->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title" id="modalLabel{{ $student->id }}">
                                                            <i class="fas fa-book me-2"></i>
                                                            Cursos de {{ $student->user->name }}
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @if($student->courses->isEmpty())
                                                            <div class="text-center py-4">
                                                                <i class="fas fa-book-open fa-3x text-muted opacity-50 mb-3"></i>
                                                                <h5 class="text-muted">Sin matrículas</h5>
                                                                <p class="text-muted">El estudiante no está matriculado en ningún curso este semestre.</p>
                                                            </div>
                                                        @else
                                                            <div class="list-group list-group-flush">
                                                                @foreach($student->courses as $course)
                                                                    <div class="list-group-item d-flex justify-content-between align-items-center border-0 border-bottom">
                                                                        <div class="flex-grow-1">
                                                                            <div class="d-flex align-items-center">
                                                                                <div class="bg-info rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                                                                    <i class="fas fa-book text-white small"></i>
                                                                                </div>
                                                                                <div>
                                                                                    <strong class="text-dark">{{ $course->name }}</strong>
                                                                                    <br>
                                                                                    <small class="text-muted">
                                                                                        <code class="bg-light px-1 py-0 rounded small">{{ $course->code }}</code>
                                                                                        • {{ $course->faculty->name }}
                                                                                        • {{ $course->credits }} créditos
                                                                                    </small>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <form action="{{ route('admin.courses.unenroll', [$course->id, $student->id]) }}" method="POST" class="ms-2">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" 
                                                                                    class="btn btn-sm btn-outline-danger" 
                                                                                    title="Eliminar matrícula"
                                                                                    onclick="return confirm('¿Está seguro de eliminar la matrícula de {{ $student->user->name }} del curso {{ $course->name }}?')">
                                                                                <i class="fas fa-trash"></i>
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer bg-light">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            <i class="fas fa-times me-1"></i> Cerrar
                                                        </button>
                                                        @if(!$student->courses->isEmpty())
                                                            <a href="{{ route('admin.enrollments.create', ['student_id' => $student->id]) }}" class="btn btn-primary">
                                                                <i class="fas fa-plus me-1"></i> Agregar Curso
                                                            </a>
                                                        @endif
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
                
                @if($students->hasPages())
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $students->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection