<div class="vh-100 bg-dark text-white d-flex flex-column" style="width: 16rem; background: linear-gradient(135deg, var(--bs-dark) 0%, var(--bs-secondary) 100%) !important;">
    <div class="p-4 d-flex align-items-center border-bottom border-secondary">
        <img src="{{ asset('images/logo_unfv.png') }}" alt="Logo UNFV" class="img-fluid me-3" style="max-height: 50px; width: auto;">
        <h1 class="fs-4 fw-bold mb-0 text-primary">Sistema UNFV</h1>
    </div>
    
    <div class="flex-fill overflow-auto py-4">
        <nav class="px-2">
            @if(Auth::user()->role === 'admin')
                <!-- Admin Links -->
                <a href="{{ route('dashboard') }}" class="nav-link d-flex align-items-center px-3 py-2 mb-1 rounded {{ request()->routeIs('dashboard') ? 'bg-primary text-white' : 'text-light' }}" style="{{ request()->routeIs('dashboard') ? 'border-left: 3px solid var(--bs-primary);' : '' }}">
                    <svg class="me-3" width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.faculties.index') }}" class="nav-link d-flex align-items-center px-3 py-2 mb-1 rounded {{ request()->routeIs('admin.faculties.*') ? 'bg-primary text-white' : 'text-light' }}" style="{{ request()->routeIs('admin.faculties.*') ? 'border-left: 3px solid var(--bs-primary);' : '' }}">
                    <svg class="me-3" width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Facultades
                </a>
                <a href="{{ route('admin.courses.index') }}" class="nav-link d-flex align-items-center px-3 py-2 mb-1 rounded {{ request()->routeIs('admin.courses.*') ? 'bg-primary text-white' : 'text-light' }}" style="{{ request()->routeIs('admin.courses.*') ? 'border-left: 3px solid var(--bs-primary);' : '' }}">
                    <svg class="me-3" width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Cursos
                </a>
                <a href="{{ route('admin.teachers.index') }}" class="nav-link d-flex align-items-center px-3 py-2 mb-1 rounded {{ request()->routeIs('admin.teachers.*') ? 'bg-primary text-white' : 'text-light' }}" style="{{ request()->routeIs('admin.teachers.*') ? 'border-left: 3px solid var(--bs-primary);' : '' }}">
                    <svg class="me-3" width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profesores
                </a>
                <a href="{{ route('admin.students.index') }}" class="nav-link d-flex align-items-center px-3 py-2 mb-1 rounded {{ request()->routeIs('admin.students.*') ? 'bg-primary text-white' : 'text-light' }}" style="{{ request()->routeIs('admin.students.*') ? 'border-left: 3px solid var(--bs-primary);' : '' }}">
                    <svg class="me-3" width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Estudiantes
                </a>
                <a href="{{ route('admin.enrollments.index') }}" class="nav-link d-flex align-items-center px-3 py-2 mb-1 rounded {{ request()->routeIs('admin.enrollments.*') ? 'bg-primary text-white' : 'text-light' }}" style="{{ request()->routeIs('admin.enrollments.*') ? 'border-left: 3px solid var(--bs-primary);' : '' }}">
                    <svg class="me-3" width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Matrículas
                </a>
                <a href="{{ route('admin.schedules.index') }}" class="nav-link d-flex align-items-center px-3 py-2 mb-1 rounded {{ request()->routeIs('admin.schedules.*') ? 'bg-primary text-white' : 'text-light' }}" style="{{ request()->routeIs('admin.schedules.*') ? 'border-left: 3px solid var(--bs-primary);' : '' }}">
                    <svg class="me-3" width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Horarios
                </a>
            @elseif(Auth::user()->role === 'teacher')
                <!-- Teacher Links -->
                <a href="{{ route('dashboard') }}" class="nav-link d-flex align-items-center px-3 py-2 mb-1 rounded {{ request()->routeIs('dashboard') ? 'bg-primary text-white' : 'text-light' }}" style="{{ request()->routeIs('dashboard') ? 'border-left: 3px solid var(--bs-primary);' : '' }}">
                    <svg class="me-3" width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('teachers.my-schedules') }}" class="nav-link d-flex align-items-center px-3 py-2 mb-1 rounded {{ request()->routeIs('teachers.my-schedules') ? 'bg-primary text-white' : 'text-light' }}" style="{{ request()->routeIs('teachers.my-schedules') ? 'border-left: 3px solid var(--bs-primary);' : '' }}">
                    <svg class="me-3" width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Mis Horarios
                </a>
            @elseif(Auth::user()->role === 'student')
                <!-- Student Links -->
                <a href="{{ route('dashboard') }}" class="nav-link d-flex align-items-center px-3 py-2 mb-1 rounded {{ request()->routeIs('dashboard') ? 'bg-primary text-white' : 'text-light' }}" style="{{ request()->routeIs('dashboard') ? 'border-left: 3px solid var(--bs-primary);' : '' }}">
                    <svg class="me-3" width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('students.my-barcode') }}" class="nav-link d-flex align-items-center px-3 py-2 mb-1 rounded {{ request()->routeIs('students.my-barcode') ? 'bg-primary text-white' : 'text-light' }}" style="{{ request()->routeIs('students.my-barcode') ? 'border-left: 3px solid var(--bs-primary);' : '' }}">
                    <!-- Icono de código de barras -->
                    <svg class="me-3" width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M2 6h1v12H2V6zm2 0h1v12H4V6zm3 0h1v12H7V6zm2 0h2v12H9V6zm3 0h1v12h-1V6zm3 0h1v12h-1V6zm2 0h2v12h-2V6zm3 0h1v12h-1V6z"/>
                    </svg>
                    Mi Código de Barras
                </a>
                <a href="{{ route('students.my-courses') }}" class="nav-link d-flex align-items-center px-3 py-2 mb-1 rounded {{ request()->routeIs('students.my-courses') ? 'bg-primary text-white' : 'text-light' }}" style="{{ request()->routeIs('students.my-courses') ? 'border-left: 3px solid var(--bs-primary);' : '' }}">
                    <svg class="me-3" width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Mis Cursos
                </a>
                <a href="{{ route('students.my-attendances') }}" class="nav-link d-flex align-items-center px-3 py-2 mb-1 rounded {{ request()->routeIs('students.my-attendances') ? 'bg-primary text-white' : 'text-light' }}" style="{{ request()->routeIs('students.my-attendances') ? 'border-left: 3px solid var(--bs-primary);' : '' }}">
                    <svg class="me-3" width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    Mis Asistencias
                </a>
            @endif
        </nav>
    </div>
    
    <div class="p-4 border-top" style="background-color: var(--bs-secondary);">
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    <svg width="16" height="16" class="text-white" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
            </div>
            <div class="ms-3">
                <p class="small fw-medium text-white mb-0">{{ Auth::user()->name ?? 'Usuario' }}</p>
                <p class="small text-light mb-0 opacity-75">{{ ucfirst(Auth::user()->role ?? 'invitado') }}</p>
            </div>
        </div>
    </div>
</div>