@extends('layouts.app')

@section('header')
    Reporte de Asistencia
@endsection

@section('content')
<div class="container">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Reporte de Asistencia</h6>
            <div>
                <a href="{{ route('teachers.scan-barcode', $schedule) }}" class="btn btn-sm btn-primary me-2">
                    <i class="fas fa-barcode"></i> Escanear Código de Barras
                </a>
                <a href="{{ route('teachers.my-schedules') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver a Horarios
                </a>
            </div>
        </div>
        
        <div class="card-body">
            <div class="mb-4">
                <h4>
                    <i class="fas fa-book me-2 text-primary"></i>
                    {{ $schedule->course->name }}
                </h4>
                <p class="text-muted">
                    <i class="far fa-clock me-1"></i> {{ $schedule->start_time }} - {{ $schedule->end_time }} | 
                    <i class="fas fa-map-marker-alt me-1"></i> {{ $schedule->classroom }} | 
                    <i class="far fa-calendar-alt me-1"></i> {{ __($schedule->day) }} |
                    <i class="fas fa-user me-1"></i> Profesor: {{ $schedule->teacher->user->name }}
                </p>
            </div>
            
            <div class="row mb-4">
                <div class="col-lg-6">
                    <form action="{{ route('attendance.report', $schedule) }}" method="GET" class="d-flex">
                        <div class="me-2 flex-grow-1">
                            <label for="date" class="form-label">
                                <i class="fas fa-calendar me-1"></i>
                                Fecha
                            </label>
                            <input type="date" id="date" name="date" class="form-control" 
                                value="{{ request('date', now()->toDateString()) }}">
                        </div>
                        <div class="d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title">Presentes</h6>
                                    <h2 class="mb-0">{{ $summary['present'] ?? 0 }}</h2>
                                </div>
                                <div class="display-4">
                                    <i class="fas fa-user-check"></i>
                                </div>
                            </div>
                            <div class="progress mt-2 bg-white bg-opacity-25">
                                <div class="progress-bar bg-white" role="progressbar" 
                                    style="width: {{ $summary['presentPercentage'] ?? 0 }}%" 
                                    aria-valuenow="{{ $summary['presentPercentage'] ?? 0 }}" 
                                    aria-valuemin="0" 
                                    aria-valuemax="100">
                                    {{ $summary['presentPercentage'] ?? 0 }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title">Tardanzas</h6>
                                    <h2 class="mb-0">{{ $summary['late'] ?? 0 }}</h2>
                                </div>
                                <div class="display-4">
                                    <i class="fas fa-user-clock"></i>
                                </div>
                            </div>
                            <div class="progress mt-2 bg-white bg-opacity-25">
                                <div class="progress-bar bg-white" role="progressbar" 
                                    style="width: {{ $summary['latePercentage'] ?? 0 }}%" 
                                    aria-valuenow="{{ $summary['latePercentage'] ?? 0 }}" 
                                    aria-valuemin="0" 
                                    aria-valuemax="100">
                                    {{ $summary['latePercentage'] ?? 0 }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title">Ausentes</h6>
                                    <h2 class="mb-0">{{ $summary['absent'] ?? 0 }}</h2>
                                </div>
                                <div class="display-4">
                                    <i class="fas fa-user-times"></i>
                                </div>
                            </div>
                            <div class="progress mt-2 bg-white bg-opacity-25">
                                <div class="progress-bar bg-white" role="progressbar" 
                                    style="width: {{ $summary['absentPercentage'] ?? 0 }}%" 
                                    aria-valuenow="{{ $summary['absentPercentage'] ?? 0 }}" 
                                    aria-valuemin="0" 
                                    aria-valuemax="100">
                                    {{ $summary['absentPercentage'] ?? 0 }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>
                                <i class="fas fa-user me-1"></i>
                                Estudiante
                            </th>
                            <th>
                                <i class="fas fa-id-card me-1"></i>
                                Código
                            </th>
                            <th>
                                <i class="fas fa-check-circle me-1"></i>
                                Estado
                            </th>
                            <th>
                                <i class="fas fa-clock me-1"></i>
                                Hora
                            </th>
                            <th>
                                <i class="fas fa-edit me-1"></i>
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>{{ $student->user->name }}</td>
                                <td>{{ $student->code }}</td>
                                <td>
                                    @if (isset($attendances[$student->id]))
                                        <span class="badge {{ $attendances[$student->id]->status === 'present' ? 'bg-success' : ($attendances[$student->id]->status === 'late' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                            @if($attendances[$student->id]->status === 'present')
                                                <i class="fas fa-check me-1"></i>Presente
                                            @elseif($attendances[$student->id]->status === 'late')
                                                <i class="fas fa-clock me-1"></i>Tardanza
                                            @else
                                                <i class="fas fa-times me-1"></i>Ausente
                                            @endif
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times me-1"></i>Ausente
                                        </span>
                                    @endif
                                </td>
                                <td>{{ isset($attendances[$student->id]) ? $attendances[$student->id]->time : '-' }}</td>
                                <td>
                                    <form action="{{ route('attendance.update-status') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="student_id" value="{{ $student->id }}">
                                        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                        <input type="hidden" name="date" value="{{ request('date', now()->toDateString()) }}">
                                        
                                        <div class="input-group input-group-sm">
                                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
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
                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    <div class="py-3">
                                        <i class="fas fa-users fa-2x text-muted mb-2"></i>
                                        <p class="text-muted mb-0">No hay estudiantes matriculados en este curso</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-chart-pie me-2"></i>
                Gráfico de Asistencia
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="chart-container" style="position: relative; height:300px;">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex flex-column justify-content-center h-100">
                        <div class="text-center mb-3">
                            <h5 class="text-muted">Total de Estudiantes</h5>
                            <h2 class="text-primary">{{ ($summary['present'] ?? 0) + ($summary['late'] ?? 0) + ($summary['absent'] ?? 0) }}</h2>
                        </div>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <span><i class="fas fa-circle text-success me-2"></i>Presentes</span>
                                <span class="badge bg-success rounded-pill">{{ $summary['present'] ?? 0 }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <span><i class="fas fa-circle text-warning me-2"></i>Tardanzas</span>
                                <span class="badge bg-warning rounded-pill">{{ $summary['late'] ?? 0 }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <span><i class="fas fa-circle text-danger me-2"></i>Ausentes</span>
                                <span class="badge bg-danger rounded-pill">{{ $summary['absent'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Datos para el gráfico
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        const attendanceChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Presentes', 'Tardanzas', 'Ausentes'],
                datasets: [{
                    data: [{{ $summary['present'] ?? 0 }}, {{ $summary['late'] ?? 0 }}, {{ $summary['absent'] ?? 0 }}],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ],
                    borderColor: [
                        'rgba(40, 167, 69, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(220, 53, 69, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush