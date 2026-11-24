<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récord Académico — {{ $student->full_name }}</title>
    <style>
        @page {
            size: letter portrait;
            margin: 1.2cm 1.3cm;
        }
        * { box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #111827;
            background-color: {{ !empty($isPdf) ? '#ffffff' : '#eef2f7' }};
            margin: 0;
            padding: {{ !empty($isPdf) ? '0' : '24px' }};
            font-size: 11px;
            line-height: 1.4;
        }
        .sheet {
            max-width: 816px;
            margin: 0 auto;
            background: #ffffff;
            padding: {{ !empty($isPdf) ? '0' : '28px 34px' }};
            border: {{ !empty($isPdf) ? 'none' : '1px solid #d1d5db' }};
            border-radius: {{ !empty($isPdf) ? '0' : '12px' }};
            box-shadow: {{ !empty($isPdf) ? 'none' : '0 10px 30px rgba(26,35,126,0.08)' }};
        }
        .toolbar {
            max-width: 816px; margin: 0 auto 16px auto; background: #ffffff;
            border: 1px solid #e5e7eb; border-radius: 12px; padding: 12px 18px;
            display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 10px;
        }
        .btn {
            display: inline-block; padding: 8px 14px; border-radius: 8px;
            font-size: 12px; font-weight: bold; text-decoration: none; cursor: pointer; border: none;
        }
        .btn-primary { background: #1A237E; color: #ffffff; }
        .btn-gold { background: #FBC02D; color: #1A237E; }
        .btn-green { background: #059669; color: #ffffff; }
        .btn-outline { background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; }
        .alert-box {
            max-width: 816px; margin: 0 auto 14px auto; padding: 12px 16px;
            border-radius: 10px; font-weight: bold; font-size: 12px;
        }
        .email-drawer {
            max-width: 816px; margin: 0 auto 16px auto; padding: 16px 20px;
            background: #ffffff; border: 2px solid #1A237E; border-radius: 12px; display: none;
        }
        .section-heading {
            background-color: #1A237E; color: #ffffff; font-size: 9.5px; font-weight: bold;
            text-transform: uppercase; letter-spacing: 0.8px; padding: 5px 10px; margin-top: 12px;
        }
        table.grid-table { width: 100%; border-collapse: collapse; }
        table.grid-table td, table.grid-table th {
            border: 1px solid #9ca3af; padding: 5px 8px; vertical-align: top;
        }
        table.grid-table th {
            background-color: #f3f4f6; font-size: 9px; text-transform: uppercase; color: #1A237E;
        }
        .lbl { display: block; font-size: 8px; font-weight: bold; color: #4b5563; text-transform: uppercase; }
        .val { font-size: 10.5px; font-weight: bold; color: #111827; text-transform: uppercase; }
        @media print {
            .no-print { display: none !important; }
            body { background: #ffffff !important; padding: 0 !important; }
            .sheet { box-shadow: none !important; border: none !important; padding: 0 !important; max-width: 100% !important; }
        }
    </style>
</head>
<body>

@if(empty($isPdf))
    @if(session('success'))
        <div class="alert-box no-print" style="background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46;">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-box no-print" style="background:#fef2f2; border:1px solid #fecaca; color:#991b1b;">⚠ {{ session('error') }}</div>
    @endif

    <div class="toolbar no-print">
        <a href="{{ route('academic-records.index') }}" class="btn btn-outline">← Volver al Récord General</a>
        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
            <button type="button" onclick="window.print()" class="btn btn-primary">🖨 Imprimir (Carta)</button>
            <a href="{{ route('academic-records.pdf', $student->id) }}" class="btn btn-gold">⬇ Descargar PDF</a>
            <a href="{{ route('mail.compose', ['student_id' => $student->id, 'doc' => 'record_academico']) }}" class="btn btn-green">
                ✉ Enviar por Correo
            </a>
        </div>
    </div>
@endif

<div class="sheet">
    @include('partials.mppe_header', [
        'documentTitle' => 'RÉCORD ACADÉMICO HISTÓRICO ESTUDIANTIL',
        'hideDeaLine'   => true,
    ])

    <!-- Datos del Estudiante -->
    <div class="section-heading">I. Identificación del Estudiante / Egresado</div>
    <table class="grid-table">
        <tr>
            <td style="width: 44%;">
                <span class="lbl">Apellidos y Nombres</span>
                <span class="val">{{ $student->last_name }} {{ $student->second_last_name }}, {{ $student->first_name }} {{ $student->second_name }}</span>
            </td>
            <td style="width: 18%;">
                <span class="lbl">Cédula de Identidad</span>
                <span class="val">{{ $student->cedula ? 'V-' . $student->cedula : 'S/C' }}</span>
            </td>
            <td style="width: 20%;">
                <span class="lbl">Fecha de Nacimiento</span>
                <span class="val">{{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->format('d/m/Y') : '—' }}</span>
            </td>
            <td style="width: 18%;">
                <span class="lbl">Estatus Actual</span>
                <span class="val">{{ $student->status }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="lbl">Lugar de Nacimiento</span>
                <span class="val">{{ $student->birth_place_locality }}, EDO. {{ $student->birth_place_state }}</span>
            </td>
            <td>
                <span class="lbl">Representante Legal</span>
                <span class="val">{{ $representative?->full_name ?: 'NO REGISTRADO' }}</span>
            </td>
            <td>
                <span class="lbl">C.I. Representante</span>
                <span class="val">{{ $representative?->cedula ? 'V-' . $representative->cedula : '—' }}</span>
            </td>
            <td>
                <span class="lbl">Situación Académica</span>
                <span class="val">
                    @if($student->graduate)
                        PROMOCIÓN {{ $student->graduate->promotion_year }}
                    @elseif($student->status === 'Retirado')
                        RETIRO DEL PLANTEL
                    @elseif($student->status === 'Egresado')
                        EGRESADO
                    @else
                        EN PROSECUCIÓN
                    @endif
                </span>
            </td>
        </tr>
    </table>

    @if($student->graduate)
        <div style="margin-top: 10px; padding: 8px 12px; background: #eff6ff; border: 1px solid #93c5fd; font-size: 10px;">
            <strong>CERTIFICACIÓN DE EGRESO INSTITUCIONAL:</strong>
            El(la) estudiante figura en el Libro de Egresados de la U.E.B. "Coronel Carlos Delgado Chalbaud" correspondiente a la Promoción <strong>{{ $student->graduate->promotion_year }}</strong>.
            @if($student->graduate->notes)
                Observación: <em>{{ $student->graduate->notes }}</em>
            @endif
        </div>
    @endif

    <!-- Historial por Periodo Escolar -->
    <div class="section-heading">II. Historial Longitudinal de Calificaciones por Año Escolar</div>

    @forelse($student->enrollments as $enr)
        @php
            $activeSubjects = $enr->section?->grade?->subjects?->where('is_active', true) ?? collect();
            $assessedSubjects = $enr->assessments->pluck('subject')->filter()->unique('id');
            $gradeSubjects = $activeSubjects->merge($assessedSubjects)->unique('id')->sortBy('name')->values();
            $enrAssessments = $enr->assessments->groupBy('subject_id');
            $isClosedPeriod = in_array($enr->status, ['Promovido', 'Repitiente'], true);
        @endphp
        <div style="margin-top: 10px; border: 1px solid #6b7280;">
            <div style="background: #e5e7eb; padding: 6px 10px; font-size: 10px; font-weight: bold; color: #111827; border-bottom: 1px solid #6b7280;">
                AÑO ESCOLAR: {{ $enr->school_year }} &nbsp;|&nbsp;
                GRADO: {{ $enr->section?->grade?->name ?? '—' }} "{{ $enr->section?->name ?? '' }}" ({{ $enr->section?->shift ?? '' }}) &nbsp;|&nbsp;
                CONDICIÓN: <span style="color: #1A237E;">{{ mb_strtoupper($enr->status ?: 'CURSADO', 'UTF-8') }}</span>
            </div>

            <table class="grid-table">
                <thead>
                    <tr>
                        <th style="text-align: left; width: 48%;">Área de Formación / Asignatura</th>
                        <th style="text-align: center; width: 13%;">Momento I</th>
                        <th style="text-align: center; width: 13%;">Momento II</th>
                        <th style="text-align: center; width: 13%;">Momento III</th>
                        <th style="text-align: center; width: 13%;">Definitiva</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gradeSubjects as $subj)
                        @php
                            $items = $enrAssessments->get($subj->id, collect());
                            $m1 = $items->firstWhere('term', 1)?->score;
                            $m2 = $items->firstWhere('term', 2)?->score;
                            $m3 = $items->firstWhere('term', 3)?->score;
                            $def = ($isClosedPeriod || ($m1 && $m2 && $m3)) ? ($m3 ?: ($m2 ?: $m1)) : null;
                        @endphp
                        <tr>
                            <td style="font-weight: bold;">{{ mb_strtoupper($subj->name, 'UTF-8') }}</td>
                            <td style="text-align: center; font-weight: bold;">{{ $m1 ?? '—' }}</td>
                            <td style="text-align: center; font-weight: bold;">{{ $m2 ?? '—' }}</td>
                            <td style="text-align: center; font-weight: bold;">{{ $m3 ?? '—' }}</td>
                            <td style="text-align: center; font-weight: 900; color: #1A237E; background: #f8fafc;">{{ $def ?? 'En curso' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: #6b7280;">Sin asignaturas asociadas a este grado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($enr->pedagogical_summary || $enr->recommendations)
                <div style="padding: 6px 10px; font-size: 9.5px; background: #fafafa; border-top: 1px solid #9ca3af;">
                    <strong>Resumen Pedagógico Final del Docente:</strong>
                    {{ $enr->pedagogical_summary ?: $enr->recommendations }}
                </div>
            @endif
        </div>
    @empty
        <div style="border: 1px solid #9ca3af; padding: 18px; text-align: center; color: #4b5563; margin-top: 6px;">
            @if($student->graduate)
                Registro correspondiente a <strong>Egresado Histórico</strong> (Promoción {{ $student->graduate->promotion_year }}). No posee desglose digitalizado de lapsos previos en plataforma.
            @else
                El estudiante aún no registra periodos académicos cursados con carga de calificaciones.
            @endif
        </div>
    @endforelse

    <!-- Firma -->
    <table style="width: 100%; border-collapse: collapse; margin-top: 42px;">
        <tr>
            <td style="width: 50%; text-align: center;">
                <div style="width: 240px; margin: 0 auto; border-top: 1px solid #374151; padding-top: 6px;">
                    <div style="font-size: 9.5px; font-weight: bold; text-transform: uppercase;">Control de Estudios y Evaluación</div>
                    <div style="font-size: 8.5px; color: #4b5563;">U.E.B. Coronel Carlos Delgado Chalbaud</div>
                </div>
            </td>
            <td style="width: 50%; text-align: center;">
                <div style="width: 240px; margin: 0 auto; border-top: 1px solid #374151; padding-top: 6px;">
                    <div style="font-size: 9.5px; font-weight: bold; text-transform: uppercase;">{{ $directorName }}</div>
                    <div style="font-size: 8.5px; color: #4b5563;">Dirección del Plantel {{ $directorCedula ? '· C.I. ' . $directorCedula : '' }}</div>
                </div>
            </td>
        </tr>
    </table>

    <div style="margin-top: 18px; border-top: 1px solid #e5e7eb; padding-top: 6px; text-align: center; font-size: 8px; color: #6b7280; text-transform: uppercase;">
        Expedido en Caracas el {{ now()->format('d/m/Y') }} · Sistema de Gestión Escolar Infochalbaud · Código DEA: OD05290104
    </div>
</div>

</body>
</html>

