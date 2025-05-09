@extends('layouts.app')

@section('header')
    Mis Asistencias
@endsection

@section('content')
    <div class="card shadow border-0 mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                <div class="mb-3 mb-md-0">
                    <h2 class="fs-1 fw-semibold mb-2">Historial de Asistencias</h2>
                    <p class="text-muted">Registro de tus asistencias a clases</p>
                </div>
                
                <div>
                    <form action="{{ route('students.my-attendances') }}" method="GET" class="d-flex flex-wrap gap-3 align-items-end">
                        <div>
                            <label for="course_id" class="form-label small fw-medium">Curso</label>
                            <select id="course_id" name="course_id" class="form-select">
                                <option value="">Todos los cursos</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="month" class="form-label small fw-medium">Mes</label>
                            <select id="month" name="month" class="form-select">
                                <option value="">Todos los meses</option>
                                @foreach($months as $key => $month)
                                    <option value="{{ $key }}" {{ request('month') == $key ? 'selected' : '' }}>
                                        {{ $month }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-secondary">
                            Filtrar
                        </button>
                    </form>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="bg-success bg-opacity-10 rounded p-3">
                        <h5 class="text-success fw-medium mb-2">Presentes</h5>
                        <p class="fs-1 fw-bold text-success mb-0">{{ $summary['present'] ?? 0 }}</p>
                        <p class="small text-success">{{ $summary['presentPercentage'] ?? 0 }}% de asistencia</p>
                    </div>
                </div>
                
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="bg-warning bg-opacity-10 rounded p-3">
                        <h5 class="text-warning fw-medium mb-2">Tardanzas</h5>
                        <p class="fs-1 fw-bold text-warning mb-0">{{ $summary['late'] ?? 0 }}</p>
                        <p class="small text-warning">{{ $summary['latePercentage'] ?? 0 }}% de tardanzas</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="bg-danger bg-opacity-10 rounded p-3">
                        <h5 class="text-danger fw-medium mb-2">Ausencias</h5>
                        <p class="fs-1 fw-bold text-danger mb-0">{{ $summary['absent'] ?? 0 }}</p>
                        <p class="small text-danger">{{ $summary['absentPercentage'] ?? 0 }}% de inasistencia</p>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col" class="fw-medium text-uppercase text-muted small">Fecha</th>
                            <th scope="col" class="fw-medium text-uppercase text-muted small">Hora</th>
                            <th scope="col" class="fw-medium text-uppercase text-muted small">Curso</th>
                            <th scope="col" class="fw-medium text-uppercase text-muted small">Profesor</th>
                            <th scope="col" class="fw-medium text-uppercase text-muted small">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}</td>
                                <td class="text-muted">{{ $attendance->time }}</td>
                                <td class="fw-medium">{{ $attendance->schedule->course->name }}</td>
                                <td class="text-muted">{{ $attendance->schedule->teacher->user->name }}</td>
                                <td>
                                    <span class="badge rounded-pill {{ $attendance->status === 'present' ? 'bg-success' : ($attendance->status === 'late' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                        {{ $attendance->status === 'present' ? 'Presente' : ($attendance->status === 'late' ? 'Tardanza' : 'Ausente') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No hay asistencias registradas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(isset($attendances) && $attendances->hasPages())
                <div class="mt-3">
                    {{ $attendances->appends(request()->except('page'))->links() }}
                </div>
            @endif
        </div>
    </div>

    <div class="card shadow border-0">
        <div class="card-body p-4">
            <h5 class="card-title fw-semibold mb-4">Resumen de Asistencia por Curso</h5>
            
            <div class="row row-gap-4">
                @foreach($coursesSummary as $course)
                    <div class="col-12">
                        <div class="border rounded p-3">
                            <h5 class="fw-medium mb-2">{{ $course->name }}</h5>
                            <div class="row mb-2">
                                <div class="col-sm-6 col-md-3 mb-2">
                                    <p class="text-muted small mb-0">Presente: <span class="fw-medium text-success">{{ $course->present_count ?? 0 }}</span></p>
                                </div>
                                <div class="col-sm-6 col-md-3 mb-2">
                                    <p class="text-muted small mb-0">Tardanza: <span class="fw-medium text-warning">{{ $course->late_count ?? 0 }}</span></p>
                                </div>
                                <div class="col-sm-6 col-md-3 mb-2">
                                    <p class="text-muted small mb-0">Ausente: <span class="fw-medium text-danger">{{ $course->absent_count ?? 0 }}</span></p>
                                </div>
                                <div class="col-sm-6 col-md-3 mb-2">
                                    <p class="text-muted small mb-0">Asistencia: <span class="fw-medium">{{ $course->attendance_percentage ?? 0 }}%</span></p>
                                </div>
                            </div>
                            <div class="progress" style="height: 1.5rem;">
                                <div 
                                    class="progress-bar bg-success" 
                                    role="progressbar" 
                                    style="width: {{ $course->present_percentage ?? 0 }}%" 
                                    aria-valuenow="{{ $course->present_percentage ?? 0 }}" 
                                    aria-valuemin="0" 
                                    aria-valuemax="100">
                                </div>
                                <div 
                                    class="progress-bar bg-warning" 
                                    role="progressbar" 
                                    style="width: {{ $course->late_percentage ?? 0 }}%" 
                                    aria-valuenow="{{ $course->late_percentage ?? 0 }}" 
                                    aria-valuemin="0" 
                                    aria-valuemax="100">
                                </div>
                                <div 
                                    class="progress-bar bg-danger" 
                                    role="progressbar" 
                                    style="width: {{ $course->absent_percentage ?? 0 }}%" 
                                    aria-valuenow="{{ $course->absent_percentage ?? 0 }}" 
                                    aria-valuemin="0" 
                                    aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gráfico de resumen general podría agregarse aquí si se desea
    });
</script>
@endpush