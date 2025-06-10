<div class="row mb-4">
    <!-- Mis Cursos -->
    <div class="col-md-4 mb-4 mb-md-0">
        <div class="card shadow border-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-info bg-opacity-10 text-info p-3 me-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div>
                        <h6 class="fw-semibold text-muted mb-1">Mis Cursos</h6>
                        <h2 class="fs-1 fw-bold mb-0">{{ $coursesCount ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Estudiantes -->
    <div class="col-md-4 mb-4 mb-md-0">
        <div class="card shadow border-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success p-3 me-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <h6 class="fw-semibold text-muted mb-1">Estudiantes</h6>
                        <h2 class="fs-1 fw-bold mb-0">{{ $studentsCount ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Asistencias Hoy -->
    <div class="col-md-4">
        <div class="card shadow border-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center p-3 me-4" style="background-color: rgba(128, 0, 128, 0.1); color: purple;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <div>
                        <h6 class="fw-semibold text-muted mb-1">Asistencias Hoy</h6>
                        <h2 class="fs-1 fw-bold mb-0">{{ $todayAttendances ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Clases de Hoy -->
    <div class="col-md-6 mb-4">
        <div class="card shadow border-0 h-100">
            <div class="card-body p-4">
                <h5 class="card-title fw-semibold mb-4">
                    <i class="fas fa-calendar-day me-2 text-primary"></i>
                    Clases de Hoy
                </h5>
                @if(isset($todaySchedules) && count($todaySchedules) > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($todaySchedules as $schedule)
                            <li class="list-group-item px-0 py-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="fw-medium mb-1">{{ $schedule->course->name }}</h6>
                                        <p class="text-muted small mb-0">
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $schedule->classroom }} | 
                                            <i class="fas fa-clock me-1"></i>{{ $schedule->start_time }} - {{ $schedule->end_time }}
                                        </p>
                                    </div>
                                    <a href="{{ route('teachers.scan-barcode', $schedule) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-barcode me-1"></i>
                                        Escanear
                                    </a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times text-muted fa-3x mb-3"></i>
                        <p class="text-muted mb-0">No tienes clases programadas para hoy</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Resumen de Asistencias -->
    <div class="col-md-6 mb-4">
        <div class="card shadow border-0 h-100">
            <div class="card-body p-4">
                <h5 class="card-title fw-semibold mb-4">
                    <i class="fas fa-chart-pie me-2 text-primary"></i>
                    Resumen de Asistencias
                </h5>
                <div style="height: 250px;">
                    <!-- Gráfico -->
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Datos para el gráfico del dashboard
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        
        // Datos de ejemplo - estos deberían venir del controlador
        const data = {
            labels: ['Presentes', 'Tardanzas', 'Ausentes'],
            datasets: [{
                data: [70, 15, 15], // Datos de ejemplo
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
        };

        const attendanceChart = new Chart(ctx, {
            type: 'doughnut',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
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
                                return `${label}: ${percentage}%`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush