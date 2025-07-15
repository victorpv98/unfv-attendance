@extends('layouts.app')

@section('header')
    Mis Horarios
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fs-1 fw-semibold text-primary">Mis Horarios de Clase</h2>
</div>

<div class="card shadow border-0 rounded-3">
    <div class="card-header bg-light border-bottom">
        <h5 class="card-title mb-0 text-secondary">
            <i class="fas fa-calendar-alt me-2"></i>Listado de Horarios
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-primary bg-opacity-10">
                    <tr>
                        <th class="fw-semibold text-primary small py-3">#</th>
                        <th class="fw-semibold text-primary small py-3">Curso</th>
                        <th class="fw-semibold text-primary small py-3">Día</th>
                        <th class="fw-semibold text-primary small py-3">Horario</th>
                        <th class="fw-semibold text-primary small py-3">Aula</th>
                        <th class="fw-semibold text-primary small py-3">Semestre</th>
                        <th class="fw-semibold text-primary small py-3">Tolerancia</th>
                        <th class="fw-semibold text-primary small py-3 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $schedule)
                        <tr class="border-bottom">
                            <td class="py-3">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $loop->iteration }}</span>
                            </td>
                            <td class="fw-medium py-3">
                                {{ $schedule->course->name }}
                                <br>
                                <code class="bg-light px-2 py-1 rounded small">{{ $schedule->course->code }}</code>
                            </td>
                            <td class="py-3 text-capitalize">
                                <span class="badge bg-warning text-dark">{{ __($schedule->day) }}</span>
                            </td>
                            <td class="py-3">
                                <div class="small">
                                    <strong class="text-dark">{{ \Carbon\Carbon::parse($schedule->start_time)->format('g:ia') }}</strong>
                                    <span class="text-muted">-</span>
                                    <strong class="text-dark">{{ \Carbon\Carbon::parse($schedule->end_time)->format('g:ia') }}</strong>
                                </div>
                            </td>
                            <td class="py-3">
                                <span class="badge bg-info bg-opacity-10 text-info">
                                    <i class="fas fa-door-open me-1"></i>{{ $schedule->classroom }}
                                </span>
                            </td>
                            <td class="py-3">
                                <span class="badge bg-success">{{ $schedule->semester }}</span>
                            </td>
                            <td class="py-3">
                                <span class="badge bg-warning text-dark fw-medium">
                                    <i class="fas fa-clock me-1"></i>{{ $schedule->tardiness_tolerance ?? 0 }} min
                                </span>
                            </td>
                            <td class="text-end py-3">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('teachers.scan-barcode', $schedule) }}" 
                                    class="btn btn-sm btn-primary" 
                                    title="Escanear código de asistencia">
                                        <i class="fas fa-qrcode"></i>
                                    </a>
                                    <a href="{{ route('attendance.report', $schedule) }}" 
                                    class="btn btn-sm btn-success" 
                                    title="Ver reporte de asistencias">
                                        <i class="fas fa-chart-line"></i>
                                    </a>
                                    <button class="btn btn-sm btn-warning text-dark" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalTolerancia{{ $schedule->id }}"
                                            title="Configurar tolerancia de tardanza">
                                        <i class="fas fa-clock"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalTolerancia{{ $schedule->id }}" tabindex="-1" aria-labelledby="modalToleranciaLabel{{ $schedule->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content shadow rounded-3">
                                    <form action="{{ route('teachers.schedules.update-tolerance', $schedule->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header bg-light">
                                            <h5 class="modal-title" id="modalToleranciaLabel{{ $schedule->id }}">
                                                Cambiar Tolerancia - {{ $schedule->course->name }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="tardiness_tolerance_{{ $schedule->id }}" class="form-label">Minutos de Tolerancia</label>
                                                <input type="number" class="form-control" name="tardiness_tolerance" id="tardiness_tolerance_{{ $schedule->id }}" value="{{ $schedule->tardiness_tolerance ?? 0 }}" min="0">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-calendar-times fa-3x mb-3 text-muted opacity-50"></i>
                                    <h5 class="text-muted">No tienes horarios registrados</h5>
                                    <p class="mb-3">Consulta con tu coordinador académico</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection