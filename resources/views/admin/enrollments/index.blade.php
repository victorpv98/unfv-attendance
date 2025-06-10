@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-1 fw-semibold">Gestión de Matrículas</h2>
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
                    <a class="nav-link active" href="{{ route('admin.enrollments.index') }}">
                        <i class="fas fa-list me-1"></i> Todas las Matrículas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.enrollments.byCourse') }}">
                        <i class="fas fa-book me-1"></i> Por Curso
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.enrollments.byStudent') }}">
                        <i class="fas fa-user-graduate me-1"></i> Por Estudiante
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($enrollments->isEmpty())
                <div class="alert alert-info">
                    No hay matrículas registradas actualmente.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Curso</th>
                                <th>Estudiante</th>
                                <th>Semestre</th>
                                <th>Fecha de Matrícula</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrollments as $enrollment)
                                <tr>
                                    <td>{{ $enrollment->id }}</td>
                                    <td>
                                        <strong>{{ $enrollment->course_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $enrollment->course_code }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $enrollment->student_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $enrollment->student_code }}</small>
                                    </td>
                                    <td>{{ $enrollment->semester }}</td>
                                    <td>{{ \Carbon\Carbon::parse($enrollment->created_at)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <form action="{{ route('admin.enrollments.destroy', $enrollment->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar esta matrícula?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $enrollments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection