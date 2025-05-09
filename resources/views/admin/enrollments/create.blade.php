@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Nueva Matrícula</h1>
        <a href="{{ route('admin.enrollments.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.enrollments.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="course_id" class="form-label">Curso <span class="text-danger">*</span></label>
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
                
                <div class="mb-3">
                    <label for="semester" class="form-label">Semestre <span class="text-danger">*</span></label>
                    <input type="text" id="semester" name="semester" class="form-control @error('semester') is-invalid @enderror" value="{{ old('semester', $currentSemester) }}" required>
                    <div class="form-text">Formato: YYYY-[I/II] (Ej: 2025-I para primer semestre, 2025-II para segundo semestre)</div>
                    @error('semester')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Estudiantes <span class="text-danger">*</span></label>
                    <div class="card">
                        <div class="card-header bg-light">
                            <input type="text" id="studentSearch" class="form-control" placeholder="Buscar estudiante por nombre o código...">
                        </div>
                        <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                            @if($students->isEmpty())
                                <p class="text-muted mb-0">No hay estudiantes registrados.</p>
                            @else
                                <div class="mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                        <label class="form-check-label" for="selectAll">
                                            <strong>Seleccionar todos</strong>
                                        </label>
                                    </div>
                                </div>
                                <hr>
                                <div id="studentsList">
                                    @foreach($students as $student)
                                        <div class="form-check student-item mb-2">
                                            <input class="form-check-input student-checkbox" type="checkbox" 
                                                name="student_ids[]" value="{{ $student->id }}" 
                                                id="student_{{ $student->id }}"
                                                {{ in_array($student->id, old('student_ids', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="student_{{ $student->id }}">
                                                <strong>{{ $student->user->name }}</strong>
                                                <br>
                                                <small class="text-muted">Código: {{ $student->code }}</small>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    @error('student_ids')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
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
    // Función para buscar estudiantes
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('studentSearch');
        const studentItems = document.querySelectorAll('.student-item');
        const selectAllCheckbox = document.getElementById('selectAll');
        const studentCheckboxes = document.querySelectorAll('.student-checkbox');
        
        // Buscador de estudiantes
        searchInput.addEventListener('input', function() {
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
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            
            studentCheckboxes.forEach(checkbox => {
                if (checkbox.closest('.student-item').style.display !== 'none') {
                    checkbox.checked = isChecked;
                }
            });
        });
        
        // Actualizar "seleccionar todos" cuando se cambian checkboxes individuales
        studentCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectAllState);
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
    });
</script>
@endpush
@endsection