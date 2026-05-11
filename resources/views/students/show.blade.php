@extends('dashboard')

@section('content')
<div class="max-w-full space-y-6 space-y-8">
    <div class="flex justify-between items-end">
        <div>
            <h3 class="text-2xl font-extrabold text-[#1A237E]">Ficha del Estudiante</h3>
            <p class="text-gray-500 font-semibold">Resumen detallado de la inscripción de {{ $student->first_name }}.</p>
        </div>
        <div class="flex space-x-3">
            @if(request('redirect_section'))
                <a href="{{ route('sections.show', request('redirect_section')) }}" class="px-6 py-2 rounded-xl bg-white text-[#FBC02D] font-bold border border-orange-100 hover:bg-orange-50 transition-all text-sm flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver al Aula
                </a>
            @elseif(request('from_graduates'))
                <a href="{{ route('graduates.index') }}" class="px-6 py-2 rounded-xl bg-white text-blue-600 font-bold border border-blue-100 hover:bg-blue-50 transition-all text-sm flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver a Egresados
                </a>
            @else
                <a href="{{ route('students.index') }}" class="px-6 py-2 rounded-xl bg-white text-gray-500 font-bold border border-gray-100 hover:bg-gray-50 transition-all text-sm">Volver</a>
            @endif
            <a href="{{ route('students.edit', $student->id) }}" class="px-6 py-2 rounded-xl bg-[#1A237E] text-white font-bold hover:bg-[#1A237E]/90 transition-all text-sm shadow-sm">Editar Datos</a>
        </div>
    </div>

    <!-- main info -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- left col: Photo & Quick Status -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex flex-col items-center">
                <div class="w-24 h-24 rounded-full bg-gray-100 mb-4 flex items-center justify-center border-4 border-[#c56c39]/10">
                    <span class="text-3xl font-bold text-[#FBC02D]">{{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}</span>
                </div>
                <h4 class="font-extrabold text-[#1A237E] text-center">{{ $student->first_name }} {{ $student->last_name }}</h4>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">C.I. {{ $student->cedula ?? 'S/C' }}</p>
                
                <div class="mt-4">
                    @if($student->status === 'Activo')
                        <span class="px-4 py-1.5 bg-green-100 text-green-600 rounded-full text-[10px] font-black uppercase tracking-widest italic border border-green-200">✓ Activo</span>
                    @elseif($student->status === 'Egresado')
                        <span class="px-4 py-1.5 bg-blue-100 text-blue-600 rounded-full text-[10px] font-black uppercase tracking-widest italic border border-blue-200">🎓 Egresado</span>
                    @else
                        <span class="px-4 py-1.5 bg-orange-50 text-orange-500 rounded-full text-[10px] font-black uppercase tracking-widest italic border border-orange-100">⚠ Retirado</span>
                    @endif
                </div>
            </div>

            <!-- Academic History -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <h5 class="text-xs font-bold text-[#FBC02D] uppercase tracking-widest mb-4 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Trayectoria Académica
                </h5>
                <div class="space-y-4">
                    @forelse($student->enrollments->sortByDesc('school_year') as $history)
                        <div class="p-4 rounded-2xl bg-gray-50/50 border border-gray-100 relative group transition-all hover:bg-white">
                            <div class="text-[10px] font-black text-[#FBC02D] uppercase tracking-tighter mb-1">{{ $history->school_year }}</div>
                            <div class="font-bold text-[#1A237E] text-sm">{{ $history->section->grade->name }}</div>
                            <div class="text-xs font-semibold text-gray-400">Sección "{{ $history->section->name }}" ({{ $history->section->shift }})</div>
                            <div class="absolute top-4 right-4 flex flex-col items-end space-y-2">
                                <div class="text-[9px] font-black uppercase px-2 py-0.5 rounded-full {{ $history->status == 'Promovido' ? 'bg-green-100 text-green-500' : ($history->status == 'Inscrito' ? 'bg-blue-50 text-blue-400' : 'bg-orange-100 text-orange-500') }}">
                                    {{ $history->status }}
                                </div>
                                <a href="{{ route('reports.boletin', $history->id) }}" target="_blank" class="text-[8px] font-bold text-[#1A237E] hover:text-[#FBC02D] border border-gray-200 px-2 py-1 rounded-lg bg-white shadow-sm transition-all flex items-center">
                                    <svg class="w-2.5 h-2.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Ver Boletín
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs font-bold text-gray-300 text-center py-4 italic">Sin historial académico registrado.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h5 class="text-xs font-bold text-[#FBC02D] uppercase tracking-widest mb-4">Tallas y Salud</h5>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400 font-semibold">Camisa</span>
                        <span class="text-gray-700 font-bold">{{ $student->shirt_size ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400 font-semibold">Pantalón</span>
                        <span class="text-gray-700 font-bold">{{ $student->pants_size ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400 font-semibold">Calzado</span>
                        <span class="text-gray-700 font-bold">{{ $student->shoes_size ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between text-sm pt-2 border-t border-gray-50">
                        <span class="text-gray-400 font-semibold">Peso (kg)</span>
                        <span class="text-gray-700 font-bold">{{ $student->weight ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400 font-semibold">Altura (m)</span>
                        <span class="text-gray-700 font-bold">{{ $student->height ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- right col: Extended Info -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h5 class="text-xs font-bold text-[#FBC02D] uppercase tracking-[0.2em] mb-6 flex items-center">
                    <span class="w-6 h-6 rounded-lg bg-[#FBC02D]/10 flex items-center justify-center mr-3 text-[10px]">1</span>
                    Detalles Personales
                </h5>
                <div class="grid grid-cols-2 gap-y-6">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Fecha de Nacimiento</p>
                        <p class="font-bold text-gray-700">{{ \Carbon\Carbon::parse($student->birth_date)->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Género</p>
                        <p class="font-bold text-gray-700">{{ $student->gender == 'M' ? 'Masculino' : 'Femenino' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Lugar de Nacimiento</p>
                        <p class="font-bold text-gray-700">{{ $student->birth_place_locality }}, {{ $student->birth_place_state }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Vive con</p>
                        <p class="font-bold text-gray-700">{{ $student->lives_with ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h5 class="text-xs font-bold text-[#FBC02D] uppercase tracking-[0.2em] mb-6 flex items-center">
                    <span class="w-6 h-6 rounded-lg bg-[#FBC02D]/10 flex items-center justify-center mr-3 text-[10px]">2</span>
                    Ubicación y Vivienda
                </h5>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Municipio / Parroquia</p>
                            <p class="font-bold text-gray-700">{{ $student->address?->municipality }} / {{ $student->address?->parish }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nro Casa/Apto</p>
                            <p class="font-bold text-gray-700">{{ $student->address?->house_apt_number }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Dirección Exacta</p>
                        <p class="font-bold text-gray-700">{{ $student->address?->sector }}</p>
                    </div>
                </div>
            </div>

            <!-- Representative data -->
            @if($student->representative)
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-6">
                <h5 class="text-xs font-bold text-[#FBC02D] uppercase tracking-[0.2em] mb-6 flex items-center">
                    <span class="w-6 h-6 rounded-lg bg-[#FBC02D]/10 flex items-center justify-center mr-3 text-[10px]">3</span>
                    Información del Representante
                </h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-6">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nombre y Parentesco</p>
                        <p class="font-bold text-gray-700">
                            {{ $student->representative->first_name }} 
                            {{ $student->representative->middle_name }} 
                            {{ $student->representative->last_name }} 
                            {{ $student->representative->second_last_name }} 
                            ({{ $student->representative->relationship }})
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Cédula</p>
                        <p class="font-bold text-gray-700">{{ $student->representative->cedula }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Contacto Principal (WhatsApp)</p>
                        <p class="font-bold text-gray-700">{{ $student->representative->phone_whatsapp ?? 'No registrado' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Teléfono Local</p>
                        <p class="font-bold text-gray-700">{{ $student->representative->phone_local ?? 'No registrado' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Correo Electrónico</p>
                        <p class="font-bold text-gray-700">{{ $student->representative->email ?? 'No registrado' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Facebook</p>
                        <p class="font-bold text-gray-700">{{ $student->representative->facebook ?? 'No registrado' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Ocupación / Cargo</p>
                        <p class="font-bold text-gray-700">{{ $student->representative->job_title ?? 'No registrado' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Lugar de Trabajo / Dirección</p>
                        <p class="font-bold text-gray-700">{{ $student->representative->workplace_address ?? 'No registrado' }}</p>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-orange-50 p-8 rounded-xl border border-orange-100 mb-6 text-center">
                <p class="text-orange-600 font-bold text-sm">Este estudiante no tiene un representante vinculado.</p>
                <a href="{{ route('students.edit', $student->id) }}" class="text-xs font-black text-orange-700 uppercase tracking-widest underline mt-2 inline-block">Vincular ahora</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
