@extends('layouts.app')

@section('header')
    Editar Facultad
@endsection

@section('content')
    <div class="card shadow border-0">
        <div class="card-body p-4">
            <div class="mb-4">
                <h2 class="fs-1 fw-semibold">Editar Facultad</h2>
                <p class="text-muted">Actualice la información de la facultad</p>
            </div>

            <form action="{{ route('admin.faculties.update', $faculty) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $faculty->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="code" class="form-label">Código</label>
                    <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $faculty->code) }}" required>
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.faculties.index') }}" class="btn btn-outline-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        Actualizar Facultad
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection