<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $documentTitle }} — {{ $student->full_name }}</title>
    <style>
        @page {
            size: letter portrait;
            margin: 1.8cm 2.2cm;
        }
        * { box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #111827;
            background-color: {{ !empty($isPdf) ? '#ffffff' : '#eef2f7' }};
            margin: 0;
            padding: {{ !empty($isPdf) ? '0' : '24px' }};
            font-size: 12.5px;
            line-height: 1.75;
        }
        .sheet {
            max-width: 816px;
            min-height: {{ !empty($isPdf) ? 'auto' : '1020px' }};
            margin: 0 auto;
            background: #ffffff;
            padding: {{ !empty($isPdf) ? '0' : '48px 58px' }};
            border: {{ !empty($isPdf) ? 'none' : '1px solid #d1d5db' }};
            border-radius: {{ !empty($isPdf) ? '0' : '12px' }};
            box-shadow: {{ !empty($isPdf) ? 'none' : '0 10px 30px rgba(26,35,126,0.08)' }};
            position: relative;
        }
        .panel-bar {
            max-width: 816px; margin: 0 auto 14px auto; background: #ffffff;
            border: 1px solid #e5e7eb; border-radius: 12px; padding: 14px 18px;
        }
        .btn {
            display: inline-block; padding: 8px 14px; border-radius: 8px;
            font-size: 12px; font-weight: bold; text-decoration: none; cursor: pointer; border: none;
        }
        .btn-primary { background: #1A237E; color: #ffffff; }
        .btn-gold { background: #FBC02D; color: #1A237E; }
        .btn-green { background: #059669; color: #ffffff; }
        .btn-outline { background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; }
        .doc-title {
            text-align: center;
            font-size: 15px;
            font-weight: 900;
            color: #1A237E;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin: 28px 0 24px 0;
            text-decoration: underline;
        }
        .prose-paragraph {
            text-align: justify;
            margin-bottom: 18px;
            font-size: 12.5px;
            line-height: 1.85;
            color: #111827;
        }
        @media print {
            .no-print { display: none !important; }
            body { background: #ffffff !important; padding: 0 !important; }
            .sheet { box-shadow: none !important; border: none !important; padding: 0 !important; min-height: auto !important; max-width: 100% !important; }
        }
    </style>
</head>
<body>

@if(empty($isPdf))
    @if(session('success'))
        <div class="panel-bar no-print" style="background:#ecfdf5; border-color:#a7f3d0; color:#065f46; font-weight:bold; font-size:12px;">
            ✓ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="panel-bar no-print" style="background:#fef2f2; border-color:#fecaca; color:#991b1b; font-weight:bold; font-size:12px;">
            ⚠ {{ session('error') }}
        </div>
    @endif

    <!-- Barra Principal de Acciones -->
    <div class="panel-bar no-print" style="display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:10px;">
        <div style="display:flex; flex-wrap:wrap; align-items:center; gap:8px;">
            <a href="{{ route('documents.index') }}" class="btn btn-outline">← Volver</a>
            <a href="{{ route('documents.preview', [$student->id, 'estudio']) }}" class="btn {{ $type === 'estudio' ? 'btn-primary' : 'btn-outline' }}">Estudio</a>
            <a href="{{ route('documents.preview', [$student->id, 'inscripcion']) }}" class="btn {{ $type === 'inscripcion' ? 'btn-primary' : 'btn-outline' }}">Inscripción</a>
            <a href="{{ route('documents.preview', [$student->id, 'prosecucion']) }}" class="btn {{ $type === 'prosecucion' ? 'btn-primary' : 'btn-outline' }}">Prosecución</a>
            <a href="{{ route('documents.preview', [$student->id, 'justificativo']) }}" class="btn {{ $type === 'justificativo' ? 'btn-primary' : 'btn-outline' }}">Justificativo</a>
        </div>

        <div style="display:flex; flex-wrap:wrap; gap:8px;">
            <button type="button" onclick="window.print()" class="btn btn-primary">🖨 Imprimir (Carta)</button>
            <a href="{{ route('documents.pdf', array_merge([$student->id, $type], request()->query())) }}" class="btn btn-gold">⬇ Descargar PDF</a>
            <a href="{{ route('mail.compose', ['student_id' => $student->id, 'doc' => $type]) }}" class="btn btn-green">
                ✉ Enviar por Correo
            </a>
        </div>
    </div>

    <!-- Barra de Personalización Rápida del Documento -->
    <div class="panel-bar no-print">
        <form action="{{ route('documents.preview', [$student->id, $type]) }}" method="GET" style="display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end;">
            @if(in_array($type, ['estudio', 'inscripcion'], true))
                <div style="flex:1; min-width:260px;">
                    <label style="display:block; font-size:10px; font-weight:bold; color:#4b5563; text-transform:uppercase; margin-bottom:4px;">Motivo de Solicitud</label>
                    <input type="text" name="purpose" value="{{ $purpose }}" style="width:100%; padding:7px 10px; border:1px solid #cbd5e1; border-radius:8px; font-size:12px;">
                </div>
            @elseif($type === 'prosecucion')
                <div style="width:120px;">
                    <label style="display:block; font-size:10px; font-weight:bold; color:#4b5563; text-transform:uppercase; margin-bottom:4px;">Literal Alcanzada</label>
                    <select name="literal" style="width:100%; padding:7px 10px; border:1px solid #cbd5e1; border-radius:8px; font-size:12px; font-weight:bold;">
                        @foreach(['A', 'B', 'C', 'D'] as $lit)
                            <option value="{{ $lit }}" {{ $literal === $lit ? 'selected' : '' }}>Literal "{{ $lit }}"</option>
                        @endforeach
                    </select>
                </div>
                <div style="flex:1; min-width:240px;">
                    <label style="display:block; font-size:10px; font-weight:bold; color:#4b5563; text-transform:uppercase; margin-bottom:4px;">Promovido(a) a:</label>
                    <input type="text" name="next_grade" value="{{ $nextGrade }}" oninput="this.value=this.value.toUpperCase()" style="width:100%; padding:7px 10px; border:1px solid #cbd5e1; border-radius:8px; font-size:12px; text-transform:uppercase;">
                </div>
            @elseif($type === 'justificativo')
                <div style="width:200px;">
                    <label style="display:block; font-size:10px; font-weight:bold; color:#4b5563; text-transform:uppercase; margin-bottom:4px;">A nombre de:</label>
                    <select name="target" style="width:100%; padding:7px 10px; border:1px solid #cbd5e1; border-radius:8px; font-size:12px; font-weight:bold;">
                        <option value="ambos" {{ $target === 'ambos' ? 'selected' : '' }}>Representante y Alumno</option>
                        <option value="representante" {{ $target === 'representante' ? 'selected' : '' }}>Solo Representante Legal</option>
                        <option value="estudiante" {{ $target === 'estudiante' ? 'selected' : '' }}>Solo Estudiante</option>
                    </select>
                </div>
                <div style="width:150px;">
                    <label style="display:block; font-size:10px; font-weight:bold; color:#4b5563; text-transform:uppercase; margin-bottom:4px;">Fecha (Día-Mes-Año)</label>
                    <x-date-input-es name="event_date" :value="$eventDate" style="width:100%; padding:7px 10px; border:1px solid #cbd5e1; border-radius:8px; font-size:12px;" />
                </div>
                <div style="width:110px;">
                    <label style="display:block; font-size:10px; font-weight:bold; color:#4b5563; text-transform:uppercase; margin-bottom:4px;">Desde</label>
                    <input type="time" name="time_from" value="{{ $timeFrom }}" style="width:100%; padding:7px 10px; border:1px solid #cbd5e1; border-radius:8px; font-size:12px;">
                </div>
                <div style="width:110px;">
                    <label style="display:block; font-size:10px; font-weight:bold; color:#4b5563; text-transform:uppercase; margin-bottom:4px;">Hasta</label>
                    <input type="time" name="time_to" value="{{ $timeTo }}" style="width:100%; padding:7px 10px; border:1px solid #cbd5e1; border-radius:8px; font-size:12px;">
                </div>
                <div style="flex:1; min-width:240px;">
                    <label style="display:block; font-size:10px; font-weight:bold; color:#4b5563; text-transform:uppercase; margin-bottom:4px;">Motivo de Comparecencia / Justificación</label>
                    <input type="text" name="reason" value="{{ $reason }}" style="width:100%; padding:7px 10px; border:1px solid #cbd5e1; border-radius:8px; font-size:12px;">
                </div>
            @endif
            <button type="submit" class="btn btn-outline" style="background:#e0e7ff; color:#1A237E; border-color:#c7d2fe;">↻ Actualizar Vista</button>
        </form>
    </div>

    <!-- Cajón de Envío por Correo -->
    <div id="docEmailDrawer" class="panel-bar no-print" style="display:none; border:2px solid #1A237E;">
        <form action="{{ route('documents.email', [$student->id, $type]) }}" method="POST">
            @csrf
            <input type="hidden" name="literal" value="{{ $literal }}">
            <input type="hidden" name="next_grade" value="{{ $nextGrade }}">
            <input type="hidden" name="target" value="{{ $target }}">
            <input type="hidden" name="event_date" value="{{ $eventDate }}">
            <input type="hidden" name="time_from" value="{{ $timeFrom }}">
            <input type="hidden" name="time_to" value="{{ $timeTo }}">
            <input type="hidden" name="reason" value="{{ $reason }}">
            <input type="hidden" name="purpose" value="{{ $purpose }}">

            <div style="font-size:13px; font-weight:bold; color:#1A237E; margin-bottom:10px;">
                Enviar {{ $documentTitle }} en PDF al Correo del Representante
            </div>
            <div style="display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end;">
                <div style="flex:1; min-width:220px;">
                    <label style="display:block; font-size:10px; font-weight:bold; color:#4b5563; text-transform:uppercase; margin-bottom:4px;">
                        Correo Destinatario ({{ $representative?->full_name ?? 'Representante' }}) *
                    </label>
                    <input type="email" name="recipient_email" required
                           value="{{ old('recipient_email', $representative?->email) }}"
                           placeholder="correo@ejemplo.com"
                           style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:8px; font-size:12px;">
                </div>
                <div style="flex:1; min-width:220px;">
                    <label style="display:block; font-size:10px; font-weight:bold; color:#4b5563; text-transform:uppercase; margin-bottom:4px;">
                        Mensaje Opcional
                    </label>
                    <input type="text" name="custom_message"
                           placeholder="Ej: Remitimos constancia oficial sellada digitalmente..."
                           style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:8px; font-size:12px;">
                </div>
                <button type="submit" class="btn btn-green" style="padding:9px 18px;">Enviar PDF Ahora</button>
            </div>
            @if($representative)
                <label style="display:inline-flex; align-items:center; gap:6px; margin-top:8px; font-size:11px; color:#4b5563; cursor:pointer;">
                    <input type="checkbox" name="save_rep_email" value="1" checked>
                    Guardar este correo en la ficha del representante ({{ $representative->full_name }})
                </label>
            @endif
        </form>
    </div>
@endif

<div class="sheet">
    @include('partials.mppe_header')

    <div class="doc-title">{{ $documentTitle }}</div>

    @php
        $studentFullName = trim("{$student->first_name} {$student->second_name} {$student->last_name} {$student->second_last_name}");
        $studentCiText   = $student->cedula ? "titular de la Cédula de Identidad / Escolar Nº V-{$student->cedula}" : "sin cédula de identidad aún asignada (en edad escolar)";
        $birthText       = $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->format('d/m/Y') : 'fecha registrada en expediente';
        $repFullName     = $representative?->full_name ?: 'SU REPRESENTANTE LEGAL';
        $repCiText       = $representative?->cedula ? "V-{$representative->cedula}" : 'registrada en expediente';
    @endphp

    @if(in_array($type, ['estudio', 'inscripcion'], true))
        <p class="prose-paragraph">
            Quien suscribe, <strong>{{ $directorName }}</strong>{{ $directorCedula ? ", titular de la Cédula de Identidad Nº {$directorCedula}" : '' }}, en su carácter de Director(a) de la <strong>UNIDAD EDUCATIVA BOLIVARIANA "CORONEL CARLOS DELGADO CHALBAUD"</strong>, inscrita ante el Ministerio del Poder Popular para la Educación bajo el <strong>Código DEA Nº OD05290104</strong>, ubicada en la Parroquia Coche, Municipio Libertador del Distrito Capital, por medio de la presente <strong>HACE CONSTAR</strong>:
        </p>

        <p class="prose-paragraph">
            Que el(la) estudiante <strong>{{ $studentFullName }}</strong>, {{ $studentCiText }}, nacido(a) en <strong>{{ mb_strtoupper($student->birth_place_locality, 'UTF-8') }}, ESTADO {{ mb_strtoupper($student->birth_place_state, 'UTF-8') }}</strong> en fecha <strong>{{ $birthText }}</strong>, representado(a) legalmente por el(la) ciudadano(a) <strong>{{ $repFullName }}</strong>, titular de la Cédula de Identidad Nº <strong>{{ $repCiText }}</strong> ({{ mb_strtoupper($representative?->relationship ?? 'Representante Legal', 'UTF-8') }}), se encuentra formalmente inscrito(a) y cursa estudios regulares en esta institución educativa en el <strong>{{ mb_strtoupper($gradeName, 'UTF-8') }}</strong> de <strong>{{ mb_strtoupper($levelName, 'UTF-8') }}</strong>, Sección <strong>"{{ mb_strtoupper($sectionName, 'UTF-8') }}"</strong>, en el Turno de la <strong>{{ mb_strtoupper($shift, 'UTF-8') }}</strong>, correspondiente al Año Escolar <strong>{{ $schoolYear }}</strong>.
        </p>

        <p class="prose-paragraph">
            Constancia que se expide a petición de la parte interesada en la ciudad de Caracas, Distrito Capital, {{ $issueDateLong }}, para {{ $purpose }}.
        </p>

    @elseif($type === 'prosecucion')
        <p class="prose-paragraph">
            Quien suscribe, <strong>{{ $directorName }}</strong>{{ $directorCedula ? ", titular de la Cédula de Identidad Nº {$directorCedula}" : '' }}, en su condición de Director(a) de la <strong>UNIDAD EDUCATIVA BOLIVARIANA "CORONEL CARLOS DELGADO CHALBAUD"</strong>, Código Estadístico y DEA <strong>OD05290104</strong>, adscrita a la Zona Educativa del Distrito Capital, por medio de la presente <strong>CERTIFICA Y HACE CONSTAR</strong>:
        </p>

        <p class="prose-paragraph">
            Que el(la) estudiante <strong>{{ $studentFullName }}</strong>, {{ $studentCiText }}, nacido(a) el <strong>{{ $birthText }}</strong> en <strong>{{ mb_strtoupper($student->birth_place_locality, 'UTF-8') }}, ESTADO {{ mb_strtoupper($student->birth_place_state, 'UTF-8') }}</strong>, cuyo representante legal es el(la) ciudadano(a) <strong>{{ $repFullName }}</strong> (C.I. Nº <strong>{{ $repCiText }}</strong>), cursó y <strong>APROBÓ</strong> satisfactoriamente en este plantel el <strong>{{ mb_strtoupper($gradeName, 'UTF-8') }}</strong> de <strong>{{ mb_strtoupper($levelName, 'UTF-8') }}</strong> durante el Año Escolar <strong>{{ $schoolYear }}</strong>, alcanzando la Expresión Cualitativa Literal <strong>"{{ $literal }}"</strong> de conformidad con lo establecido en el Artículo 108 del Reglamento General de la Ley Orgánica de Educación.
        </p>

        <p class="prose-paragraph">
            En consecuencia, el(la) mencionado(a) estudiante ha sido promovido(a) para cursar el <strong>{{ $nextGrade }}</strong>. Se expide la presente Constancia de Prosecución en Caracas, Distrito Capital, {{ $issueDateLong }}.
        </p>

    @elseif($type === 'justificativo')
        <p class="prose-paragraph">
            Quien suscribe, <strong>{{ $directorName }}</strong>{{ $directorCedula ? ", titular de la Cédula de Identidad Nº {$directorCedula}" : '' }}, en su carácter de Director(a) de la <strong>UNIDAD EDUCATIVA BOLIVARIANA "CORONEL CARLOS DELGADO CHALBAUD"</strong> (Código DEA: <strong>OD05290104</strong>), ubicada en la Parroquia Coche, Caracas — Distrito Capital, por medio de la presente <strong>HACE CONSTAR</strong>:
        </p>

        @if($target === 'representante')
            <p class="prose-paragraph">
                Que el(la) ciudadano(a) <strong>{{ $repFullName }}</strong>, titular de la Cédula de Identidad Nº <strong>{{ $repCiText }}</strong>, en su condición de <strong>{{ mb_strtoupper($representative?->relationship ?? 'Representante Legal', 'UTF-8') }}</strong> del(de la) estudiante <strong>{{ $studentFullName }}</strong> (cursante del <strong>{{ mb_strtoupper($gradeName, 'UTF-8') }} "{{ mb_strtoupper($sectionName, 'UTF-8') }}"</strong>, Año Escolar <strong>{{ $schoolYear }}</strong>), acudió y permaneció en las instalaciones de este plantel educativo el día <strong>{{ $eventDateFormatted }}</strong>, en el horario comprendido desde las <strong>{{ $timeFrom }}</strong> hasta las <strong>{{ $timeTo }} horas</strong>, con motivo de <strong>{{ $reason }}</strong>.
            </p>
        @elseif($target === 'estudiante')
            <p class="prose-paragraph">
                Que el(la) estudiante <strong>{{ $studentFullName }}</strong>, {{ $studentCiText }}, cursante regular del <strong>{{ mb_strtoupper($gradeName, 'UTF-8') }}</strong>, Sección <strong>"{{ mb_strtoupper($sectionName, 'UTF-8') }}"</strong> (Año Escolar <strong>{{ $schoolYear }}</strong>), representado(a) por <strong>{{ $repFullName }}</strong> (C.I. Nº <strong>{{ $repCiText }}</strong>), estuvo presente cumpliendo actividades institucionales en este plantel el día <strong>{{ $eventDateFormatted }}</strong>, en el horario comprendido entre las <strong>{{ $timeFrom }}</strong> y las <strong>{{ $timeTo }} horas</strong>, con motivo de <strong>{{ $reason }}</strong>.
            </p>
        @else
            <p class="prose-paragraph">
                Que el(la) ciudadano(a) <strong>{{ $repFullName }}</strong>, titular de la Cédula de Identidad Nº <strong>{{ $repCiText }}</strong>, junto a su representado(a) el(la) estudiante <strong>{{ $studentFullName }}</strong> ({{ $studentCiText }}, cursante del <strong>{{ mb_strtoupper($gradeName, 'UTF-8') }} "{{ mb_strtoupper($sectionName, 'UTF-8') }}"</strong>, Año Escolar <strong>{{ $schoolYear }}</strong>), comparecieron ante la Dirección y Coordinación Pedagógica de esta institución el día <strong>{{ $eventDateFormatted }}</strong>, desde las <strong>{{ $timeFrom }}</strong> hasta las <strong>{{ $timeTo }} horas</strong>, con motivo de <strong>{{ $reason }}</strong>.
            </p>
        @endif

        <p class="prose-paragraph">
            Se expide el presente justificativo institucional a solicitud de la parte interesada a los fines laborales, académicos o administrativos correspondientes, en Caracas, Distrito Capital, {{ $issueDateLong }}.
        </p>
    @endif

    <!-- Cuadro Resumen de Verificación -->
    <table style="width: 100%; border-collapse: collapse; margin-top: 22px; font-size: 10px; border: 1px solid #cbd5e1;">
        <tr style="background-color: #f8fafc;">
            <td style="padding: 6px 10px; border: 1px solid #cbd5e1;"><strong>ESTUDIANTE:</strong> {{ $studentFullName }}</td>
            <td style="padding: 6px 10px; border: 1px solid #cbd5e1;"><strong>GRADO / AÑO:</strong> {{ mb_strtoupper($gradeName, 'UTF-8') }} ({{ $schoolYear }})</td>
        </tr>
        <tr>
            <td style="padding: 6px 10px; border: 1px solid #cbd5e1;"><strong>REPRESENTANTE:</strong> {{ $repFullName }} ({{ $repCiText }})</td>
            <td style="padding: 6px 10px; border: 1px solid #cbd5e1;"><strong>CÓDIGO DEA:</strong> OD05290104 · PARROQUIA COCHE</td>
        </tr>
    </table>

    <!-- Bloque de Firma y Sello -->
    <table style="width: 100%; border-collapse: collapse; margin-top: 68px;">
        <tr>
            <td style="width: 65%; text-align: center; vertical-align: bottom;">
                <div style="width: 280px; margin: 0 auto; border-top: 1.5px solid #111827; padding-top: 8px;">
                    <div style="font-size: 11px; font-weight: bold; text-transform: uppercase;">{{ $directorName }}</div>
                    @if(!empty($directorCedula))
                        <div style="font-size: 10px; color: #374151;">C.I. {{ $directorCedula }}</div>
                    @endif
                    <div style="font-size: 10px; font-weight: bold; color: #1A237E; text-transform: uppercase; margin-top: 2px;">
                        Director(a) — U.E.B. "Coronel Carlos Delgado Chalbaud"
                    </div>
                </div>
            </td>
            <td style="width: 35%; text-align: center; vertical-align: bottom;">
                <div style="width: 96px; height: 96px; border: 1.5px dashed #9ca3af; border-radius: 50%; margin: 0 auto; line-height: 96px; font-size: 9px; font-weight: bold; color: #9ca3af; text-transform: uppercase;">
                    SELLO DEL PLANTEL
                </div>
            </td>
        </tr>
    </table>

    <div style="margin-top: 44px; border-top: 1px solid #e5e7eb; padding-top: 8px; text-align: center; font-size: 8.5px; color: #6b7280; text-transform: uppercase;">
        U.E.B. "Coronel Carlos Delgado Chalbaud" · Parroquia Coche, Caracas · Sistema Infochalbaud · Correo: controlestudios@infochalbaud.edu.ve
    </div>
</div>

</body>
</html>

