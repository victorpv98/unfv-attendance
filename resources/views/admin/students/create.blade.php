@extends('layouts.app')

@section('header')
    Nuevo Estudiante
@endsection

@section('content')
<div class="card shadow border-0 mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 fw-bold text-primary">Crear Nuevo Estudiante</h6>
        <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.students.store') }}" method="POST">
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
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                    id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                    id="password" name="password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                <input type="password" class="form-control" 
                    id="password_confirmation" name="password_confirmation" required>
            </div>

            <div class="mb-3">
                <label for="code" class="form-label">Código de Estudiante</label>
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
                <label for="cycle" class="form-label">Ciclo</label>
                <input type="number" class="form-control @error('cycle') is-invalid @enderror" 
                    id="cycle" name="cycle" value="{{ old('cycle') }}" required min="1" max="10">
                @error('cycle')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar Estudiante</button>
            </div>
        </form>
    </div>
</div>
@endsection