@extends('layouts.app')

@section('header')
    Mis Cursos
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-1 fw-semibold text-primary">Cursos Matriculados</h2>
    </div>

    @if($courses->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-book-open fa-3x text-muted opacity-50 mb-3"></i>
            <h4 class="text-muted">No estás matriculado en ningún curso</h4>
            <p class="text-muted">Contacta a tu coordinador académico si crees que esto es un error.</p>
        </div>
    @else
        <div class="row">
            @foreach($courses as $course)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-primary">
                                <i class="fas fa-book me-2"></i>{{ $course->name }}
                            </h5>
                            <p class="mb-1">
                                <strong>Código:</strong> <code>{{ $course->code }}</code>
                            </p>
                            <p class="mb-1">
                                <strong>Escuela:</strong> {{ $course->faculty->name ?? 'Sin facultad' }}
                            </p>
                            <p class="mb-1">
                                <strong>Semestre:</strong> {{ $course->pivot->semester }}
                            </p>
                            @if(isset($coursesAttendance[$course->id]))
                                <div class="mt-2">
                                    <strong>Asistencia:</strong>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $coursesAttendance[$course->id]['percentage'] }}%">
                                            {{ $coursesAttendance[$course->id]['percentage'] }}%
                                        </div>
                                    </div>
                                    <small class="text-muted">Asistencias: {{ $coursesAttendance[$course->id]['attended'] }} / {{ $coursesAttendance[$course->id]['total'] }}</small>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer bg-light text-end">
                            <a href="{{ route('students.course-attendances', $course->id) }}" class="btn btn-outline-primary btn-sm">
                                Ver Asistencias
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection