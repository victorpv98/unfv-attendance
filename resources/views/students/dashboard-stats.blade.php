<div class="row mb-4">
    <!-- Mis Cursos -->
    <div class="col-md-4 mb-4">
        <div class="card shadow border-0 h-100 rounded-3">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary p-3 me-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div>
                        <h6 class="fw-semibold text-muted mb-1">Mis Cursos</h6>
                        <h2 class="fs-1 fw-bold mb-0 text-primary">{{ $coursesCount ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Asistencias -->
    <div class="col-md-4 mb-4">
        <div class="card shadow border-0 h-100 rounded-3">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success p-3 me-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h6 class="fw-semibold text-muted mb-1">Asistencias</h6>
                        <h2 class="fs-1 fw-bold mb-0 text-success">{{ $attendanceCount ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Faltas -->
    <div class="col-md-4 mb-4">
        <div class="card shadow border-0 h-100 rounded-3">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger p-3 me-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h6 class="fw-semibold text-muted mb-1">Faltas</h6>
                        <h2 class="fs-1 fw-bold mb-0 text-danger">{{ $absenceCount ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Clases de Hoy -->
    <div class="col-md-6 mb-4">
        <div class="card shadow border-0 h-100 rounded-3">
            <div class="card-header bg-light border-bottom py-3">
                <h5 class="card-title fw-semibold mb-0">
                    <i class="fas fa-calendar-day me-2 text-primary"></i>
                    Clases de Hoy
                </h5>
            </div>
            <div class="card-body p-4">
                @if(isset($todaySchedules) && count($todaySchedules) > 0)
                    <div class="list-group list-group-flush">
                        @foreach($todaySchedules as $schedule)
                            <div class="list-group-item px-0 py-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="fw-semibold mb-2 text-primary">{{ $schedule->course->name }}</h6>
                                        <div class="mb-2">
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary me-2">
                                                <i class="fas fa-map-marker-alt me-1"></i>{{ $schedule->classroom }}
                                            </span>
                                            <span class="badge bg-info bg-opacity-10 text-info">
                                                <i class="fas fa-clock me-1"></i>{{ $schedule->start_time }} - {{ $schedule->end_time }}
                                            </span>
                                        </div>
                                        <p class="text-muted small mb-0">
                                            <i class="fas fa-user me-1"></i>{{ $schedule->teacher->user->name }}
                                        </p>
                                    </div>
                                    <div class="ms-3">
                                        @if(isset($todayAttendances[$schedule->id]))
                                            @if($todayAttendances[$schedule->id]->status === 'present')
                                                <span class="badge rounded-pill bg-success">
                                                    <i class="fas fa-check me-1"></i>Presente
                                                </span>
                                            @elseif($todayAttendances[$schedule->id]->status === 'late')
                                                <span class="badge rounded-pill bg-warning text-dark">
                                                    <i class="fas fa-clock me-1"></i>Tardanza
                                                </span>
                                            @else
                                                <span class="badge rounded-pill bg-danger">
                                                    <i class="fas fa-times me-1"></i>Falta
                                                </span>
                                            @endif
                                        @else
                                            <span class="badge rounded-pill bg-secondary">
                                                <i class="fas fa-hourglass-half me-1"></i>Pendiente
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times text-muted fa-4x mb-3 opacity-50"></i>
                        <h5 class="text-muted">No tienes clases hoy</h5>
                        <p class="text-muted mb-0">¡Disfruta tu día libre!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Mi Código de Barras -->
    <div class="col-md-6 mb-4">
        <div class="card shadow border-0 h-100 rounded-3">
            <div class="card-header bg-light border-bottom py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-semibold mb-0">
                        <i class="fas fa-qrcode me-2 text-primary"></i>
                        Mi Código de Barras
                    </h5>
                    <a href="{{ route('students.my-barcode') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-expand-arrows-alt me-1"></i>
                        Ver Completo
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="d-flex flex-column align-items-center">
                    <div class="mb-3 p-3 bg-white border border-2 border-primary border-opacity-25 rounded-3 shadow-sm">
                        <img src="{{ route('students.barcode-image', auth()->user()->student) }}" 
                             alt="Mi Código de Barras" 
                             class="img-fluid" 
                             style="max-width: 240px; height: auto;" 
                             id="barcode-image">
                    </div>
                    
                    <div class="alert alert-info alert-sm d-flex align-items-center mb-3" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>Presenta este código al inicio de cada clase</small>
                    </div>
                    
                    <div class="text-center">
                        <p class="text-muted small mb-1">
                            <strong>Tu código estudiantil:</strong>
                        </p>
                        <code class="bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-2 fw-bold">
                            {{ auth()->user()->student->code }}
                        </code>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>