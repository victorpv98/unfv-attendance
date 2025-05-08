@extends('layouts.app')

@section('header')
    Editar Profesor
@endsection

@section('content')
<div class="card shadow border-0 mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 fw-bold text-primary">Editar Profesor</h6>
        <a href="{{ route('admin.teachers.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.teachers.update', $teacher) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                    id="name" name="name" value="{{ old('name', $teacher->user->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                    id="email" name="email" value="{{ old('email', $teacher->user->email) }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña (dejar en blanco para mantener la actual)</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                    id="password" name="password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                <input type="password" class="form-control" 
                    id="password_confirmation" name="password_confirmation">
            </div>

            <div class="mb-3">
                <label for="code" class="form-label">Código de Profesor</label>
                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                    id="code" name="code" value="{{ old('code', $teacher->code) }}" required>
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
                        <option value="{{ $faculty->id }}" {{ old('faculty_id', $teacher->faculty_id) == $faculty->id ? 'selected' : '' }}>
                            {{ $faculty->name }}
                        </option>
                    @endforeach
                </select>
                @error('faculty_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="specialty" class="form-label">Especialidad</label>
                <input type="text" class="form-control @error('specialty') is-invalid @enderror" 
                    id="specialty" name="specialty" value="{{ old('specialty', $teacher->specialty) }}" required>
                @error('specialty')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar Profesor</button>
            </div>
        </form>
    </div>
</div>
@endsection