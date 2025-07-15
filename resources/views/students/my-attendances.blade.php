@extends('layouts.app')

@section('header')
    Mis Asistencias
@endsection

@section('content')
    <div class="card shadow border-0 rounded-3 mb-4">
        <div class="card-header bg-primary text-white py-3">
            <div class="d-flex align-items-center">
                <i class="fas fa-calendar-check me-2"></i>
                <h5 class="mb-0 fw-semibold">Historial de Asistencias</h5>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                <div class="mb-3 mb-md-0">
                    <h2 class="fs-1 fw-semibold text-primary mb-2">Registro de Asistencias</h2>
                    <p class="text-muted">Monitorea tu asistencia a clases y rendimiento académico</p>
                </div>
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
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i>
                        Filtrar
                    </button>
                </form>
            </div>

            <!-- Tarjetas resumen -->
            <div class="row g-3 mb-4">
                @php
                    $present = $summary['present'] ?? 0;
                    $late = $summary['late'] ?? 0;
                    $absent = $summary['absent'] ?? 0;
                    $presentPercent = $summary['presentPercentage'] ?? 0;
                    $latePercent = $summary['latePercentage'] ?? 0;
                    $absentPercent = $summary['absentPercentage'] ?? 0;
                @endphp

                <x-attendance-summary title="Presentes" icon="check-circle" count="{{ $present }}" percentage="{{ $presentPercent }}" class="success" />
                <x-attendance-summary title="Tardanzas" icon="clock" count="{{ $late }}" percentage="{{ $latePercent }}" class="warning" />
                <x-attendance-summary title="Ausencias" icon="times-circle" count="{{ $absent }}" percentage="{{ $absentPercent }}" class="danger" />
            </div>

            <!-- Tabla de asistencias -->
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-primary bg-opacity-10">
                        <tr>
                            <th class="fw-semibold text-primary small py-3">Fecha</th>
                            <th class="fw-semibold text-primary small py-3">Hora</th>
                            <th class="fw-semibold text-primary small py-3">Curso</th>
                            <th class="fw-semibold text-primary small py-3">Profesor</th>
                            <th class="fw-semibold text-primary small py-3 text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                            <tr class="border-bottom">
                                <td class="py-3">
                                    <strong>{{ $attendance->date->format('d/m/Y') }}</strong><br>
                                    <small class="text-muted">{{ $attendance->date->locale('es')->translatedFormat('l') }}</small>
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $loop->iteration }}</span>
                                </td>
                                <td class="py-3">
                                    <strong class="text-dark">{{ $attendance->schedule->course->name }}</strong><br>
                                    <code class="bg-light px-2 py-1 rounded small">{{ $attendance->schedule->course->code }}</code>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                            <i class="fas fa-user text-primary"></i>
                                        </div>
                                        <span class="text-dark">{{ $attendance->schedule->teacher->user->name }}</span>
                                    </div>
                                </td>
                                <td class="text-center py-3">
                                    @if($attendance->status === 'present')
                                        <span class="badge bg-success rounded-pill px-3 py-2">
                                            <i class="fas fa-check me-1"></i>Presente
                                        </span>
                                    @elseif($attendance->status === 'late')
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                            <i class="fas fa-clock me-1"></i>Tardanza
                                        </span>
                                    @else
                                        <span class="badge bg-danger rounded-pill px-3 py-2">
                                            <i class="fas fa-times me-1"></i>Ausente
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="fas fa-calendar-times fa-3x text-muted opacity-50 mb-3"></i>
                                    <h5 class="text-muted">No hay asistencias registradas</h5>
                                    <p class="text-muted mb-0">Los registros aparecerán aquí cuando se tomen asistencias</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($attendances->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $attendances->appends(request()->except('page'))->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Resumen por curso -->
    <div class="card shadow border-0 rounded-3">
        <div class="card-header bg-light border-bottom py-3">
            <div class="d-flex align-items-center">
                <i class="fas fa-chart-bar me-2 text-primary"></i>
                <h5 class="mb-0 fw-semibold text-primary">Resumen de Asistencia por Curso</h5>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="row g-4">
                @forelse($coursesSummary as $course)
                    @include('students.partials.course-summary', ['course' => $course])
                @empty
                    <div class="col-12">
                        <div class="text-center py-4">
                            <i class="fas fa-book-open fa-2x text-muted opacity-50 mb-2"></i>
                            <p class="text-muted mb-0">No hay cursos matriculados</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection