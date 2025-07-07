@extends('layouts.app')

@section('header')
    Dashboard
@endsection

@section('content')
    <div class="card shadow border-0 mb-4" style="border-left: 4px solid var(--bs-primary) !important;">
        <div class="card-body p-4">
            <h2 class="fs-1 fw-semibold mb-3 text-primary">Bienvenido, {{ Auth::user()->name }}</h2>
            <p class="text-muted">
                {{ \Carbon\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
            </p>
        </div>
    </div>
    
    @if(Auth::user()->role === 'admin')
        @include('admin.dashboard-stats')
    @elseif(Auth::user()->role === 'teacher')
        @include('teachers.dashboard-stats')
    @elseif(Auth::user()->role === 'student')
        @include('students.dashboard-stats')
    @endif
@endsection