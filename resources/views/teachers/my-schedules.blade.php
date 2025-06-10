@extends('layouts.app')

@section('header')
    Mis Horarios
@endsection

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Mis Horarios de Clase</h6>
                    <div>
                        <form action="{{ route('teachers.my-schedules') }}" method="GET" class="d-flex">
                            <select name="semester" class="form-select me-2">
                                <option value="">Todos los semestres</option>
                                @foreach($semesters as $sem)
                                    <option value="{{ $sem }}" {{ request('semester') == $sem ? 'selected' : '' }}>
                                        {{ $sem }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    @php
                        $days = ['monday' => 'Lunes', 'tuesday' => 'Martes', 'wednesday' => 'Miércoles', 
                                'thursday' => 'Jueves', 'friday' => 'Viernes', 'saturday' => 'Sábado'];
                    @endphp
                    
                    <ul class="nav nav-tabs mb-3" id="scheduleTabs" role="tablist">
                        @foreach($days as $dayKey => $dayName)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $loop->first ? 'active' : '' }}" 
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
                    
                    <div class="tab-content" id="scheduleTabsContent">
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
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Horario</th>
                                                    <th>Curso</th>
                                                    <th>Aula</th>
                                                    <th>Semestre</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($daySchedules as $schedule)
                                                    <tr>
                                                        <td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                                                        <td>{{ $schedule->course->name }} ({{ $schedule->course->code }})</td>
                                                        <td>{{ $schedule->classroom }}</td>
                                                        <td>{{ $schedule->semester }}</td>
                                                        <td>
                                                            <a href="{{ route('teachers.scan-barcode', $schedule) }}" class="btn btn-primary btn-sm">
                                                                <i class="fas fa-barcode"></i> Escanear Código
                                                            </a>
                                                            <a href="{{ route('attendance.report', $schedule) }}" class="btn btn-info btn-sm">
                                                                <i class="fas fa-clipboard-list"></i> Reporte
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        No tienes clases programadas para {{ strtolower($dayName) }}.
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
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Clases de Hoy</h6>
                </div>
                <div class="card-body">
                    @php
                        $today = strtolower(date('l'));
                        $todaySchedules = $schedules->where('day', $today);
                    @endphp
                    
                    @if($todaySchedules->count() > 0)
                    <div class="list-group">
                        @foreach($todaySchedules as $schedule)
                            <div class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-1">{{ $schedule->course->name }}</h5>
                                        <p class="mb-1">
                                            <i class="far fa-clock"></i> {{ $schedule->start_time }} - {{ $schedule->end_time }} | 
                                            <i class="fas fa-map-marker-alt"></i> {{ $schedule->classroom }}
                                        </p>
                                        <small>Código: {{ $schedule->course->code }} | Semestre: {{ $schedule->semester }}</small>
                                    </div>
                                    <div>
                                        <a href="{{ route('teachers.scan-barcode', $schedule) }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-barcode"></i> Escanear Código
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">
                        No tienes clases programadas para hoy.
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
        const tabTrigger = new bootstrap.Tab(todayTab);
        tabTrigger.show();
    }
});
</script>
@endpush