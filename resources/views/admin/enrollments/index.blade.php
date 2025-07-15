@extends('layouts.app')

@section('header')
    Matrículas
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fs-1 fw-semibold text-primary">Listado de Matrículas</h2>
    <a href="{{ route('admin.enrollments.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Nueva Matrícula
    </a>
</div>

<div class="card shadow border-0 rounded-3">
    <div class="card-body">

        {{-- Alertas --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <svg width="20" height="20" class="text-success" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
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

        {{-- Filtros --}}
        <form method="GET" action="{{ route('admin.enrollments.index') }}" class="row g-3 align-items-end mb-4">
            <div class="col-md-4">
                <label class="form-label">Curso</label>
                <select name="course_id" class="form-select">
                    <option value="">-- Todos --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Estudiante</label>
                <select name="student_id" class="form-select">
                    <option value="">-- Todos --</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Semestre</label>
                <select name="semester" class="form-select">
                    <option value="">-- Todos --</option>
                    @foreach($semesters as $semester)
                        <option value="{{ $semester }}" {{ request('semester') == $semester ? 'selected' : '' }}>
                            {{ $semester }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-1 d-grid">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter me-1"></i> Filtrar
                </button>
            </div>
        </form>

        {{-- Tabla de matrículas --}}
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
                            <th class="fw-semibold text-primary small py-3">#</th>
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
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                        {{ ($enrollments->currentPage() - 1) * $enrollments->perPage() + $loop->iteration }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    <strong class="text-dark">{{ $enrollment->course_name }}</strong><br>
                                    <code class="bg-light px-2 py-1 rounded small">{{ $enrollment->course_code }}</code>
                                </td>
                                <td class="py-3">
                                    <strong class="text-dark">{{ $enrollment->student_name }}</strong><br>
                                    <small class="text-muted">Código: {{ $enrollment->student_code }}</small>
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-info">{{ $enrollment->semester }}</span>
                                </td>
                                <td class="py-3">
                                    <strong class="text-dark">
                                        {{ \Carbon\Carbon::parse($enrollment->created_at)->setTimezone('America/Lima')->format('d/m/Y') }}
                                    </strong><br>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($enrollment->created_at)->setTimezone('America/Lima')->format('H:i') }}
                                    </small>
                                </td>
                                <td class="text-center py-3">
                                    <form action="{{ route('admin.enrollments.destroy', $enrollment->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Está seguro de eliminar esta matrícula?')">
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
                    {{ $enrollments->appends(request()->query())->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection