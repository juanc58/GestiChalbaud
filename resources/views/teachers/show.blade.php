@extends('dashboard')

@section('content')
<div class="max-w-full space-y-6 space-y-8">
    <div class="flex justify-between items-end">
        <div>
            <h3 class="text-2xl font-extrabold text-[#1A237E]">Perfil del Docente</h3>
            <p class="text-gray-500 font-semibold">Resumen profesional y asignaciones de aula.</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('teachers.index') }}" class="px-6 py-2 rounded-xl bg-white text-gray-500 font-bold border border-gray-100 hover:bg-gray-50 transition-all text-sm flex items-center">Volver</a>
            <a href="{{ route('teachers.edit', $teacher->id) }}" class="px-6 py-2 rounded-xl bg-white text-[#FBC02D] font-bold border border-orange-100 hover:bg-orange-50 transition-all text-sm">Editar Datos</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- left col: Photo & Quick Info -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex flex-col items-center">
                <div class="w-24 h-24 rounded-full bg-[#1A237E]/5 mb-4 flex items-center justify-center border-4 border-[#1A237E]/10">
                    <span class="text-3xl font-bold text-[#1A237E]">{{ substr($teacher->first_name, 0, 1) }}{{ substr($teacher->last_name, 0, 1) }}</span>
                </div>
                <h4 class="font-extrabold text-[#1A237E] text-center">{{ $teacher->first_name }} {{ $teacher->last_name }}</h4>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">C.I. V-{{ $teacher->cedula }}</p>
                
                <div class="mt-4">
                    <span class="px-4 py-1.5 {{ $teacher->status == 'Activo' ? 'bg-green-100 text-green-600 border-green-200' : 'bg-red-50 text-red-500 border-red-100' }} rounded-full text-[10px] font-black uppercase tracking-widest italic border">
                        {{ $teacher->status }}
                    </span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h5 class="text-xs font-bold text-[#FBC02D] uppercase tracking-widest mb-4">Información de Contacto</h5>
                <div class="space-y-4">
                    <div>
                        <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest mb-1">Teléfono</p>
                        <p class="text-sm font-bold text-gray-700">{{ $teacher->phone ?? 'No registrado' }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest mb-1">Correo</p>
                        <p class="text-sm font-bold text-gray-700 lowercase">{{ $teacher->email ?? 'No registrado' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- right col: Extended Info & Assignments -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h5 class="text-xs font-bold text-[#FBC02D] uppercase tracking-[0.2em] mb-6 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Trayectoria Laboral
                </h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Título Académico</p>
                        <p class="font-bold text-gray-700">{{ $teacher->academic_degree }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Fecha de Ingreso</p>
                        <p class="font-bold text-gray-700">{{ \Carbon\Carbon::parse($teacher->entry_date)->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Current Assignment -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h5 class="text-xs font-bold text-[#FBC02D] uppercase tracking-[0.2em] mb-6 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Asignación de Aula (Año {{ $currentYear }})
                </h5>
                
                @php $assignment = $teacher->assignments->where('school_year', $currentYear)->first(); @endphp

                @if($assignment)
                    <div class="p-6 rounded-2xl bg-[#FBC02D]/5 border border-[#c56c39]/10 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-black text-[#FBC02D] uppercase tracking-widest mb-1">Aula Asignada</p>
                            <h6 class="text-xl font-black text-[#1A237E]">{{ $assignment->section->grade->name }} "{{ $assignment->section->name }}"</h6>
                            <p class="text-xs font-bold text-gray-400 mt-1">Turno: {{ $assignment->section->shift }}</p>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="text-[10px] font-black text-green-500 uppercase tracking-widest bg-green-50 px-3 py-1 rounded-full mb-3">En Curso</span>
                            <a href="{{ route('sections.show', $assignment->section_id) }}" class="text-xs font-bold text-[#1A237E] hover:underline">Ver Aula</a>
                        </div>
                    </div>
                @else
                    <div class="p-8 rounded-xl border-2 border-dashed border-gray-100 text-center">
                        <p class="text-gray-400 font-bold text-sm italic mb-6 text-center">No hay asignación activa para este año escolar.</p>
                        
                        <form action="{{ route('teachers.assign-section', $teacher->id) }}" method="POST" class="flex flex-col md:flex-row gap-4 justify-center items-end">
                            @csrf
                            <input type="hidden" name="school_year" value="{{ $currentYear }}">
                            <div class="text-left">
                                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1 ml-1 block">Seleccionar Aula Libre</label>
                                <select name="section_id" required class="px-5 py-3 rounded-xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/10 outline-none font-bold text-gray-700 text-sm appearance-none min-w-[200px]">
                                    <option value="">Seleccione...</option>
                                    @foreach($sections as $sec)
                                        @php 
                                            // Optional: Check if section already has teacher (front-end check)
                                            $isOccupied = \App\Models\TeacherAssignment::where('section_id', $sec->id)->where('school_year', $currentYear)->exists();
                                        @endphp
                                        <option value="{{ $sec->id }}" {{ $isOccupied ? 'disabled' : '' }}>
                                            {{ $sec->grade->name }} "{{ $sec->name }}" {{ $isOccupied ? '(Ocupada)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="bg-[#1A237E] text-white px-8 py-3.5 rounded-xl font-black text-xs hover:bg-[#1A237E]/90 transition-all shadow-lg uppercase tracking-widest">
                                Asignar Ahora
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Historical Assignments -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h5 class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em] mb-6">Historial de Asignaciones</h5>
                <div class="space-y-4">
                    @forelse($teacher->assignments->where('school_year', '!=', $currentYear)->sortByDesc('school_year') as $history)
                        <div class="flex justify-between items-center p-4 rounded-xl border border-gray-50 bg-gray-50/30">
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $history->school_year }}</p>
                                <p class="text-sm font-bold text-[#1A237E]">{{ $history->section->grade->name }} "{{ $history->section->name }}"</p>
                            </div>
                            <span class="text-[9px] font-black text-gray-300 uppercase">Finalizado</span>
                        </div>
                    @empty
                        <p class="text-xs font-bold text-gray-300 italic text-center py-4">Sin registros históricos.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
