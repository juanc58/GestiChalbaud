<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $documentTitle }} — Infochalbaud</title>
</head>
<body style="margin: 0; padding: 24px; background-color: #f3f4f6; font-family: Arial, Helvetica, sans-serif; color: #1f2937;">
    <table style="max-width: 620px; width: 100%; margin: 0 auto; background-color: #ffffff; border-radius: 14px; overflow: hidden; border: 1px solid #e5e7eb; border-collapse: collapse;">
        <tr>
            <td style="background-color: #1A237E; padding: 24px 28px; color: #ffffff;">
                <div style="font-size: 11px; font-weight: bold; letter-spacing: 1.5px; text-transform: uppercase; color: #FBC02D;">
                    INFOCHALBAUD · CONTROL DE ESTUDIOS
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
                <p style="margin: 0 0 14px 0; font-size: 14px;">
                    Estimado(a) representante <strong>{{ $representativeName ?: 'Representante Legal' }}</strong>:
                </p>
                <p style="margin: 0 0 16px 0; font-size: 14px; line-height: 1.6; color: #374151;">
                    Reciba un cordial saludo institucional. Por medio de la presente le hacemos entrega formal en formato PDF adjunto del documento oficial:
                </p>

                <div style="background-color: #f8fafc; border-left: 4px solid #1A237E; padding: 14px 18px; border-radius: 6px; margin-bottom: 18px;">
                    <div style="font-size: 13px; font-weight: bold; color: #1A237E; text-transform: uppercase;">
                        {{ $documentTitle }}
                    </div>
                    <div style="font-size: 13px; color: #374151; margin-top: 6px;">
                        <strong>Estudiante:</strong> {{ $student->full_name }}<br>
                        <strong>Cédula:</strong> {{ $student->cedula ?: 'S/C' }}<br>
                        <strong>Periodo Escolar:</strong> {{ $schoolYear }}
                    </div>
                </div>

                @if(!empty($customMessage))
                    <div style="background-color: #fffbeb; border: 1px solid #fde68a; padding: 12px 16px; border-radius: 8px; margin-bottom: 18px; font-size: 13px; color: #92400e;">
                        <strong>Nota adicional:</strong> {{ $customMessage }}
                    </div>
                @endif

                <p style="margin: 0; font-size: 13px; line-height: 1.6; color: #4b5563;">
                    El documento se encuentra adjunto a este correo en formato PDF listo para su visualización o impresión en tamaño Carta.
                </p>
            </td>
        </tr>
        <tr>
            <td style="background-color: #f9fafb; padding: 16px 28px; border-top: 1px solid #e5e7eb; font-size: 11px; color: #6b7280; text-align: center;">
                Mensaje emitido automáticamente por el Sistema de Gestión Escolar <strong>Infochalbaud</strong>.<br>
                U.E.B. Coronel Carlos Delgado Chalbaud — Ministerio del Poder Popular para la Educación.
            </td>
        </tr>
    </table>
</body>
</html>

