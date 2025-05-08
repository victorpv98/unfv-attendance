@extends('layouts.app')

@section('header')
    Profesores
@endsection

@section('content')
<div class="container">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestión de Profesores</h1>
        <a href="{{ route('teachers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus fa-sm"></i> Nuevo Profesor
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lista de Profesores</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Código</th>
                            <th>Facultad</th>
                            <th>Especialidad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $teacher)
                            <tr>
                                <td>{{ $teacher->id }}</td>
                                <td>{{ $teacher->user->name }}</td>
                                <td>{{ $teacher->user->email }}</td>
                                <td>{{ $teacher->code }}</td>
                                <td>{{ $teacher->faculty->name }}</td>
                                <td>{{ $teacher->specialty }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('teachers.show', $teacher) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este profesor?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No hay profesores registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{ $teachers->links() }}
        </div>
    </div>
</div>
@endsection