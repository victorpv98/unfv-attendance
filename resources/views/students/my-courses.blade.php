@extends('layouts.app')

@section('header')
    Mis Cursos
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="mb-6">
            <h2 class="text-2xl font-semibold mb-2">Mis Cursos Matriculados</h2>
            <p class="text-gray-600">Semestre: {{ $currentSemester }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($courses as $course)
                <div class="border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="bg-blue-500 p-4">
                        <h3 class="text-lg font-semibold text-white">{{ $course->name }}</h3>
                        <p class="text-blue-100">{{ $course->code }}</p>
                    </div>
                    <div class="p-4">
                        <p class="text-gray-500 mb-2">Facultad: {{ $course->faculty->name }}</p>
                        <p class="text-gray-500 mb-2">Créditos: {{ $course->credits }}</p>
                        <p class="text-gray-500 mb-4">Ciclo: {{ $course->cycle }}</p>
                        
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Horarios</h4>
                            <ul class="space-y-2">
                                @foreach($course->schedules as $schedule)
                                    <li class="text-sm bg-gray-50 p-2 rounded">
                                        <span class="font-medium">{{ __($schedule->day) }}: </span>
                                        <span>{{ $schedule->start_time }} - {{ $schedule->end_time }}</span>
                                        <br>
                                        <span class="text-gray-500">Aula: {{ $schedule->classroom }}</span>
                                        <br>
                                        <span class="text-gray-500">Profesor: {{ $schedule->teacher->user->name }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        
                        <div class="mb-2">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Asistencia</h4>
                            <div class="flex items-center">
                                <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div 
                                        class="bg-green-500 h-full" 
                                        style="width: {{ $coursesAttendance[$course->id]['percentage'] ?? 0 }}%">
                                    </div>
                                </div>
                                <span class="ml-2 text-sm font-medium text-gray-500">
                                    {{ $coursesAttendance[$course->id]['percentage'] ?? 0 }}%
                                </span>
                            </div>
                        </div>
                        
                        <a href="{{ route('students.course-attendances', $course) }}" class="mt-2 inline-block text-blue-500 hover:text-blue-700 text-sm font-medium">
                            Ver detalle de asistencias →
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-yellow-50 rounded-lg p-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-yellow-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-yellow-800">No estás matriculado en ningún curso</h3>
                    <p class="mt-1 text-sm text-yellow-700">Contacta con administración para matricularte en tus cursos.</p>
                </div>
            @endforelse
        </div>
    </div>

    @if(count($pastSemesters) > 0)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold mb-4">Historial Académico</h3>
            
            <div class="mb-4">
                <form action="{{ route('students.my-courses') }}" method="GET" class="flex space-x-4">
                    <div>
                        <label for="semester" class="block text-sm font-medium text-gray-700 mb-1">Semestre</label>
                        <select id="semester" name="semester" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @foreach($pastSemesters as $semester)
                                <option value="{{ $semester }}" {{ request('semester') == $semester ? 'selected' : '' }}>
                                    {{ $semester }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                            Ver Cursos
                        </button>
                    </div>
                </form>
            </div>
            
            @if(isset($pastCourses) && count($pastCourses) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Curso</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Créditos</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asistencia</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pastCourses as $course)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $course->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $course->code }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $course->credits }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="flex items-center">
                                            <div class="w-20 h-2 bg-gray-200 rounded-full overflow-hidden">
                                                <div 
                                                    class="bg-green-500 h-full" 
                                                    style="width: {{ $pastCoursesAttendance[$course->id]['percentage'] ?? 0 }}%">
                                                </div>
                                            </div>
                                            <span class="ml-2 text-sm">
                                                {{ $pastCoursesAttendance[$course->id]['percentage'] ?? 0 }}%
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">No hay cursos disponibles para el semestre seleccionado.</p>
            @endif
        </div>
    @endif
@endsection