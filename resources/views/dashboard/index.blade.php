@extends('layouts.app')

@section('header')
    Dashboard
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-2xl font-semibold mb-4">Bienvenido, {{ Auth::user()->name }}</h2>
        <p class="text-gray-600">{{ now()->format('l, d F Y') }}</p>
    </div>
    
    @if(Auth::user()->role === 'admin')
        @include('admin.dashboard-stats')
    @elseif(Auth::user()->role === 'teacher')
        @include('teachers.dashboard-stats')
    @elseif(Auth::user()->role === 'student')
        @include('students.dashboard-stats')
    @endif
@endsection