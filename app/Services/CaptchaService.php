<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CaptchaService
{
    public static function generate()
    {
        $code = strtoupper(substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ23456789"), 0, 5));
        Session::put('captcha_result', $code);
        Session::save(); // Guardar la sesión ANTES de enviar la imagen
        return $code;
    }

    public static function render($code)
    {
        $width  = 240;
        $height = 80;
        $image  = imagecreatetruecolor($width, $height);

        $background = imagecolorallocate($image, 248, 243, 240);
        imagefill($image, 0, 0, $background);

        $text_color = imagecolorallocate($image, 26, 35, 126);   // #1A237E - Azul marino institucional
        $line_color = imagecolorallocate($image, 251, 192, 45);  // #FBC02D - Amarillo institucional

        // Buffer para texto grande
        $text_bw = 60;
        $text_bh = 20;
        $buffer  = imagecreatetruecolor($text_bw, $text_bh);
        imagefill($buffer, 0, 0, $background);
        imagestring($buffer, 5, 5, 2, $code, $text_color);

        // Escalar buffer al tamaño principal
        imagecopyresampled($image, $buffer, 20, 10, 0, 0, 200, 60, $text_bw, $text_bh);
        imagedestroy($buffer);

        // Ruido visual (líneas)
        for ($i = 0; $i < 8; $i++) {
            imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $line_color);
        }

        // Capturar la imagen en un buffer de memoria
        ob_start();
        imagepng($image);
        imagedestroy($image);
        $imageData = ob_get_clean();

        // Devolver como respuesta Laravel correcta (funciona con proxies/túneles)
        return response($imageData, 200, [
            'Content-Type'  => 'image/png',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma'        => 'no-cache',
        ]);
    }

    public static function verify($input)
    {
        return strtoupper(Session::get('captcha_result')) === strtoupper($input);
    }
}
