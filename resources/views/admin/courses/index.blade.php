@extends('layouts.app')

@section('header')
    Cursos
@endsection

@section('content')
    <!-- Header con botón de acción -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-1 fw-semibold text-primary">Gestión de Cursos</h2>
        <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Nuevo Curso
        </a>
    </div>

    <!-- Alertas de estado -->
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

    <!-- Tabla de cursos -->
    <div class="card shadow border-0 rounded-3">
        <div class="card-header bg-light border-bottom-0">
            <h5 class="card-title mb-0 text-secondary">
                <i class="fas fa-book me-2"></i>Listado de Cursos
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-primary bg-opacity-10">
                        <tr>
                            <th scope="col" class="fw-semibold text-primary small py-3">ID</th>
                            <th scope="col" class="fw-semibold text-primary small py-3">Nombre</th>
                            <th scope="col" class="fw-semibold text-primary small py-3">Código</th>
                            <th scope="col" class="fw-semibold text-primary small py-3">Escuela</th>
                            <th scope="col" class="fw-semibold text-primary small py-3">Créditos</th>
                            <th scope="col" class="fw-semibold text-primary small py-3">Ciclo</th>
                            <th scope="col" class="fw-semibold text-primary small py-3 text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                            <tr class="border-bottom">
                                <td class="py-3">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $loop->iteration }}</span>
                                </td>
                                <td class="fw-medium py-3">{{ $course->name }}</td>
                                <td class="text-muted py-3">
                                    <code class="bg-light px-2 py-1 rounded">{{ $course->code }}</code>
                                </td>
                                <td class="text-muted py-3">{{ $course->faculty->name }}</td>
                                <td class="py-3">
                                    <span class="badge bg-info">{{ $course->credits }} créditos</span>
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-warning text-dark">Ciclo {{ $course->cycle }}</span>
                                </td>
                                <td class="text-end py-3">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.courses.edit', $course) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Editar curso">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.courses.destroy', $course) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Eliminar curso"
                                                    onclick="return confirm('¿Estás seguro de eliminar este curso?')">
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
                                        <i class="fas fa-book fa-3x mb-3 text-muted opacity-50"></i>
                                        <h5 class="text-muted">No hay cursos registrados</h5>
                                        <p class="mb-0">Comienza agregando tu primer curso</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(isset($courses) && $courses->hasPages())
                <div class="card-footer bg-light border-top">
                    {{ $courses->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection