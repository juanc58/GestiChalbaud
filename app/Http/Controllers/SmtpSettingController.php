<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use App\Rules\StrictEmail;
use App\Services\SmtpMailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SmtpSettingController extends Controller
{
    /**
     * Delega al servicio separado SmtpMailService para mantener compatibilidad.
     */
    public static function applyDynamicMailConfig(): void
    {
        SmtpMailService::applyConfig();
    }

    public function edit()
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Solo la administración principal puede acceder a la configuración institucional y técnica.');
        }

        $settings = SystemSetting::getMany([
            'mail_mailer'       => 'smtp',
            'mail_host'         => '127.0.0.1',
            'mail_port'         => '1025',
            'mail_encryption'   => '',
            'mail_username'     => '',
            'mail_password'     => '',
            'mail_from_address' => 'controlestudios@infochalbaud.edu.ve',
            'mail_from_name'    => 'Infochalbaud - U.E.B. Coronel Carlos Delgado Chalbaud',
            'director_name'     => 'DIRECCIÓN DEL PLANTEL',
            'director_cedula'   => '',
        ]);

        return view('settings.smtp', compact('settings'));
    }

    public function update(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Solo la administración principal puede actualizar estos parámetros.');
        }

        $validated = $request->validate([
            'mail_mailer'       => 'required|in:smtp,log',
            'mail_host'         => 'required|string|max:255',
            'mail_port'         => 'required|integer|min:1|max:65535',
            'mail_encryption'   => 'nullable|in:tls,ssl,',
            'mail_username'     => 'nullable|string|max:255',
            'mail_password'     => 'nullable|string|max:255',
            'clear_password'    => 'nullable|boolean',
            'mail_from_address' => ['required', 'string', 'max:255', new StrictEmail()],
            'mail_from_name'    => 'required|string|max:255',
            'director_name'     => 'nullable|string|max:255',
            'director_cedula'   => 'nullable|string|max:50',
        ]);

        SystemSetting::setValue('mail_mailer', $validated['mail_mailer']);
        SystemSetting::setValue('mail_host', trim($validated['mail_host']));
        SystemSetting::setValue('mail_port', (string) $validated['mail_port']);
        SystemSetting::setValue('mail_encryption', $validated['mail_encryption'] ?? '');
        SystemSetting::setValue('mail_username', trim((string) ($validated['mail_username'] ?? '')));

        if ($request->boolean('clear_password')) {
            SystemSetting::setValue('mail_password', '');
        } elseif ($request->filled('mail_password')) {
            SystemSetting::setValue('mail_password', SmtpMailService::encryptPassword($validated['mail_password']));
        }

        SystemSetting::setValue('mail_from_address', mb_strtolower(trim($validated['mail_from_address']), 'UTF-8'));
        SystemSetting::setValue('mail_from_name', trim($validated['mail_from_name']));

        if (array_key_exists('director_name', $validated)) {
            SystemSetting::setValue('director_name', mb_strtoupper(trim((string) $validated['director_name']), 'UTF-8') ?: 'DIRECCIÓN DEL PLANTEL');
        }
        if (array_key_exists('director_cedula', $validated)) {
            SystemSetting::setValue('director_cedula', trim((string) $validated['director_cedula']));
        }

        SmtpMailService::applyConfig();

        return redirect()
            ->route('settings.smtp.edit')
            ->with('success', 'Datos institucionales y parámetros de correo guardados correctamente.');
    }

    public function test(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Solo la administración principal puede ejecutar pruebas técnicas.');
        }

        $validated = $request->validate([
            'test_email' => ['required', 'string', 'max:255', new StrictEmail()],
        ]);

        SmtpMailService::applyConfig();

        $host = config('mail.mailers.smtp.host');
        $port = config('mail.mailers.smtp.port');

        try {
            Mail::raw(
                "¡Conexión exitosa!\n\nEste es un correo de verificación enviado desde Infochalbaud (U.E.B. Coronel Carlos Delgado Chalbaud).\nFecha: " . now()->format('d/m/Y H:i:s'),
                function ($message) use ($validated) {
                    $message->to($validated['test_email'])
                            ->subject('Prueba de Conexión de Correo — Infochalbaud');
                }
            );

            return back()->with('success', "Correo de prueba enviado exitosamente a {$validated['test_email']} ({$host}:{$port}).");
        } catch (\Throwable $e) {
            return back()->with('error', "Error al enviar correo — " . $e->getMessage());
        }
    }
}
