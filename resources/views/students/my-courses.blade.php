@extends('layouts.app')

@section('header')
    Mis Cursos
@endsection

@section('content')
    <div class="card shadow border-0 rounded-3 mb-4">
        <div class="card-body p-4">
            <div class="mb-4">
                <h2 class="fs-1 fw-semibold text-primary mb-2">Mis Cursos Matriculados</h2>
                <p class="text-muted">
                    <i class="fas fa-calendar-alt me-1"></i>
                    Semestre: <span class="fw-medium">{{ $currentSemester }}</span>
                </p>
            </div>

            <div class="row g-4">
                @forelse($courses as $course)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 rounded-3 shadow-sm">
                            <div class="card-header bg-primary text-white p-3 border-0 rounded-top-3">
                                <h5 class="card-title mb-1">{{ $course->name }}</h5>
                                <p class="card-subtitle mb-0 opacity-75">
                                    <i class="fas fa-code me-1"></i>{{ $course->code }}
                                </p>
                            </div>
                            <div class="card-body p-3">
                                <div class="mb-3">
                                    <p class="text-muted small mb-1">
                                        <i class="fas fa-university me-1"></i>Escuela: {{ $course->faculty->name }}
                                    </p>
                                    <p class="text-muted small mb-1">
                                        <i class="fas fa-award me-1"></i>Créditos: {{ $course->credits }}
                                    </p>
                                    <p class="text-muted small mb-0">
                                        <i class="fas fa-layer-group me-1"></i>Ciclo: {{ $course->cycle }}
                                    </p>
                                </div>
                                
                                <div class="mb-3">
                                    <h6 class="fw-semibold small text-dark mb-2">
                                        <i class="fas fa-clock me-1 text-primary"></i>Horarios
                                    </h6>
                                    <div class="border rounded p-2" style="background-color: #f8f9fa;">
                                        @foreach($course->schedules as $schedule)
                                            <div class="mb-2 @if(!$loop->last) border-bottom pb-2 @endif">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <span class="badge bg-info text-dark small">{{ __($schedule->day) }}</span>
                                                    <span class="small fw-medium">{{ $schedule->start_time }} - {{ $schedule->end_time }}</span>
                                                </div>
                                                <div class="mt-1">
                                                    <small class="text-muted d-block">
                                                        <i class="fas fa-map-marker-alt me-1"></i>Aula: {{ $schedule->classroom }}
                                                    </small>
                                                    <small class="text-muted d-block">
                                                        <i class="fas fa-chalkboard-teacher me-1"></i>Prof: {{ $schedule->teacher->user->name }}
                                                    </small>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <h6 class="fw-semibold small text-dark mb-2">
                                        <i class="fas fa-chart-line me-1 text-success"></i>Asistencia
                                    </h6>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 me-2">
                                            <div class="progress" style="height: 8px;">
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
                                        <span class="small fw-semibold text-success">
                                            {{ $coursesAttendance[$course->id]['percentage'] ?? 0 }}%
                                        </span>
                                    </div>
                                </div>
                                
                                <a href="{{ route('students.course-attendances', $course) }}" class="btn btn-outline-primary btn-sm w-100">
                                    <i class="fas fa-eye me-1"></i>Ver detalle de asistencias
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-exclamation-triangle fa-4x text-warning opacity-50"></i>
                            </div>
                            <h4 class="fw-semibold text-dark mb-2">No estás matriculado en ningún curso</h4>
                            <p class="text-muted mb-4">Contacta con administración para matricularte en tus cursos.</p>
                            <div class="alert alert-info d-inline-block">
                                <i class="fas fa-info-circle me-2"></i>
                                Comunícate con la oficina de registros académicos para más información.
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @if(!empty($pastSemesters))
        <div class="card shadow border-0 rounded-3">
            <div class="card-header bg-light border-0 rounded-top-3 p-4">
                <h5 class="card-title fw-semibold mb-0 text-secondary">
                    <i class="fas fa-history me-2"></i>Historial Académico
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-4">
                    <form action="{{ route('students.my-courses') }}" method="GET" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="semester" class="form-label small fw-semibold">
                                <i class="fas fa-calendar me-1"></i>Seleccionar Semestre
                            </label>
                            <select id="semester" name="semester" class="form-select">
                                @foreach($pastSemesters as $semester)
                                    <option value="{{ $semester }}" {{ request('semester') == $semester ? 'selected' : '' }}>
                                        {{ $semester }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-secondary">
                                <i class="fas fa-search me-1"></i>Ver Cursos
                            </button>
                        </div>
                    </form>
                </div>
                
                @if(isset($pastCourses) && count($pastCourses) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-primary bg-opacity-10">
                                <tr>
                                    <th scope="col" class="fw-semibold text-primary small py-3">Curso</th>
                                    <th scope="col" class="fw-semibold text-primary small py-3">Código</th>
                                    <th scope="col" class="fw-semibold text-primary small py-3">Créditos</th>
                                    <th scope="col" class="fw-semibold text-primary small py-3">Asistencia</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pastCourses as $course)
                                    <tr class="border-bottom">
                                        <td class="fw-medium py-3">{{ $course->name }}</td>
                                        <td class="py-3">
                                            <code class="bg-light px-2 py-1 rounded">{{ $course->code }}</code>
                                        </td>
                                        <td class="py-3">
                                            <span class="badge bg-info">{{ $course->credits }} créditos</span>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <div style="width: 100px;" class="me-3">
                                                    <div class="progress" style="height: 8px;">
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
                                                <span class="small fw-semibold text-success">
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
                    <div class="text-center py-4">
                        <i class="fas fa-folder-open fa-3x text-muted opacity-50 mb-3"></i>
                        <p class="text-muted mb-0">No hay cursos disponibles para el semestre seleccionado.</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
@endsection