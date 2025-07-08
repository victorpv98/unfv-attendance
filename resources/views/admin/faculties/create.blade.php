@extends('layouts.app')

@section('header')
    Nueva Escuela
@endsection

@section('content')
    <div class="card shadow border-0 rounded-3">
        <div class="card-header bg-primary text-white">
            <div class="d-flex align-items-center">
                <i class="fas fa-plus-circle me-2"></i>
                <h5 class="mb-0">Nueva Escuela</h5>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="mb-4">
                <h2 class="fs-2 fw-semibold text-primary">Crear Nueva Escuela</h2>
                <p class="text-muted mb-0">Complete todos los campos requeridos para registrar una nueva escuela en el sistema</p>
            </div>

            <form action="{{ route('admin.faculties.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label for="name" class="form-label fw-medium">
                                <i class="fas fa-university text-primary me-1"></i>
                                Nombre de la Escuela
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" 
                                   placeholder="Ej: Escuela de Informática"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle text-info me-1"></i>
                                Ingrese el nombre completo y oficial de la escuela
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-4">
                            <label for="code" class="form-label fw-medium">
                                <i class="fas fa-code text-primary me-1"></i>
                                Código de Escuela
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="code" 
                                   id="code" 
                                   class="form-control @error('code') is-invalid @enderror" 
                                   value="{{ old('code') }}" 
                                   placeholder="Ej: INF"
                                   style="text-transform: uppercase;"
                                   maxlength="10"
                                   required>
                            @error('code')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle text-info me-1"></i>
                                Código único de identificación (máximo 10 caracteres)
                            </div>
                        </div>
                    </div>
                </div>

    

                <div class="alert alert-info border-0 bg-info bg-opacity-10">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-info"></i>
                        </div>
                        <div class="ms-2">
                            <small class="text-info mb-0">
                                <strong>Importante:</strong> Asegúrese de que el código de escuela sea único y siga las convenciones institucionales de la UNFV.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <div class="text-muted small">
                        <i class="fas fa-asterisk text-danger me-1"></i>
                        Los campos marcados con * son obligatorios
                    </div>
                    <div>
                        <a href="{{ route('admin.faculties.index') }}" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-times me-1"></i>
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            Guardar Escuela
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection