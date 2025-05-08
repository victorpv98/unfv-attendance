@extends('layouts.app')

@section('header')
    Mis Asistencias
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-semibold mb-2">Historial de Asistencias</h2>
                <p class="text-gray-600">Registro de tus asistencias a clases</p>
            </div>
            
            <div>
                <form action="{{ route('students.my-attendances') }}" method="GET" class="flex items-end space-x-4">
                    <div>
                        <label for="course_id" class="block text-sm font-medium text-gray-700 mb-1">Curso</label>
                        <select id="course_id" name="course_id" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">Todos los cursos</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="month" class="block text-sm font-medium text-gray-700 mb-1">Mes</label>
                        <select id="month" name="month" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">Todos los meses</option>
                            @foreach($months as $key => $month)
                                <option value="{{ $key }}" {{ request('month') == $key ? 'selected' : '' }}>
                                    {{ $month }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <button type="submit" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        Filtrar
                    </button>
                </form>
            </div>
        </div>

        <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-green-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-green-800 mb-2">Presentes</h3>
                <p class="text-3xl font-bold text-green-800">{{ $summary['present'] ?? 0 }}</p>
                <p class="text-sm text-green-600">{{ $summary['presentPercentage'] ?? 0 }}% de asistencia</p>
            </div>
            
            <div class="bg-yellow-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-yellow-800 mb-2">Tardanzas</h3>
                <p class="text-3xl font-bold text-yellow-800">{{ $summary['late'] ?? 0 }}</p>
                <p class="text-sm text-yellow-600">{{ $summary['latePercentage'] ?? 0 }}% de tardanzas</p>
            </div>
            
            <div class="bg-red-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-red-800 mb-2">Ausencias</h3>
                <p class="text-3xl font-bold text-red-800">{{ $summary['absent'] ?? 0 }}</p>
                <p class="text-sm text-red-600">{{ $summary['absentPercentage'] ?? 0 }}% de inasistencia</p>
            </div>
        </div>

        @component('components.table')
            @slot('header')
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Curso</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profesor</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
            @endslot

            @slot('body')
                @forelse($attendances as $attendance)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendance->time }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $attendance->schedule->course->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendance->schedule->teacher->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-2 py-1 rounded {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800' : ($attendance->status === 'late' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $attendance->status === 'present' ? 'Presente' : ($attendance->status === 'late' ? 'Tardanza' : 'Ausente') }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No hay asistencias registradas</td>
                    </tr>
                @endforelse
            @endslot

            @if(isset($attendances) && $attendances->hasPages())
                @slot('pagination')
                    {{ $attendances->appends(request()->except('page'))->links() }}
                @endslot
            @endif
        @endcomponent
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-4">Resumen de Asistencia por Curso</h3>
        
        <div class="grid grid-cols-1 gap-4">
            @foreach($coursesSummary as $course)
                <div class="border rounded-lg p-4">
                    <h4 class="text-lg font-medium mb-2">{{ $course->name }}</h4>
                    <div class="flex flex-wrap">
                        <div class="w-full sm:w-1/2 md:w-1/4 p-2">
                            <p class="text-gray-500 text-sm">Presente: <span class="font-medium text-green-600">{{ $course->present_count ?? 0 }}</span></p>
                        </div>
                        <div class="w-full sm:w-1/2 md:w-1/4 p-2">
                            <p class="text-gray-500 text-sm">Tardanza: <span class="font-medium text-yellow-600">{{ $course->late_count ?? 0 }}</span></p>
                        </div>
                        <div class="w-full sm:w-1/2 md:w-1/4 p-2">
                            <p class="text-gray-500 text-sm">Ausente: <span class="font-medium text-red-600">{{ $course->absent_count ?? 0 }}</span></p>
                        </div>
                        <div class="w-full sm:w-1/2 md:w-1/4 p-2">
                            <p class="text-gray-500 text-sm">Asistencia: <span class="font-medium">{{ $course->attendance_percentage ?? 0 }}%</span></p>
                        </div>
                    </div>
                    <div class="mt-2 h-6 bg-gray-200 rounded-full overflow-hidden">
                        <div class="flex h-full">
                            <div 
                                class="bg-green-500 h-full" 
                                style="width: {{ $course->present_percentage ?? 0 }}%">
                            </div>
                            <div 
                                class="bg-yellow-500 h-full" 
                                style="width: {{ $course->late_percentage ?? 0 }}%">
                            </div>
                            <div 
                                class="bg-red-500 h-full" 
                                style="width: {{ $course->absent_percentage ?? 0 }}%">
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gráfico de resumen general podría agregarse aquí si se desea
    });
</script>
@endpush