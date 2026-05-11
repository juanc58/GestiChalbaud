@extends('dashboard')

@section('content')
<div class="max-w-full space-y-6 space-y-8">
    <div class="flex justify-between items-center bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div>
            <h3 class="text-2xl font-extrabold text-[#1A237E]">Historial Académico</h3>
            <p class="text-gray-500 font-semibold">Notas y boletines de {{ $student->first_name }}.</p>
        </div>
        
        <div class="flex items-center gap-4">
            <form action="{{ route('representative.students.grades', $student->id) }}" method="GET" id="yearFilterForm" class="flex items-center gap-3">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Filtrar por Año:</label>
                <select name="school_year" onchange="document.getElementById('yearFilterForm').submit()" 
                    class="px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl font-bold text-[#1A237E] text-xs outline-none focus:border-blue-200 transition-all appearance-none cursor-pointer pr-10 relative">
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                            Período: {{ $year }}
                        </option>
                    @endforeach
                </select>
            </form>
            <a href="{{ route('representative.students.index') }}" class="px-6 py-2 rounded-xl bg-gray-50 text-gray-500 font-bold hover:bg-gray-100 transition-all text-xs uppercase tracking-widest border border-gray-100">Volver</a>
        </div>
    </div>

    @if($enrollment)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                <div>
                    <span class="text-[10px] font-black text-[#FBC02D] uppercase tracking-[0.2em]">{{ $enrollment->school_year }}</span>
                    <h4 class="text-xl font-extrabold text-[#1A237E]">{{ $enrollment->section->grade->name }}</h4>
                    <p class="text-xs font-bold text-gray-400">Sección "{{ $enrollment->section->name }}" ({{ $enrollment->section->shift }})</p>
                </div>
                <div class="flex flex-col items-end gap-2">
                    <span class="px-4 py-1.5 bg-blue-100 text-blue-600 rounded-full text-[10px] font-black uppercase tracking-widest italic border border-blue-200">
                        {{ $enrollment->status }}
                    </span>
                    <a href="{{ route('reports.boletin', $enrollment->id) }}" target="_blank" class="px-4 py-2 bg-[#1A237E] text-white rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-[#1A237E]/90 transition-all flex items-center shadow-lg shadow-[#1A237E]/10">
                        <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Descargar Boletín (PDF)
                    </a>
                </div>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 gap-8">
                    @forelse($enrollment->assessments->groupBy('subject_id') as $subjectId => $assessments)
                        @php $subject = $assessments->first()->subject; @endphp
                        <div class="bg-gray-50/50 rounded-xl border border-gray-100 overflow-hidden group hover:border-blue-100 transition-all">
                            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-white group-hover:bg-blue-50/30 transition-all">
                                <div>
                                    <h5 class="font-extrabold text-[#1A237E] text-sm">{{ $subject->name }}</h5>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">Calificaciones por Momento</p>
                                </div>
                            </div>
                            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                                @for($term = 1; $term <= 3; $term++)
                                    @php $assessment = $assessments->where('term', $term)->first(); @endphp
                                    <div class="p-4 rounded-2xl {{ $assessment ? 'bg-white shadow-sm border-blue-50' : 'bg-gray-100/30 border-dashed border-gray-200' }} border-2 flex flex-col items-center group/moment hover:scale-105 transition-all">
                                        <span class="text-[9px] font-black text-gray-400 uppercase mb-2">
                                            {{ $term == 1 ? 'Primer' : ($term == 2 ? 'Segundo' : 'Tercer') }} Momento
                                        </span>
                                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl font-black {{ $assessment ? 'text-[#FBC02D] bg-orange-50/50' : 'text-gray-300' }}">
                                            {{ $assessment->score ?? '-' }}
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <p class="text-gray-400 font-bold text-sm italic">No hay calificaciones registradas aún para el periodo {{ $selectedYear }}.</p>
                        </div>
                    @endforelse
                </div>

                @if($enrollment->recommendations)
                    <div class="mt-8 p-6 bg-blue-50/30 rounded-2xl border border-blue-100">
                        <h6 class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-2">Observaciones Generales</h6>
                        <p class="text-gray-600 text-sm leading-relaxed font-medium italic">"{{ $enrollment->recommendations }}"</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
