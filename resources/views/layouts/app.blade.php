<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistema de Asistencia UNFV') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        .dropdown-menu.show {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Estilos adicionales -->
    @stack('styles')
</head>
<body class="bg-light">
    <div class="d-flex vh-100">
        <!-- Sidebar -->
        @include('components.sidebar')
        
        <!-- Content area -->
        <div class="d-flex flex-column flex-grow-1 overflow-hidden">
            <!-- Topbar -->
            <header class="bg-white shadow-sm">
                <div class="container-fluid px-4 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="fw-semibold fs-4 text-secondary mb-0">
                            @yield('header')
                        </h2>
                        <div class="d-flex align-items-center">
                            <!-- User dropdown - Implementación simplificada -->
                            <div class="dropdown">
                                <button class="btn btn-light dropdown-toggle" 
                                        type="button" 
                                        id="user-menu-button" 
                                        data-bs-toggle="dropdown" 
                                        aria-expanded="false">
                                    <span class="me-2">{{ Auth::user()->name ?? 'Usuario' }}</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="user-menu-button">
                                    <li><a class="dropdown-item" href="#">Mi Perfil</a></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Cerrar Sesión</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-grow-1 overflow-auto bg-light p-4">
                <!-- Alertas -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- jQuery (opcional pero útil para solucionar problemas de compatibilidad) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS Bundle con Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script para la implementación manual del dropdown -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userMenuButton = document.getElementById('user-menu-button');
            
            if (userMenuButton) {
                const dropdownMenu = userMenuButton.nextElementSibling;
                
                if (typeof bootstrap !== 'undefined') {
                    try {
                        new bootstrap.Dropdown(userMenuButton);
                    } catch (e) {
                        console.error('Error al inicializar dropdown con Bootstrap:', e);
                    }
                }
                
                userMenuButton.addEventListener('click', function(e) {
                    if (!dropdownMenu.classList.contains('show')) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        document.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
                            if (menu !== dropdownMenu) {
                                menu.classList.remove('show');
                            }
                        });
                        
                        dropdownMenu.classList.toggle('show');
                    }
                });
                
                document.addEventListener('click', function(e) {
                    if (!userMenuButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        dropdownMenu.classList.remove('show');
                    }
                });
            }
            
            console.log('Scripts cargados. Bootstrap disponible:', typeof bootstrap !== 'undefined');
        });
    </script>
    
    <!-- Scripts adicionales -->
    @stack('scripts')
</body>
</html>