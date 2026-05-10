@extends('dashboard')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-50 flex flex-col md:flex-row gap-4 bg-gray-50/50">
        <form action="{{ route('students.index') }}" method="GET" class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre o Cédula..." class="w-full px-5 py-2.5 rounded-xl bg-white border border-gray-200 outline-none focus:border-[#032e5e]/20 transition-all text-sm font-semibold">
            </div>
            
            <select name="status" class="px-5 py-2.5 rounded-xl bg-white border border-gray-200 outline-none focus:border-[#032e5e]/20 transition-all text-sm font-semibold appearance-none">
                <option value="">Todos los Estatus</option>
                <option value="Activo" {{ request('status') == 'Activo' ? 'selected' : '' }}>✓ Activos</option>
                <option value="Retirado" {{ request('status') == 'Retirado' ? 'selected' : '' }}>⚠ Retirados</option>
                <option value="Egresado" {{ request('status') == 'Egresado' ? 'selected' : '' }}>🎓 Egresados</option>
            </select>

            <select name="section_id" class="px-5 py-2.5 rounded-xl bg-white border border-gray-200 outline-none focus:border-[#032e5e]/20 transition-all text-sm font-semibold appearance-none">
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
                <button type="submit" class="bg-[#032e5e] text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-[#032e5e]/90 transition-all flex-1">
                    Filtrar
                </button>
                @if(request()->anyFilled(['search', 'status', 'section_id']))
                    <a href="{{ route('students.index') }}" class="bg-gray-100 text-gray-500 p-2.5 rounded-xl hover:bg-gray-200 transition-all shadow-sm" title="Limpiar Filtros">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </div>
        </form>
        <div class="flex items-center">
            <a href="{{ route('students.create') }}" class="bg-[#c56c39] text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-[#c56c39]/90 transition-all shadow-lg shadow-[#c56c39]/20 whitespace-nowrap">
                Nueva Inscripción
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Estudiante</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Cédula</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Sección ({{ $currentYear }})</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Estatus</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($students as $student)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-gray-200 mr-3"></div>
                            <span class="font-bold text-gray-700">{{ $student->first_name }} {{ $student->last_name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500 font-semibold">{{ $student->cedula ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        @php $enrollment = $student->enrollments->first(); @endphp
                        @if($enrollment && $enrollment->section)
                            <span class="text-sm font-bold text-[#032e5e]">{{ $enrollment->section->grade->name }} "{{ $enrollment->section->name }}"</span>
                        @else
                            <span class="text-xs font-bold text-gray-300">Sin Aula</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($student->status === 'Activo')
                            <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-[10px] font-black uppercase tracking-widest italic">✓ Activo</span>
                        @elseif($student->status === 'Egresado')
                            <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-[10px] font-black uppercase tracking-widest italic">🎓 Egresado</span>
                        @else
                            <span class="px-3 py-1 bg-orange-50 text-orange-500 rounded-full text-[10px] font-black uppercase tracking-widest italic">⚠ Retirado</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 flex items-center space-x-3">
                        <a href="{{ route('students.show', $student->id) }}" class="p-2 text-gray-400 hover:text-[#032e5e] transition-colors" title="Ver Detalle">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        <a href="{{ route('students.edit', $student->id) }}" class="p-2 text-blue-400 hover:text-blue-600 transition-colors" title="Editar">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22H15C20 22 22 20 22 15V13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M16.04 3.02001L8.16 10.9C7.86 11.2 7.56 11.79 7.5 12.22L7.07 15.23C6.91 16.32 7.68 17.08 8.77 16.93L11.78 16.5C12.2 16.44 12.79 16.14 13.1 15.84L20.98 7.96001C22.34 6.60001 22.98 5.02001 20.98 3.02001C18.98 1.02001 17.4 1.66001 16.04 3.02001Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M14.91 4.1499C15.58 6.5399 17.45 8.4099 19.85 9.0899" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                        @if($student->status !== 'Egresado')
                            <form action="{{ route('students.toggle-status', $student->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="{{ $student->is_active ? 'text-orange-400 hover:text-orange-600' : 'text-green-400 hover:text-green-600' }} p-2 transition-colors" title="{{ $student->is_active ? 'Retirar Estudiante' : 'Re-activar Estudiante' }}">
                                    @if($student->is_active)
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @endif
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-semibold">
                        No hay estudiantes registrados aún.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-6 border-t border-gray-50 bg-gray-50/30">
        {{ $students->links() }}
    </div>
</div>
@endsection
