<?php

namespace App\Services;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

/**
 * Servicio dedicado exclusivamente al transporte SMTP, separado del servidor de la aplicación.
 */
class SmtpMailService
{
    public static function applyConfig(): void
    {
        try {
            if (!Schema::hasTable('system_settings')) {
                return;
            }

            $settings = SystemSetting::getMany([
                'mail_mailer'       => env('MAIL_MAILER', 'smtp'),
                'mail_host'         => env('MAIL_HOST', '127.0.0.1'),
                'mail_port'         => (string) env('MAIL_PORT', '1025'),
                'mail_encryption'   => '',
                'mail_username'     => env('MAIL_USERNAME', ''),
                'mail_password'     => env('MAIL_PASSWORD', ''),
                'mail_from_address' => env('MAIL_FROM_ADDRESS', 'controlestudios@infochalbaud.edu.ve'),
                'mail_from_name'    => env('MAIL_FROM_NAME', 'Infochalbaud - U.E.B. Coronel Carlos Delgado Chalbaud'),
            ]);

            $mailer     = $settings['mail_mailer'] ?: 'smtp';
            $host       = trim((string) ($settings['mail_host'] ?: '127.0.0.1'));
            $port       = (int) ($settings['mail_port'] ?: 1025);
            $encryption = strtolower(trim((string) ($settings['mail_encryption'] ?? '')));
            $username   = trim((string) ($settings['mail_username'] ?? ''));
            $password   = self::decryptPassword((string) ($settings['mail_password'] ?? ''));

            Config::set('mail.default', $mailer);
            Config::set('mail.mailers.smtp.transport', 'smtp');
            Config::set('mail.mailers.smtp.host', $host);
            Config::set('mail.mailers.smtp.port', $port);
            Config::set('mail.mailers.smtp.username', $username !== '' ? $username : null);
            Config::set('mail.mailers.smtp.password', $password !== '' ? $password : null);

            if ($encryption === 'ssl') {
                Config::set('mail.mailers.smtp.scheme', 'smtps');
                Config::set('mail.mailers.smtp.encryption', 'ssl');
            } elseif ($encryption === 'tls') {
                Config::set('mail.mailers.smtp.scheme', null);
                Config::set('mail.mailers.smtp.encryption', 'tls');
            } else {
                Config::set('mail.mailers.smtp.scheme', null);
                Config::set('mail.mailers.smtp.encryption', null);
                Config::set('mail.mailers.smtp.auto_tls', false);
            }

            Config::set('mail.from.address', $settings['mail_from_address'] ?: 'controlestudios@infochalbaud.edu.ve');
            Config::set('mail.from.name', $settings['mail_from_name'] ?: 'Infochalbaud - U.E.B. Coronel Carlos Delgado Chalbaud');

            Mail::purge('smtp');
        } catch (\Throwable) {
            // Ignorar silenciosamente si la BD aún no está lista durante comandos CLI
        }
    }

    public static function encryptPassword(string $plain): string
    {
        if ($plain === '') {
            return '';
        }
        return 'enc:' . Crypt::encryptString($plain);
    }

    public static function decryptPassword(string $stored): string
    {
        if ($stored === '') {
            return '';
        }
        if (str_starts_with($stored, 'enc:')) {
            try {
                return Crypt::decryptString(substr($stored, 4));
            } catch (\Throwable) {
                return '';
            }
        }
        return $stored;
    }
}
