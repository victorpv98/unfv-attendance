<x-guest-layout>
    <div class="min-vh-100 d-flex align-items-center justify-content-center py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-lg border-0 rounded-3">
                        <div class="card-body p-4 p-lg-5">
                            <!-- Logo UNFV -->
                            <div class="text-center mb-4">
                                <img src="{{ asset('images/logo_unfv.png') }}" alt="Logo UNFV" class="img-fluid mb-3" style="max-height: 80px;">
                                <h2 class="fw-bold text-primary mb-1">Recuperar Contraseña</h2>
                                <p class="text-muted small">Sistema UNFV - FIEI</p>
                            </div>

                            <!-- Descripción -->
                            <div class="alert alert-info border-0 mb-4">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-info-circle text-info me-2 mt-1 flex-shrink-0"></i>
                                    <div class="small">
                                        {{ __('¿Olvidaste tu contraseña? No te preocupes. Solo proporciona tu dirección de correo electrónico y te enviaremos un enlace para restablecer tu contraseña.') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Session Status -->
                            <x-auth-session-status class="mb-4" :status="session('status')" />

                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf

                                <!-- Email Address -->
                                <div class="mb-4">
                                    <x-input-label for="email" :value="__('Correo Electrónico')" class="form-label fw-medium" />
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-envelope text-muted"></i>
                                        </span>
                                        <x-text-input id="email" 
                                                    class="form-control" 
                                                    type="email" 
                                                    name="email" 
                                                    :value="old('email')" 
                                                    required 
                                                    autofocus
                                                    placeholder="usuario@unfv.edu.pe" />
                                    </div>
                                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                                </div>

                                <!-- Reset Button -->
                                <div class="d-grid mb-3">
                                    <x-primary-button class="btn btn-primary btn-lg">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        {{ __('Enviar Enlace de Recuperación') }}
                                    </x-primary-button>
                                </div>

                                <!-- Back to Login -->
                                <div class="text-center">
                                    <a href="{{ route('login') }}" class="text-decoration-none small text-primary">
                                        <i class="fas fa-arrow-left me-1"></i>
                                        {{ __('Volver al inicio de sesión') }}
                                    </a>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Footer del Card -->
                        <div class="card-footer bg-light text-center py-3 border-0">
                            <small class="text-muted">
                                © {{ date('Y') }} Universidad Nacional Federico Villarreal
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>