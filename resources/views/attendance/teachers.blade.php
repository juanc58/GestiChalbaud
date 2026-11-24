@extends('dashboard')

@section('content')
<div class="space-y-6">
    <!-- Encabezado y Pestañas -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="min-w-0">
            <h3 class="text-2xl font-extrabold text-[#1A237E]">
                {{ $canEdit ? 'Control de Asistencia del Personal Docente' : 'Mi Historial de Asistencia Docente' }}
            </h3>
            <p class="text-sm text-gray-500 font-semibold mt-1">
                @if($canEdit)
                    Registro oficial de condición diaria, hora de entrada y hora de salida (Exclusivo Dirección y Secretaría).
                @else
                    Consulta en modo de <strong>solo lectura</strong> de tus registros de entrada, salida y asistencia laboral.
                @endif
            </p>
        </div>
        <div class="inline-flex items-center gap-1.5 bg-white p-1.5 rounded-2xl border border-gray-200 shadow-sm self-start lg:self-auto flex-shrink-0">
            <a href="{{ route('attendance.students') }}"
               class="px-4 py-2.5 rounded-xl text-xs font-extrabold uppercase tracking-wider text-gray-500 hover:text-[#1A237E] hover:bg-gray-50 transition-all whitespace-nowrap">
                Alumnos
            </a>
            <a href="{{ route('attendance.teachers') }}"
               class="px-4 py-2.5 rounded-xl text-xs font-extrabold uppercase tracking-wider bg-[#1A237E] text-white shadow-sm whitespace-nowrap">
                {{ $canEdit ? 'Personal Docente' : 'Mi Asistencia (Lectura)' }}
            </a>
        </div>
    </div>

    <!-- Tarjetas de Resumen -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Presentes</p>
            <p class="text-2xl font-black text-[#1A237E] tabular-nums mt-1">{{ $stats['Presente'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Ausentes</p>
            <p class="text-2xl font-black text-[#D32F2F] tabular-nums mt-1">{{ $stats['Ausente'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Justificados</p>
            <p class="text-2xl font-black text-gray-800 tabular-nums mt-1">{{ $stats['Justificado'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Permisos</p>
            <p class="text-2xl font-black text-gray-800 tabular-nums mt-1">{{ $stats['Permiso'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Reposos</p>
            <p class="text-2xl font-black text-gray-800 tabular-nums mt-1">{{ $stats['Reposo'] }}</p>
        </div>
    </div>

    @if(!$canEdit)
        <!-- ══ VISTA EXCLUSIVA DE SOLO LECTURA PARA EL DOCENTE ══ -->
        <div class="p-4 rounded-xl bg-gray-50 border border-gray-200 flex items-center gap-3 text-xs font-bold text-[#1A237E]">
            <svg class="w-5 h-5 text-[#1A237E] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            <span>Modo Solo Lectura: La carga y modificación de asistencia docente es gestionada exclusivamente por el Personal Administrativo y Dirección.</span>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <form action="{{ route('attendance.teachers') }}" method="GET" class="flex flex-col sm:flex-row sm:items-end gap-4">
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Filtrar por Mes</label>
                    <input type="month" name="month" value="{{ $month }}" onchange="this.form.submit()"
                           class="w-full sm:w-auto px-5 py-3 rounded-xl bg-gray-50 border border-gray-200 font-bold text-[#1A237E] text-sm outline-none focus:border-[#1A237E]">
                </div>
                <button type="submit" class="px-6 py-3 bg-[#1A237E] text-white rounded-xl font-bold text-sm hover:bg-[#1A237E]/90 transition-all">
                    Consultar Mes
                </button>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/70 border-b border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            <th class="px-6 py-4">Fecha</th>
                            <th class="px-6 py-4">Condición</th>
                            <th class="px-6 py-4 text-center">Hora de Entrada</th>
                            <th class="px-6 py-4 text-center">Hora de Salida</th>
                            <th class="px-6 py-4">Observaciones</th>
                            <th class="px-6 py-4">Registrado por</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($myAttendances as $att)
                            @php
                                $badgeClass = match($att->status) {
                                    'Presente'    => 'bg-indigo-50 text-[#1A237E] border-indigo-200',
                                    'Ausente'     => 'bg-red-50 text-[#D32F2F] border-red-200',
                                    'Justificado' => 'bg-gray-100 text-gray-700 border-gray-200',
                                    'Permiso'     => 'bg-amber-50 text-amber-800 border-amber-200',
                                    'Reposo'      => 'bg-gray-100 text-gray-700 border-gray-200',
                                    default       => 'bg-gray-50 text-gray-700 border-gray-200',
                                };
                            @endphp
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-6 py-4 font-extrabold text-[#1A237E] text-sm">{{ $att->date->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $badgeClass }}">
                                        {{ $att->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-gray-700 text-sm">
                                    {{ $att->check_in_time ? substr($att->check_in_time, 0, 5) : '—' }}
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-gray-700 text-sm">
                                    {{ $att->check_out_time ? substr($att->check_out_time, 0, 5) : '—' }}
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold text-gray-600">{{ $att->observations ?: '—' }}</td>
                                <td class="px-6 py-4 text-xs font-bold text-gray-400">{{ $att->recorder?->full_name ?? 'Administración' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400 font-bold">
                                    No existen registros de asistencia cargados para este mes.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- ══ VISTA DE LECTURA Y ESCRITURA PARA ADMIN Y PERSONAL (WORKER) ══ -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <form action="{{ route('attendance.teachers') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-widest mb-2 ml-1">Fecha de Jornada (Día-Mes-Año)</label>
                    <x-date-input-es name="date" :value="$date" :autoSubmit="true"
                           class="w-full px-5 py-3 rounded-xl bg-gray-50 border border-gray-200 font-bold text-[#1A237E] text-sm outline-none focus:border-[#1A237E]" />
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-widest mb-2 ml-1">Buscar Docente</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Nombre, Apellido o Cédula..."
                           class="w-full px-5 py-3 rounded-xl bg-gray-50 border border-gray-200 font-semibold text-gray-700 text-sm outline-none focus:border-[#1A237E]">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-3 bg-[#1A237E] text-white rounded-xl font-bold text-sm hover:bg-[#1A237E]/90 transition-all flex-1">
                        Filtrar
                    </button>
                    <a href="{{ route('attendance.teachers', ['date' => now()->format('Y-m-d')]) }}"
                       class="px-4 py-3 bg-gray-100 border border-gray-200 text-gray-700 rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-gray-200 transition-all flex items-center">
                        Hoy
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50/70 border-b border-gray-100 flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                <div>
                    <h4 class="font-extrabold text-[#1A237E] text-base">
                        Nómina Docente Activa — {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                    </h4>
                    <p class="text-xs text-gray-500 font-semibold">{{ $teachers->count() }} docente(s) en plantilla activa</p>
                </div>
                @if($teachers->isNotEmpty())
                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" onclick="markAllTeachers('Presente')"
                            class="px-3.5 py-2 rounded-xl bg-indigo-50 text-[#1A237E] border border-indigo-200 text-xs font-extrabold hover:bg-indigo-100 transition-all inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Todos Presentes
                    </button>
                    <button type="button" onclick="fillTeacherTimes('07:00', '12:00')"
                            class="px-3.5 py-2 rounded-xl bg-white text-gray-700 border border-gray-200 text-xs font-extrabold hover:bg-gray-50 transition-all inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Turno Mañana (07:00 - 12:00)
                    </button>
                    <button type="button" onclick="fillTeacherTimes('12:30', '17:30')"
                            class="px-3.5 py-2 rounded-xl bg-white text-gray-700 border border-gray-200 text-xs font-extrabold hover:bg-gray-50 transition-all inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Turno Tarde (12:30 - 17:30)
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

            @if($teachers->isEmpty())
                <div class="p-12 text-center text-gray-400 font-bold">
                    No se encontraron docentes activos registrados.
                </div>
            @else
                <form action="{{ route('attendance.teachers.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date }}">

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50 border-b border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                    <th class="px-6 py-4">Docente / Aula Asignada</th>
                                    <th class="px-4 py-4 w-44">Condición</th>
                                    <th class="px-4 py-4 w-36 text-center">Hora Entrada</th>
                                    <th class="px-4 py-4 w-36 text-center">Hora Salida</th>
                                    <th class="px-6 py-4">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($teachers as $t)
                                    @php
                                        $att = $attendanceMap->get($t->id);
                                        $assignment = $t->assignments->first();
                                        $shift = $assignment?->section?->shift ?? 'Mañana';
                                        $defIn  = $shift === 'Tarde' ? '12:30' : '07:00';
                                        $defOut = $shift === 'Tarde' ? '17:30' : '12:00';

                                        $curStatus = old("attendance.{$t->id}.status", $att?->status ?? 'Presente');
                                        $curIn     = old("attendance.{$t->id}.check_in_time", $att?->check_in_time ? substr($att->check_in_time, 0, 5) : $defIn);
                                        $curOut    = old("attendance.{$t->id}.check_out_time", $att?->check_out_time ? substr($att->check_out_time, 0, 5) : $defOut);
                                        $curObs    = old("attendance.{$t->id}.observations", $att?->observations ?? '');
                                    @endphp
                                    <tr class="hover:bg-gray-50/60 transition-colors">
                                        <td class="px-6 py-3.5">
                                            <div class="font-extrabold text-[#1A237E] text-sm">{{ $t->user?->full_name ?? ($t->last_name . ', ' . $t->first_name) }}</div>
                                            <div class="text-[10px] font-bold text-gray-400 uppercase">
                                                C.I.: V-{{ $t->cedula }}
                                                @if($assignment?->section)
                                                    · {{ $assignment->section->grade->name }} "{{ $assignment->section->name }}" ({{ $assignment->section->shift }})
                                                @else
                                                    · Sin aula asignada
                                                @endif
                                                @if($att)
                                                    · <span class="text-[#1A237E]">Guardado</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-3.5">
                                            <select name="attendance[{{ $t->id }}][status]"
                                                    onchange="handleTeacherStatusChange(this, '{{ $t->id }}', '{{ $defIn }}', '{{ $defOut }}')"
                                                    class="teacher-status-select w-full px-3 py-2.5 rounded-xl bg-gray-50 border border-gray-200 font-bold text-xs text-[#1A237E] outline-none focus:border-[#1A237E]">
                                                @foreach(['Presente', 'Ausente', 'Justificado', 'Permiso', 'Reposo'] as $opt)
                                                    <option value="{{ $opt }}" {{ $curStatus === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-4 py-3.5 text-center">
                                            <input type="time" id="tc_in_{{ $t->id }}" name="attendance[{{ $t->id }}][check_in_time]"
                                                   value="{{ $curStatus !== 'Presente' && !$att?->check_in_time ? '' : $curIn }}"
                                                   class="teacher-in-time px-3 py-2 rounded-xl bg-gray-50 border border-gray-200 font-mono tabular-nums font-bold text-xs text-gray-800 outline-none focus:border-[#1A237E]">
                                        </td>
                                        <td class="px-4 py-3.5 text-center">
                                            <input type="time" id="tc_out_{{ $t->id }}" name="attendance[{{ $t->id }}][check_out_time]"
                                                   value="{{ $curStatus !== 'Presente' && !$att?->check_out_time ? '' : $curOut }}"
                                                   class="teacher-out-time px-3 py-2 rounded-xl bg-gray-50 border border-gray-200 font-mono tabular-nums font-bold text-xs text-gray-800 outline-none focus:border-[#1A237E]">
                                        </td>
                                        <td class="px-6 py-3.5">
                                            <input type="text" name="attendance[{{ $t->id }}][observations]" value="{{ $curObs }}"
                                                   placeholder="Opcional: permiso médico, comisión de servicio..."
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
                            Guardar Asistencia Docente
                        </button>
                    </div>
                </form>
            @endif
        </div>
    @endif
</div>

@if($canEdit)
<script>
function markAllTeachers(status) {
    document.querySelectorAll('.teacher-status-select').forEach(sel => {
        sel.value = status;
    });
}
function fillTeacherTimes(inTime, outTime) {
    document.querySelectorAll('.teacher-status-select').forEach(sel => {
        const row = sel.closest('tr');
        if (sel.value === 'Presente') {
            const inInput = row.querySelector('.teacher-in-time');
            const outInput = row.querySelector('.teacher-out-time');
            if (inInput) inInput.value = inTime;
            if (outInput) outInput.value = outTime;
        }
    });
}
function handleTeacherStatusChange(sel, teacherId, defIn, defOut) {
    const inEl = document.getElementById('tc_in_' + teacherId);
    const outEl = document.getElementById('tc_out_' + teacherId);
    if (sel.value !== 'Presente') {
        if (inEl) inEl.value = '';
        if (outEl) outEl.value = '';
    } else {
        if (inEl && !inEl.value) inEl.value = defIn;
        if (outEl && !outEl.value) outEl.value = defOut;
    }
}
</script>
@endif
@endsection

