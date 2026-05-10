@extends('dashboard')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50 flex flex-col md:flex-row justify-between items-center bg-gray-50/50 gap-4">
        <div>
            <h3 class="text-2xl font-extrabold text-[#032e5e]">Carga de Calificaciones</h3>
            <p class="text-gray-500 font-semibold mt-1">Gestión académica: Aula "{{ $section->name }}" - {{ $section->grade->name }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <span class="px-4 py-2 bg-white border border-gray-100 rounded-xl text-xs font-bold text-[#c56c39] shadow-sm">
                Año Escolar: {{ $currentYear }}
            </span>
            <a href="{{ route('sections.show', $section->id) }}" class="px-6 py-2 bg-white text-gray-500 rounded-xl text-xs font-bold border border-gray-100 hover:bg-gray-50 transition-all">
                Volver al Aula
            </a>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="p-6 border-b border-gray-50 bg-white">
        <form action="{{ route('sections.grades', $section->id) }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Seleccionar Materia</label>
                <select name="subject_id" onchange="this.form.submit()" class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/10 outline-none transition-all font-bold text-gray-700 appearance-none">
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ $subjectId == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Seleccionar Momento/Lapso</label>
                <select name="term" onchange="this.form.submit()" class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/10 outline-none transition-all font-bold text-gray-700 appearance-none">
                    <option value="1" {{ $term == 1 ? 'selected' : '' }}>Primer Momento</option>
                    <option value="2" {{ $term == 2 ? 'selected' : '' }}>Segundo Momento</option>
                    <option value="3" {{ $term == 3 ? 'selected' : '' }}>Tercer Momento</option>
                </select>
            </div>
            <div class="flex items-end">
                <p class="text-[11px] text-gray-400 font-semibold mb-3 italic">Nota: Al cambiar la materia o el momento, la lista se actualizará automáticamente.</p>
            </div>
        </form>
    </div>

    <form action="{{ route('sections.grades.store', $section->id) }}" method="POST">
        @csrf
        <input type="hidden" name="subject_id" value="{{ $subjectId }}">
        <input type="hidden" name="term" value="{{ $term }}">

        <div class="overflow-x-auto p-6">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">
                        <th class="px-6 py-4">Estudiante</th>
                        <th class="px-6 py-4 w-32 text-center">Calificación (A-E)</th>
                        <th class="px-6 py-4">Observaciones del Logro</th>
                        <th class="px-6 py-4 text-center">Estatus</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($enrollments as $enrollment)
                    @php 
                        $assessment = $enrollment->assessments->first(); 
                        $score = $assessment ? $assessment->score : '';
                        $obs = $assessment ? $assessment->observations : '';
                    @endphp
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-5">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-xl bg-[#032e5e]/5 flex items-center justify-center text-[#032e5e] font-bold mr-4">
                                    {{ substr($enrollment->student->first_name, 0, 1) }}{{ substr($enrollment->student->last_name, 0, 1) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-800">{{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}</span>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">C.I: {{ $enrollment->student->cedula }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <select name="grades[{{ $enrollment->id }}][score]" 
                                class="w-20 px-3 py-3 rounded-xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/20 focus:bg-white text-center font-bold text-gray-800 outline-none transition-all appearance-none cursor-pointer">
                                <option value="">--</option>
                                @foreach(['A', 'B', 'C', 'D', 'E'] as $letter)
                                    <option value="{{ $letter }}" {{ old('grades.'.$enrollment->id.'.score', $score) == $letter ? 'selected' : '' }}>
                                        {{ $letter }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-6 py-5">
                            <input type="text" name="grades[{{ $enrollment->id }}][observations]" value="{{ old('grades.'.$enrollment->id.'.observations', $obs) }}" placeholder="Describe el desempeño..." 
                                class="w-full px-4 py-3 rounded-xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/10 focus:bg-white text-sm font-semibold text-gray-600 outline-none transition-all">
                        </td>
                        <td class="px-6 py-5 text-center">
                            @if($assessment)
                                <span class="text-[10px] font-bold text-green-500 uppercase tracking-widest bg-green-50 px-3 py-1 rounded-full">Cargado</span>
                            @else
                                <span class="text-[10px] font-bold text-gray-300 uppercase tracking-widest bg-gray-50 px-3 py-1 rounded-full">Pendiente</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-8 bg-gray-50/50 border-t border-gray-50 flex justify-end">
            <button type="submit" class="bg-[#032e5e] text-white px-12 py-4 rounded-2xl font-extrabold text-sm hover:bg-[#032e5e]/90 transition-all shadow-xl shadow-[#032e5e]/20 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                Guardar Todas las Notas
            </button>
        </div>
    </form>
</div>
@endsection
