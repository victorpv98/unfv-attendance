@extends('layouts.app')

@section('header')
    Escanear Código de Barras
@endsection

@section('content')
<div class="container">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-barcode me-2"></i>
                Escanear Asistencia por Código de Barras
            </h6>
            <a href="{{ route('teachers.my-schedules') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver a Horarios
            </a>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                        <i class="fas fa-book text-primary fa-lg"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $schedule->course->name }}</h4>
                        <p class="text-muted mb-0">
                            <i class="far fa-clock me-1"></i> {{ $schedule->start_time }} - {{ $schedule->end_time }} | 
                            <i class="fas fa-map-marker-alt me-1"></i> {{ $schedule->classroom }} | 
                            <i class="far fa-calendar-alt me-1"></i> {{ __($schedule->day) }}
                        </p>
                    </div>
                </div>
                <div class="alert alert-info border-0">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle me-2"></i>
                        <div>
                            <strong>Fecha actual:</strong> {{ now()->format('d/m/Y') }} - {{ now()->format('H:i') }}
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-gradient-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-barcode me-2"></i>
                                Lector de Código de Barras
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Escáner de cámara -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-camera me-1"></i>
                                    Escáner por Cámara
                                </label>
                                <div id="barcode-reader" class="border rounded p-2 mb-3"></div>
                                <div class="d-grid gap-2">
                                    <button id="start-scanner" class="btn btn-primary">
                                        <i class="fas fa-play me-1"></i> Iniciar Escáner
                                    </button>
                                    <button id="stop-scanner" class="btn btn-secondary" style="display: none;">
                                        <i class="fas fa-stop me-1"></i> Detener Escáner
                                    </button>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <!-- Entrada manual -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-keyboard me-1"></i>
                                    Entrada Manual
                                </label>
                                <div class="input-group">
                                    <input type="text" 
                                           id="manual-barcode" 
                                           class="form-control" 
                                           placeholder="Escriba el código aquí o use lector externo..."
                                           autocomplete="off"
                                           maxlength="20">
                                    <button type="button" 
                                            id="submit-manual" 
                                            class="btn btn-success">
                                        <i class="fas fa-check"></i> Procesar
                                    </button>
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Escriba el código completo y presione "Procesar" o Enter
                                </small>
                            </div>
                            
                            <!-- Resultados del escaneo -->
                            <div id="barcode-reader-results" class="mt-3"></div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-gradient-success text-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-clipboard-check me-2"></i>
                                Asistencias Registradas Hoy
                            </h5>
                            <span class="badge bg-white text-dark" id="attendance-counter">{{ count($attendances) }}</span>
                        </div>
                        <div class="card-body p-0">
                            <div id="attendance-list" style="max-height: 450px; overflow-y: auto;">
                                @forelse($attendances as $attendance)
                                    <div class="border-bottom p-3 attendance-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="rounded-circle {{ $attendance->status === 'present' ? 'bg-success' : 'bg-warning' }} bg-opacity-10 p-2 me-3">
                                                        <i class="fas {{ $attendance->status === 'present' ? 'fa-check' : 'fa-clock' }} {{ $attendance->status === 'present' ? 'text-success' : 'text-warning' }}"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">{{ $attendance->student->user->name }}</h6>
                                                        <small class="text-muted">
                                                            <i class="far fa-clock me-1"></i>{{ $attendance->time }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="badge {{ $attendance->status === 'present' ? 'bg-success' : 'bg-warning' }}">
                                                {{ $attendance->status === 'present' ? 'Presente' : 'Tardanza' }}
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5" id="no-attendance-message">
                                        <i class="fas fa-clipboard-list text-muted fa-3x mb-3"></i>
                                        <h5 class="text-muted">No hay asistencias registradas</h5>
                                        <p class="text-muted mb-0">Las asistencias aparecerán aquí cuando sean escaneadas.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-users me-2 text-muted"></i>
                                <span class="text-muted">Total: <strong id="total-count">{{ count($attendances) }}</strong></span>
                            </div>
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
    #barcode-reader {
        min-height: 200px;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    #barcode-reader video {
        width: 100%;
        max-width: 400px;
        border-radius: 0.375rem;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    }
    
    .bg-gradient-success {
        background: linear-gradient(135deg, #198754 0%, #146c43 100%);
    }
    
    .attendance-item:hover {
        background-color: #f8f9fa;
    }
    
    .scanner-inactive {
        background-color: #e9ecef;
        border: 2px dashed #dee2e6;
        color: #6c757d;
        font-size: 1.1rem;
    }
    
    .pulse-animation {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/@zxing/library@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let codeReader = null;
        let selectedDeviceId = null;
        const barcodeReaderDiv = document.getElementById('barcode-reader');
        const resultDiv = document.getElementById('barcode-reader-results');
        const attendanceListDiv = document.getElementById('attendance-list');
        const manualBarcodeInput = document.getElementById('manual-barcode');
        const submitManualBtn = document.getElementById('submit-manual');
        const startScannerBtn = document.getElementById('start-scanner');
        const stopScannerBtn = document.getElementById('stop-scanner');
        const attendanceCounter = document.getElementById('attendance-counter');
        const totalCount = document.getElementById('total-count');
        const scheduleId = {{ $schedule->id }};
        
        // Inicializar el estado del escáner
        barcodeReaderDiv.innerHTML = `
            <div class="scanner-inactive text-center">
                <i class="fas fa-barcode fa-3x mb-2"></i>
                <p class="mb-0">Haga clic en "Iniciar Escáner" para comenzar</p>
            </div>
        `;
        
        // Función para inicializar el lector de códigos de barras
        async function initializeBarcodeReader() {
            try {
                codeReader = new ZXing.BrowserMultiFormatReader();
                const videoInputDevices = await codeReader.listVideoInputDevices();
                
                if (videoInputDevices.length > 0) {
                    selectedDeviceId = videoInputDevices[0].deviceId;
                    return true;
                } else {
                    throw new Error('No se encontraron cámaras disponibles');
                }
            } catch (error) {
                console.error('Error al inicializar el lector:', error);
                showError('Error al acceder a la cámara: ' + error.message);
                return false;
            }
        }
        
        // Función para iniciar el escáner
        async function startScanner() {
            if (!codeReader) {
                const initialized = await initializeBarcodeReader();
                if (!initialized) return;
            }
            
            try {
                startScannerBtn.style.display = 'none';
                stopScannerBtn.style.display = 'block';
                
                barcodeReaderDiv.innerHTML = '<video id="video-element" style="width: 100%; max-width: 400px;"></video>';
                
                const result = await codeReader.decodeFromVideoDevice(
                    selectedDeviceId, 
                    'video-element', 
                    (result, err) => {
                        if (result) {
                            onBarcodeDetected(result.text);
                        }
                        if (err && !(err instanceof ZXing.NotFoundException)) {
                            console.error('Error de escaneo:', err);
                        }
                    }
                );
            } catch (error) {
                console.error('Error al iniciar el escáner:', error);
                showError('Error al iniciar el escáner: ' + error.message);
                resetScanner();
            }
        }
        
        // Función para detener el escáner
        function stopScanner() {
            if (codeReader) {
                codeReader.reset();
            }
            resetScanner();
        }
        
        // Función para resetear la interfaz del escáner
        function resetScanner() {
            startScannerBtn.style.display = 'block';
            stopScannerBtn.style.display = 'none';
            barcodeReaderDiv.innerHTML = `
                <div class="scanner-inactive text-center">
                    <i class="fas fa-barcode fa-3x mb-2"></i>
                    <p class="mb-0">Escáner detenido. Haga clic en "Iniciar Escáner" para continuar</p>
                </div>
            `;
        }
        
        // Función cuando se detecta un código de barras
        function onBarcodeDetected(barcodeText) {
            // Pausar temporalmente el escáner
            if (codeReader) {
                codeReader.reset();
            }
            
            // Mostrar mensaje de procesamiento
            showProcessing();
            
            // Procesar el código de barras
            processBarcodeAttendance(barcodeText);
        }
        
        // Función para limpiar código de barras
        function cleanBarcodeInput(barcode) {
            // Remover sufijos comunes de escáneres para Code 39
            let cleanCode = barcode.replace(/([\s+]code\s*(39|128|129))/gi, '');
            
            // Remover espacios adicionales al inicio y final
            return cleanCode.trim();
        }
        
        // Función para procesar la asistencia
        function processBarcodeAttendance(barcodeCode) {
            // Limpiar el código antes de enviarlo
            const cleanedCode = cleanBarcodeInput(barcodeCode);
            
            // Agregar logs para debugging
            console.log('Código original:', barcodeCode);
            console.log('Código limpio:', cleanedCode);
            console.log('Schedule ID:', scheduleId);
            
            fetch('{{ route("attendance.register-by-barcode") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    barcode: cleanedCode,
                    schedule_id: scheduleId
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                
                if (data.success) {
                    showSuccess(data);
                    addToAttendanceList(data);
                    updateCounters();
                } else {
                    showError(data.message || 'Error al procesar el código de barras');
                }
                
                // Reanudar el escáner después de 3 segundos
                setTimeout(() => {
                    if (stopScannerBtn.style.display !== 'none') {
                        startScanner();
                    }
                }, 3000);
            })
            .catch(error => {
                console.error('Fetch error:', error);
                
                // Mostrar error más específico
                let errorMessage = 'Error de conexión. ';
                
                if (error.message.includes('404')) {
                    errorMessage += 'Ruta no encontrada. Verifica la configuración de rutas.';
                } else if (error.message.includes('500')) {
                    errorMessage += 'Error del servidor. Verifica el controlador.';
                } else if (error.message.includes('CSRF')) {
                    errorMessage += 'Token CSRF inválido. Recarga la página.';
                } else {
                    errorMessage += error.message;
                }
                
                showError(errorMessage);
                
                // Reanudar el escáner después de 3 segundos
                setTimeout(() => {
                    if (stopScannerBtn.style.display !== 'none') {
                        startScanner();
                    }
                }, 3000);
            });
        }
        
        // Función para mostrar estado de procesamiento
        function showProcessing() {
            resultDiv.innerHTML = `
                <div class="alert alert-info border-0">
                    <div class="d-flex align-items-center">
                        <div class="spinner-border spinner-border-sm me-2" role="status">
                            <span class="visually-hidden">Procesando...</span>
                        </div>
                        <div>Procesando código de barras...</div>
                    </div>
                </div>
            `;
        }
        
        // Función para mostrar éxito
        function showSuccess(data) {
            let alertClass = data.status === 'present' ? 'alert-success' : 'alert-warning';
            let iconClass = data.status === 'present' ? 'fa-check-circle' : 'fa-clock';
            let statusText = data.status === 'present' ? 'Presente' : 'Tardanza';
            
            resultDiv.innerHTML = `
                <div class="alert ${alertClass} border-0">
                    <div class="d-flex align-items-center">
                        <i class="fas ${iconClass} fa-lg me-2"></i>
                        <div>
                            <strong>${data.student}</strong>
                            <p class="mb-0">${data.message}</p>
                            <span class="badge ${data.status === 'present' ? 'bg-success' : 'bg-warning'}">${statusText}</span>
                        </div>
                    </div>
                </div>
            `;
        }
        
        // Función para mostrar error
        function showError(message) {
            resultDiv.innerHTML = `
                <div class="alert alert-danger border-0">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div>${message}</div>
                    </div>
                </div>
            `;
        }
        
        // Función para añadir a la lista de asistencia
        function addToAttendanceList(data) {
            // Remover mensaje de "no hay asistencias" si existe
            const noAttendanceMsg = document.getElementById('no-attendance-message');
            if (noAttendanceMsg) {
                noAttendanceMsg.remove();
            }
            
            const now = new Date();
            const timeStr = now.toLocaleTimeString();
            const statusClass = data.status === 'present' ? 'success' : 'warning';
            const iconClass = data.status === 'present' ? 'fa-check' : 'fa-clock';
            const statusText = data.status === 'present' ? 'Presente' : 'Tardanza';
            
            const newAttendanceHtml = `
                <div class="border-bottom p-3 attendance-item pulse-animation">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-2">
                                <div class="rounded-circle bg-${statusClass} bg-opacity-10 p-2 me-3">
                                    <i class="fas ${iconClass} text-${statusClass}"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">${data.student}</h6>
                                    <small class="text-muted">
                                        <i class="far fa-clock me-1"></i>${timeStr}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <span class="badge bg-${statusClass}">
                            ${statusText}
                        </span>
                    </div>
                </div>
            `;
            
            attendanceListDiv.insertAdjacentHTML('afterbegin', newAttendanceHtml);
            
            // Remover la animación después de 3 segundos
            setTimeout(() => {
                const newItem = attendanceListDiv.querySelector('.pulse-animation');
                if (newItem) {
                    newItem.classList.remove('pulse-animation');
                }
            }, 3000);
        }
        
        // Función para actualizar contadores
        function updateCounters() {
            const currentCount = parseInt(attendanceCounter.textContent) + 1;
            attendanceCounter.textContent = currentCount;
            totalCount.textContent = currentCount;
        }
        
        // Event listeners
        startScannerBtn.addEventListener('click', startScanner);
        stopScannerBtn.addEventListener('click', stopScanner);
        
        // Entrada manual de código de barras
        submitManualBtn.addEventListener('click', function() {
            const barcodeValue = cleanBarcodeInput(manualBarcodeInput.value.trim()); // Limpiar aquí también
            if (barcodeValue) {
                showProcessing();
                processBarcodeAttendance(barcodeValue);
                manualBarcodeInput.value = '';
            } else {
                showError('Por favor ingrese un código de barras válido');
            }
        });
        
        // Permitir envío con Enter en el campo manual
        manualBarcodeInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                submitManualBtn.click();
            }
        });
        
        // Auto-focus en el campo manual para lectores externos
        manualBarcodeInput.addEventListener('input', function() {
            // DESACTIVADO: Auto-procesamiento automático
            // Solo mantener el input activo para lectores externos
            // El usuario debe hacer clic en "Procesar" o presionar Enter
        });
        
        // Mantener el foco en el campo manual (solo si no hay video activo)
        setInterval(() => {
            if (document.activeElement !== manualBarcodeInput && 
                !document.querySelector('video') &&
                manualBarcodeInput.value === '') { // Solo si el campo está vacío
                manualBarcodeInput.focus();
            }
        }, 2000); // Reducido a cada 2 segundos y más restrictivo
        
        // Limpiar resultados después de 15 segundos (sin interferir con la escritura)
        let clearResultsTimeout;
        function scheduleResultsClear() {
            clearTimeout(clearResultsTimeout);
            clearResultsTimeout = setTimeout(() => {
                if (resultDiv.innerHTML.trim() !== '' && manualBarcodeInput.value.trim() === '') {
                    resultDiv.innerHTML = '';
                }
            }, 15000);
        }
        
        // Programar limpieza solo cuando sea necesario
        const originalShowSuccess = showSuccess;
        const originalShowError = showError;
        
        showSuccess = function(data) {
            originalShowSuccess(data);
            scheduleResultsClear();
        };
        
        showError = function(message) {
            originalShowError(message);
            scheduleResultsClear();
        };
        
        // Inicializar
        manualBarcodeInput.focus();
    });
</script>
@endpush