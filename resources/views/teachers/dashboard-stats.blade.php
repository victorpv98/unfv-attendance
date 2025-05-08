<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="font-semibold text-gray-500">Mis Cursos</h2>
                <p class="text-2xl font-bold">{{ $coursesCount ?? 0 }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-500">
                <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="font-semibold text-gray-500">Estudiantes</h2>
                <p class="text-2xl font-bold">{{ $studentsCount ?? 0 }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-500">
                <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="font-semibold text-gray-500">Asistencias Hoy</h2>
                <p class="text-2xl font-bold">{{ $todayAttendances ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-4">Clases de Hoy</h3>
        @if(isset($todaySchedules) && count($todaySchedules) > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($todaySchedules as $schedule)
                    <li class="py-3">
                        <div class="flex justify-between">
                            <div>
                                <h4 class="text-md font-medium">{{ $schedule->course->name }}</h4>
                                <p class="text-sm text-gray-500">{{ $schedule->classroom }} | {{ $schedule->start_time }} - {{ $schedule->end_time }}</p>
                            </div>
                            <a href="{{ route('teachers.scan-qr', $schedule) }}" class="bg-blue-500 hover:bg-blue-700 text-white text-sm font-bold py-1 px-3 rounded">
                                Escanear QR
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500">No tienes clases programadas para hoy</p>
        @endif
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-4">Resumen de Asistencias</h3>
        <div class="h-64">
            <!-- Aquí puedes insertar un gráfico con Chart.js u otra biblioteca -->
            <canvas id="attendanceChart"></canvas>
        </div>
    </div>
</div>