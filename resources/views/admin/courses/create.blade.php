@extends('layouts.app')

@section('header')
    Nuevo Curso
@endsection

@section('content')
<div class="card shadow border-0 mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 fw-bold text-primary">Crear Nuevo Curso</h6>
        <a href="{{ route('admin.courses.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.courses.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                    id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="code" class="form-label">Código</label>
                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                    id="code" name="code" value="{{ old('code') }}" required>
                @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="faculty_id" class="form-label">Facultad</label>
                <select class="form-select @error('faculty_id') is-invalid @enderror" 
                    id="faculty_id" name="faculty_id" required>
                    <option value="">Seleccione una facultad</option>
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

            <div class="mb-3">
                <label for="credits" class="form-label">Créditos</label>
                <input type="number" class="form-control @error('credits') is-invalid @enderror" 
                    id="credits" name="credits" value="{{ old('credits') }}" required min="1" max="10">
                @error('credits')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="cycle" class="form-label">Ciclo</label>
                <input type="number" class="form-control @error('cycle') is-invalid @enderror" 
                    id="cycle" name="cycle" value="{{ old('cycle') }}" required min="1" max="10">
                @error('cycle')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar Curso</button>
            </div>
        </form>
    </div>
</div>
@endsection