@extends('dashboard')

@section('content')
<div class="space-y-8">
        <div class="flex items-end space-x-6">
            <div>
                <h3 class="text-3xl font-extrabold text-[#032e5e]">Aulas y Asignaciones</h3>
                <p class="text-gray-500 font-semibold mt-1">Gestiona la matrícula escolar por periodos.</p>
            </div>
            
            <form action="{{ route('sections.index') }}" method="GET" class="flex items-end space-x-3 mb-1">
                <div class="bg-white px-5 py-2 rounded-[2rem] shadow-sm border border-gray-100 flex items-center space-x-3">
                    <span class="text-[10px] font-black text-[#c56c39] uppercase tracking-widest whitespace-nowrap">Ver Periodo:</span>
                    <select name="school_year" onchange="this.form.submit()" class="bg-transparent border-none outline-none font-black text-[#032e5e] text-sm focus:ring-0 cursor-pointer">
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        <a href="{{ route('sections.create') }}" class="bg-[#032e5e] text-white px-8 py-4 rounded-2xl font-bold hover:bg-[#032e5e]/90 transition-all shadow-xl shadow-[#032e5e]/20 flex items-center mb-1">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nueva Sección (Aula)
        </a>

    @if(session('success'))
    <div class="bg-green-100 border border-green-200 text-green-700 px-6 py-4 rounded-xl font-bold">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 gap-8">
        @forelse($grades as $grade)
            @if($grade->sections->count() > 0)
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-5 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                    <h4 class="text-lg font-bold text-[#c56c39] uppercase tracking-widest">{{ $grade->name }}</h4>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $grade->sections->count() }} Secciones</span>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($grade->sections as $section)
                        <div class="border-2 border-gray-100 rounded-[2rem] p-6 hover:border-[#032e5e]/30 transition-all group flex flex-col justify-between h-48 relative overflow-hidden">
                            <!-- Background accent -->
                            <div class="absolute -right-12 -top-12 w-32 h-32 bg-gray-50 rounded-full group-hover:bg-[#032e5e]/5 transition-colors z-0"></div>
                            
                            <div class="relative z-10">
                                <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-[10px] font-bold uppercase tracking-widest mb-4 inline-block">{{ $section->shift }}</span>
                                <h5 class="text-3xl font-extrabold text-[#032e5e] mb-1">Sección "{{ $section->name }}"</h5>
                                <p class="text-sm font-semibold text-gray-400">Matrícula ({{ $selectedYear }}): {{ $section->enrollments->count() }}</p>
                            </div>
                            
                            <div class="relative z-10 mt-4 flex justify-between items-center border-t border-gray-100 pt-4">
                                <a href="{{ route('sections.show', $section->id) }}" class="text-[#c56c39] font-bold text-sm uppercase tracking-widest hover:text-[#032e5e] transition-colors flex items-center">
                                    Ver Aula 
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        @empty
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-12 text-center">
                <p class="text-gray-400 font-bold mb-4">No hay secciones registradas en el sistema.</p>
                <a href="{{ route('sections.create') }}" class="text-[#c56c39] font-bold uppercase tracking-widest hover:underline">Crear primera sección</a>
            </div>
        @endforelse
    </div>
</div>
@endsection
