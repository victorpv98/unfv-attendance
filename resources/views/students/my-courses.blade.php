@extends('layouts.app')

@section('header')
    Mis Cursos
@endsection

@section('content')
    <div class="card shadow border-0 mb-4">
        <div class="card-body p-4">
            <div class="mb-4">
                <h2 class="fs-1 fw-semibold mb-2">Mis Cursos Matriculados</h2>
                <p class="text-muted">Semestre: {{ $currentSemester }}</p>
            </div>

            <div class="row row-gap-4">
                @forelse($courses as $course)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border rounded shadow-sm">
                            <div class="card-header bg-primary text-white p-3">
                                <h5 class="card-title mb-1">{{ $course->name }}</h5>
                                <p class="card-subtitle text-white-50 mb-0">{{ $course->code }}</p>
                            </div>
                            <div class="card-body p-3">
                                <p class="text-muted small mb-2">Escuela: {{ $course->faculty->name }}</p>
                                <p class="text-muted small mb-2">Créditos: {{ $course->credits }}</p>
                                <p class="text-muted small mb-3">Ciclo: {{ $course->cycle }}</p>
                                
                                <div class="mb-3">
                                    <h6 class="fw-medium small text-dark mb-2">Horarios</h6>
                                    <ul class="list-unstyled">
                                        @foreach($course->schedules as $schedule)
                                            <li class="bg-light p-2 rounded mb-2 small">
                                                <span class="fw-medium">{{ __($schedule->day) }}: </span>
                                                <span>{{ $schedule->start_time }} - {{ $schedule->end_time }}</span>
                                                <br>
                                                <span class="text-muted">Aula: {{ $schedule->classroom }}</span>
                                                <br>
                                                <span class="text-muted">Profesor: {{ $schedule->teacher->user->name }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                
                                <div class="mb-3">
                                    <h6 class="fw-medium small text-dark mb-2">Asistencia</h6>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 me-2">
                                            <div class="progress" style="height: 6px;">
                                                <div 
                                                    class="progress-bar bg-success" 
                                                    role="progressbar" 
                                                    style="width: {{ $coursesAttendance[$course->id]['percentage'] ?? 0 }}%" 
                                                    aria-valuenow="{{ $coursesAttendance[$course->id]['percentage'] ?? 0 }}" 
                                                    aria-valuemin="0" 
                                                    aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                        <span class="small fw-medium text-muted">
                                            {{ $coursesAttendance[$course->id]['percentage'] ?? 0 }}%
                                        </span>
                                    </div>
                                </div>
                                
                                <a href="{{ route('students.course-attendances', $course) }}" class="btn btn-link text-primary p-0 small fw-medium">
                                    Ver detalle de asistencias →
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning text-center py-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="d-block mx-auto mb-3 text-warning">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <h5 class="fw-medium text-dark mb-2">No estás matriculado en ningún curso</h5>
                            <p class="small mb-0">Contacta con administración para matricularte en tus cursos.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @if(!empty($pastSemesters))
        <div class="card shadow border-0">
            <div class="card-body p-4">
                <h5 class="card-title fw-semibold mb-4">Historial Académico</h5>
                
                <div class="mb-4">
                    <form action="{{ route('students.my-courses') }}" method="GET" class="d-flex gap-3 align-items-end">
                        <div>
                            <label for="semester" class="form-label small fw-medium">Semestre</label>
                            <select id="semester" name="semester" class="form-select">
                                @foreach($pastSemesters as $semester)
                                    <option value="{{ $semester }}" {{ request('semester') == $semester ? 'selected' : '' }}>
                                        {{ $semester }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <button type="submit" class="btn btn-secondary">
                                Ver Cursos
                            </button>
                        </div>
                    </form>
                </div>
                
                @if(isset($pastCourses) && count($pastCourses) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col" class="fw-medium text-uppercase text-muted small">Curso</th>
                                    <th scope="col" class="fw-medium text-uppercase text-muted small">Código</th>
                                    <th scope="col" class="fw-medium text-uppercase text-muted small">Créditos</th>
                                    <th scope="col" class="fw-medium text-uppercase text-muted small">Asistencia</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pastCourses as $course)
                                    <tr>
                                        <td class="fw-medium">{{ $course->name }}</td>
                                        <td class="text-muted">{{ $course->code }}</td>
                                        <td class="text-muted">{{ $course->credits }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div style="width: 80px;" class="me-2">
                                                    <div class="progress" style="height: 6px;">
                                                        <div 
                                                            class="progress-bar bg-success" 
                                                            role="progressbar" 
                                                            style="width: {{ $pastCoursesAttendance[$course->id]['percentage'] ?? 0 }}%" 
                                                            aria-valuenow="{{ $pastCoursesAttendance[$course->id]['percentage'] ?? 0 }}" 
                                                            aria-valuemin="0" 
                                                            aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="small">
                                                    {{ $pastCoursesAttendance[$course->id]['percentage'] ?? 0 }}%
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No hay cursos disponibles para el semestre seleccionado.</p>
                @endif
            </div>
        </div>
    @endif
@endsection