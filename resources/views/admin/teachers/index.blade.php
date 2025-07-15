@extends('layouts.app')

@section('header')
    Profesores
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fs-1 fw-semibold text-primary">Gestión de Profesores</h2>
    <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Nuevo Profesor
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex">
            <div class="flex-shrink-0">
                <svg width="20" height="20" class="text-success" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ms-3 flex-grow-1">
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex">
            <div class="flex-shrink-0">
                <svg width="20" height="20" class="text-danger" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ms-3 flex-grow-1">
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

<div class="card shadow border-0 rounded-3">
    <div class="card-header bg-light border-bottom-0">
        <h5 class="card-title mb-0 text-secondary">
            <i class="fas fa-chalkboard-teacher me-2"></i>Listado de Profesores
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-primary bg-opacity-10">
                    <tr>
                        <th scope="col" class="fw-semibold text-primary small py-3">ID</th>
                        <th scope="col" class="fw-semibold text-primary small py-3">Nombre</th>
                        <th scope="col" class="fw-semibold text-primary small py-3">Email</th>
                        <th scope="col" class="fw-semibold text-primary small py-3">Código</th>
                        <th scope="col" class="fw-semibold text-primary small py-3">Escuela</th>
                        <th scope="col" class="fw-semibold text-primary small py-3">Especialidad</th>
                        <th scope="col" class="fw-semibold text-primary small py-3 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                        <tr class="border-bottom">
                            <td class="py-3">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $loop->iteration }}</span>
                            </td>
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                                        <i class="fas fa-user text-white small"></i>
                                    </div>
                                    <strong class="text-dark">{{ $teacher->user->name }}</strong>
                                </div>
                            </td>
                            <td class="py-3">
                                <a href="mailto:{{ $teacher->user->email }}" class="text-muted text-decoration-none">
                                    <i class="fas fa-envelope me-1"></i>{{ $teacher->user->email }}
                                </a>
                            </td>
                            <td class="py-3">
                                <code class="bg-light px-2 py-1 rounded">{{ $teacher->code }}</code>
                            </td>
                            <td class="py-3">
                                <span class="badge bg-info bg-opacity-10 text-info">{{ $teacher->faculty->name }}</span>
                            </td>
                            <td class="py-3">
                                <span class="text-muted">{{ $teacher->specialty }}</span>
                            </td>
                            <td class="text-end py-3">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.teachers.edit', $teacher) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Editar profesor">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.teachers.destroy', $teacher) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger" 
                                                title="Eliminar profesor"
                                                onclick="return confirm('¿Estás seguro de eliminar este profesor?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-chalkboard-teacher fa-3x mb-3 text-muted opacity-50"></i>
                                    <h5 class="text-muted">No hay profesores registrados</h5>
                                    <p class="mb-0">Comienza agregando el primer profesor</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($teachers) && $teachers->hasPages())
            <div class="card-footer bg-light border-top">
                {{ $teachers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection