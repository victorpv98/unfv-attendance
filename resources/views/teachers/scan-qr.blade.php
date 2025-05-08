@extends('layouts.app')

@section('header')
    Escanear Código QR
@endsection

@section('content')
<div class="container">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Escanear Asistencia</h6>
            <a href="{{ route('teachers.my-schedules') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver a Horarios
            </a>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <h4>{{ $schedule->course->name }}</h4>
                <p>
                    <i class="far fa-clock"></i> {{ $schedule->start_time }} - {{ $schedule->end_time }} | 
                    <i class="fas fa-map-marker-alt"></i> {{ $schedule->classroom }} | 
                    <i class="far fa-calendar-alt"></i> {{ __($schedule->day) }}
                </p>
                <p>Fecha actual: {{ now()->format('d/m/Y') }}</p>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">Lector QR</h5>
                        </div>
                        <div class="card-body">
                            <div id="qr-reader" class="mb-3"></div>
                            <div id="qr-reader-results" class="mt-3"></div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">Asistencias Registradas Hoy</h5>
                        </div>
                        <div class="card-body">
                            <div id="attendance-list" style="max-height: 400px; overflow-y: auto;">
                                @forelse($attendances as $attendance)
                                    <div class="alert {{ $attendance->status === 'present' ? 'alert-success' : 'alert-warning' }} mb-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ $attendance->student->user->name }}</strong>
                                                <br>
                                                <small>{{ $attendance->time }} | {{ $attendance->status === 'present' ? 'Presente' : 'Tardanza' }}</small>
                                            </div>
                                            <span class="badge {{ $attendance->status === 'present' ? 'bg-success' : 'bg-warning' }}">
                                                {{ $attendance->status === 'present' ? 'Presente' : 'Tardanza' }}
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="alert alert-info text-center">
                                        No hay asistencias registradas para hoy.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <span class="text-muted">Total: {{ count($attendances) }}</span>
                            <a href="{{ route('attendance.report', $schedule) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-clipboard-list"></i> Ver Reporte Completo
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    #qr-reader {
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
    }
    
    #qr-reader video {
        width: 100%;
        border-radius: 0.25rem;
    }
    
    #qr-reader__dashboard_section_csr button {
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 0.25rem;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        cursor: pointer;
    }
    
    #qr-reader__dashboard_section_csr button:hover {
        background-color: #0069d9;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const html5QrCode = new Html5Qrcode("qr-reader");
        const qrResultDiv = document.getElementById('qr-reader-results');
        const attendanceListDiv = document.getElementById('attendance-list');
        const config = { fps: 10, qrbox: 250 };
        const scheduleId = {{ $schedule->id }};
        
        // Función para iniciar el escáner
        function startScanner() {
            html5QrCode.start(
                { facingMode: "environment" }, 
                config, 
                onScanSuccess, 
                onScanFailure
            );
        }
        
        // Función cuando se escanea exitosamente
        function onScanSuccess(qrCodeMessage) {
            // Pausar el escáner temporalmente
            html5QrCode.pause();
            
            // Mostrar mensaje de procesamiento
            qrResultDiv.innerHTML = `
                <div class="alert alert-info">
                    <div class="d-flex align-items-center">
                        <div class="spinner-border spinner-border-sm me-2" role="status">
                            <span class="visually-hidden">Procesando...</span>
                        </div>
                        <div>Procesando código QR...</div>
                    </div>
                </div>
            `;
            
            // Enviar al servidor
            fetch('{{ route("attendance.register-by-qr") }}', {
                method: 'POST', 
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    qr_code: qrCodeMessage,
                    schedule_id: scheduleId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    // Mostrar resultado
                    let alertClass = data.status === 'present' ? 'alert-success' : 'alert-warning';
                    let badgeClass = data.status === 'present' ? 'bg-success' : 'bg-warning';
                    let statusText = data.status === 'present' ? 'Presente' : 'Tardanza';
                    
                    qrResultDiv.innerHTML = `
                        <div class="alert ${alertClass}">
                            <strong>${data.student}</strong>
                            <p class="mb-0">${data.message}</p>
                            <span class="badge ${badgeClass}">${statusText}</span>
                        </div>
                    `;
                    
                    // Añadir a la lista de asistencia
                    const noAttendanceMsg = attendanceListDiv.querySelector('.alert-info');
                    if (noAttendanceMsg) {
                        noAttendanceMsg.remove();
                    }
                    
                    const now = new Date();
                    const timeStr = now.toLocaleTimeString();
                    
                    const newAttendanceHtml = `
                        <div class="alert ${alertClass} mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>${data.student}</strong>
                                    <br>
                                    <small>${timeStr} | ${statusText}</small>
                                </div>
                                <span class="badge ${badgeClass}">
                                    ${statusText}
                                </span>
                            </div>
                        </div>
                    `;
                    
                    attendanceListDiv.insertAdjacentHTML('afterbegin', newAttendanceHtml);
                    
                    // Reanudar el escáner después de 3 segundos
                    setTimeout(() => {
                        html5QrCode.resume();
                    }, 3000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                qrResultDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Error al procesar. Inténtelo nuevamente.
                    </div>
                `;
                
                // Reanudar el escáner después de 3 segundos
                setTimeout(() => {
                    html5QrCode.resume();
                }, 3000);
            });
        }
        
        // Función cuando hay un error en el escaneo
        function onScanFailure(error) {
            // No mostrar errores en la interfaz para evitar distracciones
            console.warn(`Código QR no detectado: ${error}`);
        }
        
        // Iniciar el escáner al cargar la página
        startScanner();
    });
</script>
@endpush