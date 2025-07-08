@extends('layouts.app')

@section('header')
    Nuevo Estudiante
@endsection

@section('content')
<div class="card shadow border-0 mb-4 rounded-3">
    <div class="card-header bg-light py-3 d-flex flex-row align-items-center justify-content-between border-bottom">
        <h5 class="m-0 fw-semibold text-primary">
            <i class="fas fa-user-plus me-2"></i>Crear Nuevo Estudiante
        </h5>
        <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.students.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-medium">
                            <i class="fas fa-user me-1 text-primary"></i>Nombre Completo
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
                        <label for="code" class="form-label fw-medium">
                            <i class="fas fa-id-badge me-1 text-primary"></i>Código de Estudiante
                        </label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" 
                            id="code" name="code" value="{{ old('code') }}" 
                            placeholder="Ej: 2024001234" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-medium">
                    <i class="fas fa-envelope me-1 text-primary"></i>Correo Electrónico
                </label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                    id="email" name="email" value="{{ old('email') }}" 
                    placeholder="estudiante@unfv.edu.pe" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="password" class="form-label fw-medium">
                            <i class="fas fa-lock me-1 text-primary"></i>Contraseña
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
                            <i class="fas fa-lock me-1 text-primary"></i>Confirmar Contraseña
                        </label>
                        <input type="password" class="form-control" 
                            id="password_confirmation" name="password_confirmation" 
                            placeholder="Confirme la contraseña" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="faculty_id" class="form-label fw-medium">
                            <i class="fas fa-building me-1 text-primary"></i>Escuela Profesional
                        </label>
                        <select class="form-select @error('faculty_id') is-invalid @enderror" 
                            id="faculty_id" name="faculty_id" required>
                            <option value="">Seleccione una escuela profesional</option>
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

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="cycle" class="form-label fw-medium">
                            <i class="fas fa-layer-group me-1 text-primary"></i>Ciclo Académico
                        </label>
                        <select class="form-select @error('cycle') is-invalid @enderror" 
                            id="cycle" name="cycle" required>
                            <option value="">Seleccione</option>
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}" {{ old('cycle') == $i ? 'selected' : '' }}>
                                    {{ $i }}° Ciclo
                                </option>
                            @endfor
                        </select>
                        @error('cycle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Guardar Estudiante
                </button>
            </div>
        </form>
    </div>
</div>
@endsection