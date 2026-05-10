<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

class CaptchaService
{
    public static function generate()
    {
        $code = strtoupper(substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ23456789"), 0, 5));
        Session::put('captcha_result', $code);
        return $code;
    }

    public static function render($code)
    {
        $width = 240;
        $height = 80;
        $image = imagecreatetruecolor($width, $height);
        $background = imagecolorallocate($image, 248, 243, 240); // #f8f3f0
        imagefill($image, 0, 0, $background);
        
        $text_color = imagecolorallocate($image, 3, 46, 94); // #032e5e
        $line_color = imagecolorallocate($image, 197, 108, 57); // #c56c39
        
        // 1. Create a small buffer for text to scale it up later
        $text_bw = 60;
        $text_bh = 20;
        $buffer = imagecreatetruecolor($text_bw, $text_bh);
        imagefill($buffer, 0, 0, $background);
        imagestring($buffer, 5, 5, 2, $code, $text_color);
        
        // 2. Scale buffer to main image (creating Large Text)
        imagecopyresampled($image, $buffer, 20, 10, 0, 0, 200, 60, $text_bw, $text_bh);
        imagedestroy($buffer);

        // 3. Add random lines on top for noise
        for ($i = 0; $i < 8; $i++) {
            imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $line_color);
        }
        
        header('Content-Type: image/png');
        imagepng($image);
        imagedestroy($image);
    }

    public static function verify($input)
    {
        return strtoupper(Session::get('captcha_result')) === strtoupper($input);
    }
}
