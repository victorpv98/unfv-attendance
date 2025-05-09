@extends('layouts.app')

@section('header')
    Mi Código QR
@endsection

@section('content')
    <div class="card shadow border-0">
        <div class="card-body p-4">
            <div class="mb-4">
                <h2 class="fs-1 fw-semibold mb-2">Mi Código QR de Asistencia</h2>
                <p class="text-muted">Este código QR te identifica para registrar tu asistencia a clases</p>
            </div>

            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0 text-center">
                    @if($student->qr_code)
                        <div class="mb-4 p-3 bg-white border rounded shadow-sm d-inline-block">
                            <img src="{{ route('students.qr-image', $student) }}" alt="Mi Código QR" class="img-fluid" style="max-width: 220px;">
                        </div>
                        
                        <p class="small text-muted mb-4">Código: {{ $student->qr_code }}</p>
                        
                        <form action="{{ route('students.regenerate-qr') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning">
                                Regenerar Código QR
                            </button>
                        </form>
                    @else
                        <div class="alert alert-warning mb-4 text-center">
                            <p class="mb-4">No tienes un código QR asignado.</p>
                            
                            <form action="{{ route('students.regenerate-qr') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    Generar Código QR
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
                
                <div class="col-md-8">
                    <div class="card bg-info bg-opacity-10 border-0 mb-4">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-semibold text-primary mb-3">Instrucciones de Uso</h5>
                            <ul class="list-unstyled mb-0">
                                <li class="d-flex mb-2">
                                    <i class="fas fa-check-circle text-primary me-2 mt-1"></i>
                                    <span>Muestra este código QR al profesor al inicio de cada clase.</span>
                                </li>
                                <li class="d-flex mb-2">
                                    <i class="fas fa-check-circle text-primary me-2 mt-1"></i>
                                    <span>El profesor escaneará tu código con su dispositivo móvil.</span>
                                </li>
                                <li class="d-flex mb-2">
                                    <i class="fas fa-check-circle text-primary me-2 mt-1"></i>
                                    <span>Tu asistencia quedará registrada automáticamente.</span>
                                </li>
                                <li class="d-flex mb-2">
                                    <i class="fas fa-check-circle text-primary me-2 mt-1"></i>
                                    <span>Puedes ver el historial de tus asistencias en la sección "Mis Asistencias".</span>
                                </li>
                                <li class="d-flex mb-0">
                                    <i class="fas fa-exclamation-triangle text-warning me-2 mt-1"></i>
                                    <span class="text-dark">Si llegas después de 15 minutos de iniciada la clase, se registrará como tardanza.</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="card bg-light border-0">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-semibold mb-3">Información Personal</h5>
                            <div class="row row-gap-3">
                                <div class="col-md-6">
                                    <p class="small text-muted mb-1">Nombre</p>
                                    <p class="fw-medium mb-0">{{ $student->user->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="small text-muted mb-1">Código de Estudiante</p>
                                    <p class="fw-medium mb-0">{{ $student->code }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="small text-muted mb-1">Facultad</p>
                                    <!-- Contenido faltante -->
                                    <p class="fw-medium mb-0">{{ $student->faculty->name ?? 'No asignada' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection