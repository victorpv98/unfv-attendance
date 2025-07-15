@extends('layouts.app')

@section('header')
    Escanear Código de Barras
@endsection

@section('content')
<div class="container">
    <div class="card shadow border-0 rounded-3 mb-4">
        <div class="card-header bg-primary bg-opacity-10 border-0 py-3 d-flex flex-row align-items-center justify-content-between">
            <h5 class="mb-0 fw-semibold text-primary">
                <i class="fas fa-qrcode me-2"></i>
                Escanear Asistencia por Código de Barras
            </h5>
            <a href="{{ route('teachers.my-schedules') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver a Horarios
            </a>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                        <i class="fas fa-book text-primary fa-lg"></i>
                    </div>
                    <div>
                        <h4 class="mb-1 text-dark">{{ $schedule->course->name }}</h4>
                        <div class="d-flex flex-wrap gap-3 text-muted">
                            <span><i class="fas fa-clock me-1 text-primary"></i> {{ $schedule->start_time }} - {{ $schedule->end_time }}</span>
                            <span><i class="fas fa-map-marker-alt me-1 text-primary"></i> {{ $schedule->classroom }}</span>
                            <span><i class="fas fa-calendar-alt me-1 text-primary"></i> {{ __($schedule->day) }}</span>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info border-0 bg-info bg-opacity-10">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle me-2 text-info"></i>
                        <div>
                            <strong class="text-info">Fecha actual:</strong> 
                            <span class="text-dark">{{ now()->format('d/m/Y') }} - {{ now()->format('H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-primary text-white border-0">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-qrcode me-2"></i>
                                Lector de Código de Barras
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Escáner de cámara -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-camera me-1 text-primary"></i>
                                    Escáner por Cámara
                                </label>
                                <div id="barcode-reader" class="border border-primary rounded-2 p-3 mb-3 bg-light"></div>
                                <div class="d-grid gap-2">
                                    <button id="start-scanner" class="btn btn-primary">
                                        <i class="fas fa-play me-1"></i> Iniciar Escáner
                                    </button>
                                    <button id="stop-scanner" class="btn btn-secondary" style="display: none;">
                                        <i class="fas fa-stop me-1"></i> Detener Escáner
                                    </button>
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            
                            <!-- Entrada manual -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-keyboard me-1 text-primary"></i>
                                    Entrada Manual
                                </label>
                                <div class="input-group">
                                    <input type="text" 
                                           id="manual-barcode" 
                                           class="form-control border-primary" 
                                           placeholder="Escriba el código aquí o use lector externo..."
                                           autocomplete="off"
                                           maxlength="20">
                                    <button type="button" 
                                            id="submit-manual" 
                                            class="btn btn-success">
                                        <i class="fas fa-check me-1"></i> Procesar
                                    </button>
                                </div>
                                <small class="form-text text-muted mt-2">
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
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-success text-white border-0 d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-clipboard-check me-2"></i>
                                Asistencias Registradas Hoy
                            </h5>
                            <span class="badge bg-white text-success fw-bold" id="attendance-counter">{{ count($attendances) }}</span>
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
                                                        <h6 class="mb-1 text-dark">{{ $attendance->student->user->name }}</h6>
                                                        <small class="text-muted">
                                                            <i class="fas fa-clock me-1"></i>{{ $attendance->time }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="badge {{ $attendance->status === 'present' ? 'bg-success' : 'bg-warning text-dark' }} px-3 py-2">
                                                {{ $attendance->status === 'present' ? 'Presente' : 'Tardanza' }}
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5" id="no-attendance-message">
                                        <i class="fas fa-clipboard-list text-muted fa-4x mb-3 opacity-50"></i>
                                        <h5 class="text-muted">No hay asistencias registradas</h5>
                                        <p class="text-muted mb-0">Las asistencias aparecerán aquí cuando sean escaneadas.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        <div class="card-footer bg-light border-0 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-users me-2 text-primary"></i>
                                <span class="text-dark">Total: <strong id="total-count">{{ count($attendances) }}</strong></span>
                            </div>
                            <a href="{{ route('attendance.report', $schedule) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-chart-line me-1"></i> Ver Reporte Completo
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
    
    .attendance-item:hover {
        background-color: rgba(var(--bs-primary-rgb), 0.05);
        transition: background-color 0.2s ease;
    }
    
    .scanner-inactive {
        background-color: #e9ecef;
        border: 2px dashed #dee2e6;
        color: #6c757d;
        font-size: 1.1rem;
        border-radius: 0.375rem;
    }
    
    .pulse-animation {
        animation: pulse 2s infinite;
        background-color: rgba(var(--bs-success-rgb), 0.1) !important;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.02); }
        100% { transform: scale(1); }
    }
    
    #manual-barcode:focus {
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.25);
    }
    
    .alert {
        border-radius: 0.5rem;
    }
    
    .alert-success {
        background-color: rgba(var(--bs-success-rgb), 0.1);
        border-color: var(--bs-success);
    }
    
    .alert-warning {
        background-color: rgba(var(--bs-warning-rgb), 0.1);
        border-color: var(--bs-warning);
    }
    
    .alert-danger {
        background-color: rgba(var(--bs-danger-rgb), 0.1);
        border-color: var(--bs-danger);
    }
    
    .alert-info {
        background-color: rgba(var(--bs-info-rgb), 0.1);
        border-color: var(--bs-info);
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
        
        // URL corregida para el endpoint
        const registerUrl = '{{ route("attendance.register-by-barcode") }}';
        const csrfToken = '{{ csrf_token() }}';
        
        // Inicializar el estado del escáner
        barcodeReaderDiv.innerHTML = `
            <div class="scanner-inactive text-center w-100 py-4">
                <i class="fas fa-qrcode fa-3x text-primary mb-3"></i>
                <h6 class="text-muted">Lector de Código de Barras</h6>
                <p class="mb-0 small">Haga clic en "Iniciar Escáner" para comenzar</p>
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
                
                barcodeReaderDiv.innerHTML = '<video id="video-element" class="w-100 rounded-2" style="max-width: 400px;"></video>';
                
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
                <div class="scanner-inactive text-center w-100 py-4">
                    <i class="fas fa-qrcode fa-3x text-primary mb-3"></i>
                    <h6 class="text-muted">Escáner detenido</h6>
                    <p class="mb-0 small">Haga clic en "Iniciar Escáner" para continuar</p>
                </div>
            `;
        }
        
        // Función cuando se detecta un código de barras
        function onBarcodeDetected(barcodeText) {
            if (codeReader) {
                codeReader.reset();
            }
            showProcessing();
            processBarcodeAttendance(barcodeText);
        }
        
        // Función para limpiar código de barras
        function cleanBarcodeInput(barcode) {
            if (!barcode) return '';
            
            let cleanCode = String(barcode);
            cleanCode = cleanCode.replace(/([\s+]code\s*(39|128|129))/gi, '');
            cleanCode = cleanCode.replace(/[^\w\-]/g, '');
            return cleanCode.trim();
        }
        
        // Función para procesar la asistencia - CORREGIDA
        function processBarcodeAttendance(barcodeCode) {
            const cleanedCode = cleanBarcodeInput(barcodeCode);
            
            if (!cleanedCode || cleanedCode.length < 3) {
                showError('El código de barras debe tener al menos 3 caracteres');
                return;
            }
            
            console.log('Código original:', barcodeCode);
            console.log('Código limpio:', cleanedCode);
            console.log('Schedule ID:', scheduleId);
            console.log('URL:', registerUrl);
            
            fetch(registerUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    barcode: cleanedCode,
                    schedule_id: scheduleId
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                
                return response.text().then(text => {
                    console.log('Response text:', text);
                    
                    if (!response.ok) {
                        if (response.status === 500) {
                            throw new Error(`Error del servidor (500): ${text.substring(0, 200)}...`);
                        } else if (response.status === 404) {
                            throw new Error('Ruta no encontrada (404). Verifica que la ruta esté definida.');
                        } else if (response.status === 422) {
                            throw new Error('Error de validación (422). Verifica los datos enviados.');
                        } else if (response.status === 419) {
                            throw new Error('Token CSRF expirado (419). Recarga la página.');
                        } else {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                    }
                    
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Error al parsear JSON:', e);
                        throw new Error('Respuesta del servidor no es JSON válido');
                    }
                });
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
                
                setTimeout(() => {
                    if (stopScannerBtn.style.display !== 'none') {
                        startScanner();
                    }
                }, 3000);
            })
            .catch(error => {
                console.error('Fetch error:', error);
                showError(error.message || 'Error de conexión desconocido');
                
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
                        <div class="spinner-border spinner-border-sm me-3 text-info" role="status">
                            <span class="visually-hidden">Procesando...</span>
                        </div>
                        <div>
                            <strong class="text-info">Procesando código de barras...</strong>
                            <p class="mb-0 small">Por favor espere...</p>
                        </div>
                    </div>
                </div>
            `;
        }
        
        // Función para mostrar éxito
        function showSuccess(data) {
            let alertClass = data.status === 'present' ? 'alert-success' : 'alert-warning';
            let iconClass = data.status === 'present' ? 'fa-check-circle' : 'fa-clock';
            let statusText = data.status === 'present' ? 'Presente' : 'Tardanza';
            let textColor = data.status === 'present' ? 'text-success' : 'text-warning';
            
            resultDiv.innerHTML = `
                <div class="alert ${alertClass} border-0">
                    <div class="d-flex align-items-center">
                        <i class="fas ${iconClass} fa-lg me-3 ${textColor}"></i>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 ${textColor}">${data.student}</h6>
                            <p class="mb-2 text-dark">${data.message}</p>
                            <span class="badge ${data.status === 'present' ? 'bg-success' : 'bg-warning text-dark'} px-3 py-2">${statusText}</span>
                        </div>
                    </div>
                </div>
            `;
        }
        
        // Función para mostrar error - MEJORADA
        function showError(message) {
            const now = new Date().toLocaleTimeString();
            
            resultDiv.innerHTML = `
                <div class="alert alert-danger border-0">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle fa-lg me-3 text-danger"></i>
                        <div>
                            <h6 class="mb-1 text-danger">Error (${now})</h6>
                            <p class="mb-1 text-dark">${message}</p>
                        </div>
                    </div>
                </div>
            `;
        }
        
        // Función para añadir a la lista de asistencia
        function addToAttendanceList(data) {
            const noAttendanceMsg = document.getElementById('no-attendance-message');
            if (noAttendanceMsg) {
                noAttendanceMsg.remove();
            }
            
            const now = new Date();
            const timeStr = now.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
            const statusClass = data.status === 'present' ? 'success' : 'warning';
            const iconClass = data.status === 'present' ? 'fa-check' : 'fa-clock';
            const statusText = data.status === 'present' ? 'Presente' : 'Tardanza';
            const badgeClass = data.status === 'present' ? 'bg-success' : 'bg-warning text-dark';
            
            const newAttendanceHtml = `
                <div class="border-bottom p-3 attendance-item pulse-animation">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-2">
                                <div class="rounded-circle bg-${statusClass} bg-opacity-10 p-2 me-3">
                                    <i class="fas ${iconClass} text-${statusClass}"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-dark">${data.student}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>${timeStr}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <span class="badge ${badgeClass} px-3 py-2">
                            ${statusText}
                        </span>
                    </div>
                </div>
            `;
            
            attendanceListDiv.insertAdjacentHTML('afterbegin', newAttendanceHtml);
            
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
            const barcodeValue = manualBarcodeInput.value.trim();
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
                e.preventDefault();
                submitManualBtn.click();
            }
        });
        
        // Mantener el foco en el campo manual para lectores externos
        manualBarcodeInput.addEventListener('blur', function() {
            setTimeout(() => {
                if (!document.querySelector('video') && manualBarcodeInput.value === '') {
                    manualBarcodeInput.focus();
                }
            }, 100);
        });
        
        // Limpiar resultados después de 10 segundos
        let clearResultsTimeout;
        function scheduleResultsClear() {
            clearTimeout(clearResultsTimeout);
            clearResultsTimeout = setTimeout(() => {
                if (resultDiv.innerHTML.trim() !== '') {
                    resultDiv.innerHTML = '';
                }
            }, 10000);
        }
        
        // Programar limpieza cuando se muestren resultados
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' && resultDiv.innerHTML.trim() !== '') {
                    scheduleResultsClear();
                }
            });
        });
        
        observer.observe(resultDiv, { childList: true });
        
        // Inicializar
        manualBarcodeInput.focus();
        
        console.log('Sistema inicializado correctamente');
        console.log('Schedule ID:', scheduleId);
        console.log('Register URL:', registerUrl);
    });
</script>
@endpush