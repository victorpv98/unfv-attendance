@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="alert alert-primary" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ __('You are logged in!') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection