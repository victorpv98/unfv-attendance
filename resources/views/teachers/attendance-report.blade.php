@extends('layouts.app')

@section('header')
    Reporte de Asistencia
@endsection

@section('content')
<div class="container">
    <div class="card shadow border-0 rounded-3 mb-4">
        <div class="card-header bg-primary bg-opacity-10 border-0 py-3 d-flex flex-row align-items-center justify-content-between">
            <h5 class="mb-0 fw-semibold text-primary">
                <i class="fas fa-chart-line me-2"></i>Reporte de Asistencia
            </h5>
            <div>
                <a href="{{ route('teachers.scan-barcode', $schedule) }}" class="btn btn-sm btn-primary me-2">
                    <i class="fas fa-qrcode me-1"></i> Escanear Código
                </a>
                <a href="{{ route('teachers.my-schedules') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Volver a Horarios
                </a>
            </div>
        </div>
        
        <div class="card-body">
            <div class="mb-4 p-3 bg-light rounded-2">
                <h4 class="text-dark mb-2">
                    <i class="fas fa-book me-2 text-primary"></i>
                    {{ $schedule->course->name }}
                </h4>
                <div class="d-flex flex-wrap gap-3 text-muted">
                    <span><i class="fas fa-clock me-1 text-primary"></i> {{ $schedule->start_time }} - {{ $schedule->end_time }}</span>
                    <span><i class="fas fa-map-marker-alt me-1 text-primary"></i> {{ $schedule->classroom }}</span>
                    <span><i class="fas fa-calendar-alt me-1 text-primary"></i> {{ __($schedule->day) }}</span>
                    <span><i class="fas fa-user me-1 text-primary"></i> Profesor: {{ $schedule->teacher->user->name }}</span>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-lg-6">
                    <form action="{{ route('attendance.report', $schedule) }}" method="GET" class="d-flex gap-2">
                        <div class="flex-grow-1">
                            <label for="date" class="form-label fw-medium">
                                <i class="fas fa-calendar me-1 text-primary"></i>
                                Fecha
                            </label>
                            <input type="date" id="date" name="date" class="form-control border-primary" 
                                value="{{ request('date', now()->toDateString()) }}">
                        </div>
                        <div class="d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i> Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="row mb-4 g-3">
                <div class="col-md-4">
                    <div class="card bg-success text-white border-0 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-1">Presentes</h6>
                                    <h2 class="mb-0 fw-bold">{{ $summary['present'] ?? 0 }}</h2>
                                </div>
                                <div class="fs-1 opacity-75">
                                    <i class="fas fa-user-check"></i>
                                </div>
                            </div>
                            <div class="progress mt-3" style="height: 6px;">
                                <div class="progress-bar bg-white" role="progressbar" 
                                    style="width: {{ $summary['presentPercentage'] ?? 0 }}%" 
                                    aria-valuenow="{{ $summary['presentPercentage'] ?? 0 }}" 
                                    aria-valuemin="0" 
                                    aria-valuemax="100">
                                </div>
                            </div>
                            <small class="mt-1 d-block">{{ $summary['presentPercentage'] ?? 0 }}% del total</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card bg-warning text-dark border-0 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-1">Tardanzas</h6>
                                    <h2 class="mb-0 fw-bold">{{ $summary['late'] ?? 0 }}</h2>
                                </div>
                                <div class="fs-1 opacity-75">
                                    <i class="fas fa-user-clock"></i>
                                </div>
                            </div>
                            <div class="progress mt-3" style="height: 6px;">
                                <div class="progress-bar bg-dark" role="progressbar" 
                                    style="width: {{ $summary['latePercentage'] ?? 0 }}%" 
                                    aria-valuenow="{{ $summary['latePercentage'] ?? 0 }}" 
                                    aria-valuemin="0" 
                                    aria-valuemax="100">
                                </div>
                            </div>
                            <small class="mt-1 d-block">{{ $summary['latePercentage'] ?? 0 }}% del total</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card bg-danger text-white border-0 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-1">Ausentes</h6>
                                    <h2 class="mb-0 fw-bold">{{ $summary['absent'] ?? 0 }}</h2>
                                </div>
                                <div class="fs-1 opacity-75">
                                    <i class="fas fa-user-times"></i>
                                </div>
                            </div>
                            <div class="progress mt-3" style="height: 6px;">
                                <div class="progress-bar bg-white" role="progressbar" 
                                    style="width: {{ $summary['absentPercentage'] ?? 0 }}%" 
                                    aria-valuenow="{{ $summary['absentPercentage'] ?? 0 }}" 
                                    aria-valuemin="0" 
                                    aria-valuemax="100">
                                </div>
                            </div>
                            <small class="mt-1 d-block">{{ $summary['absentPercentage'] ?? 0 }}% del total</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-primary bg-opacity-10">
                        <tr>
                            <th class="fw-semibold text-primary py-3">
                                <i class="fas fa-user me-1"></i>
                                Estudiante
                            </th>
                            <th class="fw-semibold text-primary py-3">
                                <i class="fas fa-id-card me-1"></i>
                                Código
                            </th>
                            <th class="fw-semibold text-primary py-3">
                                <i class="fas fa-check-circle me-1"></i>
                                Estado
                            </th>
                            <th class="fw-semibold text-primary py-3">
                                <i class="fas fa-clock me-1"></i>
                                Hora
                            </th>
                            <th class="fw-semibold text-primary py-3 text-center">
                                <i class="fas fa-edit me-1"></i>
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="fas fa-user text-primary"></i>
                                        </div>
                                        <strong class="text-dark">{{ $student->user->name }}</strong>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <code class="bg-light px-2 py-1 rounded">{{ $student->code }}</code>
                                </td>
                                <td class="py-3">
                                    @if (isset($attendances[$student->id]))
                                        <span class="badge {{ $attendances[$student->id]->status === 'present' ? 'bg-success' : ($attendances[$student->id]->status === 'late' ? 'bg-warning text-dark' : 'bg-danger') }} px-3 py-2">
                                            @if($attendances[$student->id]->status === 'present')
                                                <i class="fas fa-check me-1"></i>Presente
                                            @elseif($attendances[$student->id]->status === 'late')
                                                <i class="fas fa-clock me-1"></i>Tardanza
                                            @else
                                                <i class="fas fa-times me-1"></i>Ausente
                                            @endif
                                        </span>
                                    @else
                                        <span class="badge bg-danger px-3 py-2">
                                            <i class="fas fa-times me-1"></i>Ausente
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3">
                                    @if(isset($attendances[$student->id]))
                                        <span class="text-dark fw-medium">{{ $attendances[$student->id]->time }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center py-3">
                                    <form action="{{ route('attendance.update-status') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="student_id" value="{{ $student->id }}">
                                        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                        <input type="hidden" name="date" value="{{ request('date', now()->toDateString()) }}">
                                        
                                        <div class="input-group input-group-sm" style="max-width: 200px;">
                                            <select name="status" class="form-select form-select-sm border-primary" onchange="this.form.submit()">
                                                <option value="present" {{ isset($attendances[$student->id]) && $attendances[$student->id]->status === 'present' ? 'selected' : '' }}>
                                                    Presente
                                                </option>
                                                <option value="late" {{ isset($attendances[$student->id]) && $attendances[$student->id]->status === 'late' ? 'selected' : '' }}>
                                                    Tardanza
                                                </option>
                                                <option value="absent" {{ !isset($attendances[$student->id]) || $attendances[$student->id]->status === 'absent' ? 'selected' : '' }}>
                                                    Ausente
                                                </option>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-outline-primary" title="Actualizar estado">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="fas fa-users fa-4x text-muted mb-3 opacity-50"></i>
                                    <h5 class="text-muted">No hay estudiantes matriculados</h5>
                                    <p class="text-muted mb-0">Este curso no tiene estudiantes registrados</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush