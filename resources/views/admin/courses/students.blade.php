@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Estudiantes matriculados en {{ $course->name }}</h1>
        <div>
            <a href="{{ route('admin.enrollments.index') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i> Volver a matricula
            </a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#enrollStudentsModal">
                <i class="fas fa-user-plus me-1"></i> Matricular estudiantes
            </button>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($students->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>No hay estudiantes matriculados en este curso.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Código</th>
                                <th>Email</th>
                                <th>Semestre</th>
                                <th>Fecha de matrícula</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                <tr>
                                    <td>{{ $student->user->name }}</td>
                                    <td>{{ $student->code }}</td>
                                    <td>{{ $student->user->email }}</td>
                                    <td>{{ $student->pivot->semester }}</td>
                                    <td>{{ \Carbon\Carbon::parse($student->pivot->created_at)->format('d/m/Y') }}</td>
                                    <td>
                                        <form action="{{ route('admin.courses.unenroll', [$course->id, $student->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro que desea eliminar a este estudiante del curso?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para matricular estudiantes -->
<div class="modal fade" id="enrollStudentsModal" tabindex="-1" aria-labelledby="enrollStudentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="enrollStudentsModalLabel">Matricular estudiantes en {{ $course->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.courses.enroll', $course->id) }}" method="POST" id="enrollForm">
                <div class="modal-body">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="semester" class="form-label">Semestre</label>
                        <input type="text" class="form-control" id="semester" name="semester" 
                               value="{{ date('Y') . '-' . (date('n') <= 6 ? 'I' : 'II') }}" required>
                        <div class="form-text">Formato: YYYY-I o YYYY-II (Ej: 2025-I)</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Seleccionar estudiantes</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Buscar estudiantes..." id="studentSearch">
                            <button class="btn btn-outline-secondary" type="button" id="selectAllBtn">Seleccionar todos</button>
                        </div>
                        
                        <div class="card" style="max-height: 300px; overflow-y: auto;">
                            <div class="card-body">
                                <div id="studentList">
                                    @php
                                        $enrolledStudentIds = $students->pluck('id')->toArray();
                                        $allStudents = App\Models\Student::with('user')->orderBy('code')->get();
                                    @endphp
                                    
                                    @if($allStudents->isEmpty())
                                        <div class="alert alert-warning">
                                            No hay estudiantes disponibles para matricular.
                                        </div>
                                    @else
                                        @foreach($allStudents as $student)
                                            <div class="form-check student-item mb-2 {{ in_array($student->id, $enrolledStudentIds) ? 'text-muted' : '' }}">
                                                <input class="form-check-input student-checkbox" type="checkbox" name="student_ids[]" 
                                                    value="{{ $student->id }}" id="student{{ $student->id }}" 
                                                    {{ in_array($student->id, $enrolledStudentIds) ? 'disabled checked' : '' }}>
                                                <label class="form-check-label" for="student{{ $student->id }}">
                                                    <strong>{{ $student->user->name }}</strong> ({{ $student->code }})
                                                    @if(in_array($student->id, $enrolledStudentIds))
                                                        <span class="badge bg-info">Ya matriculado</span>
                                                    @endif
                                                </label>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-2">
                            <small class="text-muted">
                                <span id="selectedCount">0</span> estudiante(s) seleccionado(s)
                            </small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        Matricular
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const studentSearch = document.getElementById('studentSearch');
        const studentItems = document.querySelectorAll('.student-item');
        const selectAllBtn = document.getElementById('selectAllBtn');
        const studentCheckboxes = document.querySelectorAll('.student-checkbox:not([disabled])');
        const selectedCount = document.getElementById('selectedCount');
        const submitBtn = document.getElementById('submitBtn');
        const enrollForm = document.getElementById('enrollForm');
        
        // Función para actualizar el contador
        function updateSelectedCount() {
            const checkedBoxes = document.querySelectorAll('.student-checkbox:not([disabled]):checked');
            selectedCount.textContent = checkedBoxes.length;
            
            // Habilitar/deshabilitar botón de envío
            submitBtn.disabled = checkedBoxes.length === 0;
        }
        
        // Buscador de estudiantes
        studentSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            studentItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
        
        // Seleccionar todos
        selectAllBtn.addEventListener('click', function() {
            const visibleCheckboxes = Array.from(studentCheckboxes).filter(checkbox => {
                return checkbox.closest('.student-item').style.display !== 'none';
            });
            
            const anyUnchecked = visibleCheckboxes.some(checkbox => !checkbox.checked);
            
            visibleCheckboxes.forEach(checkbox => {
                checkbox.checked = anyUnchecked;
            });
            
            updateSelectedCount();
        });
        
        // Eventos de checkbox
        studentCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedCount);
        });
        
        // Envío del formulario
        enrollForm.addEventListener('submit', function(e) {
            const checkedBoxes = document.querySelectorAll('.student-checkbox:not([disabled]):checked');
            
            if (checkedBoxes.length === 0) {
                e.preventDefault();
                alert('Debe seleccionar al menos un estudiante para matricular.');
                return;
            }
            
            // Mostrar spinner
            const spinner = submitBtn.querySelector('.spinner-border');
            spinner.classList.remove('d-none');
            submitBtn.disabled = true;
        });
        
        // Inicializar contador
        updateSelectedCount();
        
        // Reset modal cuando se cierre
        const modal = document.getElementById('enrollStudentsModal');
        modal.addEventListener('hidden.bs.modal', function() {
            enrollForm.reset();
            studentSearch.value = '';
            studentItems.forEach(item => item.style.display = '');
            updateSelectedCount();
            
            // Ocultar spinner
            const spinner = submitBtn.querySelector('.spinner-border');
            spinner.classList.add('d-none');
            submitBtn.disabled = false;
        });
    });
</script>
@endpush
@endsection