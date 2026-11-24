@extends('dashboard')

@section('content')
<div class="space-y-8">
    <!-- Cabecera del Aula -->
    <div class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <a href="{{ route('sections.index') }}" class="text-gray-400 hover:text-[#1A237E] transition-colors font-bold text-sm flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        Volver a Aulas
                    </a>
                    <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-[10px] font-bold uppercase tracking-widest">{{ $section->shift }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <h3 class="text-3xl font-extrabold text-[#1A237E]">
                        {{ $section->grade->name }} "{{ $section->name }}"
                    </h3>
                    @if(($isLatestYear || $isAdmin) && $isAdmin)
                        <a href="{{ route('sections.edit', $section->id) }}" class="p-2 bg-white text-gray-400 hover:text-[#FBC02D] rounded-xl border border-gray-200 shadow-sm transition-all" title="Editar detalles del aula">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22H15C20 22 22 20 22 15V13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M16.04 3.02001L8.16 10.9C7.86 11.2 7.56 11.79 7.5 12.22L7.07 15.23C6.91 16.32 7.68 17.08 8.77 16.93L11.78 16.5C12.2 16.44 12.79 16.14 13.1 15.84L20.98 7.96001C22.34 6.60001 22.98 5.02001 20.98 3.02001C18.98 1.02001 17.4 1.66001 16.04 3.02001Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M14.91 4.1499C15.58 6.5399 17.45 8.4099 19.85 9.0899" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <form action="{{ route('sections.show', $section->id) }}" method="GET" id="yearFilterForm" class="flex items-center gap-2 bg-white border border-gray-200 rounded-xl px-4 py-2 shadow-sm">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest whitespace-nowrap">Período:</span>
                    <select name="school_year" onchange="document.getElementById('yearFilterForm').submit()" class="bg-transparent font-bold text-[#1A237E] text-sm outline-none cursor-pointer">
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                {{ $year }} {{ $year == $currentYear ? '(Actual)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </form>
                @if($isAdmin && !$isLatestYear)
                    <div class="px-4 py-2.5 bg-red-50 text-red-600 rounded-xl font-bold text-[10px] uppercase tracking-widest flex items-center border border-red-100 whitespace-nowrap">
                        Modo Admin (Histórico)
                    </div>
                @endif
            </div>
        </div>

        <!-- Barra Simétrica de Acciones del Aula -->
        @if($isLatestYear || $isAdmin)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <a href="{{ route('attendance.students', ['section_id' => $section->id]) }}" class="bg-white border border-gray-200 text-[#1A237E] px-4 py-3 rounded-xl font-bold hover:bg-gray-50 transition-all shadow-sm text-sm flex items-center justify-center whitespace-nowrap">
                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    Asistencia Estudiantil
                </a>
                <a href="{{ route('sections.assign.view', $section->id) }}" class="bg-white text-[#1A237E] px-4 py-3 rounded-xl font-bold hover:bg-gray-50 transition-all border border-gray-200 shadow-sm text-sm flex items-center justify-center whitespace-nowrap">
                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Asignar Estudiante
                </a>
                <a href="{{ route('sections.grades', $section->id) }}" class="bg-[#1A237E] text-white px-4 py-3 rounded-xl font-bold hover:bg-[#1A237E]/90 transition-all shadow-sm text-sm flex items-center justify-center whitespace-nowrap">
                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    Cargar Notas
                </a>
                <a href="{{ route('sections.promote.view', $section->id) }}" class="bg-[#FBC02D] text-[#1A237E] px-4 py-3 rounded-xl font-bold hover:bg-[#FBC02D]/90 transition-all shadow-sm text-sm flex items-center justify-center whitespace-nowrap">
                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Promoción / Cierre
                </a>
            </div>
        @else
            <div class="px-6 py-3 bg-gray-100 text-gray-500 rounded-xl font-bold text-xs uppercase tracking-widest inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                Período Histórico (Solo Lectura)
            </div>
        @endif
    </div>

    <!-- Docente Card -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center text-[#1A237E] mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
            <div>
                <h4 class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Docente Encargado</h4>
                @if($currentTeacher)
                    <p class="text-lg font-extrabold text-[#1A237E]">{{ $currentTeacher->first_name }} {{ $currentTeacher->last_name }}</p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">C.I. V-{{ $currentTeacher->cedula }} | {{ $currentTeacher->academic_degree }}</p>
                @else
                    <p class="text-lg font-extrabold text-gray-300 italic">Sin docente asignado</p>
                @endif
            </div>
        </div>
        
        @if($isAdmin && $currentTeacher && $isLatestYear)
            @php $currentAssignmentId = $section->currentTeacherAssignment($selectedYear)->first()?->id; @endphp
            <form action="{{ route('teachers.unassign-section', ['teacher' => $currentTeacher->id, 'assignment' => $currentAssignmentId]) }}" method="POST" onsubmit="return confirm('¿Estás seguro de retirar al docente de esta aula?')">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-50 text-red-500 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-red-100 transition-colors">
                    Retirar Docente
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-200 text-green-700 px-6 py-4 rounded-xl font-bold">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-8 py-5 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
            <h4 class="text-lg font-bold text-gray-800">Nómina de Estudiantes</h4>
            <span class="px-4 py-1.5 bg-[#1A237E]/10 text-[#1A237E] rounded-xl text-xs font-extrabold tracking-widest">
                Total: {{ $section->enrollments->count() }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-gray-100">
                        <th class="px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Cédula</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Apellidos y Nombres</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] text-center">Edad</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] text-center">Estatus</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($section->enrollments as $enrollment)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-8 py-5 text-gray-500 font-bold text-sm">{{ $enrollment->student->cedula ?? 'S/C' }}</td>
                        <td class="px-8 py-5">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-gray-100 text-[#1A237E] font-bold text-xs flex items-center justify-center mr-3 border border-gray-200">
                                    {{ substr($enrollment->student->first_name, 0, 1) }}{{ substr($enrollment->student->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <span class="font-extrabold text-gray-800">{{ $enrollment->student->last_name }}, {{ $enrollment->student->first_name }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-center text-gray-500 font-bold text-sm">
                            {{ \Carbon\Carbon::parse($enrollment->student->birth_date)->age }}
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-[10px] font-bold uppercase tracking-widest">{{ $enrollment->status ?? 'Activo' }}</span>
                        </td>
                        <td class="px-8 py-5 text-right whitespace-nowrap">
                            <div class="inline-flex items-center justify-end gap-3">
                                <a href="{{ route('reports.boletin', $enrollment->id) }}" target="_blank" class="text-[#FBC02D] hover:text-[#FBC02D]/80 transition-all" title="Ver Boletín">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </a>
                                <a href="{{ route('students.show', ['student' => $enrollment->student->id, 'redirect_section' => $section->id]) }}" class="text-gray-400 hover:text-[#1A237E] transition-colors font-bold text-xs uppercase tracking-widest whitespace-nowrap">Ver Perfil</a>
                                
                                @if($isLatestYear || $isAdmin)
                                    <form action="{{ route('enrollments.destroy', $enrollment->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de remover a este estudiante del aula?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600 transition-colors p-2 rounded-lg hover:bg-red-50" title="Remover del aula">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-16 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <p class="text-gray-400 font-bold text-lg">Sin registros</p>
                            <p class="text-gray-400 text-sm mt-1">No hay estudiantes registrados para el periodo {{ $selectedYear }}.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
