@extends('dashboard')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-8 border-b border-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center bg-gray-50/50 gap-4">
        <div>
            <h3 class="text-2xl font-extrabold text-[#1A237E]">Docentes</h3>
            <p class="text-gray-500 font-semibold mt-1">Gestión del personal académico y asignaciones de aula.</p>
        </div>
        <a href="{{ route('teachers.create') }}" class="px-8 py-3 bg-[#1A237E] text-white rounded-2xl font-bold hover:bg-[#1A237E]/90 transition-all shadow-sm flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nuevo Docente
        </a>
    </div>

    <div class="p-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest border-b border-gray-50">
                        <th class="px-6 py-4">Docente</th>
                        <th class="px-6 py-4">Cédula</th>
                        <th class="px-6 py-4">Aula Actual</th>
                        <th class="px-6 py-4">Estatus</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($teachers as $teacher)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-5">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-xl bg-[#1A237E]/5 flex items-center justify-center text-[#1A237E] font-bold mr-4 overflow-hidden flex-shrink-0">
                                    @if($teacher->photo_url)
                                        <img src="{{ $teacher->photo_url }}" alt="Foto" class="w-full h-full object-cover">
                                    @else
                                        {{ substr($teacher->first_name, 0, 1) }}{{ substr($teacher->last_name, 0, 1) }}
                                    @endif
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-800">{{ $teacher->full_name }}</span>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $teacher->academic_degree ?? 'Docente Institucional' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-sm font-bold text-gray-600">V-{{ $teacher->cedula }}</span>
                        </td>
                        <td class="px-6 py-5">
                            @php 
                                $currentYear = \App\Models\SchoolYear::current();
                                $assignment = $teacher->assignments->where('school_year', $currentYear)->first(); 
                            @endphp
                            @if($assignment)
                                <span class="px-4 py-1.5 bg-orange-50 text-[#FBC02D] rounded-full text-[10px] font-black uppercase tracking-widest border border-orange-100 whitespace-nowrap inline-block">
                                    {{ $assignment->section->grade->name }} "{{ $assignment->section->name }}"
                                </span>
                            @else
                                <span class="text-[10px] font-bold text-gray-300 uppercase tracking-widest italic">Sin aula asignada</span>
                            @endif
                        </td>
                        <td class="px-6 py-5">
                            <span class="px-3 py-1 {{ $teacher->status == 'Activo' ? 'bg-green-100 text-green-600' : 'bg-red-50 text-red-400' }} rounded-full text-[10px] font-bold uppercase tracking-widest">
                                {{ $teacher->status }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-right whitespace-nowrap">
                            <div class="inline-flex items-center justify-end gap-3">
                                <a href="{{ route('teachers.show', $teacher->id) }}" class="text-[#1A237E] hover:text-[#1A237E]/80 font-bold text-xs uppercase tracking-widest">Ver Perfil</a>
                                <a href="{{ route('teachers.edit', $teacher->id) }}" class="text-gray-400 hover:text-[#FBC02D] font-bold text-xs uppercase tracking-widest">Editar</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">No hay docentes registrados todavía.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
