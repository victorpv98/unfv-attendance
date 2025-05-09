<div class="row mb-4">
    <!-- Facultades -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card shadow border-0">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary p-3 me-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <h6 class="fw-semibold text-muted mb-1">Facultades</h6>
                        <h2 class="fs-1 fw-bold mb-0">{{ $faculties ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Cursos -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card shadow border-0">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success p-3 me-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div>
                        <h6 class="fw-semibold text-muted mb-1">Cursos</h6>
                        <h2 class="fs-1 fw-bold mb-0">{{ $courses ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Profesores -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card shadow border-0">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-info bg-opacity-10 text-info p-3 me-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <h6 class="fw-semibold text-muted mb-1">Profesores</h6>
                        <h2 class="fs-1 fw-bold mb-0">{{ $teachers ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Estudiantes -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card shadow border-0">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center p-3 me-4" style="background-color: rgba(128, 0, 128, 0.1); color: purple;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <h6 class="fw-semibold text-muted mb-1">Estudiantes</h6>
                        <h2 class="fs-1 fw-bold mb-0">{{ $students ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Gráfico de Asistencias -->
    <div class="col-md-6 mb-4">
        <div class="card shadow border-0">
            <div class="card-body p-4">
                <h5 class="card-title fw-semibold mb-4">Asistencias por Facultad</h5>
                <div style="height: 250px;">
                    <!-- Gráfico -->
                    <canvas id="facultyAttendanceChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Actividad Reciente -->
    <div class="col-md-6 mb-4">
        <div class="card shadow border-0">
            <div class="card-body p-4">
                <h5 class="card-title fw-semibold mb-4">Actividad Reciente</h5>
                <ul class="list-unstyled">
                    @foreach($recentActivities ?? [] as $activity)
                        <li class="d-flex align-items-start mb-3">
                            <div class="flex-shrink-0">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light" style="width: 32px; height: 32px;">
                                    <span class="fw-medium text-secondary">
                                        {{ $activity->user ? substr($activity->user->name, 0, 1) : '?' }}
                                    </span>
                                </span>
                            </div>
                            <div class="ms-3">
                                <p class="mb-1 fw-medium">{{ $activity->description }}</p>
                                <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>