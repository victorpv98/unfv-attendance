@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-primary">Nueva Matrícula</h1>
        <a href="{{ route('admin.enrollments.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card shadow border-0 rounded-3">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-clipboard-list me-2"></i>Formulario de Matrícula
            </h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.enrollments.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="course_id" class="form-label fw-medium">
                            Curso <span class="text-danger">*</span>
                        </label>
                        <select id="course_id" name="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
                            <option value="">Seleccione un curso</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->name }} ({{ $course->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="semester" class="form-label fw-medium">
                            Semestre <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="semester" name="semester" class="form-control @error('semester') is-invalid @enderror" value="{{ old('semester', $currentSemester) }}" required>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Formato: YYYY-[I/II] (Ej: 2025-I para primer semestre, 2025-II para segundo semestre)
                        </div>
                        @error('semester')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-medium">
                        Estudiantes <span class="text-danger">*</span>
                    </label>
                    <div class="card border">
                        <div class="card-header bg-light py-3">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" id="studentSearch" class="form-control border-start-0" placeholder="Buscar estudiante por nombre o código...">
                            </div>
                        </div>
                        <div class="card-body p-3" style="max-height: 350px; overflow-y: auto;">
                            @if($students->isEmpty())
                                <div class="text-center py-4">
                                    <i class="fas fa-user-graduate fa-3x text-muted opacity-50 mb-3"></i>
                                    <p class="text-muted mb-0">No hay estudiantes registrados.</p>
                                </div>
                            @else
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                        <label class="form-check-label fw-medium text-primary" for="selectAll">
                                            <i class="fas fa-check-double me-1"></i>Seleccionar todos
                                        </label>
                                    </div>
                                </div>
                                <hr class="my-3">
                                <div id="studentsList">
                                    @foreach($students as $student)
                                        <div class="form-check student-item mb-3 p-2 rounded border-start border-primary border-3" style="border-left-width: 3px !important;">
                                            <input class="form-check-input student-checkbox" type="checkbox" 
                                                name="student_ids[]" value="{{ $student->id }}" 
                                                id="student_{{ $student->id }}"
                                                {{ in_array($student->id, old('student_ids', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label w-100" for="student_{{ $student->id }}">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong class="text-dark">{{ $student->user->name }}</strong>
                                                        <br>
                                                        <small class="text-muted">
                                                            <i class="fas fa-id-badge me-1"></i>
                                                            Código: {{ $student->code }}
                                                        </small>
                                                    </div>
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                        ID: {{ $student->id }}
                                                    </span>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <div id="noResults" class="text-center py-4 d-none">
                                    <i class="fas fa-search fa-2x text-muted opacity-50 mb-2"></i>
                                    <p class="text-muted mb-0">No se encontraron estudiantes con esos criterios</p>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer bg-light py-2">
                            <small class="text-muted">
                                <i class="fas fa-users me-1"></i>
                                <span id="selectedCount">0</span> estudiante(s) seleccionado(s)
                            </small>
                        </div>
                    </div>
                    @error('student_ids')
                        <div class="text-danger mt-2">
                            <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end pt-3 border-top">
                    <a href="{{ route('admin.enrollments.index') }}" class="btn btn-outline-secondary me-md-2">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Guardar Matrícula
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('studentSearch');
        const studentItems = document.querySelectorAll('.student-item');
        const selectAllCheckbox = document.getElementById('selectAll');
        const studentCheckboxes = document.querySelectorAll('.student-checkbox');
        const selectedCountElement = document.getElementById('selectedCount');
        const noResultsElement = document.getElementById('noResults');
        
        // Función para actualizar contador
        function updateSelectedCount() {
            const checkedCount = document.querySelectorAll('.student-checkbox:checked').length;
            selectedCountElement.textContent = checkedCount;
        }
        
        // Función para mostrar/ocultar mensaje de "sin resultados"
        function toggleNoResults() {
            const visibleItems = Array.from(studentItems).filter(item => item.style.display !== 'none');
            if (visibleItems.length === 0 && searchInput.value.trim() !== '') {
                noResultsElement.classList.remove('d-none');
            } else {
                noResultsElement.classList.add('d-none');
            }
        }
        
        // Buscador de estudiantes
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            
            studentItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (searchTerm === '' || text.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
            
            toggleNoResults();
            updateSelectAllState();
        });
        
        // Seleccionar todos
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            
            studentCheckboxes.forEach(checkbox => {
                if (checkbox.closest('.student-item').style.display !== 'none') {
                    checkbox.checked = isChecked;
                }
            });
            
            updateSelectedCount();
        });
        
        // Actualizar "seleccionar todos" cuando se cambian checkboxes individuales
        studentCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateSelectAllState();
                updateSelectedCount();
            });
        });
        
        function updateSelectAllState() {
            const visibleCheckboxes = Array.from(studentCheckboxes).filter(
                checkbox => checkbox.closest('.student-item').style.display !== 'none'
            );
            
            const allChecked = visibleCheckboxes.every(checkbox => checkbox.checked);
            const someChecked = visibleCheckboxes.some(checkbox => checkbox.checked);
            
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = someChecked && !allChecked;
        }
        
        // Inicializar contador
        updateSelectedCount();
    });
</script>
@endpush
@endsection