<div class="row mb-4">
    <!-- Mis Cursos -->
    <div class="col-md-4 mb-4">
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
    
    <!-- Asistencias -->
    <div class="col-md-4 mb-4">
        <div class="card shadow border-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success p-3 me-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h6 class="fw-semibold text-muted mb-1">Asistencias</h6>
                        <h2 class="fs-1 fw-bold mb-0">{{ $attendanceCount ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Faltas -->
    <div class="col-md-4 mb-4">
        <div class="card shadow border-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger p-3 me-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h6 class="fw-semibold text-muted mb-1">Faltas</h6>
                        <h2 class="fs-1 fw-bold mb-0">{{ $absenceCount ?? 0 }}</h2>
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
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="fw-medium mb-1">{{ $schedule->course->name }}</h6>
                                        <p class="text-muted small mb-1">
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $schedule->classroom }} | 
                                            <i class="fas fa-clock me-1"></i>{{ $schedule->start_time }} - {{ $schedule->end_time }}
                                        </p>
                                        <p class="text-muted small mb-0">
                                            <i class="fas fa-user me-1"></i>Profesor: {{ $schedule->teacher->user->name }}
                                        </p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        @if(isset($todayAttendances[$schedule->id]))
                                            <span class="badge rounded-pill {{ $todayAttendances[$schedule->id]->status === 'present' ? 'bg-success' : 'bg-warning text-dark' }}">
                                                {{ $todayAttendances[$schedule->id]->status === 'present' ? 'Presente' : 'Tardanza' }}
                                            </span>
                                        @else
                                            <span class="badge rounded-pill bg-secondary">Pendiente</span>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times text-muted fa-3x mb-3"></i>
                        <p class="text-muted">No tienes clases programadas para hoy</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Mi Código de Barras -->
    <div class="col-md-6 mb-4">
        <div class="card shadow border-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title fw-semibold mb-0">
                        <i class="fas fa-barcode me-2 text-primary"></i>
                        Mi Código de Barras
                    </h5>
                    <a href="{{ route('students.my-barcode') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-external-link-alt me-1"></i>
                        Ver Completo
                    </a>
                </div>
                
                <div class="d-flex flex-column align-items-center">
                    <div class="mb-3 p-2 bg-white border rounded shadow-sm">
                        <img src="{{ route('students.barcode-image', auth()->user()->student) }}" alt="Mi Código de Barras" class="img-fluid" style="max-width: 240px; height: auto;" id="barcode-image">
                    </div>
                    <p class="text-muted small text-center mb-2">
                        <i class="fas fa-info-circle me-1"></i>
                        Muestra este código de barras al profesor al inicio de cada clase
                    </p>
                    <p class="text-muted small text-center mb-0">
                        <strong>Código:</strong> {{ auth()->user()->student->code }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>