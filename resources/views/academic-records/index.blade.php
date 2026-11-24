@extends('dashboard')

@section('content')
<div class="space-y-6">
    <!-- Encabezado y Selector de Vista -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="min-w-0">
            <h3 class="text-2xl font-extrabold text-[#1A237E]">Récord Académico General</h3>
            <p class="text-sm text-gray-500 font-semibold mt-1">
                Consulta integral de calificaciones históricas de estudiantes activos, retirados y egresados de la institución.
            </p>
        </div>

        <div class="inline-flex items-center gap-6 border-b border-gray-200 self-start lg:self-auto flex-shrink-0">
            <a href="{{ route('academic-records.index', ['tab' => 'directory']) }}"
               class="pb-3 -mb-px text-xs uppercase tracking-wider transition-all whitespace-nowrap border-b-[3px] {{ $tab === 'directory' ? 'border-[#1A237E] text-[#1A237E] font-extrabold' : 'border-transparent text-gray-500 hover:text-[#1A237E] font-bold' }}">
                Expedientes y Egresados
            </a>
            <a href="{{ route('academic-records.index', ['tab' => 'matrix']) }}"
               class="pb-3 -mb-px text-xs uppercase tracking-wider transition-all whitespace-nowrap border-b-[3px] {{ $tab === 'matrix' ? 'border-[#1A237E] text-[#1A237E] font-extrabold' : 'border-transparent text-gray-500 hover:text-[#1A237E] font-bold' }}">
                Sábana de Notas por Aula
            </a>
        </div>
    </div>

    @if($tab === 'matrix')
        <!-- ══ PESTAÑA 2: SÁBANA DE CALIFICACIONES POR SECCIÓN ══ -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <form action="{{ route('academic-records.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <input type="hidden" name="tab" value="matrix">

                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Año Escolar</label>
                    <select name="school_year" onchange="this.form.submit()"
                            class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 font-bold text-[#1A237E] text-sm outline-none">
                        @foreach($years as $yr)
                            <option value="{{ $yr }}" {{ ($matrixData['matrixYear'] ?? $currentYear) == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Aula / Sección</label>
                    <select name="section_id" onchange="this.form.submit()"
                            class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 font-bold text-[#1A237E] text-sm outline-none">
                        @foreach($sections as $sec)
                            <option value="{{ $sec->id }}" {{ ($matrixData['matrixSection']?->id == $sec->id) ? 'selected' : '' }}>
                                {{ $sec->grade->name }} "{{ $sec->name }}" ({{ $sec->shift }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Momento a Mostrar</label>
                    @php $mTerm = $matrixData['matrixTerm'] ?? 'final'; @endphp
                    <select name="term" onchange="this.form.submit()"
                            class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 font-bold text-[#1A237E] text-sm outline-none">
                        <option value="final" {{ $mTerm === 'final' ? 'selected' : '' }}>Literal Definitiva</option>
                        <option value="1" {{ $mTerm == '1' ? 'selected' : '' }}>I Momento</option>
                        <option value="2" {{ $mTerm == '2' ? 'selected' : '' }}>II Momento</option>
                        <option value="3" {{ $mTerm == '3' ? 'selected' : '' }}>III Momento</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-3 bg-[#1A237E] text-white rounded-xl font-bold text-sm hover:bg-[#1A237E]/90 transition-all flex-1">
                        Actualizar Sábana
                    </button>
                </div>
            </form>
        </div>

        @if($matrixData && $matrixData['matrixSection'])
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50/70 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h4 class="font-extrabold text-[#1A237E]">
                            Sábana de Notas: {{ $matrixData['matrixSection']->grade->name }} "{{ $matrixData['matrixSection']->name }}" ({{ $matrixData['matrixYear'] }})
                        </h4>
                        <p class="text-xs text-gray-500 font-semibold">
                            {{ $matrixData['enrollments']->count() }} estudiante(s) · {{ $matrixData['subjects']->count() }} áreas de formación
                        </p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-[10px] font-black text-gray-500 uppercase tracking-wider">
                                <th class="px-4 py-3.5 sticky left-0 z-10 bg-gray-50 shadow-[2px_0_6px_-2px_rgba(0,0,0,0.06)]">Estudiante</th>
                                <th class="px-3 py-3.5 text-center">C.I.</th>
                                @foreach($matrixData['subjects'] as $subj)
                                    <th class="px-3 py-3.5 text-center" title="{{ $subj->name }}">
                                        {{ \Illuminate\Support\Str::limit($subj->name, 14) }}
                                    </th>
                                @endforeach
                                <th class="px-3 py-3.5 text-center">Condición</th>
                                <th class="px-4 py-3.5 text-right">Récord</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-xs">
                            @forelse($matrixData['enrollments'] as $enr)
                                <tr class="bg-white hover:bg-gray-50/80 transition-colors">
                                    <td class="px-4 py-3 font-extrabold text-[#1A237E] whitespace-nowrap sticky left-0 bg-inherit shadow-[2px_0_6px_-2px_rgba(0,0,0,0.05)]">
                                        {{ $enr->student->last_name }}, {{ $enr->student->first_name }}
                                    </td>
                                    <td class="px-3 py-3 text-center font-bold text-gray-500 tabular-nums">
                                        {{ $enr->student->cedula ?? 'S/C' }}
                                    </td>
                                    @foreach($matrixData['subjects'] as $subj)
                                        @php
                                            $bySubj = $enr->assessments->where('subject_id', $subj->id);
                                            if ($matrixData['matrixTerm'] === 'final') {
                                                $m1 = $bySubj->firstWhere('term', 1)?->score;
                                                $m2 = $bySubj->firstWhere('term', 2)?->score;
                                                $m3 = $bySubj->firstWhere('term', 3)?->score;
                                                $isClosed = in_array($enr->status, ['Promovido', 'Repitiente'], true);
                                                $val = ($isClosed || ($m1 && $m2 && $m3)) ? ($m3 ?? $m2 ?? $m1) : null;
                                            } else {
                                                $val = $bySubj->firstWhere('term', (int) $matrixData['matrixTerm'])?->score;
                                            }
                                            $scoreClass = match(strtoupper((string) $val)) {
                                                'D', 'E' => 'text-[#D32F2F]',
                                                default  => $val ? 'text-[#1A237E]' : 'text-gray-300',
                                            };
                                        @endphp
                                        <td class="px-3 py-3 text-center font-black {{ $scoreClass }}">
                                            {{ $val ?? '—' }}
                                        </td>
                                    @endforeach
                                    <td class="px-3 py-3 text-center">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-[#1A237E]">
                                            {{ $enr->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right whitespace-nowrap">
                                        <a href="{{ route('academic-records.show', $enr->student_id) }}" class="text-[#1A237E] font-extrabold hover:underline">
                                            Ver Récord →
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ 4 + $matrixData['subjects']->count() }}" class="px-6 py-12 text-center text-gray-400 font-bold">
                                        No hay inscripciones registradas para esta sección en el año {{ $matrixData['matrixYear'] }}.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @else
        <!-- ══ PESTAÑA 1: DIRECTORIO DE EXPEDIENTES Y EGRESADOS ══ -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <form action="{{ route('academic-records.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                <input type="hidden" name="tab" value="directory">

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Estudiante o Egresado</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre, Apellido o Cédula..."
                           class="w-full px-4 py-2.5 rounded-xl bg-gray-50 border border-gray-200 font-semibold text-sm outline-none focus:border-[#1A237E]">
                </div>

                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Condición</label>
                    <select name="status" class="w-full px-4 py-2.5 rounded-xl bg-gray-50 border border-gray-200 font-bold text-sm text-[#1A237E] outline-none">
                        <option value="">Todos (Activos + Egresados)</option>
                        <option value="Activo" {{ request('status') === 'Activo' ? 'selected' : '' }}>Activos</option>
                        <option value="Egresado" {{ request('status') === 'Egresado' ? 'selected' : '' }}>Egresados</option>
                        <option value="Retirado" {{ request('status') === 'Retirado' ? 'selected' : '' }}>Retirados</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Año Escolar / Promoción</label>
                    <select name="school_year" class="w-full px-4 py-2.5 rounded-xl bg-gray-50 border border-gray-200 font-bold text-sm text-[#1A237E] outline-none">
                        <option value="">Todos los Años</option>
                        @foreach($years as $yr)
                            <option value="{{ $yr }}" {{ request('school_year') == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="px-5 py-2.5 bg-[#1A237E] text-white rounded-xl font-bold text-sm hover:bg-[#1A237E]/90 transition-all flex-1">
                        Consultar
                    </button>
                    @if(request()->anyFilled(['search', 'status', 'school_year', 'section_id']))
                        <a href="{{ route('academic-records.index') }}" class="px-3.5 py-2.5 bg-gray-100 text-gray-500 rounded-xl hover:bg-gray-200 flex items-center justify-center" title="Limpiar">✕</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/70 border-b border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            <th class="px-6 py-4">Estudiante / Egresado</th>
                            <th class="px-6 py-4">Estatus Institucional</th>
                            <th class="px-6 py-4">Última Ubicación / Promoción</th>
                            <th class="px-6 py-4 text-center">Periodos Cursados</th>
                            <th class="px-6 py-4 text-center">Evaluaciones</th>
                            <th class="px-6 py-4 text-right">Acciones Académicas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($students as $st)
                            @php
                                $latestEnr = $st->enrollments->first();
                                $totalAssessments = $st->enrollments->sum(fn ($e) => $e->assessments->count());
                                $statusBadge = match($st->status) {
                                    'Activo'   => 'bg-indigo-50 text-[#1A237E] border-indigo-200',
                                    'Egresado' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    default    => 'bg-gray-100 text-gray-600 border-gray-200',
                                };
                            @endphp
                            <tr class="hover:bg-blue-50/20 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-extrabold text-[#1A237E] text-sm">
                                        {{ $st->last_name }} {{ $st->second_last_name }}, {{ $st->first_name }} {{ $st->second_name }}
                                    </div>
                                    <div class="text-xs font-bold text-gray-400">
                                        C.I.: {{ $st->cedula ? 'V-' . $st->cedula : 'S/C' }}
                                        @if($st->representative)
                                            · Rep: {{ $st->representative->full_name }}
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $statusBadge }}">
                                        {{ $st->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-gray-700">
                                    @if($st->status === 'Egresado' && $st->graduate)
                                        Promoción {{ $st->graduate->promotion_year }}
                                    @elseif($latestEnr?->section)
                                        {{ $latestEnr->section->grade->name }} "{{ $latestEnr->section->name }}" ({{ $latestEnr->school_year }})
                                    @else
                                        Sin inscripción registrada
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center font-black text-[#1A237E] text-sm">
                                    {{ $st->enrollments->count() }}
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-gray-600 text-xs">
                                    {{ $totalAssessments }} nota(s)
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <div class="inline-flex flex-wrap justify-end items-center gap-2">
                                        <a href="{{ route('academic-records.show', $st->id) }}"
                                           class="px-3.5 py-2 bg-[#1A237E] text-white rounded-xl text-xs font-bold hover:bg-[#1A237E]/90 transition-all shadow-sm whitespace-nowrap">
                                            Ver Récord
                                        </a>
                                        <a href="{{ route('academic-records.pdf', $st->id) }}"
                                           class="px-3 py-2 bg-[#FBC02D] text-[#1A237E] rounded-xl text-xs font-extrabold hover:bg-[#FBC02D]/90 transition-all whitespace-nowrap"
                                           title="Descargar Récord en PDF">
                                            PDF
                                        </a>
                                        @if($latestEnr)
                                            <a href="{{ route('reports.boletin', $latestEnr->id) }}" target="_blank"
                                               class="px-3 py-2 bg-gray-100 text-gray-700 rounded-xl text-xs font-bold hover:bg-gray-200 transition-all whitespace-nowrap"
                                               title="Abrir último boletín">
                                                Boletín
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400 font-bold">
                                    No se encontraron estudiantes ni egresados con los criterios indicados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-6 border-t border-gray-100 bg-gray-50/40">
                {{ $students->links() }}
            </div>
        </div>
    @endif
</div>
@endsection

