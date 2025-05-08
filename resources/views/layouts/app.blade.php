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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Estilos adicionales -->
    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        @include('components.sidebar')
        
        <!-- Content area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Topbar (Opcional) -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        @yield('header')
                    </h2>
                    <div class="flex items-center">
                        <!-- User dropdown -->
                        <div class="ml-3 relative">
                            <div>
                                <button type="button" class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="mr-2">{{ Auth::user()->name ?? 'Usuario' }}</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            <div class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1" id="user-menu">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Mi Perfil</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Cerrar Sesión</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <!-- Alertas -->
                @if (session('success'))
                    @include('components.alert', ['type' => 'success', 'message' => session('success')])
                @endif

                @if (session('error'))
                    @include('components.alert', ['type' => 'error', 'message' => session('error')])
                @endif

                @if (session('warning'))
                    @include('components.alert', ['type' => 'warning', 'message' => session('warning')])
                @endif

                @if (session('info'))
                    @include('components.alert', ['type' => 'info', 'message' => session('info')])
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Scripts adicionales -->
    <script>
        // Toggle user dropdown
        document.getElementById('user-menu-button').addEventListener('click', function() {
            document.getElementById('user-menu').classList.toggle('hidden');
        });
        
        // Close dropdown when clicking outside
        window.addEventListener('click', function(e) {
            if (!document.getElementById('user-menu-button').contains(e.target)) {
                document.getElementById('user-menu').classList.add('hidden');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>