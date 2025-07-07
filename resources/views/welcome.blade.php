<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                /* Variables UNFV */
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
                    font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
                }
                
                .bg-unfv-light {
                    background-color: #FDFDFC;
                }
                
                .bg-unfv-dark {
                    background-color: #0a0a0a;
                }
                
                .text-unfv-dark {
                    color: #1b1b18;
                }
                
                .border-unfv-light {
                    border-color: #19140035 !important;
                }
                
                .border-unfv-hover:hover {
                    border-color: #1915014a !important;
                }
                
                .text-unfv-red {
                    color: #f53003;
                }
                
                .text-unfv-gray {
                    color: #706f6c;
                }
                
                @media (prefers-color-scheme: dark) {
                    .dark\:bg-unfv-dark {
                        background-color: #0a0a0a;
                    }
                    
                    .dark\:bg-unfv-card {
                        background-color: #161615;
                    }
                    
                    .dark\:text-unfv-light {
                        color: #EDEDEC;
                    }
                    
                    .dark\:text-unfv-gray {
                        color: #A1A09A;
                    }
                    
                    .dark\:text-unfv-red {
                        color: #FF4433;
                    }
                    
                    .dark\:border-unfv-dark {
                        border-color: #3E3E3A !important;
                    }
                    
                    .dark\:border-unfv-hover:hover {
                        border-color: #62605b !important;
                    }
                    
                    .dark\:bg-unfv-red {
                        background-color: #1D0002;
                    }
                }
                
                .custom-shadow {
                    box-shadow: inset 0px 0px 0px 1px rgba(26,26,0,0.16);
                }
                
                .dark\:custom-shadow-dark {
                    box-shadow: inset 0px 0px 0px 1px #fffaed2d;
                }
                
                .transition-all-custom {
                    transition: all 0.75s cubic-bezier(0.4, 0, 0.2, 1);
                }
                
                .starting-opacity {
                    opacity: 0;
                    transform: translateY(1rem);
                }
                
                .animate-in {
                    opacity: 1;
                    transform: translateY(0);
                }
            </style>
        @endif
    </head>
    <body class="bg-unfv-light dark:bg-unfv-dark text-unfv-dark d-flex p-3 p-lg-4 align-items-center justify-content-lg-center min-vh-100 flex-column">
        <header class="w-100" style="max-width: 56rem;">
            @if (Route::has('login'))
                <nav class="d-flex align-items-center justify-content-end gap-3 mb-4 small">
                    @auth
                        <a href="{{ url('/dashboard') }}" 
                           class="d-inline-block px-3 py-2 dark:text-unfv-light border border-unfv-light border-unfv-hover text-unfv-dark dark:border-unfv-dark dark:border-unfv-hover rounded-1 text-decoration-none">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="d-inline-block px-3 py-2 dark:text-unfv-light text-unfv-dark border border-transparent hover:border-unfv-light dark:hover:border-unfv-dark rounded-1 text-decoration-none">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" 
                               class="d-inline-block px-3 py-2 dark:text-unfv-light border border-unfv-light border-unfv-hover text-unfv-dark dark:border-unfv-dark dark:border-unfv-hover rounded-1 text-decoration-none">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>
        
        <div class="d-flex align-items-center justify-content-center w-100 transition-all-custom opacity-100 flex-grow-1">
            <main class="d-flex w-100 flex-column-reverse flex-lg-row" style="max-width: 56rem;">
                <div class="flex-fill p-3 p-lg-5 bg-white dark:bg-unfv-card dark:text-unfv-light custom-shadow dark:custom-shadow-dark rounded-bottom rounded-lg-start rounded-lg-bottom-0" style="font-size: 13px; line-height: 20px;">
                    <h1 class="mb-2 fw-medium">Let's get started</h1>
                    <p class="mb-3 text-unfv-gray dark:text-unfv-gray">Laravel has an incredibly rich ecosystem. <br>We suggest starting with the following.</p>
                    
                    <ul class="list-unstyled d-flex flex-column mb-3 mb-lg-4">
                        <li class="d-flex align-items-center gap-3 py-2 position-relative">
                            <div class="position-absolute start-0" style="left: 0.4rem; top: 50%; bottom: 0; border-left: 1px solid #e3e3e0;"></div>
                            <span class="position-relative py-1 bg-white dark:bg-unfv-card">
                                <span class="d-flex align-items-center justify-content-center rounded-circle bg-white dark:bg-unfv-card shadow-sm border border-light-subtle" style="width: 14px; height: 14px;">
                                    <span class="rounded-circle" style="width: 6px; height: 6px; background-color: #dbdbd7;"></span>
                                </span>
                            </span>
                            <span>
                                Read the
                                <a href="https://laravel.com/docs" target="_blank" class="d-inline-flex align-items-center gap-1 fw-medium text-decoration-underline text-unfv-red dark:text-unfv-red ms-1">
                                    <span>Documentation</span>
                                    <svg width="10" height="11" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 10px; height: 10px;">
                                        <path d="M7.70833 6.95834V2.79167H3.54167M2.5 8L7.5 3.00001" stroke="currentColor" stroke-linecap="square" />
                                    </svg>
                                </a>
                            </span>
                        </li>
                        <li class="d-flex align-items-center gap-3 py-2 position-relative">
                            <div class="position-absolute start-0" style="left: 0.4rem; bottom: 50%; top: 0; border-left: 1px solid #e3e3e0;"></div>
                            <span class="position-relative py-1 bg-white dark:bg-unfv-card">
                                <span class="d-flex align-items-center justify-content-center rounded-circle bg-white dark:bg-unfv-card shadow-sm border border-light-subtle" style="width: 14px; height: 14px;">
                                    <span class="rounded-circle" style="width: 6px; height: 6px; background-color: #dbdbd7;"></span>
                                </span>
                            </span>
                            <span>
                                Watch video tutorials at
                                <a href="https://laracasts.com" target="_blank" class="d-inline-flex align-items-center gap-1 fw-medium text-decoration-underline text-unfv-red dark:text-unfv-red ms-1">
                                    <span>Laracasts</span>
                                    <svg width="10" height="11" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 10px; height: 10px;">
                                        <path d="M7.70833 6.95834V2.79167H3.54167M2.5 8L7.5 3.00001" stroke="currentColor" stroke-linecap="square" />
                                    </svg>
                                </a>
                            </span>
                        </li>
                    </ul>
                    
                    <ul class="list-unstyled d-flex gap-2 small">
                        <li>
                            <a href="https://cloud.laravel.com" target="_blank" class="d-inline-block btn btn-dark px-3 py-2 rounded-1 text-white text-decoration-none small">
                                Deploy now
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="bg-danger bg-opacity-10 dark:bg-unfv-red position-relative border-start-0 border-bottom-0 border-lg-bottom border-lg-start rounded-top rounded-lg-top-0 rounded-lg-end d-flex flex-shrink-0 overflow-hidden" style="aspect-ratio: 335/376; width: 100%; max-width: 438px;">
                    {{-- Laravel Logo --}}
                    <svg class="w-100 text-primary transition-all-custom opacity-100" style="max-width: none; transform: translateY(0);" viewBox="0 0 438 104" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.2036 -3H0V102.197H49.5189V86.7187H17.2036V-3Z" fill="currentColor" />
                        <path d="M110.256 41.6337C108.061 38.1275 104.945 35.3731 100.905 33.3681C96.8667 31.3647 92.8016 30.3618 88.7131 30.3618C83.4247 30.3618 78.5885 31.3389 74.201 33.2923C69.8111 35.2456 66.0474 37.928 62.9059 41.3333C59.7643 44.7401 57.3198 48.6726 55.5754 53.1293C53.8287 57.589 52.9572 62.274 52.9572 67.1813C52.9572 72.1925 53.8287 76.8995 55.5754 81.3069C57.3191 85.7173 59.7636 89.6241 62.9059 93.0293C66.0474 96.4361 69.8119 99.1155 74.201 101.069C78.5885 103.022 83.4247 103.999 88.7131 103.999C92.8016 103.999 96.8667 102.997 100.905 100.994C104.945 98.9911 108.061 96.2359 110.256 92.7282V102.195H126.563V32.1642H110.256V41.6337ZM108.76 75.7472C107.762 78.4531 106.366 80.8078 104.572 82.8112C102.776 84.8161 100.606 86.4183 98.0637 87.6206C95.5202 88.823 92.7004 89.4238 89.6103 89.4238C86.5178 89.4238 83.7252 88.823 81.2324 87.6206C78.7388 86.4183 76.5949 84.8161 74.7998 82.8112C73.004 80.8078 71.6319 78.4531 70.6856 75.7472C69.7356 73.0421 69.2644 70.1868 69.2644 67.1821C69.2644 64.1758 69.7356 61.3205 70.6856 58.6154C71.6319 55.9102 73.004 53.5571 74.7998 51.5522C76.5949 49.5495 78.738 47.9451 81.2324 46.7427C83.7252 45.5404 86.5178 44.9396 89.6103 44.9396C92.7012 44.9396 95.5202 45.5404 98.0637 46.7427C100.606 47.9451 102.776 49.5487 104.572 51.5522C106.367 53.5571 107.762 55.9102 108.76 58.6154C109.756 61.3205 110.256 64.1758 110.256 67.1821C110.256 70.1868 109.756 73.0421 108.76 75.7472Z" fill="currentColor" />
                        <path d="M242.805 41.6337C240.611 38.1275 237.494 35.3731 233.455 33.3681C229.416 31.3647 225.351 30.3618 221.262 30.3618C215.974 30.3618 211.138 31.3389 206.75 33.2923C202.36 35.2456 198.597 37.928 195.455 41.3333C192.314 44.7401 189.869 48.6726 188.125 53.1293C186.378 57.589 185.507 62.274 185.507 67.1813C185.507 72.1925 186.378 76.8995 188.125 81.3069C189.868 85.7173 192.313 89.6241 195.455 93.0293C198.597 96.4361 202.361 99.1155 206.75 101.069C211.138 103.022 215.974 103.999 221.262 103.999C225.351 103.999 229.416 102.997 233.455 100.994C237.494 98.9911 240.611 96.2359 242.805 92.7282V102.195H259.112V32.1642H242.805V41.6337ZM241.31 75.7472C240.312 78.4531 238.916 80.8078 237.122 82.8112C235.326 84.8161 233.156 86.4183 230.614 87.6206C228.07 88.823 225.251 89.4238 222.16 89.4238C219.068 89.4238 216.275 88.823 213.782 87.6206C211.289 86.4183 209.145 84.8161 207.35 82.8112C205.554 80.8078 204.182 78.4531 203.236 75.7472C202.286 73.0421 201.814 70.1868 201.814 67.1821C201.814 64.1758 202.286 61.3205 203.236 58.6154C204.182 55.9102 205.554 53.5571 207.35 51.5522C209.145 49.5495 211.288 47.9451 213.782 46.7427C216.275 45.5404 219.068 44.9396 222.16 44.9396C225.251 44.9396 228.07 45.5404 230.614 46.7427C233.156 47.9451 235.326 49.5487 237.122 51.5522C238.917 53.5571 240.312 55.9102 241.31 58.6154C242.306 61.3205 242.806 64.1758 242.806 67.1821C242.805 70.1868 242.305 73.0421 241.31 75.7472Z" fill="currentColor" />
                        <path d="M438 -3H421.694V102.197H438V-3Z" fill="currentColor" />
                        <path d="M139.43 102.197H155.735V48.2834H183.712V32.1665H139.43V102.197Z" fill="currentColor" />
                        <path d="M324.49 32.1665L303.995 85.794L283.498 32.1665H266.983L293.748 102.197H314.242L341.006 32.1665H324.49Z" fill="currentColor" />
                        <path d="M376.571 30.3656C356.603 30.3656 340.797 46.8497 340.797 67.1828C340.797 89.6597 356.094 104 378.661 104C391.29 104 399.354 99.1488 409.206 88.5848L398.189 80.0226C398.183 80.031 389.874 90.9895 377.468 90.9895C363.048 90.9895 356.977 79.3111 356.977 73.269H411.075C413.917 50.1328 398.775 30.3656 376.571 30.3656ZM357.02 61.0967C357.145 59.7487 359.023 43.3761 376.442 43.3761C393.861 43.3761 395.978 59.7464 396.099 61.0967H357.02Z" fill="currentColor" />
                    </svg>

                    <div class="position-absolute top-0 start-0 w-100 h-100 rounded-top rounded-lg-top-0 rounded-lg-end custom-shadow dark:custom-shadow-dark"></div>
                </div>
            </main>
        </div>

        @if (Route::has('login'))
            <div style="height: 3.625rem;" class="d-none d-lg-block"></div>
        @endif
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>