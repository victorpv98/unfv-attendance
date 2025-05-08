@extends('layouts.app')

@section('header')
    Nueva Facultad
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6">
            <h2 class="text-2xl font-semibold">Crear Nueva Facultad</h2>
            <p class="text-gray-600">Complete la información para registrar una nueva facultad</p>
        </div>

        <form action="{{ route('admin.faculties.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                <input type="text" name="name" id="name" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ old('name') }}" required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Código</label>
                <input type="text" name="code" id="code" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ old('code') }}" required>
                @error('code')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end mt-6">
                <a href="{{ route('admin.faculties.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">Cancelar</a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Guardar Facultad
                </button>
            </div>
        </form>
    </div>
@endsection