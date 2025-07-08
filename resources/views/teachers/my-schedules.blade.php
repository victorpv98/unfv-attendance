@extends('layouts.app')

@section('header')
    Mis Horarios
@endsection

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow border-0 rounded-3">
                <div class="card-header bg-primary bg-opacity-10 border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold text-primary">
                        <i class="fas fa-calendar-alt me-2"></i>Mis Horarios de Clase
                    </h5>
                    <div>
                        <form action="{{ route('teachers.my-schedules') }}" method="GET" class="d-flex">
                            <select name="semester" class="form-select me-2 border-primary">
                                <option value="">Todos los semestres</option>
                                @foreach($semesters as $sem)
                                    <option value="{{ $sem }}" {{ request('semester') == $sem ? 'selected' : '' }}>
                                        {{ $sem }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i>Filtrar
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    @php
                        $days = ['monday' => 'Lunes', 'tuesday' => 'Martes', 'wednesday' => 'Miércoles', 
                                'thursday' => 'Jueves', 'friday' => 'Viernes', 'saturday' => 'Sábado'];
                    @endphp
                    
                    <ul class="nav nav-tabs border-0 ps-3 pt-3" id="scheduleTabs" role="tablist">
                        @foreach($days as $dayKey => $dayName)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link border-0 {{ $loop->first ? 'active bg-primary text-white rounded-2' : 'text-secondary' }}" 
                                    id="{{ $dayKey }}-tab" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#{{ $dayKey }}" 
                                    type="button" 
                                    role="tab" 
                                    aria-controls="{{ $dayKey }}" 
                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                    {{ $dayName }}
                                </button>
                            </li>
                        @endforeach
                    </ul>
                    
                    <div class="tab-content p-3" id="scheduleTabsContent">
                        @foreach($days as $dayKey => $dayName)
                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                                id="{{ $dayKey }}" 
                                role="tabpanel" 
                                aria-labelledby="{{ $dayKey }}-tab">
                                
                                @php
                                    $daySchedules = $schedules->where('day', $dayKey);
                                @endphp
                                
                                @if($daySchedules->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="bg-primary bg-opacity-10">
                                                <tr>
                                                    <th class="fw-semibold text-primary py-3">Horario</th>
                                                    <th class="fw-semibold text-primary py-3">Curso</th>
                                                    <th class="fw-semibold text-primary py-3">Aula</th>
                                                    <th class="fw-semibold text-primary py-3">Semestre</th>
                                                    <th class="fw-semibold text-primary py-3 text-center">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($daySchedules as $schedule)
                                                    <tr>
                                                        <td class="py-3">
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-clock text-primary me-2"></i>
                                                                <span class="fw-medium">{{ $schedule->start_time }} - {{ $schedule->end_time }}</span>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div>
                                                                <strong class="text-dark">{{ $schedule->course->name }}</strong>
                                                                <br>
                                                                <code class="bg-light px-2 py-1 rounded small">{{ $schedule->course->code }}</code>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                                <i class="fas fa-map-marker-alt me-1"></i>{{ $schedule->classroom }}
                                                            </span>
                                                        </td>
                                                        <td class="py-3">
                                                            <span class="badge bg-info">{{ $schedule->semester }}</span>
                                                        </td>
                                                        <td class="text-center py-3">
                                                            <div class="btn-group" role="group">
                                                                <a href="{{ route('teachers.scan-barcode', $schedule) }}" 
                                                                   class="btn btn-sm btn-primary" 
                                                                   title="Escanear código">
                                                                    <i class="fas fa-qrcode"></i>
                                                                </a>
                                                                <a href="{{ route('attendance.report', $schedule) }}" 
                                                                   class="btn btn-sm btn-outline-info" 
                                                                   title="Ver reporte">
                                                                    <i class="fas fa-chart-line"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="fas fa-calendar-times text-muted fa-4x mb-3 opacity-50"></i>
                                        <h5 class="text-muted">No hay clases para {{ strtolower($dayName) }}</h5>
                                        <p class="text-muted mb-0">Día libre para planificar otras actividades</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow border-0 rounded-3">
                <div class="card-header bg-success bg-opacity-10 border-0 py-3">
                    <h5 class="mb-0 fw-semibold text-success">
                        <i class="fas fa-calendar-day me-2"></i>Clases de Hoy
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $today = strtolower(date('l'));
                        $todaySchedules = $schedules->where('day', $today);
                    @endphp
                    
                    @if($todaySchedules->count() > 0)
                        <div class="row g-3">
                            @foreach($todaySchedules as $schedule)
                                <div class="col-md-6">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body p-4">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-semibold mb-2 text-dark">{{ $schedule->course->name }}</h6>
                                                    <div class="d-flex flex-wrap gap-2 mb-2">
                                                        <small class="badge bg-primary bg-opacity-10 text-primary">
                                                            <i class="fas fa-clock me-1"></i>{{ $schedule->start_time }} - {{ $schedule->end_time }}
                                                        </small>
                                                        <small class="badge bg-secondary bg-opacity-10 text-secondary">
                                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $schedule->classroom }}
                                                        </small>
                                                    </div>
                                                    <div class="d-flex gap-2">
                                                        <code class="bg-white px-2 py-1 rounded small">{{ $schedule->course->code }}</code>
                                                        <span class="badge bg-info small">{{ $schedule->semester }}</span>
                                                    </div>
                                                </div>
                                                <a href="{{ route('teachers.scan-barcode', $schedule) }}" 
                                                   class="btn btn-success btn-sm">
                                                    <i class="fas fa-qrcode me-1"></i>
                                                    Escanear
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-check text-success fa-4x mb-3 opacity-50"></i>
                            <h5 class="text-success">¡Día libre!</h5>
                            <p class="text-muted mb-0">No tienes clases programadas para hoy. Disfruta tu tiempo libre.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Obtener el día actual (0: domingo, 1: lunes, ..., 6: sábado)
    const today = new Date().getDay();
    
    // Mapear el día de la semana al índice del tab (ajustando para que lunes sea 0)
    const dayMap = {
        1: 'monday',    // Lunes
        2: 'tuesday',   // Martes
        3: 'wednesday', // Miércoles
        4: 'thursday',  // Jueves
        5: 'friday',    // Viernes
        6: 'saturday',  // Sábado
        0: 'monday'     // Domingo (mostramos lunes por defecto)
    };
    
    // Obtener el tab del día actual
    const todayTab = document.getElementById(`${dayMap[today]}-tab`);
    
    // Activar el tab del día actual si existe
    if (todayTab) {
        // Remover clases activas de todos los tabs
        document.querySelectorAll('.nav-link').forEach(tab => {
            tab.classList.remove('active', 'bg-primary', 'text-white');
            tab.classList.add('text-secondary');
        });
        
        // Activar el tab del día actual
        todayTab.classList.add('active', 'bg-primary', 'text-white', 'rounded-2');
        todayTab.classList.remove('text-secondary');
        
        const tabTrigger = new bootstrap.Tab(todayTab);
        tabTrigger.show();
    }
});

// Manejar clics en tabs para cambiar estilos
document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
    tab.addEventListener('shown.bs.tab', function (e) {
        // Remover estilos activos de todos los tabs
        document.querySelectorAll('.nav-link').forEach(t => {
            t.classList.remove('active', 'bg-primary', 'text-white');
            t.classList.add('text-secondary');
        });
        
        // Aplicar estilos al tab activo
        e.target.classList.add('active', 'bg-primary', 'text-white', 'rounded-2');
        e.target.classList.remove('text-secondary');
    });
});
</script>
@endpush