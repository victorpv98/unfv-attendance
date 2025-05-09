@extends('layouts.app')

@section('header')
    Asistencias del Curso
@endsection

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-check me-2"></i>
                        Asistencias del curso: {{ $course->name }}
                    </h5>
                    <a href="{{ route('students.my-courses') }}" class="btn btn-sm btn-light">
                        <i class="fas fa-arrow-left me-1"></i> Volver a mis cursos
                    </a>
                </div>
                <div class="card-body">
                    @if($attendances->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>No hay registros de asistencia disponibles para este curso.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Día</th>
                                        <th>Horario</th>
                                        <th>Estado</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attendances as $attendance)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($attendance->date)->locale('es')->dayName }}</td>
                                            <td>
                                                @if($attendance->schedule)
                                                    {{ \Carbon\Carbon::parse($attendance->schedule->start_time)->format('H:i') }} - 
                                                    {{ \Carbon\Carbon::parse($attendance->schedule->end_time)->format('H:i') }}
                                                @else
                                                    No disponible
                                                @endif
                                            </td>
                                            <td>
                                                @if($attendance->status == 'present')
                                                    <span class="badge bg-success">Presente</span>
                                                @elseif($attendance->status == 'late')
                                                    <span class="badge bg-warning text-dark">Tardanza</span>
                                                @elseif($attendance->status == 'absent')
                                                    <span class="badge bg-danger">Ausente</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $attendance->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($attendance->observations)
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $attendance->observations }}">
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
                        
                        <!-- Resumen de asistencia -->
                        <div class="mt-4">
                            <h6 class="border-bottom pb-2 mb-3">Resumen de asistencia</h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h3 class="mb-0 text-success">
                                                {{ $attendances->where('status', 'present')->count() }}
                                            </h3>
                                            <p class="mb-0 text-muted">Presentes</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h3 class="mb-0 text-warning">
                                                {{ $attendances->where('status', 'late')->count() }}
                                            </h3>
                                            <p class="mb-0 text-muted">Tardanzas</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h3 class="mb-0 text-danger">
                                                {{ $attendances->where('status', 'absent')->count() }}
                                            </h3>
                                            <p class="mb-0 text-muted">Ausencias</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Gráfica de asistencia -->
                        <div class="mt-4">
                            <h6 class="border-bottom pb-2 mb-3">Porcentaje de asistencia</h6>
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 me-2">
                                    <div class="progress" style="height: 25px;">
                                        @php
                                            $total = $attendances->count();
                                            $present = $attendances->where('status', 'present')->count();
                                            $percentage = $total > 0 ? round(($present / $total) * 100) : 0;
                                        @endphp
                                        
                                        <div class="progress-bar bg-success" role="progressbar" 
                                            style="width: {{ $percentage }}%;" 
                                            aria-valuenow="{{ $percentage }}" 
                                            aria-valuemin="0" 
                                            aria-valuemax="100">
                                            {{ $percentage }}%
                                        </div>
                                    </div>
                                </div>
                                <span class="small fw-medium text-muted">
                                    {{ $percentage }}%
                                </span>
                            </div>
                            <div class="text-center mt-2">
                                <small class="text-muted">Asistencias: {{ $present }} de {{ $total }} clases</small>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-light">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Curso del semestre: {{ $course->pivot->semester ?? 'No especificado' }}
                    </small>
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