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
                                <h2 class="fw-bold text-primary mb-1">Sistema UNFV</h2>
                            </div>

                            <!-- Session Status -->
                            <x-auth-session-status class="mb-4" :status="session('status')" />

                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <!-- Email Address -->
                                <div class="mb-3">
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
                                                    autocomplete="username"
                                                    placeholder="usuario@unfv.edu.pe" />
                                    </div>
                                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                                </div>

                                <!-- Password -->
                                <div class="mb-3">
                                    <x-input-label for="password" :value="__('Contraseña')" class="form-label fw-medium" />
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-lock text-muted"></i>
                                        </span>
                                        <x-text-input id="password" 
                                                    class="form-control"
                                                    type="password"
                                                    name="password"
                                                    required 
                                                    autocomplete="current-password"
                                                    placeholder="Ingrese su contraseña" />
                                    </div>
                                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                                </div>

                                <!-- Remember Me -->
                                <div class="form-check mb-4">
                                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                                    <label for="remember_me" class="form-check-label">
                                        {{ __('Recordar sesión') }}
                                    </label>
                                </div>

                                <!-- Login Button -->
                                <div class="d-grid mb-3">
                                    <x-primary-button class="btn btn-primary btn-lg">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        {{ __('Iniciar Sesión') }}
                                    </x-primary-button>
                                </div>

                                <!-- Forgot Password Link -->
                                @if (Route::has('password.request'))
                                    <div class="text-center">
                                        <a class="text-decoration-none small text-primary" href="{{ route('password.request') }}">
                                            <i class="fas fa-key me-1"></i>
                                            {{ __('¿Olvidaste tu contraseña?') }}
                                        </a>
                                    </div>
                                @endif
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