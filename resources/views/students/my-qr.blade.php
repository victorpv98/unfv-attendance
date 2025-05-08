@extends('layouts.app')

@section('header')
    Mi Código QR
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6">
            <h2 class="text-2xl font-semibold mb-2">Mi Código QR de Asistencia</h2>
            <p class="text-gray-600">Este código QR te identifica para registrar tu asistencia a clases</p>
        </div>

        <div class="flex flex-col md:flex-row items-center md:items-start">
            <div class="w-full md:w-1/3 flex flex-col items-center mb-6 md:mb-0">
                @if($student->qr_code)
                    <div class="mb-4 p-4 bg-white border rounded-lg shadow-md">
                        <img src="{{ route('students.qr-image', $student) }}" alt="Mi Código QR" class="w-full max-w-xs">
                    </div>
                    
                    <p class="text-sm text-gray-500 mb-4 text-center">Código: {{ $student->qr_code }}</p>
                    
                    <form action="{{ route('students.regenerate-qr') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                            Regenerar Código QR
                        </button>
                    </form>
                @else
                    <div class="bg-yellow-100 p-4 rounded-lg mb-4 text-center">
                        <p class="text-yellow-800 mb-4">No tienes un código QR asignado.</p>
                        
                        <form action="{{ route('students.regenerate-qr') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                Generar Código QR
                            </button>
                        </form>
                    </div>
                @endif
            </div>
            
            <div class="w-full md:w-2/3 md:pl-8">
                <div class="bg-blue-50 p-6 rounded-lg mb-6">
                    <h3 class="text-lg font-semibold text-blue-800 mb-4">Instrucciones de Uso</h3>
                    <ul class="space-y-2 text-blue-800">
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-blue-600 mr-2 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Muestra este código QR al profesor al inicio de cada clase.</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-blue-600 mr-2 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>El profesor escaneará tu código con su dispositivo móvil.</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-blue-600 mr-2 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Tu asistencia quedará registrada automáticamente.</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-blue-600 mr-2 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Puedes ver el historial de tus asistencias en la sección "Mis Asistencias".</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-yellow-600 mr-2 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-yellow-800">Si llegas después de 15 minutos de iniciada la clase, se registrará como tardanza.</span>
                        </li>
                    </ul>
                </div>
                
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4">Información Personal</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Nombre</p>
                            <p class="font-medium">{{ $student->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Código de Estudiante</p>
                            <p class="font-medium">{{ $student->code }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Facultad</p>