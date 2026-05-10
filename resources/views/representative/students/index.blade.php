@extends('dashboard')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <div class="flex justify-between items-end">
        <div>
            <h3 class="text-2xl font-extrabold text-[#032e5e]">Mis Representados</h3>
            <p class="text-gray-500 font-semibold">Gestiona la información académica de tus hijos.</p>
        </div>
        <a href="{{ route('representative.students.create') }}" class="px-6 py-3 bg-[#032e5e] text-white rounded-2xl font-bold flex items-center shadow-lg shadow-[#032e5e]/20 hover:bg-[#032e5e]/90 transition-all text-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Registrar Nuevo Hijo(a)
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($students as $student)
            <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100 flex flex-col items-center text-center group hover:shadow-xl hover:shadow-primary/5 transition-all">
                <div class="w-20 h-20 rounded-full bg-blue-50 flex items-center justify-center text-[#032e5e] mb-4 border-2 border-blue-100 group-hover:scale-110 transition-transform">
                    <span class="text-2xl font-black uppercase">{{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}</span>
                </div>
                <h4 class="font-extrabold text-[#032e5e] text-lg">{{ $student->first_name }} {{ $student->last_name }}</h4>
                
                @php $latestEnrollment = $student->enrollments->first(); @endphp
                @if($latestEnrollment)
                    <div class="mt-2 text-xs font-bold text-[#c56c39] uppercase tracking-widest bg-orange-50 px-3 py-1 rounded-full border border-orange-100">
                        {{ $latestEnrollment->section->grade->name }} - Sección "{{ $latestEnrollment->section->name }}"
                    </div>
                @endif

                <div class="mt-8 w-full space-y-3">
                    <div class="flex flex-wrap gap-2 justify-center">
                        <a href="{{ route('representative.students.edit', $student->id) }}"
                            class="px-4 py-2 bg-orange-50 text-orange-600 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-orange-100 transition-all flex items-center">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Editar Datos
                        </a>

                        <a href="{{ route('representative.students.grades', $student->id) }}"
                            class="px-4 py-2 bg-blue-50 text-blue-600 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-blue-100 transition-all flex items-center">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Récord Académico
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="md:col-span-2 lg:col-span-3 py-20 text-center bg-white rounded-[3rem] border-2 border-dashed border-gray-100">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <h5 class="text-gray-400 font-bold mb-2">No tienes hijos registrados</h5>
                <p class="text-gray-300 text-sm max-w-xs mx-auto">Comienza registrando a tus hijos para hacer seguimiento a sus notas.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
