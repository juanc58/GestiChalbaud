@extends('dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#1A237E]">Estudiantes</h2>
            <p class="text-sm text-gray-500 mt-1">Directorio y gestión de estudiantes inscritos.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('students.create') }}" class="bg-[#FBC02D] text-[#1A237E] font-semibold text-sm px-4 py-2.5 rounded-lg hover:bg-[#FBC02D]/90 transition-colors flex items-center gap-2 shadow-sm border border-[#FBC02D]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nueva Inscripción
            </a>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white border border-gray-200 shadow-sm rounded-xl overflow-hidden flex flex-col">
        
        <!-- Filters Bar -->
        <div class="p-4 border-b border-gray-200 bg-gray-50 flex flex-col md:flex-row gap-4">
            <form action="{{ route('students.index') }}" method="GET" class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre o Cédula..." class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-[#1A237E]/20 focus:border-[#1A237E] outline-none transition-all">
                </div>
                
                <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-[#1A237E]/20 focus:border-[#1A237E] outline-none transition-all appearance-none bg-white">
                    <option value="">Todos los Estatus</option>
                    <option value="Activo" {{ request('status') == 'Activo' ? 'selected' : '' }}>Activos</option>
                    <option value="Retirado" {{ request('status') == 'Retirado' ? 'selected' : '' }}>Retirados</option>
                    <option value="Egresado" {{ request('status') == 'Egresado' ? 'selected' : '' }}>Egresados</option>
                </select>

                <select name="section_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-[#1A237E]/20 focus:border-[#1A237E] outline-none transition-all appearance-none bg-white">
                    <option value="">Todas las Aulas</option>
                    @foreach($sections->groupBy('grade.name') as $gradeName => $gradeSections)
                        <optgroup label="{{ $gradeName }}">
                            @foreach($gradeSections as $s)
                                <option value="{{ $s->id }}" {{ request('section_id') == $s->id ? 'selected' : '' }}>
                                    {{ $gradeName }} - "{{ $s->name }}"
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>

                <div class="flex gap-2">
                    <button type="submit" class="bg-[#1A237E] text-white px-4 py-2 rounded-lg font-medium text-sm hover:bg-[#1A237E]/90 transition-colors flex-1 border border-[#1A237E]">
                        Filtrar
                    </button>
                    @if(request()->anyFilled(['search', 'status', 'section_id']))
                        <a href="{{ route('students.index') }}" class="bg-white text-gray-500 px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors border border-gray-300 flex items-center justify-center" title="Limpiar Filtros">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Cédula</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Sección ({{ $currentYear }})</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Estatus</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($students as $student)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-[#1A237E]/10 text-[#1A237E] flex items-center justify-center font-bold text-xs mr-3">
                                    {{ substr($student->first_name, 0, 1) }}
                                </div>
                                <div>
                                    <span class="font-semibold text-sm text-[#1A237E]">{{ $student->first_name }} {{ $student->last_name }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $student->cedula ?? 'No asignada' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php $enrollment = $student->enrollments->first(); @endphp
                            @if($enrollment && $enrollment->section)
                                <span class="text-sm font-medium text-gray-900">{{ $enrollment->section->grade->name }} "{{ $enrollment->section->name }}"</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                    Sin Aula
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($student->status === 'Activo')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Activo
                                </span>
                            @elseif($student->status === 'Egresado')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Egresado
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    Retirado
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('students.show', $student->id) }}" class="text-gray-400 hover:text-[#1A237E] transition-colors p-1" title="Ver Perfil">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('students.edit', $student->id) }}" class="text-gray-400 hover:text-[#FBC02D] transition-colors p-1" title="Editar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                @if($student->status !== 'Egresado')
                                    <form action="{{ route('students.toggle-status', $student->id) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Confirma esta acción?');">
                                        @csrf
                                        <button type="submit" class="{{ $student->is_active ? 'text-gray-400 hover:text-[#D32F2F]' : 'text-gray-400 hover:text-green-600' }} transition-colors p-1" title="{{ $student->is_active ? 'Retirar Estudiante' : 'Re-activar Estudiante' }}">
                                            @if($student->is_active)
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            @endif
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <h3 class="text-sm font-medium text-gray-900">No hay estudiantes</h3>
                                <p class="text-sm text-gray-500 mt-1">Registra tu primer estudiante o cambia los filtros de búsqueda.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($students->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-white">
            {{ $students->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
