@extends('layouts.app')

@section('header')
    Nuevo Profesor
@endsection

@section('content')
<div class="card shadow border-0 mb-4 rounded-3">
    <div class="card-header bg-primary text-white py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 fw-bold">
            <i class="fas fa-user-plus me-2"></i>Crear Nuevo Profesor
        </h6>
        <a href="{{ route('admin.teachers.index') }}" class="btn btn-sm btn-light">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.teachers.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-medium">
                            <i class="fas fa-user text-primary me-1"></i>Nombre Completo
                        </label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                            id="name" name="name" value="{{ old('name') }}" 
                            placeholder="Ingrese el nombre completo" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label fw-medium">
                            <i class="fas fa-envelope text-primary me-1"></i>Correo Electrónico
                        </label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                            id="email" name="email" value="{{ old('email') }}" 
                            placeholder="ejemplo@unfv.edu.pe" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="password" class="form-label fw-medium">
                            <i class="fas fa-lock text-primary me-1"></i>Contraseña
                        </label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                            id="password" name="password" 
                            placeholder="Mínimo 8 caracteres" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label fw-medium">
                            <i class="fas fa-lock text-primary me-1"></i>Confirmar Contraseña
                        </label>
                        <input type="password" class="form-control" 
                            id="password_confirmation" name="password_confirmation" 
                            placeholder="Repita la contraseña" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="code" class="form-label fw-medium">
                            <i class="fas fa-id-badge text-primary me-1"></i>Código de Profesor
                        </label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" 
                            id="code" name="code" value="{{ old('code') }}" 
                            placeholder="Ej: PROF001" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="faculty_id" class="form-label fw-medium">
                            <i class="fas fa-building text-primary me-1"></i>Escuela Profesional
                        </label>
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
                </div>
            </div>

            <div class="mb-4">
                <label for="specialty" class="form-label fw-medium">
                    <i class="fas fa-graduation-cap text-primary me-1"></i>Especialidad
                </label>
                <input type="text" class="form-control @error('specialty') is-invalid @enderror" 
                    id="specialty" name="specialty" value="{{ old('specialty') }}" 
                    placeholder="Ej: Ingeniería de Informática, etc." required>
                @error('specialty')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Ingrese el área de especialización del profesor</div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Guardar Profesor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection