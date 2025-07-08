@extends('layouts.app')

@section('header')
    Asistencias del Curso
@endsection

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow border-0 rounded-3">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-calendar-check me-2"></i>
                        {{ $course->name }}
                    </h5>
                    <a href="{{ route('students.my-courses') }}" class="btn btn-sm btn-light">
                        <i class="fas fa-arrow-left me-1"></i> Volver a mis cursos
                    </a>
                </div>
                
                <div class="card-body p-4">
                    @if($attendances->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted opacity-50 mb-3"></i>
                            <h4 class="text-muted">Sin registros de asistencia</h4>
                            <p class="text-muted mb-0">Aún no hay clases registradas para este curso</p>
                        </div>
                    @else
                        <!-- Resumen de asistencia -->
                        <div class="row mb-4">
                            <div class="col-md-3 mb-3">
                                <div class="card bg-success bg-opacity-10 border-success border-opacity-25 h-100">
                                    <div class="card-body text-center py-3">
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <i class="fas fa-check-circle text-success fs-4 me-2"></i>
                                            <h3 class="mb-0 text-success fw-bold">
                                                {{ $attendances->where('status', 'present')->count() }}
                                            </h3>
                                        </div>
                                        <p class="mb-0 text-success fw-medium">Presentes</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-warning bg-opacity-10 border-warning border-opacity-25 h-100">
                                    <div class="card-body text-center py-3">
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <i class="fas fa-clock text-warning fs-4 me-2"></i>
                                            <h3 class="mb-0 text-warning fw-bold">
                                                {{ $attendances->where('status', 'late')->count() }}
                                            </h3>
                                        </div>
                                        <p class="mb-0 text-warning fw-medium">Tardanzas</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-danger bg-opacity-10 border-danger border-opacity-25 h-100">
                                    <div class="card-body text-center py-3">
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <i class="fas fa-times-circle text-danger fs-4 me-2"></i>
                                            <h3 class="mb-0 text-danger fw-bold">
                                                {{ $attendances->where('status', 'absent')->count() }}
                                            </h3>
                                        </div>
                                        <p class="mb-0 text-danger fw-medium">Ausencias</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-primary bg-opacity-10 border-primary border-opacity-25 h-100">
                                    <div class="card-body text-center py-3">
                                        @php
                                            $total = $attendances->count();
                                            $present = $attendances->where('status', 'present')->count();
                                            $percentage = $total > 0 ? round(($present / $total) * 100) : 0;
                                        @endphp
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <i class="fas fa-chart-pie text-primary fs-4 me-2"></i>
                                            <h3 class="mb-0 text-primary fw-bold">{{ $percentage }}%</h3>
                                        </div>
                                        <p class="mb-0 text-primary fw-medium">Asistencia</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Barra de progreso -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0 fw-semibold text-secondary">Progreso de asistencia</h6>
                                <small class="text-muted">{{ $present }} de {{ $total }} clases</small>
                            </div>
                            <div class="progress" style="height: 12px;">
                                <div class="progress-bar bg-primary" role="progressbar" 
                                    style="width: {{ $percentage }}%;" 
                                    aria-valuenow="{{ $percentage }}" 
                                    aria-valuemin="0" 
                                    aria-valuemax="100">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">0%</small>
                                <small class="fw-medium text-primary">{{ $percentage }}%</small>
                                <small class="text-muted">100%</small>
                            </div>
                        </div>

                        <!-- Tabla de asistencias -->
                        <div class="card border-0 bg-light">
                            <div class="card-header bg-transparent border-bottom">
                                <h6 class="mb-0 fw-semibold text-secondary">
                                    <i class="fas fa-list me-2"></i>
                                    Detalle de asistencias
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="bg-primary bg-opacity-10">
                                            <tr>
                                                <th class="fw-semibold text-primary small py-3">Fecha</th>
                                                <th class="fw-semibold text-primary small py-3">Día</th>
                                                <th class="fw-semibold text-primary small py-3">Horario</th>
                                                <th class="fw-semibold text-primary small py-3">Estado</th>
                                                <th class="fw-semibold text-primary small py-3 text-center">Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($attendances as $attendance)
                                                <tr class="border-bottom">
                                                    <td class="py-3">
                                                        <strong>{{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}</strong>
                                                    </td>
                                                    <td class="py-3">
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                            {{ \Carbon\Carbon::parse($attendance->date)->locale('es')->dayName }}
                                                        </span>
                                                    </td>
                                                    <td class="py-3">
                                                        @if($attendance->schedule)
                                                            <code class="bg-light px-2 py-1 rounded">
                                                                {{ \Carbon\Carbon::parse($attendance->schedule->start_time)->format('H:i') }} - 
                                                                {{ \Carbon\Carbon::parse($attendance->schedule->end_time)->format('H:i') }}
                                                            </code>
                                                        @else
                                                            <span class="text-muted">No disponible</span>
                                                        @endif
                                                    </td>
                                                    <td class="py-3">
                                                        @if($attendance->status == 'present')
                                                            <span class="badge bg-success">
                                                                <i class="fas fa-check me-1"></i>Presente
                                                            </span>
                                                        @elseif($attendance->status == 'late')
                                                            <span class="badge bg-warning text-dark">
                                                                <i class="fas fa-clock me-1"></i>Tardanza
                                                            </span>
                                                        @elseif($attendance->status == 'absent')
                                                            <span class="badge bg-danger">
                                                                <i class="fas fa-times me-1"></i>Ausente
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ $attendance->status }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center py-3">
                                                        @if($attendance->observations)
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-outline-info" 
                                                                    data-bs-toggle="tooltip" 
                                                                    data-bs-placement="top" 
                                                                    title="{{ $attendance->observations }}">
                                                                <i class="fas fa-comment-dots"></i>
                                                            </button>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="card-footer bg-light border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Semestre: {{ $course->pivot->semester ?? 'No especificado' }}
                        </small>
                        <small class="text-muted">
                            <i class="fas fa-graduation-cap me-1"></i>
                            Código: {{ $course->code }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Activar tooltips de Bootstrap
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
@endsection