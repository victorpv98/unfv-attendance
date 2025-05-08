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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="font-semibold text-gray-500">Asistencias</h2>
                <p class="text-2xl font-bold">{{ $attendanceCount ?? 0 }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 text-red-500">
                <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="font-semibold text-gray-500">Faltas</h2>
                <p class="text-2xl font-bold">{{ $absenceCount ?? 0 }}</p>
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
                                <p class="text-sm text-gray-500">Profesor: {{ $schedule->teacher->user->name }}</p>
                            </div>
                            <div class="flex items-center">
                                @if(isset($todayAttendances[$schedule->id]))
                                    <span class="px-2 py-1 rounded {{ $todayAttendances[$schedule->id]->status === 'present' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $todayAttendances[$schedule->id]->status === 'present' ? 'Presente' : 'Tardanza' }}
                                    </span>
                                @else
                                    <span class="px-2 py-1 rounded bg-gray-100 text-gray-800">Pendiente</span>
                                @endif
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500">No tienes clases programadas para hoy</p>
        @endif
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Mi Código QR</h3>
            <a href="{{ route('students.my-qr') }}" class="text-blue-500 hover:text-blue-700">Ver Completo</a>
        </div>
        
        @if(isset($student) && $student->qr_code)
            <div class="flex flex-col items-center">
                <div class="mb-4">
                    <img src="{{ route('students.qr-image', $student) }}" alt="Mi Código QR" class="w-48 h-48 border p-2 rounded">
                </div>
                <p class="text-sm text-gray-500">Muestra este código QR al profesor al inicio de cada clase</p>
            </div>
        @else
            <div class="bg-yellow-100 p-4 rounded-lg text-center">
                <p class="text-yellow-800">No tienes un código QR asignado. Genera uno para registrar tu asistencia.</p>
                <a href="{{ route('students.my-qr') }}" class="mt-2 inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                    Generar Código QR
                </a>
            </div>
        @endif
    </div>
</div>