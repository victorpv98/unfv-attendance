@extends('layouts.app')

@section('header')
    Editar Estudiante
@endsection

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.students.index') }}" class="text-decoration-none">
                    <i class="fas fa-user-graduate me-1"></i>Estudiantes
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Editar Estudiante</li>
        </ol>
    </nav>

    <div class="card shadow border-0 rounded-3 mb-4">
        <div class="card-header bg-primary text-white py-3 d-flex flex-row align-items-center justify-content-between">
            <h5 class="m-0 fw-semibold">
                <i class="fas fa-edit me-2"></i>Editar Estudiante
            </h5>
            <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-light text-primary">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.students.update', $student) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Información Personal -->
                    <div class="col-lg-6">
                        <h6 class="text-secondary mb-3">
                            <i class="fas fa-user me-2"></i>Información Personal
                        </h6>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label fw-medium">
                                <i class="fas fa-user me-1 text-muted"></i>Nombre Completo
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name', $student->user->name) }}" 
                                placeholder="Ingrese el nombre completo" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-medium">
                                <i class="fas fa-envelope me-1 text-muted"></i>Correo Electrónico
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                id="email" name="email" value="{{ old('email', $student->user->email) }}" 
                                placeholder="estudiante@unfv.edu.pe" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-medium">
                                <i class="fas fa-lock me-1 text-muted"></i>Nueva Contraseña
                            </label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                id="password" name="password" placeholder="Dejar en blanco para mantener actual">
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Dejar en blanco si no desea cambiar la contraseña
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label fw-medium">
                                <i class="fas fa-lock me-1 text-muted"></i>Confirmar Contraseña
                            </label>
                            <input type="password" class="form-control" 
                                id="password_confirmation" name="password_confirmation" 
                                placeholder="Confirme la nueva contraseña">
                        </div>
                    </div>

                    <!-- Información Académica -->
                    <div class="col-lg-6">
                        <h6 class="text-secondary mb-3">
                            <i class="fas fa-graduation-cap me-2"></i>Información Académica
                        </h6>

                        <div class="mb-3">
                            <label for="code" class="form-label fw-medium">
                                <i class="fas fa-id-card me-1 text-muted"></i>Código de Estudiante
                            </label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                id="code" name="code" value="{{ old('code', $student->code) }}" 
                                placeholder="202312345" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="faculty_id" class="form-label fw-medium">
                                <i class="fas fa-building me-1 text-muted"></i>Escuela Profesional
                            </label>
                            <select class="form-select @error('faculty_id') is-invalid @enderror" 
                                id="faculty_id" name="faculty_id" required>
                                <option value="">Seleccione una escuela profesional</option>
                                @foreach($faculties as $faculty)
                                    <option value="{{ $faculty->id }}" {{ old('faculty_id', $student->faculty_id) == $faculty->id ? 'selected' : '' }}>
                                        {{ $faculty->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('faculty_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="cycle" class="form-label fw-medium">
                                <i class="fas fa-layer-group me-1 text-muted"></i>Ciclo Académico
                            </label>
                            <select class="form-select @error('cycle') is-invalid @enderror" 
                                id="cycle" name="cycle" required>
                                <option value="">Seleccione el ciclo</option>
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ old('cycle', $student->cycle) == $i ? 'selected' : '' }}>
                                        Ciclo {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            @error('cycle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Información adicional -->
                        <div class="alert alert-primary" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Información:</strong> Los cambios se aplicarán inmediatamente después de guardar.
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        <i class="fas fa-clock me-1"></i>
                        Última actualización: {{ $student->updated_at->format('d/m/Y H:i') }}
                    </div>
                    <div>
                        <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Actualizar Estudiante
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection