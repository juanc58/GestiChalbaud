@extends('dashboard')

@section('content')
<div class="max-w-2xl mx-auto py-12">
    <div class="bg-white p-12 rounded-[3rem] shadow-sm border border-gray-100 text-center space-y-8">
        <div class="w-24 h-24 bg-blue-50 rounded-xl flex items-center justify-center mx-auto text-blue-500">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        </div>
        
        <div class="space-y-4">
            <h3 class="text-3xl font-black text-[#1A237E]">Récord Académico No Disponible</h3>
            <p class="text-gray-500 font-bold text-lg leading-relaxed">
                No se encuentran registros escolares para <strong>{{ $student->first_name }} {{ $student->last_name }}</strong> en el sistema administrativo todavía.
            </p>
            <div class="p-6 bg-amber-50 rounded-2xl border border-amber-100">
                <p class="text-amber-800 font-bold text-sm">
                    Para que las notas y boletines estén disponibles, la institución debe completar el proceso de inscripción asignando una sección al estudiante.
                </p>
            </div>
            <p class="text-blue-600 font-black text-sm uppercase tracking-widest">
                Por favor contactar con la Institución
            </p>
        </div>

        <div class="pt-4">
            <a href="{{ route('representative.students.index') }}" class="inline-block px-10 py-5 bg-[#1A237E] text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-[#1A237E]/20 hover:scale-[1.02] transition-all">
                Volver a Mis Hijos
            </a>
        </div>
    </div>
</div>
@endsection
