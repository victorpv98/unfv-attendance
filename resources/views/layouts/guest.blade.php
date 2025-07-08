<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Variables UNFV -->
        <style>
            :root {
                --unfv-primary: #ff6600;
                --unfv-secondary: #2c3e50;
                --unfv-dark: #1a1a1a;
                --unfv-success: #28a745;
                --unfv-warning: #ffc107;
                --unfv-danger: #dc3545;
                --unfv-info: #17a2b8;
                --unfv-light: #f8f9fa;
                --unfv-white: #ffffff;
                
                --bs-primary: var(--unfv-primary);
                --bs-primary-rgb: 255, 102, 0;
                --bs-secondary: var(--unfv-secondary);
                --bs-secondary-rgb: 44, 62, 80;
                --bs-success: var(--unfv-success);
                --bs-warning: var(--unfv-warning);
                --bs-danger: var(--unfv-danger);
                --bs-info: var(--unfv-info);
                --bs-light: var(--unfv-light);
                --bs-dark: var(--unfv-dark);
            }
            
            body {
                font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif;
                background-color: #f8f9fa;
            }
            
            .btn-primary:focus {
                box-shadow: 0 0 0 0.2rem rgba(255, 102, 0, 0.25);
            }
            
            .form-control:focus {
                border-color: var(--unfv-primary);
                box-shadow: 0 0 0 0.2rem rgba(255, 102, 0, 0.25);
            }
            
            .form-check-input:checked {
                background-color: var(--unfv-primary);
                border-color: var(--unfv-primary);
            }
            
            .form-check-input:focus {
                box-shadow: 0 0 0 0.2rem rgba(255, 102, 0, 0.25);
            }
            
            .text-primary {
                color: var(--unfv-primary) !important;
            }
            
            .bg-primary {
                background-color: var(--unfv-primary) !important;
            }
            
            .border-primary {
                border-color: var(--unfv-primary) !important;
            }
            
            .btn-primary {
                background-color: var(--unfv-primary);
                border-color: var(--unfv-primary);
            }
            
            .btn-primary:hover {
                background-color: #e55a00;
                border-color: #e55a00;
            }
            
            .btn-outline-primary {
                color: var(--unfv-primary);
                border-color: var(--unfv-primary);
            }
            
            .btn-outline-primary:hover {
                background-color: var(--unfv-primary);
                border-color: var(--unfv-primary);
            }
            
            .input-group-text {
                border-color: #ced4da;
            }
            
            .rounded-4 {
                border-radius: 1rem !important;
            }
            
            @media (max-width: 767.98px) {
                .container-fluid {
                    padding: 0;
                }
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        {{ $slot }}
        
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        
        @stack('scripts')
    </body>
</html>