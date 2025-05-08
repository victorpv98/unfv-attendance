@extends('layouts.app')

@section('header')
    Nuevo Horario
@endsection

@section('content')
<div class="card shadow border-0 mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 fw-bold text-primary">Crear Nuevo Horario</h6>
        <a href="{{ route('admin.schedules.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.schedules.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="course_id" class="form-label">Curso</label>
                <select class="form-select @error('course_id') is-invalid @enderror" 
                    id="course_id" name="course_id" required>
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
                <label for="teacher_id" class="form-label">Profesor</label>
                <select class="form-select @error('teacher_id') is-invalid @enderror" 
                    id="teacher_id" name="teacher_id" required>
                    <option value="">Seleccione un profesor</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->user->name }} ({{ $teacher->code }})
                        </option>
                    @endforeach
                </select>
                @error('teacher_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="classroom" class="form-label">Aula</label>
                <input type="text" class="form-control @error('classroom') is-invalid @enderror" 
                    id="classroom" name="classroom" value="{{ old('classroom') }}" required>
                @error('classroom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="day" class="form-label">Día</label>
                <select class="form-select @error('day') is-invalid @enderror" 
                    id="day" name="day" required>
                    <option value="">Seleccione un día</option>
                    <option value="monday" {{ old('day') == 'monday' ? 'selected' : '' }}>Lunes</option>
                    <option value="tuesday" {{ old('day') == 'tuesday' ? 'selected' : '' }}>Martes</option>
                    <option value="wednesday" {{ old('day') == 'wednesday' ? 'selected' : '' }}>Miércoles</option>
                    <option value="thursday" {{ old('day') == 'thursday' ? 'selected' : '' }}>Jueves</option>
                    <option value="friday" {{ old('day') == 'friday' ? 'selected' : '' }}>Viernes</option>
                    <option value="saturday" {{ old('day') == 'saturday' ? 'selected' : '' }}>Sábado</option>
                </select>
                @error('day')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="start_time" class="form-label">Hora de inicio</label>
                    <input type="time" class="form-control @error('start_time') is-invalid @enderror" 
                        id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                    @error('start_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="end_time" class="form-label">Hora de fin</label>
                    <input type="time" class="form-control @error('end_time') is-invalid @enderror" 
                        id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                    @error('end_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="semester" class="form-label">Semestre</label>
                <input type="text" class="form-control @error('semester') is-invalid @enderror" 
                    id="semester" name="semester" value="{{ old('semester', date('Y').'-I') }}" required placeholder="Ejemplo: 2025-I">
                @error('semester')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar Horario</button>
            </div>
        </form>
    </div>
</div>
@endsection