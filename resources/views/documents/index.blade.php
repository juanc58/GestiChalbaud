@extends('dashboard')

@section('content')
<div class="space-y-6">
    <!-- Encabezado -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h3 class="text-2xl font-extrabold text-[#1A237E]">Emisión de Constancias y Documentos Oficiales</h3>
            <p class="text-sm text-gray-500 font-semibold mt-1">
                Genera, imprime en formato Carta, descarga en PDF o envía por correo Constancias de Estudio, Prosecución y Justificativos Institucionales con membrete MPPE.
            </p>
        </div>
    </div>

    <!-- Tarjetas Informativas de los 3 Documentos -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
            <span class="px-2.5 py-1 rounded-lg bg-indigo-50 text-[#1A237E] text-[10px] font-black uppercase tracking-wider">1. Estudio Regular</span>
            <h4 class="font-extrabold text-[#1A237E] text-sm mt-2">Constancia de Estudio</h4>
            <p class="text-xs text-gray-500 font-medium mt-1">Certifica la matrícula vigente, grado, sección, turno y datos del representante legal.</p>
        </div>
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
            <span class="px-2.5 py-1 rounded-lg bg-red-50 text-[#D32F2F] text-[10px] font-black uppercase tracking-wider">2. Promoción / Egreso</span>
            <h4 class="font-extrabold text-[#1A237E] text-sm mt-2">Constancia de Prosecución</h4>
            <p class="text-xs text-gray-500 font-medium mt-1">Acredita la aprobación del grado cursado, literal alcanzada y promoción al nivel inmediato superior.</p>
        </div>
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
            <span class="px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 text-[10px] font-black uppercase tracking-wider">3. Representante o Alumno</span>
            <h4 class="font-extrabold text-[#1A237E] text-sm mt-2">Justificativo Institucional</h4>
            <p class="text-xs text-gray-500 font-medium mt-1">Justifica asistencia o comparecencia laboral/académica del representante, del alumno o de ambos.</p>
        </div>
    </div>

    <!-- Buscador de Estudiantes -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <form action="{{ route('documents.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div class="md:col-span-2">
                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Buscar Estudiante o Egresado</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre, Apellido o Cédula..."
                       class="w-full px-4 py-2.5 rounded-xl bg-gray-50 border border-gray-200 font-semibold text-sm outline-none focus:border-[#1A237E]">
            </div>

            <div>
                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Filtrar por Aula ({{ $currentYear }})</label>
                <select name="section_id" class="w-full px-4 py-2.5 rounded-xl bg-gray-50 border border-gray-200 font-bold text-sm text-[#1A237E] outline-none">
                    <option value="">Todas las Aulas / Egresados</option>
                    @foreach($sections as $sec)
                        <option value="{{ $sec->id }}" {{ request('section_id') == $sec->id ? 'selected' : '' }}>
                            {{ $sec->grade->name }} "{{ $sec->name }}" ({{ $sec->shift }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="px-6 py-2.5 bg-[#1A237E] text-white rounded-xl font-bold text-sm hover:bg-[#1A237E]/90 transition-all flex-1">
                    Buscar
                </button>
                @if(request()->anyFilled(['search', 'section_id', 'status']))
                    <a href="{{ route('documents.index') }}" class="px-3.5 py-2.5 bg-gray-100 text-gray-500 rounded-xl hover:bg-gray-200 flex items-center justify-center" title="Limpiar">✕</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabla de Emisión Rápida -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/70 border-b border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        <th class="px-6 py-4">Estudiante</th>
                        <th class="px-6 py-4">Representante Legal</th>
                        <th class="px-6 py-4">Grado / Estatus</th>
                        <th class="px-6 py-4 text-right">Seleccionar Documento a Emitir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($students as $st)
                        @php
                            $enr = $st->enrollments->first();
                            $rep = $st->representative;
                        @endphp
                        <tr class="hover:bg-blue-50/20 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-extrabold text-[#1A237E] text-sm">
                                    {{ $st->last_name }} {{ $st->second_last_name }}, {{ $st->first_name }} {{ $st->second_name }}
                                </div>
                                <div class="text-xs font-bold text-gray-400">
                                    C.I.: {{ $st->cedula ? 'V-' . $st->cedula : 'S/C' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($rep)
                                    <div class="text-xs font-bold text-gray-700">{{ $rep->full_name }}</div>
                                    <div class="text-[10px] font-bold text-gray-400">
                                        C.I.: {{ $rep->cedula ? 'V-' . $rep->cedula : '—' }}
                                        {{ $rep->email ? '· ' . $rep->email : '' }}
                                    </div>
                                @else
                                    <span class="text-xs font-semibold text-gray-400 italic">Sin representante</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-gray-700">
                                @if($st->status === 'Egresado')
                                    <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 text-[10px] font-black uppercase">
                                        Egresado ({{ $st->graduate?->promotion_year ?? 'Histórico' }})
                                    </span>
                                @elseif($enr?->section)
                                    {{ $enr->section->grade->name }} "{{ $enr->section->name }}" ({{ $enr->school_year }})
                                @else
                                    {{ $st->status }}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                @php
                                    $canEstudio = \App\Services\DocumentGeneratorService::checkDocumentEligibility($st, 'estudio') === null;
                                    $canProsecucion = \App\Services\DocumentGeneratorService::checkDocumentEligibility($st, 'prosecucion') === null;
                                @endphp
                                <div class="inline-flex flex-wrap justify-end items-center gap-2">
                                    @if($canEstudio)
                                        <a href="{{ route('documents.preview', [$st->id, 'estudio']) }}"
                                           class="px-3 py-2 rounded-xl bg-[#1A237E] text-white text-xs font-bold hover:bg-[#1A237E]/90 transition-all shadow-sm whitespace-nowrap inline-flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            Estudio / Inscripción
                                        </a>
                                    @else
                                        <span title="Solo disponible para estudiantes activos con matrícula vigente"
                                              class="px-3 py-2 rounded-xl bg-gray-100 border border-gray-200 text-gray-400 text-xs font-bold cursor-not-allowed whitespace-nowrap inline-flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            Estudio
                                        </span>
                                    @endif
                                    @if($canProsecucion)
                                        <a href="{{ route('documents.preview', [$st->id, 'prosecucion']) }}"
                                           class="px-3 py-2 rounded-xl bg-[#D32F2F] text-white text-xs font-bold hover:bg-[#D32F2F]/90 transition-all shadow-sm whitespace-nowrap inline-flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                                            Prosecución
                                        </a>
                                    @else
                                        <span title="Requiere estar Promovido o Egresado"
                                              class="px-3 py-2 rounded-xl bg-gray-100 border border-gray-200 text-gray-400 text-xs font-bold cursor-not-allowed whitespace-nowrap inline-flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                                            Prosecución
                                        </span>
                                    @endif
                                    <a href="{{ route('documents.preview', [$st->id, 'justificativo']) }}"
                                       class="px-3 py-2 rounded-xl bg-[#FBC02D] text-[#1A237E] text-xs font-extrabold hover:bg-[#FBC02D]/90 transition-all shadow-sm whitespace-nowrap inline-flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Justificativo
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400 font-bold">
                                No se encontraron registros estudiantiles para emitir documentos.
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
</div>
@endsection

