<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boletín Oficial — {{ $enrollment->student->full_name }} ({{ $enrollment->school_year }})</title>
    <style>
        @page {
            size: letter portrait;
            margin: 1.1cm 1.2cm;
        }
        * {
            box-sizing: border-box;
        }
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
            max-width: 816px;
            margin: 0 auto 16px auto;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 12px 18px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }
        .btn {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
            border: none;
        }
        .btn-primary { background: #1A237E; color: #ffffff; }
        .btn-gold { background: #FBC02D; color: #1A237E; }
        .btn-green { background: #059669; color: #ffffff; }
        .btn-outline { background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; }
        .alert-success {
            max-width: 816px; margin: 0 auto 14px auto; padding: 12px 16px;
            background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46;
            border-radius: 10px; font-weight: bold; font-size: 12px;
        }
        .alert-error {
            max-width: 816px; margin: 0 auto 14px auto; padding: 12px 16px;
            background: #fef2f2; border: 1px solid #fecaca; color: #991b1b;
            border-radius: 10px; font-weight: bold; font-size: 12px;
        }
        .email-drawer {
            max-width: 816px; margin: 0 auto 16px auto; padding: 16px 20px;
            background: #ffffff; border: 2px solid #1A237E; border-radius: 12px;
            display: none;
        }
        .section-heading {
            background-color: #1A237E;
            color: #ffffff;
            font-size: 9.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 5px 10px;
            margin-top: 10px;
            margin-bottom: 0;
        }
        table.info-grid {
            width: 100%;
            border-collapse: collapse;
        }
        table.info-grid td, table.info-grid th {
            border: 1px solid #9ca3af;
            padding: 5px 8px;
            vertical-align: top;
        }
        .lbl {
            display: block;
            font-size: 8px;
            font-weight: bold;
            color: #4b5563;
            text-transform: uppercase;
            margin-bottom: 1px;
        }
        .val {
            font-size: 10.5px;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
        }
        table.grades-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.grades-table th {
            background-color: #f3f4f6;
            border: 1px solid #6b7280;
            padding: 6px 8px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            color: #1A237E;
        }
        table.grades-table td {
            border: 1px solid #9ca3af;
            padding: 5px 8px;
            font-size: 10px;
        }
        .obs-box {
            border: 1px solid #9ca3af;
            padding: 6px 8px;
            min-height: 36px;
            font-size: 9.5px;
            color: #1f2937;
            background: #fafafa;
        }
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
        <div class="alert-success no-print">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-error no-print">⚠ {{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert-error no-print">
            @foreach($errors->all() as $err) <div>• {{ $err }}</div> @endforeach
        </div>
    @endif

    <div class="toolbar no-print">
        @php
            $backRoute = auth()->check() && auth()->user()->isAdmin()
                ? route('students.show', $enrollment->student_id)
                : route('sections.show', $enrollment->section_id);
        @endphp
        <a href="{{ $backRoute }}" class="btn btn-outline">← Volver</a>

        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
            <button type="button" onclick="window.print()" class="btn btn-primary">🖨 Imprimir (Carta)</button>
            <a href="{{ route('reports.boletin.pdf', $enrollment->id) }}" class="btn btn-gold">⬇ Descargar PDF</a>
            <a href="{{ route('mail.compose', ['student_id' => $enrollment->student_id, 'doc' => 'boletin:' . $enrollment->id]) }}" class="btn btn-green">
                ✉ Enviar por Correo
            </a>
        </div>
    </div>
@endif

<div class="sheet">
    @include('partials.mppe_header', [
        'documentTitle' => 'BOLETÍN INFORMATIVO DE RENDIMIENTO ESTUDIANTIL — AÑO ESCOLAR ' . $enrollment->school_year
    ])

    <!-- 1. DATOS DE IDENTIFICACIÓN -->
    <div class="section-heading">I. Datos de Identificación del Estudiante y Representante Legal</div>
    <table class="info-grid">
        <tr>
            <td style="width: 46%;">
                <span class="lbl">Apellidos y Nombres del Estudiante</span>
                <span class="val">{{ $enrollment->student->last_name }} {{ $enrollment->student->second_last_name }}, {{ $enrollment->student->first_name }} {{ $enrollment->student->second_name }}</span>
            </td>
            <td style="width: 18%;">
                <span class="lbl">Cédula / C.E.</span>
                <span class="val">{{ $enrollment->student->cedula ? 'V-' . $enrollment->student->cedula : 'S/C' }}</span>
            </td>
            <td style="width: 18%;">
                <span class="lbl">Fecha Nac. / Edad</span>
                <span class="val">
                    {{ $enrollment->student->birth_date ? \Carbon\Carbon::parse($enrollment->student->birth_date)->format('d/m/Y') : '—' }}
                    ({{ $enrollment->student->birth_date ? \Carbon\Carbon::parse($enrollment->student->birth_date)->age . ' AÑOS' : '—' }})
                </span>
            </td>
            <td style="width: 18%;">
                <span class="lbl">Sexo</span>
                <span class="val">{{ $enrollment->student->gender === 'F' ? 'FEMENINO' : 'MASCULINO' }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="lbl">Nivel / Grado Cursado</span>
                <span class="val">{{ $enrollment->section->grade->level->name ?? 'EDUCACIÓN PRIMARIA' }} — {{ $enrollment->section->grade->name }}</span>
            </td>
            <td>
                <span class="lbl">Sección y Turno</span>
                <span class="val">"{{ $enrollment->section->name }}" ({{ $enrollment->section->shift }})</span>
            </td>
            <td>
                <span class="lbl">Asistencias / Inasistencias</span>
                <span class="val">{{ $attendanceSummary['Presente'] }} P / {{ $attendanceSummary['Ausente'] }} I ({{ $attendanceSummary['Justificado'] }} J)</span>
            </td>
            <td>
                <span class="lbl">Código Control</span>
                <span class="val">#BOL-{{ str_pad($enrollment->id, 5, '0', STR_PAD_LEFT) }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="lbl">Representante Legal</span>
                <span class="val">{{ $representative?->full_name ?: 'NO REGISTRADO' }}</span>
            </td>
            <td>
                <span class="lbl">C.I. Representante</span>
                <span class="val">{{ $representative?->cedula ? 'V-' . $representative->cedula : '—' }}</span>
            </td>
            <td>
                <span class="lbl">Parentesco</span>
                <span class="val">{{ $representative?->relationship ?: '—' }}</span>
            </td>
            <td>
                <span class="lbl">Teléfono Contacto</span>
                <span class="val">{{ $representative?->phone_whatsapp ?: '—' }}</span>
            </td>
        </tr>
    </table>

    <!-- 2. RENDIMIENTO ACADÉMICO POR MOMENTOS PEDAGÓGICOS -->
    <div class="section-heading">II. Registro de Evaluación por Áreas de Formación (Escala Literal MPPE)</div>
    <table class="grades-table">
        <thead>
            <tr>
                <th style="text-align: left; width: 38%;">Área de Formación / Asignatura</th>
                <th style="text-align: center; width: 11%;">I Momento</th>
                <th style="text-align: center; width: 11%;">II Momento</th>
                <th style="text-align: center; width: 11%;">III Momento</th>
                <th style="text-align: center; width: 11%; background-color: #e0e7ff;">Definitiva</th>
                <th style="text-align: left; width: 18%;">Apreciación Cualitativa</th>
            </tr>
        </thead>
        <tbody>
            @php
                $literalLabels = [
                    'A' => 'Consolidado (Excelente)',
                    'B' => 'Avanzado (Muy Bueno)',
                    'C' => 'En Proceso (Bueno)',
                    'D' => 'Iniciado (Requiere Apoyo)',
                    'E' => 'No Logrado',
                ];
            @endphp
            @forelse($subjects as $subject)
                @php
                    $subjectAssessments = $assessments->get($subject->id, collect());
                    $m1 = $subjectAssessments->firstWhere('term', 1)?->score;
                    $m2 = $subjectAssessments->firstWhere('term', 2)?->score;
                    $m3 = $subjectAssessments->firstWhere('term', 3)?->score;
                    $def = $m3 ?: ($m2 ?: ($m1 ?: null));
                @endphp
                <tr>
                    <td style="font-weight: bold; color: #1f2937;">{{ mb_strtoupper($subject->name, 'UTF-8') }}</td>
                    <td style="text-align: center; font-weight: bold; color: #1A237E;">{{ $m1 ?? '—' }}</td>
                    <td style="text-align: center; font-weight: bold; color: #1A237E;">{{ $m2 ?? '—' }}</td>
                    <td style="text-align: center; font-weight: bold; color: #1A237E;">{{ $m3 ?? '—' }}</td>
                    <td style="text-align: center; font-weight: 900; font-size: 11.5px; color: #1A237E; background-color: #f8fafc;">
                        {{ $def ?? '—' }}
                    </td>
                    <td style="font-size: 9px; font-weight: bold; color: #4b5563;">
                        {{ $def ? ($literalLabels[$def] ?? '') : 'En evaluación' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 12px; color: #6b7280;">
                        No existen áreas de formación configuradas para este grado.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div style="font-size: 8px; color: #4b5563; margin-top: 4px; text-align: justify;">
        <strong>Escala Oficial Art. 108 RLOE (MPPE):</strong>
        <strong>A:</strong> El alumno alcanzó todas las competencias y en algunos casos superó las expectativas para el grado.
        <strong>B:</strong> Alcanzó todas las competencias previstas para el grado.
        <strong>C:</strong> Alcanzó la mayoría de las competencias previstas para el grado.
        <strong>D:</strong> Alcanzó algunas competencias pero requiere proceso de nivelación.
        <strong>E:</strong> No logró adquirir las competencias mínimas requeridas.
    </div>

    <!-- 3. PROYECTO DE APRENDIZAJE Y DIAGNÓSTICOS DESCRIPTIVOS -->
    <div class="section-heading">III. Proyecto de Aprendizaje e Informe Descriptivo por Momentos</div>
    <table class="info-grid">
        <tr>
            <td colspan="3" style="background-color: #fffbeb;">
                <span class="lbl" style="color: #92400e;">Proyecto de Aprendizaje Desarrollado</span>
                <div style="font-size: 10px; font-weight: bold; color: #1f2937;">
                    {{ $enrollment->learning_projects ?: 'Proyecto Pedagógico Integral de Aula acorde al Currículo Nacional Bolivariano.' }}
                </div>
            </td>
        </tr>
        <tr>
            <td style="width: 33.3%;">
                <span class="lbl">I Momento Pedagógico</span>
                <div style="font-size: 9.5px; color: #374151; min-height: 34px;">
                    {{ $enrollment->diagnostic_1 ?: 'En proceso de valoración descriptiva.' }}
                </div>
            </td>
            <td style="width: 33.3%;">
                <span class="lbl">II Momento Pedagógico</span>
                <div style="font-size: 9.5px; color: #374151; min-height: 34px;">
                    {{ $enrollment->diagnostic_2 ?: 'En proceso de valoración descriptiva.' }}
                </div>
            </td>
            <td style="width: 33.3%;">
                <span class="lbl">III Momento Pedagógico</span>
                <div style="font-size: 9.5px; color: #374151; min-height: 34px;">
                    {{ $enrollment->diagnostic_3 ?: 'Pendiente cierre del tercer momento.' }}
                </div>
            </td>
        </tr>
    </table>

    <!-- 4. RESUMEN FINAL DEL DOCENTE Y DECISIÓN DE PROMOCIÓN -->
    <div class="section-heading">IV. Resumen Pedagógico Final del Docente y Condición del Estudiante</div>
    <table class="info-grid">
        <tr>
            <td style="width: 74%;">
                <span class="lbl">Síntesis Valorativa y Recomendaciones del Docente Titular</span>
                <div style="font-size: 10px; color: #111827; min-height: 42px; line-height: 1.45;">
                    {{ $enrollment->pedagogical_summary ?: ($enrollment->recommendations ?: 'Se recomienda mantener el acompañamiento familiar constante para fortalecer los hábitos de lectura, escritura y pensamiento lógico-matemático.') }}
                </div>
            </td>
            <td style="width: 26%; text-align: center; vertical-align: middle; background-color: #f8fafc;">
                <span class="lbl">Condición del Año Escolar</span>
                <div style="font-size: 13px; font-weight: 900; color: {{ $enrollment->status === 'Promovido' ? '#047857' : '#1A237E' }}; text-transform: uppercase; margin-top: 4px;">
                    {{ $enrollment->status ?: 'CURSANDO' }}
                </div>
            </td>
        </tr>
    </table>

    <!-- 5. FIRMAS Y SELLO -->
    <table style="width: 100%; border-collapse: collapse; margin-top: 38px;">
        <tr>
            <td style="width: 33%; text-align: center; vertical-align: bottom; padding: 0 10px;">
                <div style="border-top: 1px solid #374151; padding-top: 5px;">
                    <div style="font-size: 9.5px; font-weight: bold; text-transform: uppercase;">Docente de Aula</div>
                    <div style="font-size: 9px; color: #374151; font-weight: bold; text-transform: uppercase;">
                        {{ $sectionTeacher?->full_name ?: 'DOCENTE TITULAR DE SECCIÓN' }}
                    </div>
                    @if($sectionTeacher?->cedula)
                        <div style="font-size: 8px; color: #6b7280;">C.I. V-{{ $sectionTeacher->cedula }}</div>
                    @endif
                </div>
            </td>
            <td style="width: 34%; text-align: center; vertical-align: bottom; padding: 0 10px;">
                <div style="width: 64px; height: 64px; border: 1px dashed #9ca3af; border-radius: 50%; margin: 0 auto 6px auto; line-height: 64px; font-size: 8px; color: #9ca3af; font-weight: bold; text-transform: uppercase;">
                    SELLO
                </div>
                <div style="border-top: 1px solid #374151; padding-top: 5px;">
                    <div style="font-size: 9.5px; font-weight: bold; text-transform: uppercase;">Dirección del Plantel</div>
                    <div style="font-size: 9px; color: #374151; font-weight: bold; text-transform: uppercase;">
                        {{ $directorName }}
                    </div>
                    @if(!empty($directorCedula))
                        <div style="font-size: 8px; color: #6b7280;">C.I. {{ $directorCedula }}</div>
                    @endif
                </div>
            </td>
            <td style="width: 33%; text-align: center; vertical-align: bottom; padding: 0 10px;">
                <div style="border-top: 1px solid #374151; padding-top: 5px;">
                    <div style="font-size: 9.5px; font-weight: bold; text-transform: uppercase;">Representante Legal</div>
                    <div style="font-size: 9px; color: #374151; font-weight: bold; text-transform: uppercase;">
                        {{ $representative?->full_name ?: 'FIRMA DEL REPRESENTANTE' }}
                    </div>
                    @if($representative?->cedula)
                        <div style="font-size: 8px; color: #6b7280;">C.I. V-{{ $representative->cedula }}</div>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <div style="margin-top: 16px; border-top: 1px solid #e5e7eb; padding-top: 6px; text-align: center; font-size: 8px; color: #6b7280; text-transform: uppercase;">
        Documento emitido en Caracas a los {{ now()->format('d') }} días del mes de {{ ucfirst(now()->locale('es')->translatedFormat('F')) }} de {{ now()->format('Y') }} · Sistema Infochalbaud — U.E.B. Coronel Carlos Delgado Chalbaud
    </div>
</div>

</body>
</html>
