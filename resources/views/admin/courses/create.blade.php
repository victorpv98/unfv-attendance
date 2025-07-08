@extends('layouts.app')

@section('header')
    Nuevo Curso
@endsection

@section('content')
<div class="card shadow border-0 mb-4">
    <div class="card-header bg-primary text-white py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 fw-bold">Crear Nuevo Curso</h6>
        <a href="{{ route('admin.courses.index') }}" class="btn btn-sm btn-light">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.courses.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label fw-medium">Nombre del Curso</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                    id="name" name="name" value="{{ old('name') }}" required placeholder="Ingrese el nombre del curso">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="code" class="form-label fw-medium">Código del Curso</label>
                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                    id="code" name="code" value="{{ old('code') }}" required placeholder="Ej: MAT101">
                @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="faculty_id" class="form-label fw-medium">Escuela</label>
                <select class="form-select @error('faculty_id') is-invalid @enderror" 
                    id="faculty_id" name="faculty_id" required>
                    <option value="">Seleccione una escuela</option>
                    @foreach($faculties as $faculty)
                        <option value="{{ $faculty->id }}" {{ old('faculty_id') == $faculty->id ? 'selected' : '' }}>
                            {{ $faculty->name }}
                        </option>
                    @endforeach
                </select>
                @error('faculty_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="credits" class="form-label fw-medium">Créditos</label>
                        <input type="number" class="form-control @error('credits') is-invalid @enderror" 
                            id="credits" name="credits" value="{{ old('credits') }}" required min="1" max="10" placeholder="1-10">
                        @error('credits')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="cycle" class="form-label fw-medium">Ciclo Académico</label>
                        <input type="number" class="form-control @error('cycle') is-invalid @enderror" 
                            id="cycle" name="cycle" value="{{ old('cycle') }}" required min="1" max="10" placeholder="1-10">
                        @error('cycle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Guardar Curso
                </button>
            </div>
        </form>
    </div>
</div>
@endsection