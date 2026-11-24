@extends('dashboard')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <h3 class="text-3xl font-extrabold text-[#1A237E]">Aulas y Secciones</h3>
            <p class="text-gray-500 font-semibold mt-1">Gestiona la matrícula escolar por períodos.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <form action="{{ route('sections.index') }}" method="GET" class="flex items-center">
                <div class="bg-white px-4 py-2.5 rounded-xl shadow-sm border border-gray-200 flex items-center space-x-2">
                    <span class="text-[10px] font-black text-[#FBC02D] uppercase tracking-widest whitespace-nowrap">Período:</span>
                    <select name="school_year" onchange="this.form.submit()" class="bg-transparent border-none outline-none font-black text-[#1A237E] text-sm focus:ring-0 cursor-pointer">
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
            <a href="{{ route('sections.create') }}" class="bg-[#1A237E] text-white px-6 py-3 rounded-xl font-bold hover:bg-[#1A237E]/90 transition-all shadow-sm flex items-center whitespace-nowrap text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nueva Sección (Aula)
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-200 text-green-700 px-6 py-4 rounded-xl font-bold">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 gap-8">
        @forelse($grades as $grade)
            @if($grade->sections->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-8 py-5 border-b border-gray-100 bg-gray-50/60 flex justify-between items-center">
                    <h4 class="text-lg font-extrabold text-[#1A237E] uppercase tracking-widest">{{ $grade->name }}</h4>
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">{{ $grade->sections->count() }} Secciones</span>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($grade->sections as $section)
                        <div class="border border-gray-200 bg-white rounded-xl p-6 hover:border-[#1A237E]/40 transition-all group flex flex-col justify-between min-h-[12.5rem]">
                            <div>
                                <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-[10px] font-extrabold uppercase tracking-widest mb-3 inline-block">{{ $section->shift }}</span>
                                <h5 class="text-2xl font-extrabold text-[#1A237E] mb-1">Sección "{{ $section->name }}"</h5>
                                <p class="text-sm font-semibold text-gray-500 tabular-nums">Matrícula ({{ $selectedYear }}): {{ $section->enrollments->count() }}</p>
                            </div>
                            
                            <div class="mt-4 flex flex-wrap items-center justify-between gap-2 border-t border-gray-100 pt-4">
                                <a href="{{ route('sections.show', $section->id) }}" class="text-[#1A237E] font-bold text-xs uppercase tracking-wider hover:underline transition-colors inline-flex items-center whitespace-nowrap">
                                    Ver Aula
                                    <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l7 7m7-7H3"></path></svg>
                                </a>
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('sections.grades', $section->id) }}" class="px-2.5 py-1 rounded-lg bg-indigo-50 text-[#1A237E] text-[11px] font-bold hover:bg-indigo-100 transition-colors whitespace-nowrap">
                                        Notas
                                    </a>
                                    <a href="{{ route('attendance.students', ['section_id' => $section->id]) }}" class="px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 text-[11px] font-bold hover:bg-gray-200 transition-colors whitespace-nowrap">
                                        Asistencia
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <p class="text-gray-500 font-bold mb-4">No hay secciones registradas en el sistema.</p>
                <a href="{{ route('sections.create') }}" class="text-[#1A237E] font-bold uppercase tracking-widest hover:underline">Crear primera sección</a>
            </div>
        @endforelse
    </div>
</div>
@endsection
