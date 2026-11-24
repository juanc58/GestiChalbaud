@extends('dashboard')

@section('content')
<div class="space-y-6">
    <!-- Encabezado y Pestañas del Módulo de Asistencia -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="min-w-0">
            <h3 class="text-2xl font-extrabold text-[#1A237E]">Control de Asistencia de Estudiantes</h3>
            <p class="text-sm text-gray-500 font-semibold mt-1">
                Registro diario de asistencia, hora de entrada y hora de salida por sección ({{ $currentYear }}).
            </p>
        </div>
        <div class="inline-flex items-center gap-1.5 bg-white p-1.5 rounded-2xl border border-gray-200 shadow-sm self-start lg:self-auto flex-shrink-0">
            <a href="{{ route('attendance.students') }}"
               class="px-4 py-2.5 rounded-xl text-xs font-extrabold uppercase tracking-wider bg-[#1A237E] text-white shadow-sm whitespace-nowrap">
                Alumnos
            </a>
            <a href="{{ route('attendance.teachers') }}"
               class="px-4 py-2.5 rounded-xl text-xs font-extrabold uppercase tracking-wider text-gray-500 hover:text-[#1A237E] hover:bg-gray-50 transition-all whitespace-nowrap">
                {{ Auth::user()->isDocente() ? 'Mi Asistencia (Lectura)' : 'Personal Docente' }}
            </a>
        </div>
    </div>

    <!-- Barra de Filtros: Sección y Fecha -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <form action="{{ route('attendance.students') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Aula / Sección</label>
                @if(Auth::user()->isDocente())
                    <input type="hidden" name="section_id" value="{{ $selectedSection?->id }}">
                    <div class="px-5 py-3 rounded-xl bg-gray-50 border border-gray-200 font-bold text-[#1A237E] text-sm">
                        {{ $selectedSection ? $selectedSection->grade->name . ' "' . $selectedSection->name . '" (' . $selectedSection->shift . ')' : 'Sin aula asignada' }}
                    </div>
                @else
                    <select name="section_id" onchange="this.form.submit()"
                            class="w-full px-5 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-[#1A237E] outline-none font-bold text-[#1A237E] text-sm">
                        @foreach($sections as $sec)
                            <option value="{{ $sec->id }}" {{ ($selectedSection?->id == $sec->id) ? 'selected' : '' }}>
                                {{ $sec->grade->name }} "{{ $sec->name }}" — Turno {{ $sec->shift }}
                            </option>
                        @endforeach
                    </select>
                @endif
            </div>

            <div>
                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Fecha de Asistencia (Día-Mes-Año)</label>
                <x-date-input-es name="date" :value="$date" :autoSubmit="true"
                       class="w-full px-5 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-[#1A237E] outline-none font-bold text-[#1A237E] text-sm" />
            </div>

            <div class="flex gap-2">
                <button type="submit" class="px-6 py-3 bg-[#1A237E] text-white rounded-xl font-bold text-sm hover:bg-[#1A237E]/90 transition-all shadow-sm flex-1">
                    Consultar Fecha
                </button>
                <a href="{{ route('attendance.students', ['section_id' => $selectedSection?->id, 'date' => now()->format('Y-m-d')]) }}"
                   class="px-4 py-3 bg-gray-100 text-gray-600 rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-gray-200 transition-all flex items-center">
                    Hoy
                </a>
            </div>
        </form>
    </div>

    @if(!$selectedSection)
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-8 text-center">
            <h4 class="text-lg font-extrabold text-amber-900">No tienes un aula asignada en el periodo {{ $currentYear }}</h4>
            <p class="text-sm text-amber-700 font-semibold mt-2">Solicita a la Dirección o Secretaría que vincule tu ficha docente a una sección para registrar asistencia de alumnos.</p>
        </div>
    @else
        <!-- Resumen del Día -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Presentes</p>
                    <p class="text-2xl font-black text-[#1A237E] tabular-nums">{{ $stats['Presente'] }}</p>
                </div>
                <span class="w-10 h-10 rounded-xl bg-indigo-50 text-[#1A237E] flex items-center justify-center font-black">P</span>
            </div>
            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Ausentes</p>
                    <p class="text-2xl font-black text-[#D32F2F] tabular-nums">{{ $stats['Ausente'] }}</p>
                </div>
                <span class="w-10 h-10 rounded-xl bg-red-50 text-[#D32F2F] flex items-center justify-center font-black">A</span>
            </div>
            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Justificados</p>
                    <p class="text-2xl font-black text-gray-800 tabular-nums">{{ $stats['Justificado'] }}</p>
                </div>
                <span class="w-10 h-10 rounded-xl bg-gray-100 text-gray-700 flex items-center justify-center font-black">J</span>
            </div>
            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Tardanzas</p>
                    <p class="text-2xl font-black text-gray-800 tabular-nums">{{ $stats['Tardanza'] }}</p>
                </div>
                <span class="w-10 h-10 rounded-xl bg-gray-100 text-gray-700 flex items-center justify-center font-black">T</span>
            </div>
        </div>

        <!-- Formulario de Asistencia -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @php
                $defaultIn  = $selectedSection->shift === 'Tarde' ? '12:30' : '07:00';
                $defaultOut = $selectedSection->shift === 'Tarde' ? '17:30' : '12:00';
            @endphp

            <div class="px-6 py-4 bg-gray-50/70 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h4 class="font-extrabold text-[#1A237E] text-base">
                        {{ $selectedSection->grade->name }} "{{ $selectedSection->name }}" — {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                    </h4>
                    <p class="text-xs text-gray-500 font-semibold">Turno {{ $selectedSection->shift }} · {{ $enrollments->count() }} estudiante(s) inscritos</p>
                </div>
                @if($enrollments->isNotEmpty())
                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" onclick="markAllStudents('Presente')"
                            class="px-3.5 py-2 rounded-xl bg-indigo-50 text-[#1A237E] border border-indigo-200 text-xs font-extrabold hover:bg-indigo-100 transition-all inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Todos Presentes
                    </button>
                    <button type="button" onclick="fillStudentShiftTimes('{{ $defaultIn }}', '{{ $defaultOut }}')"
                            class="px-3.5 py-2 rounded-xl bg-white text-gray-700 border border-gray-200 text-xs font-extrabold hover:bg-gray-50 transition-all inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Horario Turno ({{ $defaultIn }} - {{ $defaultOut }})
                    </button>
                </div>
                @endif
            </div>

            @if($errors->any())
                <div class="m-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-xs font-bold">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($enrollments->isEmpty())
                <div class="p-12 text-center text-gray-400 font-bold">
                    No hay estudiantes inscritos en esta aula para el año escolar {{ $currentYear }}.
                </div>
            @else
                <form action="{{ route('attendance.students.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="section_id" value="{{ $selectedSection->id }}">
                    <input type="hidden" name="date" value="{{ $date }}">

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50 border-b border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                    <th class="px-6 py-4">Estudiante</th>
                                    <th class="px-4 py-4 w-44">Condición</th>
                                    <th class="px-4 py-4 w-36 text-center">Hora Entrada</th>
                                    <th class="px-4 py-4 w-36 text-center">Hora Salida</th>
                                    <th class="px-6 py-4">Observaciones / Justificación</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($enrollments as $enrollment)
                                    @php
                                        $st = $enrollment->student;
                                        $att = $attendanceMap->get($st->id);
                                        $curStatus = old("attendance.{$st->id}.status", $att?->status ?? 'Presente');
                                        $curIn     = old("attendance.{$st->id}.check_in_time", $att?->check_in_time ? substr($att->check_in_time, 0, 5) : $defaultIn);
                                        $curOut    = old("attendance.{$st->id}.check_out_time", $att?->check_out_time ? substr($att->check_out_time, 0, 5) : $defaultOut);
                                        $curObs    = old("attendance.{$st->id}.observations", $att?->observations ?? '');
                                    @endphp
                                    <tr class="hover:bg-gray-50/60 transition-colors">
                                        <td class="px-6 py-3.5">
                                            <div class="font-extrabold text-[#1A237E] text-sm">{{ $st->last_name }} {{ $st->second_last_name }}, {{ $st->first_name }}</div>
                                            <div class="text-[10px] font-bold text-gray-400 uppercase">
                                                C.I.: {{ $st->cedula ?? 'S/C' }}
                                                @if($att)
                                                    · <span class="text-[#1A237E]">Registrado</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-3.5">
                                            <select name="attendance[{{ $st->id }}][status]"
                                                    onchange="handleStudentStatusChange(this, '{{ $st->id }}', '{{ $defaultIn }}', '{{ $defaultOut }}')"
                                                    class="student-status-select w-full px-3 py-2.5 rounded-xl bg-gray-50 border border-gray-200 font-bold text-xs text-[#1A237E] outline-none focus:border-[#1A237E]">
                                                @foreach(['Presente', 'Ausente', 'Justificado', 'Tardanza'] as $opt)
                                                    <option value="{{ $opt }}" {{ $curStatus === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-4 py-3.5 text-center">
                                            <input type="time" id="st_in_{{ $st->id }}" name="attendance[{{ $st->id }}][check_in_time]"
                                                   value="{{ in_array($curStatus, ['Ausente']) && !$att?->check_in_time ? '' : $curIn }}"
                                                   class="student-in-time px-3 py-2 rounded-xl bg-gray-50 border border-gray-200 font-mono tabular-nums font-bold text-xs text-gray-800 outline-none focus:border-[#1A237E]">
                                        </td>
                                        <td class="px-4 py-3.5 text-center">
                                            <input type="time" id="st_out_{{ $st->id }}" name="attendance[{{ $st->id }}][check_out_time]"
                                                   value="{{ in_array($curStatus, ['Ausente']) && !$att?->check_out_time ? '' : $curOut }}"
                                                   class="student-out-time px-3 py-2 rounded-xl bg-gray-50 border border-gray-200 font-mono tabular-nums font-bold text-xs text-gray-800 outline-none focus:border-[#1A237E]">
                                        </td>
                                        <td class="px-6 py-3.5">
                                            <input type="text" name="attendance[{{ $st->id }}][observations]" value="{{ $curObs }}"
                                                   placeholder="Opcional: nota, reposo médico, retiro temprano..."
                                                   class="w-full px-4 py-2 rounded-xl bg-gray-50 border border-gray-200 font-semibold text-xs text-gray-700 outline-none focus:border-[#1A237E]">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-6 bg-gray-50/60 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="px-10 py-4 bg-[#1A237E] text-white rounded-2xl font-extrabold text-sm hover:bg-[#1A237E]/90 transition-all shadow-lg shadow-[#1A237E]/20 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Guardar Asistencia de Alumnos
                        </button>
                    </div>
                </form>
            @endif
        </div>
    @endif
</div>

<script>
function markAllStudents(status) {
    document.querySelectorAll('.student-status-select').forEach(sel => {
        sel.value = status;
    });
}
function fillStudentShiftTimes(inTime, outTime) {
    document.querySelectorAll('.student-status-select').forEach(sel => {
        const row = sel.closest('tr');
        if (sel.value === 'Presente' || sel.value === 'Tardanza') {
            const inInput = row.querySelector('.student-in-time');
            const outInput = row.querySelector('.student-out-time');
            if (inInput) inInput.value = inTime;
            if (outInput) outInput.value = outTime;
        }
    });
}
function handleStudentStatusChange(sel, studentId, defIn, defOut) {
    const inEl = document.getElementById('st_in_' + studentId);
    const outEl = document.getElementById('st_out_' + studentId);
    if (sel.value === 'Ausente') {
        if (inEl) inEl.value = '';
        if (outEl) outEl.value = '';
    } else if (sel.value === 'Presente' || sel.value === 'Tardanza') {
        if (inEl && !inEl.value) inEl.value = defIn;
        if (outEl && !outEl.value) outEl.value = defOut;
    }
}
</script>
@endsection

