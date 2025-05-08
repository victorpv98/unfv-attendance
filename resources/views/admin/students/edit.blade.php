@extends('layouts.app')

@section('header')
    Editar Estudiante
@endsection

@section('content')
<div class="container">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Editar Estudiante</h6>
            <a href="{{ route('students.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('students.update', $student) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                        id="name" name="name" value="{{ old('name', $student->user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                        id="email" name="email" value="{{ old('email', $student->user->email) }}" required>
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
                    <label for="code" class="form-label">Código de Estudiante</label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror" 
                        id="code" name="code" value="{{ old('code', $student->code) }}" required>
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
                    <label for="cycle" class="form-label">Ciclo</label>
                    <input type="number" class="form-control @error('cycle') is-invalid @enderror" 
                        id="cycle" name="cycle" value="{{ old('cycle', $student->cycle) }}" required min="1" max="10">
                    @error('cycle')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('students.index') }}" class="btn btn-secondary me-md-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Actualizar Estudiante</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection