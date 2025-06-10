@extends('layouts.app')

@section('header')
    Mi Código de Barras
@endsection

@section('content')
    <div class="card shadow border-0">
        <div class="card-body p-4">
            <div class="mb-4">
                <h2 class="fs-1 fw-semibold mb-2">
                    <i class="fas fa-barcode me-3 text-primary"></i>
                    Mi Código de Barras de Asistencia
                </h2>
                <p class="text-muted">Este código de barras te identifica para registrar tu asistencia a clases</p>
            </div>

            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0 text-center">
                    <div class="mb-4 p-3 bg-white border rounded shadow-sm d-inline-block">
                        <img src="{{ route('students.barcode-image', $student) }}" alt="Mi Código de Barras" class="img-fluid" style="max-width: 280px; height: auto;" id="barcode-image">
                    </div>
                    
                    <div class="mb-4">
                        <p class="small text-muted mb-1">Código de identificación:</p>
                        <code class="bg-light p-2 rounded">{{ $student->code }}</code>
                        <p class="small text-muted mt-2 mb-0">Este código es único y permanente</p>
                    </div>
                </div>
                
                <div class="col-md-8">
                    <div class="card bg-info bg-opacity-10 border-0 mb-4">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-semibold text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                Instrucciones de Uso
                            </h5>
                            <ul class="list-unstyled mb-0">
                                <li class="d-flex mb-2">
                                    <i class="fas fa-check-circle text-primary me-2 mt-1"></i>
                                    <span>Muestra este código de barras al profesor al inicio de cada clase.</span>
                                </li>
                                <li class="d-flex mb-2">
                                    <i class="fas fa-check-circle text-primary me-2 mt-1"></i>
                                    <span>El profesor escaneará tu código con su dispositivo móvil usando la cámara.</span>
                                </li>
                                <li class="d-flex mb-2">
                                    <i class="fas fa-check-circle text-primary me-2 mt-1"></i>
                                    <span>Tu asistencia quedará registrada automáticamente en el sistema.</span>
                                </li>
                                <li class="d-flex mb-2">
                                    <i class="fas fa-check-circle text-primary me-2 mt-1"></i>
                                    <span>Puedes ver el historial de tus asistencias en la sección "Mis Asistencias".</span>
                                </li>
                                <li class="d-flex mb-2">
                                    <i class="fas fa-lightbulb text-info me-2 mt-1"></i>
                                    <span class="text-dark">Asegúrate de que el código de barras esté bien iluminado al momento del escaneo.</span>
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
                            <h5 class="card-title fw-semibold mb-3">
                                <i class="fas fa-user me-2"></i>
                                Información Personal
                            </h5>
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
                                    <p class="fw-medium mb-0">{{ $student->faculty->name ?? 'No asignada' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="small text-muted mb-1">Ciclo</p>
                                    <p class="fw-medium mb-0">{{ $student->cycle ?? 'No asignado' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card bg-success bg-opacity-10 border-success border-opacity-25 mt-3">
                        <div class="card-body p-3">
                            <h6 class="card-title fw-semibold text-success mb-2">
                                <i class="fas fa-shield-alt me-2"></i>
                                Código Único y Seguro
                            </h6>
                            <p class="small mb-0 text-dark">
                                <strong>Tu código de barras es único y permanente</strong>, basado en tu código de estudiante. 
                                No puede ser modificado ni duplicado, garantizando la seguridad del sistema de asistencias. 
                                <strong>No compartas</strong> este código con otros estudiantes.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection