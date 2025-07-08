@extends('layouts.app')

@section('header')
    Editar Profesor
@endsection

@section('content')
<div class="card shadow border-0 mb-4 rounded-3">
    <div class="card-header bg-primary text-white py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 fw-bold">
            <i class="fas fa-user-edit me-2"></i>Editar Profesor
        </h6>
        <a href="{{ route('admin.teachers.index') }}" class="btn btn-sm btn-light">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.teachers.update', $teacher) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Información Personal -->
                <div class="col-12">
                    <h6 class="text-secondary mb-3">
                        <i class="fas fa-user me-2"></i>Información Personal
                    </h6>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label fw-medium">Nombre Completo</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                        id="name" name="name" value="{{ old('name', $teacher->user->name) }}" 
                        placeholder="Ingrese el nombre completo" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label fw-medium">Correo Electrónico</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                        id="email" name="email" value="{{ old('email', $teacher->user->email) }}" 
                        placeholder="ejemplo@unfv.edu.pe" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Seguridad -->
                <div class="col-12 mt-3">
                    <h6 class="text-secondary mb-3">
                        <i class="fas fa-lock me-2"></i>Seguridad de la Cuenta
                    </h6>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label fw-medium">Nueva Contraseña</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                        id="password" name="password" placeholder="Dejar en blanco para mantener actual">
                    <div class="form-text">Dejar en blanco para mantener la contraseña actual</div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="password_confirmation" class="form-label fw-medium">Confirmar Contraseña</label>
                    <input type="password" class="form-control" 
                        id="password_confirmation" name="password_confirmation" 
                        placeholder="Confirme la nueva contraseña">
                </div>

                <!-- Información Académica -->
                <div class="col-12 mt-3">
                    <h6 class="text-secondary mb-3">
                        <i class="fas fa-graduation-cap me-2"></i>Información Académica
                    </h6>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="code" class="form-label fw-medium">Código de Profesor</label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror" 
                        id="code" name="code" value="{{ old('code', $teacher->code) }}" 
                        placeholder="Ej: DOC001" required>
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="faculty_id" class="form-label fw-medium">Facultad/Escuela</label>
                    <select class="form-select @error('faculty_id') is-invalid @enderror" 
                        id="faculty_id" name="faculty_id" required>
                        <option value="">Seleccione una facultad</option>
                        @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}" {{ old('faculty_id', $teacher->faculty_id) == $faculty->id ? 'selected' : '' }}>
                                {{ $faculty->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('faculty_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <label for="specialty" class="form-label fw-medium">Especialidad</label>
                    <input type="text" class="form-control @error('specialty') is-invalid @enderror" 
                        id="specialty" name="specialty" value="{{ old('specialty', $teacher->specialty) }}" 
                        placeholder="Ej: Ingeniería de Informática, etc." required>
                    @error('specialty')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Actualizar Profesor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection