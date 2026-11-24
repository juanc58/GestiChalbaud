@php
    $mppeLogoPath = public_path('assets/mppe-logo.png');
    $logoPath     = public_path('assets/logo.jpeg');

    $mppeLogoSrc = file_exists($mppeLogoPath)
        ? 'data:image/png;base64,' . base64_encode(file_get_contents($mppeLogoPath))
        : '';

    $logoSrc = file_exists($logoPath)
        ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoPath))
        : '';

    $docSubtitle = $documentTitle ?? null;
    $showDeaLine = empty($hideDeaLine);
@endphp

<table style="width: 100%; border-collapse: collapse; margin-bottom: 12px; border-bottom: 2px solid #1A237E; padding-bottom: 10px;">
    <tr>
        <td style="width: 16%; text-align: left; vertical-align: middle; padding-bottom: 8px;">
            @if($mppeLogoSrc)
                <img src="{{ $mppeLogoSrc }}" alt="Logo MPPE" style="width: 68px; height: 68px; object-fit: contain;">
            @endif
        </td>
        <td style="width: 68%; text-align: center; vertical-align: middle; padding: 0 8px 8px 8px; line-height: 1.35;">
            <div style="font-size: 10px; font-weight: bold; color: #1f2937; text-transform: uppercase; letter-spacing: 0.6px;">
                REPÚBLICA BOLIVARIANA DE VENEZUELA
            </div>
            <div style="font-size: 10.5px; font-weight: bold; color: #1A237E; text-transform: uppercase; letter-spacing: 0.5px;">
                MINISTERIO DEL PODER POPULAR PARA LA EDUCACIÓN
            </div>
            <div style="font-size: 9.5px; font-weight: bold; color: #374151; text-transform: uppercase;">
                ZONA EDUCATIVA DEL DISTRITO CAPITAL — PARROQUIA COCHE
            </div>
            <div style="font-size: 12px; font-weight: 900; color: #1A237E; text-transform: uppercase; margin-top: 2px;">
                U.E.B. "CORONEL CARLOS DELGADO CHALBAUD"
            </div>
            @if($showDeaLine)
            <div style="font-size: 9px; font-weight: bold; color: #4b5563; text-transform: uppercase; margin-top: 2px;">
                CÓDIGO DEA: OD05290104 &nbsp;|&nbsp; CARACAS - DISTRITO CAPITAL &nbsp;|&nbsp; SISTEMA INFOCHALBAUD
            </div>
            @endif
            <!-- Barra Tricolor Institucional -->
            <table style="width: 180px; margin: 5px auto 0 auto; border-collapse: collapse;">
                <tr>
                    <td style="width: 33.3%; height: 3px; background-color: #FBC02D;"></td>
                    <td style="width: 33.3%; height: 3px; background-color: #1A237E;"></td>
                    <td style="width: 33.3%; height: 3px; background-color: #D32F2F;"></td>
                </tr>
            </table>
            @if($docSubtitle)
                <div style="font-size: 12px; font-weight: 900; color: #111827; text-transform: uppercase; letter-spacing: 1px; margin-top: 7px;">
                    {{ $docSubtitle }}
                </div>
            @endif
        </td>
        <td style="width: 16%; text-align: right; vertical-align: middle; padding-bottom: 8px;">
            @if($logoSrc)
                <img src="{{ $logoSrc }}" alt="Logo Institucional" style="width: 68px; height: 68px; border-radius: 8px; border: 1px solid #d1d5db;">
            @endif
        </td>
    </tr>
</table>

