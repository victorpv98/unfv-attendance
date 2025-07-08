@extends('layouts.app')

@section('header')
    Editar Escuela
@endsection

@section('content')
    <div class="card shadow border-0 rounded-3">
        <div class="card-header bg-primary text-white">
            <div class="d-flex align-items-center">
                <i class="fas fa-university me-2"></i>
                <h5 class="mb-0">Editar Escuela</h5>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="mb-4">
                <h2 class="fs-2 fw-semibold text-primary">Actualizar Información</h2>
                <p class="text-muted mb-0">Modifique los datos de la escuela según sea necesario</p>
            </div>

            <form action="{{ route('admin.faculties.update', $faculty) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label for="name" class="form-label fw-medium">
                                <i class="fas fa-university text-primary me-1"></i>
                                Nombre de la Escuela
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $faculty->name) }}" 
                                   placeholder="Ingrese el nombre de la escuela"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-4">
                            <label for="code" class="form-label fw-medium">
                                <i class="fas fa-code text-primary me-1"></i>
                                Código de Escuela
                            </label>
                            <input type="text" 
                                   name="code" 
                                   id="code" 
                                   class="form-control @error('code') is-invalid @enderror" 
                                   value="{{ old('code', $faculty->code) }}" 
                                   placeholder="Ej: INF, ELEC, etc."
                                   style="text-transform: uppercase;"
                                   required>
                            @error('code')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle text-info me-1"></i>
                                Código único para identificar la escuela
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-light rounded-2 p-3 mb-4">
                    <h6 class="text-secondary mb-2">
                        <i class="fas fa-info-circle me-1"></i>
                        Información Actual
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">Nombre actual:</small>
                            <div class="fw-medium">{{ $faculty->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Código actual:</small>
                            <div class="fw-medium">{{ $faculty->code }}</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <div class="text-muted small">
                        <i class="fas fa-clock me-1"></i>
                        Última actualización: {{ $faculty->updated_at->format('d/m/Y H:i') }}
                    </div>
                    <div>
                        <a href="{{ route('admin.faculties.index') }}" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-times me-1"></i>
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            Actualizar Escuela
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection