<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $customSubject }} — Infochalbaud</title>
</head>
<body style="margin: 0; padding: 24px; background-color: #f3f4f6; font-family: Arial, Helvetica, sans-serif; color: #1f2937;">
    <table style="max-width: 640px; width: 100%; margin: 0 auto; background-color: #ffffff; border-radius: 14px; overflow: hidden; border: 1px solid #e5e7eb; border-collapse: collapse;">
        <tr>
            <td style="background-color: #1A237E; padding: 24px 28px; color: #ffffff;">
                <div style="font-size: 11px; font-weight: bold; letter-spacing: 1.5px; text-transform: uppercase; color: #FBC02D;">
                    INFOCHALBAUD · COMUNICACIÓN INSTITUCIONAL
                </div>
                <div style="font-size: 18px; font-weight: bold; margin-top: 4px;">
                    U.E.B. "Coronel Carlos Delgado Chalbaud"
                </div>
                <div style="font-size: 11px; color: #cbd5e1; margin-top: 2px;">
                    Código DEA: OD05290104 · Parroquia Coche, Caracas
                </div>
            </td>
        </tr>
        <tr>
            <td style="height: 4px; background: linear-gradient(90deg, #FBC02D 33%, #1A237E 33%, #1A237E 66%, #D32F2F 66%);"></td>
        </tr>
        <tr>
            <td style="padding: 28px;">
                <div style="font-size: 14px; line-height: 1.7; color: #1f2937; white-space: pre-line;">{{ $bodyText }}</div>

                @if(count($attachedDocs) > 0)
                    <div style="margin-top: 22px; background-color: #f8fafc; border-left: 4px solid #1A237E; padding: 14px 18px; border-radius: 6px;">
                        <div style="font-size: 11px; font-weight: bold; color: #1A237E; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 8px;">
                            Documentos Oficiales Adjuntos ({{ count($attachedDocs) }})
                        </div>
                        <ul style="margin: 0; padding-left: 18px; font-size: 13px; color: #374151;">
                            @foreach($attachedDocs as $doc)
                                <li style="margin-bottom: 4px;">
                                    <strong>{{ $doc['title'] }}</strong> — Folio: <code>{{ $doc['folio'] }}</code>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </td>
        </tr>
        <tr>
            <td style="background-color: #f9fafb; padding: 16px 28px; border-top: 1px solid #e5e7eb; font-size: 11px; color: #6b7280; text-align: center;">
                Mensaje enviado a través del Sistema de Gestión Escolar <strong>Infochalbaud</strong>.<br>
                U.E.B. Coronel Carlos Delgado Chalbaud — Ministerio del Poder Popular para la Educación.
            </td>
        </tr>
    </table>
</body>
</html>
